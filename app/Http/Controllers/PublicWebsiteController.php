<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\Plan;
use App\Models\Testimonial;
use Illuminate\View\View;

class PublicWebsiteController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'testimonials' => Testimonial::where('is_featured', true)->get(),
            'caseStudies' => CaseStudy::where('is_featured', true)->limit(3)->get(),
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
            'faqs' => Faq::where('is_published', true)->orderBy('sort_order')->limit(6)->get(),
        ]);
    }

    public function features(): View
    {
        return view('pages.features', [
            'modules' => config('modules.modules', []),
        ]);
    }

    public function featureDetail(string $module): View
    {
        $meta = config("modules.modules.{$module}");
        if (! $meta) {
            abort(404);
        }

        return view('pages.feature-detail', [
            'key' => $module,
            'feature' => $meta,
        ]);
    }

    public function solutionsIndustry(?string $industry = null): View
    {
        $industries = [
            'saas' => 'SaaS & Cloud Sales',
            'real_estate' => 'Real Estate & Property',
            'insurance' => 'Insurance & Underwriting',
            'banking' => 'Banking & Microfinance',
            'telecom' => 'Telecom & ISP',
            'education' => 'Education & Admissions',
            'healthcare' => 'Healthcare & Appointments',
            'ecommerce' => 'E-Commerce & High-Ticket Retail',
            'recruitment' => 'Recruitment & Staffing',
            'bpo' => 'BPO & Call Centers',
        ];

        return view('pages.solutions-industry', [
            'current' => $industry ?? 'saas',
            'industries' => $industries,
        ]);
    }

    public function solutionsRole(?string $role = null): View
    {
        $roles = [
            'leaders' => 'Sales Leaders & VPs',
            'agents' => 'Sales Agents & Reps',
            'qa' => 'QA Teams & Analysts',
            'compliance' => 'Compliance Officers',
            'trainers' => 'Sales Coaches & Trainers',
        ];

        return view('pages.solutions-role', [
            'current' => $role ?? 'leaders',
            'roles' => $roles,
        ]);
    }

    public function pricing(): View
    {
        return view('pages.pricing', [
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
            'modules' => config('modules.modules', []),
            'faqs' => Faq::where('category', 'Billing')->orWhere('category', 'General')->get(),
        ]);
    }

    public function moduleExplorer(): View
    {
        return view('pages.module-explorer', [
            'modules' => config('modules.modules', []),
            'presets' => config('modules.presets', []),
        ]);
    }

    public function liveDemo(): View
    {
        return view('pages.live-demo');
    }

    public function integrations(): View
    {
        return view('pages.integrations');
    }

    public function security(): View
    {
        return view('pages.security');
    }

    public function caseStudies(): View
    {
        return view('pages.case-studies', [
            'caseStudies' => CaseStudy::all(),
        ]);
    }

    public function caseStudyDetail(string $slug): View
    {
        $caseStudy = CaseStudy::where('slug', $slug)->firstOrFail();

        return view('pages.case-study-detail', ['caseStudy' => $caseStudy]);
    }

    public function blog(): View
    {
        return view('pages.blog', [
            'posts' => BlogPost::where('is_published', true)->latest()->paginate(9),
        ]);
    }

    public function blogDetail(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();

        return view('pages.blog-detail', ['post' => $post]);
    }

    public function compare(): View
    {
        return view('pages.compare');
    }

    public function roiCalculator(): View
    {
        return view('pages.roi-calculator');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function legal(string $page): View
    {
        $validPages = ['terms', 'privacy', 'dpa', 'cookie-policy', 'acceptable-use', 'sla'];
        if (! in_array($page, $validPages)) {
            abort(404);
        }

        return view('pages.legal', ['page' => $page]);
    }
}
