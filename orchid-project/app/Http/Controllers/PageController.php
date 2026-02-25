<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Category;
use App\Models\Company;
use App\Models\Counter;
use App\Models\Hero;
use App\Models\MaterialPage;
use App\Models\Materials;
use App\Models\Partners;
use App\Models\Request as ModelsRequest;
use App\Models\WorkBlock;
use App\Models\Works;
use App\Models\WorksPage;
use Illuminate\Support\Facades\View as ViewFacade;

class PageController extends Controller
{
    public function worksLegacy(string $locale, ?string $categorySlug = null)
    {
        if (! filled($categorySlug)) {
            return $this->works($locale, null, null);
        }

        return redirect()->to(page_url('works', [
            'categorySlug' => $categorySlug,
        ], $locale), 301);
    }

    public function workLegacy(string $locale, string $categorySlug, string $workSlug)
    {
        $work = Works::query()
            ->with('workCategory')
            ->whereHas('translations', fn ($query) => $query->where('slug', $workSlug))
            ->whereHas('workCategory', fn ($query) => $query
                ->whereHas('translations', fn ($translationQuery) => $translationQuery->where('slug', $categorySlug)))
            ->first();

        if (! $work) {
            abort(404);
        }

        return redirect()->to(work_url($work, $locale), 301);
    }

    public function worksOrLegacyWork(string $locale, string $worksPageSlug, string $categorySlug)
    {
        $localizedWorksSlug = seo_slug('works', $locale, 'works');

        if ($worksPageSlug === $localizedWorksSlug) {
            return $this->works($locale, $worksPageSlug, $categorySlug);
        }

        return $this->workLegacy($locale, $worksPageSlug, $categorySlug);
    }

    public function home()
    {
        $locale = app()->getLocale();
        $hero = Hero::first();
        $partners = Partners::first();
        $request = ModelsRequest::first();
        $blockWorks = WorkBlock::first();
        $works = Works::query()
            ->with('workCategory')
            ->where('view_on_home_page', true)
            ->whereHas('translations', fn ($query) => $query->whereNotNull('slug'))
            ->whereHas('workCategory', fn ($query) => $query
                ->whereHas('translations', fn ($translationQuery) => $translationQuery
                    ->where('locale', $locale)
                    ->whereNotNull('slug')))
            ->get();
        $counters = Counter::first();
        return view('app.home', compact('hero', 'partners', 'counters', 'request', 'works', 'blockWorks'));
    }

    public function about()
    {
        $about = About::first();
        $request = ModelsRequest::first();
        $counters = Counter::first();
        $company = Company::first();

        return view('app.about', compact('about', 'request', 'counters', 'company'));
    }

    public function material()
    {
        $materialsPage = MaterialPage::first();
        $materials = Materials::all();

        return view('app.materials', compact('materialsPage', 'materials'));
    }

    public function works(string $locale, ?string $worksPageSlug = null, ?string $categorySlug = null)
    {
        $localizedWorksSlug = seo_slug('works', $locale, 'works');

        if (filled($worksPageSlug) && $localizedWorksSlug !== $worksPageSlug) {
            return redirect()->to(page_url('works', [
                'categorySlug' => $categorySlug,
            ], $locale), 301);
        }

        if (! filled($categorySlug) && ! filled($worksPageSlug)) {
            if (filled($localizedWorksSlug) && $localizedWorksSlug !== 'works') {
                return redirect()->route('page.static', [
                    'locale' => $locale,
                    'pageSlug' => $localizedWorksSlug,
                ], 301);
            }
        }

        $categories = Category::query()
            ->whereHas('translations', fn ($query) => $query
                ->where('locale', $locale)
                ->whereNotNull('slug'))
            ->whereHas('works', fn ($query) => $query->whereHas('translations', fn ($translationQuery) => $translationQuery->whereNotNull('slug')))
            ->with([
                'works' => fn ($query) => $query
                    ->with('workCategory')
                    ->whereHas('translations', fn ($translationQuery) => $translationQuery->whereNotNull('slug'))
                    ->orderByDesc('id'),
            ])
            ->get()
            ->sortBy(fn (Category $category) => mb_strtolower((string) ($category->name ?? '')))
            ->values();

        $selectedCategory = null;
        if (filled($categorySlug)) {
            $selectedCategory = $categories->first(fn (Category $category): bool => (string) $category->slug === $categorySlug);
            if ($selectedCategory === null) {
                $selectedCategory = Category::query()
                    ->whereHas('translations', fn ($query) => $query->where('slug', $categorySlug))
                    ->with([
                        'works' => fn ($query) => $query
                            ->with('workCategory')
                            ->whereHas('translations', fn ($translationQuery) => $translationQuery->whereNotNull('slug'))
                            ->orderByDesc('id'),
                    ])
                    ->first();
            }
            if ($selectedCategory === null) {
                abort(404);
            }

            $localizedCategorySlug = (string) ($selectedCategory->translate($locale)?->slug ?? '');
            if ($localizedCategorySlug !== '' && $localizedCategorySlug !== $categorySlug) {
                return redirect()->to(page_url('works', [
                    'categorySlug' => $localizedCategorySlug,
                ], $locale), 301);
            }
        }

        $worksPage = WorksPage::first();
        return view('app.works', compact('worksPage', 'categories', 'selectedCategory'));
    }

