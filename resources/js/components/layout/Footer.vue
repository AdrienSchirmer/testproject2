<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
import AppLogoIcon from '../AppLogoIcon.vue';
</script>

<template>
    <footer class="w-full border-t border-gray-200 bg-white px-4 py-8 md:px-10">
        <div class="flex flex-col gap-8 md:flex-row md:justify-between">
            <div class="max-w-sm">
                <Link :href="home()">
                    <div
                        class="flex text-base font-black whitespace-nowrap text-black md:text-2xl"
                    >
                        <div
                            class="mr-2 flex aspect-square size-8 items-center justify-center"
                        >
                            <AppLogoIcon class="size-5 fill-current" />
                        </div>
                        TestProject
                        <span class="text-orange-500">2</span>
                    </div>
                </Link>
                <p class="mt-3 text-sm leading-relaxed text-gray-600">
                    Fomentant software de qualitat, com els llocs que trovareu
                    aquí!
                </p>
            </div>

            <div>
                <ul
                    class="flex flex-col gap-3 font-semibold text-gray-700 md:text-right"
                >
                    <li class="text-green-600 decoration-green-600">
                        <a
                            >La pagina web no nomes es sostenible, si no
                            accessible :> yeey</a
                        >
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</template>
