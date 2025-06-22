<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';

const props = defineProps<{
    data: {
        expenses: any[];
        daily_total_expense: number;
        weekly_total_expense: number;
        monthly_total_expense: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expense',
        href: '/expense',
    },
];

const form = useForm({
    name: '',
    cost: '' as any,
    qty: 1,
});

const calculatedTotal = computed(() => {
    return (form.cost * form.qty).toFixed(2);
});

const groupedByDate = computed(() => {
    const groups: Record<string, { items: any[]; total: number }> = {};

    for (const item of props.data.expenses) {
        const date = new Date(item.created_at).toLocaleDateString();

        if (!groups[date]) {
            groups[date] = { items: [], total: 0 };
        }

        groups[date].items.push(item);
        groups[date].total += parseFloat(item.cost);
    }

    return groups;
});

function formatDate(dateStr: string): string {
    const date = new Date(dateStr);
    return date.toLocaleDateString();
}

// Static amounts for now
const income = 0;
const expense = props.data.daily_total_expense ?? 0;
const remaining = income - expense;
</script>

<template>

    <Head title="Expenses" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 text-xs">

            <div class="relative h-0">
                <div class="absolute -top-8 right-4 flex gap-2 z-10">

                    <!-- Income Card -->
                    <div class="rounded-md bg-green-100 text-green-800 px-5 py-2 shadow border border-green-300">
                        <p class="font-semibold">This Week Expenses: ${{ props.data.weekly_total_expense ?? 'error' }}
                        </p>
                    </div>

                    <!-- Remaining Card -->
                    <div class="rounded-md bg-green-100 text-green-800 px-5 py-2 shadow border border-green-300">
                        <p class="font-semibold">This Month Expenses: ${{ props.data.monthly_total_expense ?? 'error' }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Section Bar -->
            <div
                class="rounded-xl border border-gray-300 bg-white dark:bg-gray-900 dark:border-gray-700 p-6 text-end pr-20">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Today Total Expense : ${{ props.data.daily_total_expense ?? 0 }}
                </h2>
            </div>

            <div class="rounded-md border border-gray-200 bg-gray-50 p-4 dark:bg-gray-800 dark:border-gray-600">
                <form @submit.prevent="form.post('/expense/add')"
                    class="flex flex-col sm:flex-row sm:items-end gap-4 w-full">

                    <div class="flex flex-col gap-1 w-full sm:w-5/12">
                        <label class="text-sm font-medium">Item Name</label>
                        <input type="text" v-model="form.name"
                            class="w-full rounded-md border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"
                            placeholder="e.g. Bread" />
                        <div v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</div>
                    </div>

                    <div class="flex flex-col gap-1 w-full sm:w-3/12">
                        <label class="text-sm font-medium">Cost</label>
                        <input type="number" v-model.number="form.cost" step="0.01"
                            class="w-full rounded-md border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"
                            placeholder="e.g. 40.50" />
                        <div v-if="form.errors.cost" class="text-red-500 text-xs">{{ form.errors.cost }}</div>
                    </div>

                    <div class="flex flex-col gap-1 w-full sm:w-1/12">
                        <label class="text-sm font-medium">Quantity</label>
                        <input type="number" v-model.number="form.qty"
                            class="w-full rounded-md border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"
                            placeholder="e.g. 1" />
                        <div v-if="form.errors.qty" class="text-red-500 text-xs">{{ form.errors.qty }}</div>
                    </div>

                    <Transition name="total-width-slide">
                        <div v-if="form.qty > 1" class="flex flex-col gap-1 w-full sm:w-2/12">
                            <label class="text-sm font-medium">Total</label>
                            <input type="text" :value="calculatedTotal" readonly
                                class="w-full rounded-md border px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </Transition>

                    <div class="w-full sm:w-2/12">
                        <button type="submit"
                            class="w-full rounded-md bg-green-600 px-5 py-2 text-white text-sm font-semibold hover:bg-green-700"
                            :disabled="form.processing">
                            Add
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 border-b font-semibold">Good</th>
                            <th class="px-4 py-3 border-b font-semibold">Qty</th>
                            <th class="px-4 py-3 border-b font-semibold text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(group, date) in groupedByDate" :key="date">
                            <!-- Date header row -->
                            <tr class="bg-green-100 dark:bg-green-800">
                                <td colspan="3" class="px-4 py-2 font-bold text-green-900 dark:text-white">
                                    Date : {{ date }}
                                </td>
                            </tr>

                            <!-- Items of the day -->
                            <tr v-for="item in group.items" :key="item.id">
                                <td class="px-4 py-2">{{ item.name ?? 'Nameless item' }}</td>
                                <td class="px-4 py-2">{{ item.qty ?? 1 }}</td>
                                <td class="px-4 py-2 text-right">${{ item.cost }}</td>
                            </tr>

                            <!-- Daily total row -->
                            <tr class="bg-gray-100 dark:bg-gray-700 border-t">
                                <td class="px-4 py-2 text-right font-semibold" colspan="2">Daily Total:</td>
                                <td class="px-4 py-2 font-bold text-right">${{ group.total.toFixed(2) }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>

            </div>

        </div>
    </AppLayout>
</template>

<style>
/* For Total field transition (fade and slide in/out) */
.total-width-slide-enter-active,
.total-width-slide-leave-active {
    transition: opacity 0.3s ease-out, transform 0.3s ease-out, width 0.3s ease-out, padding 0.3s ease-out, margin 0.3s ease-out;
    /* Add width, padding, margin to transition */
    overflow: hidden;
    /* Hide overflow during transition */
}

.total-width-slide-enter-from,
.total-width-slide-leave-to {
    opacity: 0;
    transform: translateY(10px);
    width: 0 !important;
    /* Force width to 0 for animation */
    padding-left: 0;
    padding-right: 0;
    margin-left: 0;
    margin-right: 0;
}

/* No total-width-slide-enter-to needed if default state is desired final state */
</style>