    public function workShow(string $locale, string $worksPageSlug, string $categorySlug, string $workSlug)
    {
        $localizedWorksSlug = seo_slug('works', $locale, 'works');
        if ($localizedWorksSlug !== $worksPageSlug) {
            return redirect()->route('work.show', [
                'locale' => $locale,
                'worksPageSlug' => $localizedWorksSlug,
                'categorySlug' => $categorySlug,
                'workSlug' => $workSlug,
            ], 301);
        }

        $work = Works::query()
            ->with('workCategory')
            ->whereHas('translations', fn ($query) => $query
                ->where('locale', $locale)
                ->where('slug', $workSlug))
            ->whereHas('workCategory', fn ($query) => $query
                ->whereHas('translations', fn ($translationQuery) => $translationQuery
                    ->where('locale', $locale)
                    ->where('slug', $categorySlug)))
            ->first();

        if (! $work) {
            $work = Works::query()
                ->with('workCategory')
                ->whereHas('translations', fn ($query) => $query->where('slug', $workSlug))
                ->whereHas('workCategory', fn ($query) => $query
                    ->whereHas('translations', fn ($translationQuery) => $translationQuery->where('slug', $categorySlug)))
                ->first();

            if (! $work) {
                abort(404);
            }

            $localizedSlug = (string) ($work->translate($locale)?->slug ?? '');
            $localizedCategorySlug = (string) ($work->workCategory?->translate($locale)?->slug ?: $categorySlug);
            $targetWorkSlug = $localizedSlug !== '' ? $localizedSlug : $workSlug;
            if ($targetWorkSlug !== $workSlug || $localizedCategorySlug !== $categorySlug) {
                return redirect()->route('work.show', [
                    'locale' => $locale,
                    'worksPageSlug' => $localizedWorksSlug,
                    'categorySlug' => $localizedCategorySlug,
                    'workSlug' => $targetWorkSlug,
                ], 301);
            }
        }

        $totalWorks = Works::query()
            ->whereHas('translations', fn ($query) => $query->whereNotNull('slug'))
            ->whereHas('workCategory', fn ($query) => $query
                ->whereHas('translations', fn ($translationQuery) => $translationQuery
                    ->where('locale', $locale)
                    ->whereNotNull('slug')))
            ->count();

        $suggestedWorks = collect();
        if ($totalWorks > 3) {
            $suggestedWorks = Works::query()
                ->with('workCategory')
                ->whereHas('translations', fn ($query) => $query->whereNotNull('slug'))
                ->where('id', '!=', $work->id)
                ->whereHas('workCategory', fn ($query) => $query
                    ->whereHas('translations', fn ($translationQuery) => $translationQuery
                        ->where('locale', $locale)
                        ->whereNotNull('slug')))
                ->inRandomOrder()
                ->limit(2)
                ->get();
        }

        return view('app.work', compact('work', 'suggestedWorks', 'totalWorks'));
    }

    public function staticPage(string $locale, string $pageSlug)
    {
        $pageKey = resolve_static_page_key_by_slug($pageSlug, $locale);

        return match ($pageKey) {
            'works' => $this->works($locale, $pageSlug),
            'about' => $this->about(),
            'material' => $this->material(),
            'contacts' => $this->contacts(),
            default => abort(404),
        };
    }

    public function contacts()
    {
        if (ViewFacade::exists('app.contacts')) {
            return view('app.contacts');
        }

        abort(404);
    }
}
