<?php

namespace App\Http\Controllers\Settings;

use App\Currency;
use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fetch the setting directly from the model (no custom method)
        $setting = UserSetting::where('user_id', $user->id)
            ->where('name', 'currency')
            ->first();

        $currentCurrency = $setting ? $setting->value['value'] ?? Currency::BDT->value : Currency::BDT->value;

        return Inertia::render('settings/Currency', [
            'currencies'       => Currency::options(),
            'currentCurrency'  => $currentCurrency,
        ]);
    }

    /**
     * Store the selected currency.
     */
    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency' => ['required', new Enum(Currency::class)],
        ]);

        $user = auth()->user();

        // Upsert the setting using plain Eloquent
        UserSetting::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'currency'],
            ['value' => ['value' => $request->currency]]
        );

        return back()->with('success', 'Currency updated.');
    }
}
