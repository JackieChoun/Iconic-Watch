<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const page = usePage();

defineProps<{
    films: Array<{ id: number; tmdb_id: number; title: string }>;
}>();

// flash message réactif
const flashMessage = ref<string | null>(null);

// Watch les props Inertia pour récupérer flash.success dynamiquement
watch(
    () => usePage().props.flash?.success,
    (message) => {
        if (message) {
            flashMessage.value = message;
            setTimeout(() => {
                flashMessage.value = null;
            }, 4000); // disparaît après 4 sec
        }
    },
    { immediate: true },
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Films', href: route('films.index') },
];

const form = useForm({
    tmdb_id: '',
});

function submitForm() {
    if (!form.tmdb_id) {
        alert('Merci de saisir un Identifiant TMDB.');
        return;
    }

    if (!confirm("Confirmez-vous l'ajout de ce film ?")) {
        return;
    }

    form.post(route('films.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('tmdb_id'); // vide le champ après ajout
        },
    });
}

// Fonction suppression
function deleteFilm(id: number) {
    if (confirm('Confirmer la suppression ?')) {
        router.delete(route('films.destroy', id));
    }
}
</script>

<template>
    <Head title="Films (Admin)" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="flashMessage" class="my-4 rounded border border-green-400 bg-green-100 px-4 py-2 text-center text-green-700 lg:mx-100">
            {{ flashMessage }}
        </div>

        <div class="mx-4 my-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Liste des films</h1>
            <div>
                <input v-model="form.tmdb_id" type="number" placeholder="ID TMDB" class="rounded border px-3 py-1" required />
                <button class="cursor-pointer rounded bg-blue-700 px-4 py-1 text-white hover:bg-blue-800" @click.prevent="submitForm">
                    Ajouter un film
                </button>
            </div>
        </div>

        <table class="w-full border text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">TMDb ID</th>
                    <th class="border px-4 py-2">Titre</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="film in films" :key="film.id">
                    <td class="border px-4 py-2">{{ film.id }}</td>
                    <td class="border px-4 py-2">{{ film.tmdb_id }}</td>
                    <td class="border px-4 py-2">{{ film.title }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button
                            @click="deleteFilm(film.id)"
                            class="cursor-pointer rounded bg-red-700 px-3 py-1 text-white transition hover:bg-red-800"
                        >
                            Supprimer
                        </button>
                    </td>
                </tr>
                <tr v-if="films.length === 0">
                    <td colspan="4" class="py-4 text-center text-gray-500">Aucun film pour le moment.</td>
                </tr>
            </tbody>
        </table>
    </AppLayout>
</template>
