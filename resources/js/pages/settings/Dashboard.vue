<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import WidgetCard from '@/components/dashboard/WidgetCard.vue';
import AddWidgetModal from '@/components/dashboard/AddWidgetModal.vue';
import type { BreadcrumbItem } from '@/types';

// ── Types ────────────────────────────────────────────────────
interface ConfigField {
    type: 'text' | 'select';
    label: string;
    required: boolean;
    default: string;
    options?: string[];
}

interface WidgetTypeDefinition {
    label: string;
    icon: string;
    config_schema: Record<string, ConfigField>;
}

interface Widget {
    id: number;
    key: string;
    type: string;
    config: Record<string, string>;
    position: number;
    is_visible: boolean;
}

interface Props {
    widgets: Widget[];
    widgetTypes: Record<string, WidgetTypeDefinition>;
}

const props = defineProps<Props>();

// ── State ────────────────────────────────────────────────────
const showAddModal = ref(false);

// Local copy for optimistic reorder UI
const localWidgets = ref<Widget[]>([...props.widgets]);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard settings', href: '/settings/dashboard' },
];

// ── Reorder ──────────────────────────────────────────────────
// Called after drag-and-drop (or up/down buttons for now)
const moveWidget = (index: number, direction: 'up' | 'down') => {
    const swapIndex = direction === 'up' ? index - 1 : index + 1;
    if (swapIndex < 0 || swapIndex >= localWidgets.value.length) return;

    // Optimistic swap
    const copy = [...localWidgets.value];
    [copy[index], copy[swapIndex]] = [copy[swapIndex], copy[index]];
    localWidgets.value = copy;

    // Persist
    router.post(route('settings.dashboard.widgets.reorder'), {
        order: localWidgets.value.map(w => w.id),
    }, { preserveScroll: true });
};

const onWidgetAdded = () => {
    showAddModal.value = false;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Dashboard settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">

                <div class="flex items-center justify-between">
                    <HeadingSmall
                        title="Dashboard Widgets"
                        description="Add, remove, or configure the charts shown on your dashboard."
                    />
                    <Button @click="showAddModal = true">
                        Add Widget
                    </Button>
                </div>

                <!-- Empty state -->
                <div
                    v-if="localWidgets.length === 0"
                    class="flex flex-col items-center justify-center rounded-lg border border-dashed p-12 text-center text-muted-foreground"
                >
                    <p class="text-sm">No widgets yet.</p>
                    <p class="text-sm">Click <strong>Add Widget</strong> to get started.</p>
                </div>

                <!-- Widget list -->
                <div v-else class="flex flex-col gap-3">
                    <WidgetCard
                        v-for="(widget, index) in localWidgets"
                        :key="widget.id"
                        :widget="widget"
                        :schema="widgetTypes[widget.type]"
                        :is-first="index === 0"
                        :is-last="index === localWidgets.length - 1"
                        @move="(dir) => moveWidget(index, dir)"
                    />
                </div>

            </div>
        </SettingsLayout>

        <!-- Add Widget Modal -->
        <AddWidgetModal
            v-if="showAddModal"
            :widget-types="widgetTypes"
            @close="showAddModal = false"
            @added="onWidgetAdded"
        />
    </AppLayout>
</template>