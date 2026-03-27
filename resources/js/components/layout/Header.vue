<script setup lang="ts">
import { home, login, register, logout as logout } from '@/routes';
import { usePage, Link } from '@inertiajs/vue3';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { MessageSquare } from 'lucide-vue-next';
import AppLogoIcon from '../AppLogoIcon.vue';
import TextLink from '@/components/TextLink.vue';

defineProps<{
    canRegister: boolean;
}>();
const page = usePage();
const user = page.props.auth?.user ?? null;
const avatarurl = user?.avatar ? `/storage/${user.avatar}` : null;
</script>

<template>
    <header
        class="sticky top-0 z-10 w-full bg-white text-sm not-has-[nav]:hidden"
    >
        <nav
            class="flex items-center justify-between gap-4 border-b border-gray-200 px-3 py-3 md:gap-8 md:px-10"
        >
            <Link :href="home()">
                <div
                    class="flex text-base font-black whitespace-nowrap md:text-2xl"
                >
                    <div
                        class="mr-2 flex aspect-square size-8 items-center justify-center"
                    >
                        <AppLogoIcon class="size-5 fill-current" />
                    </div>
                    <span class="hidden text-black sm:block">TestProject</span>
                    <span class="hidden text-orange-500 sm:block">2</span>
                </div>
            </Link>

            <div class="flex items-center gap-4">
                <template v-if="$page.props.auth.user">
                    <Link :href="logout()" class="mx-auto block text-sm">
                        <span
                            class="hidden text-black hover:text-blue-400 hover:underline sm:block"
                            >Tancar sessió</span
                        >
                    </Link>
                </template>
                <div
                    v-else
                    class="flex gap-2 font-semibold whitespace-nowrap md:gap-6"
                >
                    <Link
                        :href="login()"
                        class="inline-block rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-400 hover:bg-gray-50"
                    >
                        Inicia sessió
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-block rounded-lg bg-orange-500 px-5 py-2 text-sm font-medium text-white transition-all hover:bg-orange-600 hover:shadow-lg hover:shadow-orange-500/30"
                    >
                        Registra't
                    </Link>
                </div>
            </div>
        </nav>
    </header>
</template>
