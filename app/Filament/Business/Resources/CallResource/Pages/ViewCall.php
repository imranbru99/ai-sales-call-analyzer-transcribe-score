<?php

namespace App\Filament\Business\Resources\CallResource\Pages;

use App\Filament\Business\Resources\CallResource;
use App\Models\Call;
use App\Models\CoachingAction;
use App\Models\CoachingPlan;
use App\Models\User;
use App\Services\CallAnalysisService;
use App\Services\PdfReportService;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCall extends ViewRecord
{
    protected static string $resource = CallResource::class;

    protected static string $view = 'filament.business.resources.call-resource.pages.view-call';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_pdf')
                ->label('Download PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function (PdfReportService $pdfService) {
                    /** @var Call $record */
                    $record = $this->getRecord();

                    return $pdfService->generateCallScorecardPdf($record, 'en');
                }),

            Actions\Action::make('rescore_call')
                ->label('Re-Analyze Call')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function (CallAnalysisService $analysisService) {
                    /** @var Call $record */
                    $record = $this->getRecord();
                    $analysisService->analyze($record);

                    Notification::make()
                        ->title('Call Re-Analyzed')
                        ->body('AI scores, summaries, and action items have been refreshed.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('create_coaching')
                ->label('Assign Coaching Action')
                ->icon('heroicon-o-academic-cap')
                ->color('warning')
                ->form([
                    TextInput::make('title')
                        ->label('Coaching Task')
                        ->default('Review objection handling timestamp 02:45')
                        ->required(),
                    Textarea::make('description')
                        ->label('Coach Instructions')
                        ->default('Focus on value proposition rather than discounting on pricing pushback.'),
                    DatePicker::make('due_date')
                        ->default(now()->addDays(3)),
                ])
                ->action(function (array $data) {
                    /** @var Call $record */
                    $record = $this->getRecord();

                    $plan = CoachingPlan::firstOrCreate(
                        ['tenant_id' => $record->tenant_id, 'agent_id' => $record->agent_id],
                        ['title' => 'Core Sales Skills Coaching', 'status' => 'active']
                    );

                    CoachingAction::create([
                        'coaching_plan_id' => $plan->id,
                        'tenant_id' => $record->tenant_id,
                        'agent_id' => $record->agent_id,
                        'call_id' => $record->id,
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'due_date' => $data['due_date'],
                        'status' => 'pending',
                    ]);

                    $agentName = $record->agent instanceof User ? $record->agent->name : 'Agent';
                    Notification::make()
                        ->title('Coaching Task Assigned')
                        ->body("Task assigned to {$agentName}")
                        ->success()
                        ->send();
                }),
        ];
    }
}
