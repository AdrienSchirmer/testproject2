<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        recommendations: {
            user?: {
                name: string;
            };
            id: number;
            site_name: string;
            city: string;
            description: string;
            created_at: string;
            valoration: number;
            categories: {
                name: string;
            }[];
        }[];
        categories: {
            id: number;
            name: string;
        }[];
        canRegister?: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const selectedCategory = ref('');
const searchquery = ref<string>('');

const recommendationsData = ref(props.recommendations);

const filterByCategory = () => {
    const categoryquery = selectedCategory.value
        ? `?category_id=${encodeURIComponent(selectedCategory.value)}`
        : '';
    const searchqueryquery = searchquery.value
        ? `?search=${searchquery.value}`
        : '';
    fetch(`/recommendations/filterall${categoryquery}${searchqueryquery}`)
        .then((response) => response.json())
        .then((data) => {
            recommendationsData.value = data.recommendations ?? [];
        })
        .catch((error) => {
            console.error('Error', error);
        });
};

const filterBySearch = () => {
    const searchqueryquery = searchquery.value
        ? `?search=${searchquery.value}`
        : '';
    const categoryquery = selectedCategory.value
        ? `?category_id=${encodeURIComponent(selectedCategory.value)}`
        : '';
    console.log(searchquery);
    fetch(`/recommendations/filterall${searchqueryquery}${categoryquery}`)
        .then((response) => response.json())
        .then((data) => {
            recommendationsData.value = data.recommendations ?? [];
        })
        .catch((error) => {
            console.error('Error', error);
        });
};
const formatDate = (date: string) =>
    new Date(date).toLocaleDateString('ca-ES', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
</script>

<template>
    <Head title="Recommendations" />

    <PublicLayout :can-register="canRegister">
        <div class="p-4">
            <h1 class="text-2xl font-semibold">Recommenacions</h1>

            <input
                v-model="searchquery"
                @keyup="filterBySearch()"
                aria-labelledby="search"
                type="search"
                placeholder="Cercar una recomenació per nom del lloc o ciutat...."
                class="w-full rounded-lg border border-sidebar-border bg-white py-2 pr-4 pl-10 text-black"
                aria-label="Cerca d'usuaris"
            />

            <div class="mt-4 max-w-xs">
                <label
                    for="category_filter"
                    class="mb-1 block text-sm font-medium"
                >
                    Filtra per categoria
                </label>
                <select
                    id="category_filter"
                    v-model="selectedCategory"
                    @change="filterByCategory"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-black"
                >
                    <option value="">Totes</option>
                    <option
                        v-for="category in props.categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <p
                v-if="recommendationsData.length === 0"
                class="mt-3 text-sm text-gray-500"
            >
                Encara no hi ha cap recommenació per part de la comunitat 😴😔
            </p>

            <ul v-else class="mt-4 space-y-3">
                <li
                    v-for="recommendation in recommendationsData"
                    :key="recommendation.id"
                    class="rounded-lg border border-gray-200 bg-white p-4"
                >
                    <p class="font-semibold text-black">
                        {{ recommendation.site_name }}:
                        <span class="font-normal text-gray-500">
                            {{ recommendation.city }}</span
                        >
                    </p>
                    <p class="text-sm text-gray-600">
                        Usuari: {{ recommendation.user?.name ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Valoració: {{ recommendation.valoration }}/5
                    </p>
                    <div class="flex">
                        <p class="mt-1 text-sm text-gray-600">Categories:</p>
                        <p
                            v-for="categoria in recommendation.categories"
                            class="mt-1 text-sm text-gray-600"
                        >
                            {{ categoria.name }}
                        </p>
                    </div>
                    <p class="mt-1 text-sm text-black">Descripció:</p>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ recommendation.description }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        Data publicació:
                        {{ formatDate(recommendation.created_at) }}
                    </p>
                </li>
            </ul>
        </div>
    </PublicLayout>
</template>
