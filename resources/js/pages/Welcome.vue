<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { dashboard, login, register, logout } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage();
const user = page.props.auth?.user ?? null;
</script>

<template>
    <Head title="Benvinguda" />

    <PublicLayout :can-register="canRegister">
        <div class="flex-lg-row flex gap-3">
            <section
                class="mx-auto max-w-3xl rounded-2xl border border-gray-200 bg-white p-8 shadow-sm"
                v-if="$page.props.auth.user"
            >
                <h1 class="text-3xl font-bold text-gray-900">
                    Hola de nou! {{ user.name }}
                </h1>
                <p class="mt-3 text-gray-600">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center rounded-lg bg-orange-500 px-5 py-2.5 font-semibold text-white hover:bg-orange-600"
                    >
                        Veure els meus llocs recomentas!
                    </Link>
                </p>
                <h3 class="text-3xl font-bold text-gray-900 text-center mt-3">O</h3>
                <Link :href="logout()" class="mx-auto mt-3 block text-sm">
                    <span
                        class="inline-flex items-center rounded-lg bg-gray-500 px-5 py-2.5 font-semibold text-white hover:bg-red-600""
                        >Tancar sessió</span
                    >
                </Link>
            </section>

            <section
                class="mx-auto max-w-3xl rounded-2xl border border-gray-200 bg-white p-8 shadow-sm"
            >
                <h1 class="text-3xl font-bold text-gray-900">
                    Benvingut a TESTPROJECT2
                </h1>
                <p class="mt-3 text-gray-600">
                    Aquesta pagina web busca asolir el testproject2, un petit
                    projecte que s'ha de fer en un dia per poder mesurar les
                    habilitats en desenvolupament web. L'objectiu, consta en
                    crear una pagina minimalista, a on els usuaris poden
                    recomenar llocs.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <Link
                        :href="dashboard()"
                        class="inline-flex items-center rounded-lg bg-orange-500 px-5 py-2.5 font-semibold text-white hover:bg-orange-600"
                    >
                        Veure llocs recomenats per la comunitat!
                    </Link>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
