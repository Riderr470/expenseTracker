<?php

namespace App\Http\Middleware;

use App\Currency;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

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
        'currency' => function () use ($request) {
            if (! $request->user()) {
                return ['code' => 'BDT', 'symbol' => '৳'];
            }

            $setting = \App\Models\UserSetting::where('user_id', $request->user()->id)
                ->where('name', 'currency')
                ->first();

            $code = $setting ? $setting->value['value'] ?? 'BDT' : 'BDT';
            $enum = Currency::from($code);

            return [
                'code'   => $enum->value,
                'symbol' => $enum->symbol(),
                'label'  => $enum->label(),
            ];
        },
    ]);
    }
}
