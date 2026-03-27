<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import AppLayout from '@/layouts/AppLayout.vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
        tests: Array<{
            id: number;
            name: string;
            description: string;
        }>;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>

    <AppLayout>
        <Head title="Tests" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-4">Tests</h1>
                        <ul>
                            <li v-for="test in tests" :key="test.id" class="mb-2">
                                <h2 class="text-xl font-semibold">{{ test.name }}</h2>
                                <p>{{ test.description }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
