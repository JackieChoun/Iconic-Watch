<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Marques', href: route('marques.index') },
];

const props = defineProps<{
    marques: Array<{ id_marque: number; nom_marque: string; photo_marque: string | null; logo_marque: string | null }>;
}>();

const open = ref(false);
const id = ref<number | null>(null);

function closeDialog() {
    open.value = false;
}

function openDialog(marqueId: number) {
    id.value = marqueId;
    open.value = true;
}

function deleteMarque() {
    if (id.value) {
        router.delete(route('marques.destroy', { marque: id.value }), {
            onSuccess: () => closeDialog(),
            onError: (error) => console.error('Erreur lors de la suppression:', error),
        });
    }
}
</script>

<template>
    <Head title="Marques (Admin)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4 my-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Liste des marques</h1>
            <Link href="/admin/marques/create">
                <button class="m-4 cursor-pointer rounded bg-blue-800 px-4 py-2 font-bold text-white hover:bg-blue-900">Ajouter une marque</button>
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
                            <!-- <Link :href="route('marques.edit', marque.id_marque)" class="text-blue-600 hover:underline">Modifier</Link> -->
                            <button
                                @click="openDialog(marque.id_marque)"
                                class="inline-flex items-center rounded-md border border-transparent bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800"
                            >
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Dialog v-model:open="open">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Êtes-vous sûr ?</DialogTitle>
                        <DialogDescription> Vous êtes sur le point de supprimer cette marque. Cette action est irréversible. </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <button @click="deleteMarque" class="rounded-md bg-red-700 px-4 py-2 text-white hover:bg-red-800">Oui, supprimer</button>
                        <button @click="closeDialog" class="ml-2 rounded-md bg-gray-300 px-4 py-2 text-black hover:bg-gray-400">Annuler</button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
