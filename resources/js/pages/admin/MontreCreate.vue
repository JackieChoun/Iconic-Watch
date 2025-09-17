<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Montres', href: route('montres.index') },
    { title: 'Création montre', href: route('montres.create') },
];

const props = defineProps(['marques']);

const form = useForm({
    info_montre: '',
    id_marque: '',
    image_montre: null,
});

function createMontre() {
    form.post(route('montres.store'), {
        forceFormData: true, // indispensable pour l’upload
    });
}
</script>

<template>
    <Head title="Montres" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-6 max-w-xl rounded-xl p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold">Création d'une montre</h1>

            <form @submit.prevent="createMontre" enctype="multipart/form-data" class="space-y-6">
                <!-- Nom / infos -->
                <div>
                    <label for="info_montre" class="block text-lg font-medium text-gray-700">Nom / Infos</label>
                    <input
                        type="text"
                        v-model="form.info_montre"
                        id="info_montre"
                        class="mt-1 block w-full rounded-md border-gray-300 text-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required
                    />
                    <p v-if="form.errors.info_montre" class="text-sm text-red-500">{{ form.errors.info_montre }}</p>
                </div>

                <!-- Marque -->
                <div>
                    <label for="id_marque" class="block text-lg font-medium text-gray-700">Marque</label>
                    <select
                        v-model="form.id_marque"
                        id="id_marque"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none sm:text-sm"
                        required
                    >
                        <option value="">Sélectionner une marque</option>
                        <option v-for="marque in props.marques" :key="marque.id_marque" :value="marque.id_marque">
                            {{ marque.nom_marque }}
                        </option>
                    </select>
                    <p v-if="form.errors.id_marque" class="text-sm text-red-500">{{ form.errors.id_marque }}</p>
                </div>

                <!-- Image -->
                <div>
                    <label for="image_montre" class="block text-lg font-medium text-gray-700">Image</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="(e) => (form.image_montre = e.target.files[0])"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                        required
                    />
                    <p v-if="form.errors.image_montre" class="text-sm text-red-500">{{ form.errors.image_montre }}</p>
                </div>

                <!-- Bouton -->
                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="cursor-pointer rounded bg-blue-700 px-4 py-2 font-semibold text-white shadow hover:bg-blue-800 disabled:opacity-50"
                    >
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
