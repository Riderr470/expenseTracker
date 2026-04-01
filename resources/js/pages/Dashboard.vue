<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    dailyAvgExpense: number;
    weeklyAvgExpense: number;
    monthlyAvgExpense: number;
}>();
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                
                <!-- Expense Averages -->
<div class="px-4 pt-4 space-y-2">

    <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3">
        <div class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="text-green-700 dark:text-green-500 shrink-0" aria-hidden="true">
                <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
                <path d="M8 5v3l2 1.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400">Daily Average</span>
        </div>
        <span class="text-sm font-bold text-green-700 dark:text-green-500 tabular-nums">
            ${{ (props.dailyAvgExpense ?? 0).toFixed(2) }}
        </span>
    </div>

    <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3">
        <div class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="text-green-700 dark:text-green-500 shrink-0" aria-hidden="true">
                <rect x="1.5" y="2.5" width="13" height="11" rx="2" stroke="currentColor" stroke-width="1.4"/>
                <path d="M5 1v3M11 1v3M1.5 6.5h13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400">Weekly Average</span>
        </div>
        <span class="text-sm font-bold text-green-700 dark:text-green-500 tabular-nums">
            ${{ (props.weeklyAvgExpense ?? 0).toFixed(2) }}
        </span>
    </div>

    <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3">
        <div class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="text-green-700 dark:text-green-500 shrink-0" aria-hidden="true">
                <path d="M2 12V5l6-3 6 3v7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 16v-4h4v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400">Monthly Total</span>
        </div>
        <span class="text-sm font-bold text-green-700 dark:text-green-500 tabular-nums">
            ${{ (props.monthlyAvgExpense ?? 0).toFixed(2) }}
        </span>
    </div>

</div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
