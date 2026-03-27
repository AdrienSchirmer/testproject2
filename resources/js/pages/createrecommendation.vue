<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { recommendations_store } from '@/routes';

type Category = {
    id: number;
    name: string;
};

withDefaults(
    defineProps<{
        categories: Category[];
        canRegister?: boolean;
    }>(),
    {
        canRegister: false,
    },
);
</script>

<template>
    <Head title="Create recommendation" />

    <PublicLayout :can-register="canRegister">
        <div
            class="mx-auto max-w-2xl rounded-lg border border-gray-200 bg-white p-6 text-black"
        >
            <h1 class="text-xl font-semibold">Create recommendation</h1>

            <Form
                class="mt-4 space-y-4"
                v-bind="recommendations_store.form()"
                v-slot="{ errors, processing }"
            >
                <div>
                    <label
                        for="site_name"
                        class="mb-1 block text-sm font-medium"
                        >Nom del lloc</label
                    >
                    <input
                        id="site_name"
                        name="site_name"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    />
                    <p
                        v-if="errors.site_name"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.site_name }}
                    </p>
                </div>

                <div>
                    <label for="city" class="mb-1 block text-sm font-medium"
                        >Ciutat</label
                    >
                    <input
                        id="city"
                        name="city"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    />
                    <p v-if="errors.city" class="mt-1 text-sm text-red-600">
                        {{ errors.city }}
                    </p>
                </div>

                <div>
                    <label
                        for="description"
                        class="mb-1 block text-sm font-medium"
                        >Descripció</label
                    >
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    />
                    <p
                        v-if="errors.description"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.description }}
                    </p>
                </div>

                <div>
                    <label
                        for="valoration"
                        class="mb-1 block text-sm font-medium"
                        >Valoració (1-5)</label
                    >
                    <input
                        id="valoration"
                        name="valoration"
                        type="number"
                        min="1"
                        max="5"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    />
                    <p
                        v-if="errors.valoration"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.valoration }}
                    </p>
                </div>

                <div>
                    <p class="mb-2 text-sm font-medium">Categories</p>
                    <div class="space-y-2">
                        <label
                            v-for="category in categories"
                            :key="category.id"
                            class="flex items-center gap-2 text-sm"
                        >
                            <input
                                type="checkbox"
                                name="category_ids[]"
                                :value="category.id"
                            />
                            <span>{{ category.name }}</span>
                        </label>
                    </div>
                    <p
                        v-if="errors.category_ids"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.category_ids }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="processing"
                    class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {{ processing ? 'Saving...' : 'Save recommendation' }}
                </button>
            </Form>
        </div>
    </PublicLayout>
</template>
