<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}" dir="{{ in_array($locale ?? 'en', ['ar', 'ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Call Intelligence Scorecard #{{ $call->id }}</title>
    <style>
        body {
            font-family: 'SolaimanLipi', 'NotoSansBengali', 'Amiri', 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #1e1b4b;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-pass { background-color: #dcfce7; color: #166534; }
        .badge-fail { background-color: #fee2e2; color: #991b1b; }
        .grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .metric-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }
        .metric-val {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }
        .metric-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 600;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #334155;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="border:none; width:100%;">
            <tr style="border:none;">
                <td style="border:none; padding:0;">
                    <div class="title">{{ $call->title }}</div>
                    <div style="color: #64748b; font-size: 12px;">
                        Agent: <strong>{{ $call->agent?->name ?? 'Rep' }}</strong> |
                        Date: <strong>{{ $call->created_at->format('M d, Y H:i') }}</strong> |
                        Duration: <strong>{{ gmdate('i:s', $call->duration_seconds) }}</strong>
                    </div>
                </td>
                <td style="border:none; text-align:right; padding:0;">
                    <span class="badge {{ $call->isPassing() ? 'badge-pass' : 'badge-fail' }}">
                        {{ $call->isPassing() ? 'PASSED QA' : 'NEEDS COACHING' }}
                    </span>
                    <div style="font-size: 28px; font-weight: bold; color: {{ $call->overall_score >= 80 ? '#10b981' : ($call->overall_score >= 70 ? '#f59e0b' : '#ef4444') }};">
                        {{ number_format($call->overall_score, 1) }} / 100
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table style="border:none; width:100%; margin-bottom: 15px;">
        <tr style="border:none;">
            <td style="border:none; width: 25%; padding: 4px;">
                <div class="metric-box">
                    <div class="metric-val">{{ $call->talk_listen_ratio }}</div>
                    <div class="metric-label">Talk / Listen Ratio</div>
                </div>
            </td>
            <td style="border:none; width: 25%; padding: 4px;">
                <div class="metric-box">
                    <div class="metric-val">{{ ucfirst($call->sentiment_label) }}</div>
                    <div class="metric-label">Customer Sentiment</div>
                </div>
            </td>
            <td style="border:none; width: 25%; padding: 4px;">
                <div class="metric-box">
                    <div class="metric-val">{{ $call->deal_likelihood_score ?? 75 }}%</div>
                    <div class="metric-label">Deal Likelihood</div>
                </div>
            </td>
            <td style="border:none; width: 25%; padding: 4px;">
                <div class="metric-box">
                    <div class="metric-val">{{ $call->words_per_minute }} WPM</div>
                    <div class="metric-label">Pace</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Executive Summary</div>
    <p style="background: #f8fafc; border-left: 3px solid #6366f1; padding: 10px; margin: 0;">
        {{ $call->executive_summary ?? $call->summary }}
    </p>

    <div class="section-title">Scorecard Evaluation Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Score</th>
                <th>Weight</th>
                <th>AI Evidence & Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($call->scorecard_breakdown ?? [] as $key => $item)
            <tr>
                <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                <td>{{ $item['score'] ?? '-' }} / {{ $item['max'] ?? 100 }}</td>
                <td>{{ $item['weight'] ?? 10 }}%</td>
                <td>{{ $item['notes'] ?? 'Satisfactory adherence.' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($call->action_items))
    <div class="section-title">Action Items & Commitments</div>
    <table>
        <thead>
            <tr>
                <th>Action Item</th>
                <th>Assignee</th>
                <th>Target Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($call->action_items as $action)
            <tr>
                <td>{{ $action['task'] ?? '' }}</td>
                <td><span style="font-weight:600;">{{ $action['assignee'] ?? 'Agent' }}</span></td>
                <td>{{ $action['due'] ?? 'Next follow-up' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Generated by <strong>AI Sales Call Analyzer</strong> (https://imrandev.bd) • Powered by ImranDev Unicode PDF Engine
    </div>
</body>
</html>
