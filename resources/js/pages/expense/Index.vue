<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';
import { useCurrency } from '@/composables/useCurrency'

const { currency, format } = useCurrency()

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
        groups[date].total += parseFloat(item.cost) * (item.qty ?? 1);
    }

    return groups;
});

function formatDate(dateStr: string): string {
    const date = new Date(dateStr);
    return date.toLocaleDateString();
}

const income = 0;
const expense = props.data.daily_total_expense ?? 0;
const remaining = income - expense;

const swipeStyles = ref<Record<number, string>>({});
const touchStartX = ref<Record<number, number>>({});
const touchDeltaX = ref<Record<number, number>>({});
const SWIPE_THRESHOLD = typeof window !== 'undefined' ? window.innerWidth * 0.7 : 200;

function deleteItem(id: number) {
    useForm({}).delete(`/expense/${id}`);
}

function onTouchStart(e: TouchEvent, id: number) {
    touchStartX.value[id] = e.touches[0].clientX;
    touchDeltaX.value[id] = 0;
}

function onTouchMove(e: TouchEvent, id: number) {
    const delta = e.touches[0].clientX - touchStartX.value[id];
    touchDeltaX.value[id] = delta;
    swipeStyles.value[id] = `transform: translateX(${delta}px); transition: none; opacity: ${1 - Math.abs(delta) / 160};`;
}

function onTouchEnd(e: TouchEvent, id: number) {
    const delta = touchDeltaX.value[id] ?? 0;
    if (Math.abs(delta) >= SWIPE_THRESHOLD) {
        swipeStyles.value[id] = `transform: translateX(${delta > 0 ? '60%' : '-60%'}); transition: transform 0.2s ease, opacity 0.2s ease; opacity: 0.3;`;
        setTimeout(() => requestDelete(id), 200);  // <-- changed from deleteItem to requestDelete
    } else {
        swipeStyles.value[id] = `transform: translateX(0); transition: transform 0.3s ease, opacity 0.3s ease; opacity: 1;`;
    }
}

const showConfirm = ref(false);
const pendingDeleteId = ref<number | null>(null);

function requestDelete(id: number) {
    pendingDeleteId.value = id;
    showConfirm.value = true;
}

function confirmDelete() {
    if (pendingDeleteId.value !== null) {
        deleteItem(pendingDeleteId.value);
    }
    showConfirm.value = false;
    pendingDeleteId.value = null;
}

function cancelDelete() {
    // snap swipe back if triggered from mobile
    if (pendingDeleteId.value !== null) {
        swipeStyles.value[pendingDeleteId.value] = `transform: translateX(0); transition: transform 0.3s ease, opacity 0.3s ease; opacity: 1;`;
    }
    showConfirm.value = false;
    pendingDeleteId.value = null;
}
</script>

<template>

    <Head title="Expenses" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 text-xs">

            <div class="relative h-0">
                <div class="absolute -top-8 right-4 flex gap-2 z-10">

                    <!-- Income Card -->
                    <div class="rounded-md bg-green-100 text-green-800 px-5 py-2 shadow border border-green-300">
                        <p class="font-semibold">This Week Expenses: {{ format(props.data.weekly_total_expense) ??
                            'error' }}
                        </p>
                    </div>

                    <!-- Remaining Card -->
                    <div class="rounded-md bg-green-100 text-green-800 px-5 py-2 shadow border border-green-300">
                        <p class="font-semibold">This Month Expenses: {{ format(props.data.monthly_total_expense) ??
                            'error' }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Section Bar -->
            <div
                class="rounded-xl border border-gray-300 bg-white dark:bg-gray-900 dark:border-gray-700 p-6 text-end pr-20">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Today Total Expense : {{ format(props.data.daily_total_expense ?? 0) }}
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
                        <input type="number" v-model.number="form.qty" min="1"
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
                            <th class="px-4 py-3 border-b font-semibold text-right">Total</th>
                            <th class="w-8"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(group, date) in groupedByDate" :key="date">
                            <!-- Date header row -->
                            <tr class="bg-green-100 dark:bg-green-800">
                                <td colspan="5" class="px-4 py-2 font-bold text-green-900 dark:text-white">
                                    Date : {{ date }}
                                </td>
                            </tr>

                            <!-- Items of the day -->
                            <tr v-for="item in group.items" :key="item.id" class="group relative" :data-id="item.id"
                                @touchstart="onTouchStart($event, item.id)" @touchmove="onTouchMove($event, item.id)"
                                @touchend="onTouchEnd($event, item.id)" :style="swipeStyles[item.id]">
                                <td class="px-4 py-2">{{ item.name ?? 'Nameless item' }}</td>
                                <td class="px-4 py-2">{{ item.qty ?? 1 }}</td>
                                <td class="px-4 py-2 text-right">{{ format(item.cost) }}</td>
                                <td class="px-4 py-2 text-right">{{ format(item.cost * item.qty) }}</td>
                                <td class="px-4 py-2 w-8 text-right">
                                    <button @click="requestDelete(item.id)" aria-label="Delete expense"
                                        class="hidden sm:inline-flex opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-gray-400 hover:text-red-500 p-1 rounded">
                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                                            <path d="M3 3.5h9M6 3.5V2h3v1.5M5.5 6v5M9.5 6v5M4 3.5l.5 9h6l.5-9"
                                                stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            <!-- Daily total row -->
                            <tr class="bg-gray-100 dark:bg-gray-700 border-t">
                                <td class="px-4 py-2 text-right font-semibold" colspan="3">Daily Total:</td>
                                <td class="px-4 py-2 font-bold text-right">{{ format(group.total) }}</td>
                                <td class="w-8"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>

            </div>

        </div>

        <!-- Delete Confirmation Modal -->
        <Transition name="fade">
            <div v-if="showConfirm"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
                @click.self="cancelDelete">
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xl w-full max-w-sm p-6">

                    <div class="flex items-center gap-3 mb-3">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-red-500 shrink-0"
                            aria-hidden="true">
                            <circle cx="10" cy="10" r="8.5" stroke="currentColor" stroke-width="1.5" />
                            <path d="M10 6v4M10 13.5v.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Delete this expense?</h3>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-5 leading-relaxed">
                        This action cannot be undone. The expense entry will be permanently removed.
                    </p>

                    <div class="flex gap-2 justify-end">
                        <button @click="cancelDelete"
                            class="px-4 py-2 rounded-md text-xs font-medium border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            Cancel
                        </button>
                        <button @click="confirmDelete"
                            class="px-4 py-2 rounded-md text-xs font-medium bg-red-500 hover:bg-red-600 text-white transition-colors">
                            Yes, delete
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
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

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
