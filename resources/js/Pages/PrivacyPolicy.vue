<script setup>
import { Head } from '@inertiajs/inertia-vue3';

defineProps({
    policy: String,
});
</script>

<template>

    <Head :title="__('Privacy Policy')" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <Link class="flex-none text-xl font-semibold dark:text-white" :href="route('dashboard')"
                        aria-label="Brand">
                    <app-logo class="block h-1/2 w-auto" />
                    </Link>
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose"
                    v-html="policy" />
            </div>
        </div>
    </div>
</template>
