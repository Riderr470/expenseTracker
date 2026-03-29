<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                        Log in
                    </Link>
                    <Link :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div>
            <!-- Welcome Body -->
            <div class="w-full max-w-[335px] lg:max-w-4xl mx-auto">

                <!-- Brand -->
                <div class="flex items-center gap-3 mb-12">
                    <span
                        class="text-[15px] font-semibold tracking-[0.1em] text-green-700 dark:text-green-500 uppercase">
                        ExpenseTracker
                    </span>
                </div>

                <!-- Headline -->
                <div class="mb-8 max-w-xl">
                    <h1
                        class="text-[clamp(1.75rem,1rem+2.5vw,3rem)] font-bold leading-[1.12] tracking-tight text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Log it today.<br />
                        <span class="text-green-700 dark:text-green-500">Understand it by month-end.</span>
                    </h1>
                    <p
                        class="text-[clamp(0.95rem,0.9rem+0.25vw,1.05rem)] text-[#6b6b65] dark:text-[#9a9994] leading-relaxed">
                        A no-fuss personal expense journal. Add what you spend,
                        tag it simply, and see exactly where your money went — every single month.
                    </p>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-4 mb-16 flex-wrap">
                    <Link :href="route('register')"
                        class="inline-flex items-center gap-2 rounded-sm bg-green-700 hover:bg-green-800 dark:bg-green-600 dark:hover:bg-green-700 text-white px-6 py-2.5 text-[14px] font-medium tracking-wide transition-all duration-200">
                        Start tracking — it's free
                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                            <path d="M2 6.5h9M7.5 3l3.5 3.5-3.5 3.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </Link>
                    <Link :href="route('login')"
                        class="text-[14px] text-[#6b6b65] dark:text-[#9a9994] hover:text-green-700 dark:hover:text-green-500 transition-colors duration-200">
                        Already have an account? Log in →
                    </Link>
                </div>

                <!-- What it does — horizontal divider strip -->
                <div class="grid grid-cols-3 gap-px bg-[#e0dfd9] dark:bg-[#2a2927] rounded-sm overflow-hidden mb-12">
                    <div class="bg-[#FDFDFC] dark:bg-[#111110] px-5 py-5">
                        <p
                            class="text-[13.5px] font-semibold text-green-700 dark:text-green-500 mb-1 uppercase tracking-widest">
                            Log</p>
                        <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-snug">Add expenses quickly —
                            amount,
                            category, note.</p>
                    </div>
                    <div class="bg-[#FDFDFC] dark:bg-[#111110] px-5 py-5">
                        <p
                            class="text-[13.5px] font-semibold text-green-700 dark:text-green-500 mb-1 uppercase tracking-widest">
                            Organise</p>
                        <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-snug">Group by category — food,
                            rent,
                            travel, anything you need.</p>
                    </div>
                    <div class="bg-[#FDFDFC] dark:bg-[#111110] px-5 py-5">
                        <p
                            class="text-[13.5px] font-semibold text-green-700 dark:text-green-500 mb-1 uppercase tracking-widest">
                            Review</p>
                        <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-snug">See your monthly
                            breakdown —
                            total spent, by category.</p>
                    </div>
                </div>

                <!-- Feature list — two column, left aligned, no icon circles -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-14">
                    <div
                        class="border border-black/[0.07] dark:border-white/[0.07] rounded-sm p-5 hover:shadow-[0_4px_14px_oklch(0.2_0.01_80/0.06)] transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 shrink-0 text-green-700 dark:text-green-500" width="18" height="18"
                                viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <rect x="1" y="3" width="16" height="12" rx="2" stroke="currentColor"
                                    stroke-width="1.4" />
                                <path d="M1 7.5h16" stroke="currentColor" stroke-width="1.4" />
                                <path d="M5 11.5h3M10 11.5h3" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" />
                            </svg>
                            <div>
                                <h3 class="text-[14px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Manual
                                    expense
                                    entry</h3>
                                <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-relaxed">You're in full
                                    control. Add each expense yourself — no bank syncing, no automation.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="border border-black/[0.07] dark:border-white/[0.07] rounded-sm p-5 hover:shadow-[0_4px_14px_oklch(0.2_0.01_80/0.06)] transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 shrink-0 text-green-700 dark:text-green-500" width="18" height="18"
                                viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M2 13 6 8l4 3 4-6" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="14" cy="4" r="2" stroke="currentColor" stroke-width="1.4" />
                            </svg>
                            <div>
                                <h3 class="text-[14px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Monthly
                                    spend
                                    summary</h3>
                                <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-relaxed">At the end of
                                    any
                                    month, see exactly how much went where — clear, no guesswork.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="border border-black/[0.07] dark:border-white/[0.07] rounded-sm p-5 hover:shadow-[0_4px_14px_oklch(0.2_0.01_80/0.06)] transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 shrink-0 text-green-700 dark:text-green-500" width="18" height="18"
                                viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M9 1v16M1 9h16" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" />
                                <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="1.4" />
                            </svg>
                            <div>
                                <h3 class="text-[14px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Custom
                                    categories
                                </h3>
                                <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-relaxed">Name your
                                    categories
                                    however you like. Groceries, petrol, chai — your labels, your way.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="border border-black/[0.07] dark:border-white/[0.07] rounded-sm p-5 hover:shadow-[0_4px_14px_oklch(0.2_0.01_80/0.06)] transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 shrink-0 text-green-700 dark:text-green-500" width="18" height="18"
                                viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <rect x="3" y="1" width="12" height="16" rx="2" stroke="currentColor"
                                    stroke-width="1.4" />
                                <path d="M6 5h6M6 8.5h6M6 12h4" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" />
                            </svg>
                            <div>
                                <h3 class="text-[14px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Expense
                                    history
                                </h3>
                                <p class="text-[13px] text-[#6b6b65] dark:text-[#6b6b65] leading-relaxed">Browse past
                                    months
                                    anytime. Your full history stays intact, always accessible.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer note -->
                <div
                    class="border-t border-black/[0.07] dark:border-white/[0.07] pt-6 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-[12.5px] text-[#9a9994] dark:text-[#5a5957]">
                        Free forever. Just for you.
                    </p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-700 dark:bg-green-500 inline-block"></span>
                        <span class="text-[12.5px] text-[#9a9994] dark:text-[#5a5957]">Your data stays yours</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
