<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { recommendations_create as create } from '@/routes';
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        recommendations: {
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
        canRegister: false,
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
    fetch(`/recommendations/filterme${categoryquery}${searchqueryquery}`)
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
    fetch(`/recommendations/filterme${searchqueryquery}${categoryquery}`)
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
    <Head title="My Recommendations" />

    <PublicLayout :can-register="canRegister">
        <div class="p-4">
            <h1 class="text-2xl font-semibold">Les meves recomenacións</h1>

            <Link
                class="mt-5 inline-flex items-center rounded-lg bg-gray-500 px-5 py-2.5 font-semibold text-white hover:bg-orange-600"
                :href="create()"
            >
                <Plus></Plus>
                Nova recomenació
            </Link>

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
            <div
                v-if="recommendationsData.length === 0"
                class="mt-4 text-sm text-gray-500"
            >
                <p>Encara no tens recomenacions!</p>

                <Link
                    class="mt-5 inline-flex items-center rounded-lg bg-gray-500 px-5 py-2.5 font-semibold text-white hover:bg-orange-600"
                    :href="create()"
                >
                    Crea la teva primera recomenació
                </Link>
            </div>

            <ul v-else class="mt-4 space-y-3">
                <li
                    v-for="recommendation in recommendationsData"
                    :key="recommendation.id"
                    class="rounded-lg border border-gray-200 bg-white p-4"
                >
                    <p class="font-semibold text-gray-900">
                        {{ recommendation.site_name }}:
                        <span class="font-normal text-gray-500">
                            {{ recommendation.city }}</span
                        >
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        Valoració: {{ recommendation.valoration }}/5
                    </p>

                    <div class="flex">
                        <p class="right-1 mt-1 text-sm text-gray-600">
                            Categories:
                        </p>
                        <p
                            v-for="categoria in recommendation.categories"
                            class="mt-1 text-sm text-gray-600"
                        >
                            {{ categoria.name }}
                        </p>
                    </div>

                    <p class="mt-2 text-sm text-gray-700">
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
