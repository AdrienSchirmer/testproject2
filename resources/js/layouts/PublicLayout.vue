<script setup lang="ts">
import Header from '@/components/layout/Header.vue';
import Footer from '@/components/layout/Footer.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps<{
    canRegister: boolean;
}>();
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <Header :can-register="canRegister" />

        <main class="mx-auto my-10 w-full max-w-7xl flex-1 px-10">
            <slot></slot>
        </main>

        <Footer />
    </div>
</template>
