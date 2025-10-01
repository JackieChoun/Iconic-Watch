<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Montres', href: route('admin.montres.index') },
];

const props = defineProps<{ marques: any[] }>();

// Pour supprimer une montre
const openDelete = ref(false);
const id = ref<number | null>(null);

function closeDeleteDialog() {
    openDelete.value = false;
}

function openDeleteDialog(montreId: number) {
    id.value = montreId;
    openDelete.value = true;
}

function deleteMontre() {
    if (id.value) {
        router.delete(route('admin.montres.destroy', { montre: id.value }), {
            onSuccess: () => closeDeleteDialog(),
            onError: (error) => console.error('Erreur lors de la suppression:', error),
        });
    }
}

// Pour dérouler les marques
const expandedMarqueId = ref<number | null>(null);
function toggleMarque(marqueId: number) {
    expandedMarqueId.value = expandedMarqueId.value === marqueId ? null : marqueId;
}

// Pour l’agrandissement de la photo
const openImage = ref(false);
const selectedImage = ref<string | null>(null);

function openImageDialog(image: string) {
    selectedImage.value = image;
    openImage.value = true;
}
</script>

<template>
    <Head title="Montres (Admin)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4 my-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Liste des montres</h1>

            <Link :href="route('admin.montres.create')">
                <button class="m-4 cursor-pointer rounded bg-blue-800 px-4 py-2 font-bold text-white hover:bg-blue-900">Ajouter une montre</button>
            </Link>
        </div>

        <div v-for="marque in props.marques" :key="marque.id_marque" class="mx-4 mt-6">
            <!-- Titre cliquable -->
            <h2 @click="toggleMarque(marque.id_marque)" class="cursor-pointer text-2xl font-bold hover:text-blue-700">
                {{ marque.nom_marque }}
                <span class="ml-2 text-sm text-gray-500"> {{ expandedMarqueId === marque.id_marque ? '▼' : '►' }} </span>
            </h2>

            <!-- Liste des montres affichée seulement si la marque est ouverte -->
            <ul v-if="expandedMarqueId === marque.id_marque" role="list" class="mt-4">
                <li v-for="montre in marque.montres" :key="montre.id_montre" class="grid grid-cols-3 border-b py-5">
                    <div class="col-span-1 flex items-center">
                        <p class="text-lg font-semibold text-gray-500">{{ montre.info_montre }}</p>
                    </div>
                    <div
                        class="col-span-1 flex cursor-pointer items-center justify-center"
                        @click="openImageDialog(`/storage/${montre.image_montre}`)"
                    >
                        <img
                            v-if="montre.image_montre"
                            :src="`/storage/${montre.image_montre}`"
                            alt="Image montre"
                            class="h-20 w-20 rounded-full object-cover hover:scale-110 hover:shadow-lg"
                        />
                        <span v-else class="text-gray-500">Aucune image</span>
                    </div>
                    <div class="col-span-1 flex items-center justify-end">
                        <button
                            @click="openDeleteDialog(montre.id_montre)"
                            class="inline-flex items-center rounded-md border border-transparent bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800"
                        >
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Dialog suppression -->
        <Dialog v-model:open="openDelete">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Êtes-vous sûr ?</DialogTitle>
                    <DialogDescription> Vous êtes sur le point de supprimer cette montre. Cette action est irréversible. </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <button @click="deleteMontre" class="rounded-md bg-red-700 px-4 py-2 text-white hover:bg-red-800">Oui, supprimer</button>
                    <button @click="closeDeleteDialog" class="ml-2 rounded-md bg-gray-300 px-4 py-2 text-black hover:bg-gray-400">Annuler</button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Dialog image agrandie -->
        <Dialog v-model:open="openImage">
            <DialogContent class="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>Image de la montre</DialogTitle>
                </DialogHeader>
                <div class="flex justify-center">
                    <img v-if="selectedImage" :src="selectedImage" alt="Montre agrandie" class="max-h-[80vh] rounded-lg shadow-lg" />
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
