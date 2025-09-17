<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Montres', href: route('montres.index') },
];

const props = defineProps<{ marques: any[] }>();

const open = ref(false);
const id = ref<number | null>(null);

function closeDialog() {
    open.value = false;
}

function openDialog(montreId: number) {
    id.value = montreId;
    open.value = true;
}

function deleteMontre() {
    if (id.value) {
        router.delete(route('montres.destroy', { montre: id.value }), {
            onSuccess: () => closeDialog(),
            onError: (error) => console.error('Erreur lors de la suppression:', error),
        });
    }
}
</script>

<template>
    <Head title="Montres (Admin)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-4 my-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Liste des montres</h1>

            <Link :href="route('montres.create')">
                <button class="m-4 cursor-pointer rounded bg-blue-800 px-4 py-2 font-bold text-white hover:bg-blue-900">Ajouter une montre</button>
            </Link>
        </div>

        <div v-for="marque in props.marques" :key="marque.id_marque" class="mx-4 mt-6">
            <h2 class="text-2xl font-bold">{{ marque.nom_marque }}</h2>

            <ul role="list" class="mt-4">
                <li v-for="montre in marque.montres" :key="montre.id_montre" class="flex justify-between gap-x-6 border-b py-5">
                    <div>
                        <p class="text-lg font-semibold text-gray-500">{{ montre.info_montre }}</p>
                    </div>
                    <div>
                        <img
                            v-if="montre.image_montre"
                            :src="`/storage/${montre.image_montre}`"
                            alt="Image montre"
                            class="h-20 w-20 rounded-full object-cover"
                        />
                        <span v-else class="text-gray-500">Aucune image</span>
                    </div>
                    <!-- <div>
                        <Link :href="route('montres.edit', montre.id_montre)">
                            <button class="rounded bg-blue-800 px-4 py-2 text-sm font-medium text-white hover:bg-blue-900">Modifier</button>
                        </Link>
                    </div> -->
                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                        <button
                            @click="openDialog(montre.id_montre)"
                            class="inline-flex items-center rounded-md border border-transparent bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800"
                        >
                            Supprimer
                        </button>

                        <Dialog v-model:open="open">
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Êtes-vous sûr ?</DialogTitle>
                                    <DialogDescription>
                                        Vous êtes sur le point de supprimer cette montre. Cette action est irréversible.
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <button @click="deleteMontre" class="rounded-md bg-red-700 px-4 py-2 text-white hover:bg-red-800">
                                        Oui, supprimer
                                    </button>
                                    <button @click="closeDialog" class="ml-2 rounded-md bg-gray-300 px-4 py-2 text-black hover:bg-gray-400">
                                        Annuler
                                    </button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </div>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
