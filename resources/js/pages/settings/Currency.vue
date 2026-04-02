<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

// ── Types ────────────────────────────────────────────────────
interface CurrencyOption {
    code: string;
    symbol: string;
    label: string;
}

interface Props {
    currencies: CurrencyOption[];
    currentCurrency: string;
}

const props = defineProps<Props>();

// ── State ────────────────────────────────────────────────────
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Currency settings', href: '/settings/currency' },
];

const form = useForm({
    currency: props.currentCurrency,
});

// ── Actions ──────────────────────────────────────────────────
const submit = () => {
    form.post(route('settings.currency.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Currency settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                
                <!-- Header -->
                <HeadingSmall
                    title="Global Currency"
                    description="Select the currency you want to use throughout the application. All prices will be displayed in this format."
                />

                <!-- Form -->
                <form @submit.prevent="submit" class="flex flex-col space-y-6 max-w-xl">
                    
                    <div class="flex flex-col space-y-2">
                        <label 
                            for="currency" 
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                            Currency Preference
                        </label>
                        
                        <!-- Styled to match shadcn-vue / generic UI inputs -->
                        <select
                            id="currency"
                            v-model="form.currency"
                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option 
                                v-for="c in currencies" 
                                :key="c.code" 
                                :value="c.code"
                            >
                                {{ c.label }} ({{ c.symbol }}) - {{ c.code }}
                            </option>
                        </select>
                        
                        <p v-if="form.errors.currency" class="text-[0.8rem] font-medium text-destructive">
                            {{ form.errors.currency }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing">
                            Save Selection
                        </Button>
                        
                        <Transition
                            enter-active-class="transition ease-in-out duration-300"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out duration-300"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm text-muted-foreground">
                                Saved successfully.
                            </p>
                        </Transition>
                    </div>
                </form>

            </div>
        </SettingsLayout>
    </AppLayout>
</template>