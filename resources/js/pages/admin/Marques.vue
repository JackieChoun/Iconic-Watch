<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Marques', href: route('marques.index') },
];

const props = defineProps<{
    marques: Array<{ id_marque: number; nom_marque: string; photo_marque: string | null; logo_marque: string | null }>;
}>();

// Fonction suppression
function deleteMarque(id: number) {
    if (confirm('Confirmer la suppression ?')) {
        router.delete(route('marques.destroy', id));
    }
}
</script>

<template>
    <Head title="Marques (Admin)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4 my-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Liste des marques</h1>
            <Link href="/admin/marques/create">
                <button class="cursor-pointer rounded bg-blue-700 px-4 py-1 text-white hover:bg-blue-800">Ajouter une marque</button>
            </Link>
        </div>
        <div class="mx-4 my-4">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nom</th>
                        <th class="border px-4 py-2">Image</th>
                        <th class="border px-4 py-2">Logo</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="marque in marques" :key="marque.id_marque">
                        <td class="border px-4 py-2">{{ marque.id_marque }}</td>
                        <td class="border px-4 py-2">{{ marque.nom_marque }}</td>
                        <td class="border px-4 py-2">
                            <img v-if="marque.photo_marque" :src="marque.photo_marque" alt="Image" class="h-20 w-20 rounded-full object-cover" />
                            <span v-else class="text-gray-500">Aucune image</span>
                        </td>
                        <td class="border px-4 py-2">
                            <img v-if="marque.logo_marque" :src="marque.logo_marque" alt="Logo" class="h-20 w-20 rounded-full object-cover" />
                            <span v-else class="text-gray-500">Aucun logo</span>
                        </td>
                        <td class="border px-4 py-2">
                            <Link :href="route('marques.edit', marque.id_marque)" class="text-blue-600 hover:underline">Modifier</Link>
                            <button @click.prevent="deleteMarque(marque.id_marque)" class="ml-2 cursor-pointer text-red-600 hover:underline">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
