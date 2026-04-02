<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\DashboardWidget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardSettingController extends Controller
{
    public function index(): Response
    {
        $widgets = auth()->user()
            ->dashboardWidgets()
            ->orderBy('position')
            ->get();

        return Inertia::render('settings/Dashboard', [
            'widgets'     => $widgets,
            'widgetTypes' => config('dashboard.widgets'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type   = $request->validate(['type' => 'required|string|in:' . implode(',', array_keys(config('dashboard.widgets')))])['type'];
        $schema = config("dashboard.widgets.{$type}.config_schema");

        // Build default config from schema defaults
        $config = collect($schema)
            ->mapWithKeys(fn($def, $field) => [$field => $def['default']])
            ->toArray();

        $position = auth()->user()->dashboardWidgets()->max('position') + 1;

        auth()->user()->dashboardWidgets()->create([
            'key'        => 'widget_' . uniqid(),
            'type'       => $type,
            'config'     => $config,
            'position'   => $position,
            'is_visible' => true,
        ]);

        return back()->with('status', 'widget-added');
    }

    public function update(Request $request, DashboardWidget $widget): RedirectResponse
    {
        $this->authorize('update', $widget); // or: abort_if($widget->user_id !== auth()->id(), 403);

        $schema = config("dashboard.widgets.{$widget->type}.config_schema");

        // Dynamically build validation rules from schema
        $rules = [];
        foreach ($schema as $field => $definition) {
            $fieldRules = [$definition['required'] ? 'required' : 'nullable'];

            if ($definition['type'] === 'select') {
                $fieldRules[] = 'in:' . implode(',', $definition['options']);
            }
            if ($definition['type'] === 'text') {
                $fieldRules[] = 'string|max:100';
            }

            $rules["config.{$field}"] = $fieldRules;
        }

        $validated = $request->validate($rules);

        $widget->update(['config' => $validated['config']]);

        return back()->with('status', 'widget-updated');
    }

    public function destroy(DashboardWidget $widget): RedirectResponse
    {
        abort_if($widget->user_id !== auth()->id(), 403);

        $widget->delete();

        // Re-sequence positions
        auth()->user()
            ->dashboardWidgets()
            ->orderBy('position')
            ->get()
            ->each(fn($w, $i) => $w->update(['position' => $i + 1]));

        return back()->with('status', 'widget-removed');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:dashboard_widgets,id',
        ]);

        foreach ($request->order as $position => $id) {
            auth()->user()
                ->dashboardWidgets()
                ->where('id', $id)
                ->update(['position' => $position + 1]);
        }

        return back()->with('status', 'widgets-reordered');
    }
}
