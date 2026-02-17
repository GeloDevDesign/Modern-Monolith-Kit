<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
      
        $segments = $request->segments();
        $breadcrumbs = [];
        $url = '';

      
        $breadcrumbs[] = [
            'label' => 'Home',
            'route' => 'dashboard.index', 
        ];

        foreach ($segments as $segment) {
            $url .= '/'.$segment;

          
            if (is_numeric($segment)) {
                continue;
            }

            $label = ucwords(str_replace(['-', '_'], ' ', $segment));

            $breadcrumbs[] = [
                'label' => $label,
                'url' => url($url),
            ];
        }

       
        if (count($breadcrumbs) > 0) {
            $breadcrumbs[count($breadcrumbs) - 1]['url'] = null;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],

            'breadcrumbs' => $breadcrumbs,
        ];
    }
}
