<script setup lang="ts">
defineProps<{
    dailyAvgExpense: number;
    weeklyAvgExpense: number;
    monthlyAvgExpense: number;
}>();
</script>

<template>
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
                ${{ (dailyAvgExpense ?? 0).toFixed(2) }}
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
                ${{ (weeklyAvgExpense ?? 0).toFixed(2) }}
            </span>
        </div>

        <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3">
            <div class="flex items-center gap-3">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="text-green-700 dark:text-green-500 shrink-0" aria-hidden="true">
                    <path d="M2 12V5l6-3 6 3v7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 16v-4h4v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">Monthly Average</span>
            </div>
            <span class="text-sm font-bold text-green-700 dark:text-green-500 tabular-nums">
                ${{ (monthlyAvgExpense ?? 0).toFixed(2) }}
            </span>
        </div>

    </div>
</template>