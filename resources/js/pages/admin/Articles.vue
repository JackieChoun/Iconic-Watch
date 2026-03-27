<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';

// -----------------
// Types
// -----------------
interface Film {
    id: number;
    title: string;
}

interface Marque {
    id_marque: number;
    nom_marque: string;
}

interface Montre {
    id_montre: number;
    info_montre: string;
    marque?: Marque;
}

interface Article {
    id_article: number;
    film?: Film;
    montre?: Montre;
    description?: string;
}

// -----------------
// Props
// -----------------
const props = defineProps<{
    articles: Article[];
}>();

// -----------------
// Actions
// -----------------
function deleteArticle(id: number) {
    if (confirm('Confirmer la suppression ?')) {
        router.delete(route('admin.articles.destroy', id));
    }
}

// -----------------
// Breadcrumbs
// -----------------
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: route('admin.articles.index') },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Liste des articles</h1>
                <Link :href="route('admin.articles.create')" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Nouvel article
                </Link>
            </div>

            <table class="w-full overflow-x-auto border text-left">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Film</th>
                        <th class="border px-4 py-2">Montre</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="article in articles" :key="article.id_article">
                        <td class="border px-4 py-2">{{ article.id_article }}</td>
                        <td class="border px-4 py-2">
                            {{ article.film?.title ?? 'Film non trouvé' }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ article.montre?.info_montre }}
                            ({{ article.montre?.marque?.nom_marque }})
                        </td>
                        <td class="border px-4 py-2">
                            {{ article.description ? article.description.substring(0, 50) + '...' : '-' }}
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <Link
                                :href="route('admin.articles.edit', article.id_article)"
                                class="mr-2 rounded bg-yellow-500 px-3 py-1 text-white transition-colors hover:bg-yellow-600"
                            >
                                Modifier
                            </Link>
                            <button
                                @click="deleteArticle(article.id_article)"
                                title="Supprimer cet article"
                                class="cursor-pointer rounded bg-red-600 px-3 py-1 text-white transition-colors hover:bg-red-700"
                            >
                                Supprimer
                            </button>
                        </td>
                    </tr>
                    <tr v-if="articles.length === 0">
                        <td colspan="5" class="py-4 text-center text-gray-500">Aucun article pour le moment.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
