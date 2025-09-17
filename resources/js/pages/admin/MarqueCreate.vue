<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Marques', href: route('marques.index') },
    { title: 'Création', href: route('marques.index') },
];

const form = useForm({
    nom_marque: '',
    photo: null as File | null,
    logo: null as File | null,
});

// Pour les aperçus
const photoPreview = ref<string | null>(null);
const logoPreview = ref<string | null>(null);

function handlePhotoChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.photo = target.files[0];
        photoPreview.value = URL.createObjectURL(form.photo);
    } else {
        form.photo = null;
        photoPreview.value = null;
    }
}

function handleLogoChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.logo = target.files[0];
        logoPreview.value = URL.createObjectURL(form.logo);
    } else {
        form.logo = null;
        logoPreview.value = null;
    }
}

function submit() {
    form.post(route('marques.store'), {
        forceFormData: true,
        onSuccess: () => {
            // Nettoyage des previews après création
            photoPreview.value = null;
            logoPreview.value = null;
        },
    });
}
</script>

<template>
    <Head title="Création Marques" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-6 max-w-xl rounded-xl bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold">Création d'une marque</h1>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
                <!-- Nom de la marque -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Nom de la marque</label>
                    <input
                        v-model="form.nom_marque"
                        type="text"
                        class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-400 focus:ring focus:outline-none"
                    />
                    <p v-if="form.errors.nom_marque" class="mt-1 text-sm text-red-600">
                        {{ form.errors.nom_marque }}
                    </p>
                </div>

                <!-- Image / Photo -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Image de montre</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handlePhotoChange"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p v-if="form.errors.photo" class="mt-1 text-sm text-red-600">
                        {{ form.errors.photo }}
                    </p>
                    <div v-if="photoPreview" class="mt-3">
                        <img :src="photoPreview" alt="Aperçu photo" class="max-h-40 rounded shadow" />
                    </div>
                </div>

                <!-- Logo -->
                <div>
                    <label class="mb-1 block font-medium text-gray-700">Logo marque</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleLogoChange"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p v-if="form.errors.logo" class="mt-1 text-sm text-red-600">
                        {{ form.errors.logo }}
                    </p>
                    <div v-if="logoPreview" class="mt-3">
                        <img :src="logoPreview" alt="Aperçu logo" class="max-h-40 rounded shadow" />
                    </div>
                </div>

                <!-- Bouton -->
                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded bg-blue-600 px-4 py-2 font-semibold text-white shadow hover:bg-blue-700 disabled:opacity-50"
                    >
                        Créer la marque
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
