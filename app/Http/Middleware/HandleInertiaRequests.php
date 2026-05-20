<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Category;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],

            'categories' => fn () => Category::with([
                    'currentTranslation', 
                    'children' => function ($query) {
                        $query->where('active', true)
                              ->orderBy('position')
                              ->with(['currentTranslation']);
                    },
                    'children.children' => function ($query) {
                        $query->where('active', true)
                              ->orderBy('position')
                              ->with(['currentTranslation']);
                    }
                ])
                ->whereNull('parent_id')
                ->where('active', true)
                ->orderBy('position')
                ->get(),
        ]);
    }
}
