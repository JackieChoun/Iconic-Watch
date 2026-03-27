<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    affiche_film: string | null;
    images_montre: string[];
    mouvement_montre: string;
    en_vente: boolean;
    lien_vente: string | null;
    taille_montre: string;
    description: string;
    id_film: number;
    id_montre: number;
}

// -----------------
// Props
// -----------------
const props = defineProps<{
    article: Article;
    films: Film[];
    montres: Montre[];
}>();

// -----------------
// Formulaire Inertia
// -----------------
const form = useForm({
    affiche_film: null as File | null,
    images_montre: [] as (File | null)[],
    existing_images: [...props.article.images_montre],
    mouvement_montre: props.article.mouvement_montre || '',
    en_vente: !!props.article.en_vente,
    lien_vente: props.article.lien_vente ?? '',
    taille_montre: props.article.taille_montre || '',
    description: props.article.description || '',
    id_film: props.article.id_film || 0,
    id_montre: props.article.id_montre || 0,
});

// -----------------
// Previews
// -----------------
const affichePreview = ref(props.article.affiche_film ? `/storage/${props.article.affiche_film}` : null);
const montrePreviews = ref(props.article.images_montre.map((img) => `/storage/${img}`));

// -----------------
// Gestion images
// -----------------
function handleAffiche(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files?.[0]) {
        form.affiche_film = target.files[0];
        affichePreview.value = URL.createObjectURL(target.files[0]);
    }
}

function addImageInput() {
    if (form.images_montre.length + montrePreviews.value.length < 4) {
        form.images_montre.push(null);
    }
}

function handleImageChange(e: Event, index: number) {
    const target = e.target as HTMLInputElement;
    if (target.files?.[0]) {
        form.images_montre[index] = target.files[0];
        montrePreviews.value.push(URL.createObjectURL(target.files[0]));
    }
}

function removeExistingImage(index: number) {
    montrePreviews.value.splice(index, 1);
    form.existing_images.splice(index, 1);
}

function removeNewImage(index: number) {
    form.images_montre.splice(index, 1);
}

// -----------------
// Submit
// -----------------
function submit() {
    const data = new FormData();

    if (form.affiche_film) data.append('affiche_film', form.affiche_film);
    form.images_montre.forEach((file, i) => {
        if (file) data.append(`images_montre[${i}]`, file);
    });

    data.append('id_film', String(form.id_film));
    data.append('id_montre', String(form.id_montre));
    data.append('mouvement_montre', form.mouvement_montre);
    data.append('en_vente', form.en_vente ? '1' : '0');
    data.append('lien_vente', form.lien_vente);
    data.append('taille_montre', form.taille_montre);
    data.append('description', form.description);
    form.existing_images.forEach((img) => data.append('existing_images[]', img));

    router.post(route('admin.articles.update.post', props.article.id_article), data, {
        onSuccess: () => console.log('Article mis à jour'),
        onError: (errors) => console.log('Erreurs', errors),
    });
}

// -----------------
// Breadcrumbs
// -----------------
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: route('admin.articles.index') },
    { title: 'Modifier article', href: route('admin.articles.edit', props.article.id_article) },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Modifier l’article</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Film -->
                <div>
                    <label class="block font-medium">Film</label>
                    <select v-model.number="form.id_film" class="w-full rounded border p-2">
                        <option disabled value="0">-- Sélectionner un film --</option>
                        <option v-for="film in films" :key="film.id" :value="film.id">{{ film.title }}</option>
                    </select>
                </div>

                <!-- Montre -->
                <div>
                    <label class="block font-medium">Montre</label>
                    <select v-model.number="form.id_montre" class="w-full rounded border p-2">
                        <option disabled value="0">-- Sélectionner une montre --</option>
                        <option v-for="montre in montres" :key="montre.id_montre" :value="montre.id_montre">
                            {{ montre.info_montre }} ({{ montre.marque?.nom_marque }})
                        </option>
                    </select>
                </div>

                <!-- Affiche -->
                <div>
                    <label class="block font-medium">Affiche du film</label>
                    <input type="file" @change="handleAffiche" accept="image/*" class="mt-2" />
                    <img v-if="affichePreview" :src="affichePreview" class="mt-2 h-40 rounded shadow" />
                </div>

                <!-- Images montre -->
                <div>
                    <label class="block font-medium">Images de la montre</label>
                    <div class="mt-2 flex flex-wrap gap-3">
                        <div v-for="(img, i) in montrePreviews" :key="i" class="relative">
                            <img :src="img" class="h-24 w-24 rounded object-cover" />
                            <button
                                type="button"
                                @click="removeExistingImage(i)"
                                class="absolute top-1 right-1 rounded bg-red-600 px-1 text-xs text-white"
                            >
                                X
                            </button>
                        </div>

                        <div v-for="(img, i) in form.images_montre" :key="'new-' + i">
                            <input type="file" @change="(e) => handleImageChange(e, i)" />
                            <button type="button" @click="removeNewImage(i)" class="text-sm text-red-600">Supprimer</button>
                        </div>

                        <button
                            v-if="montrePreviews.length + form.images_montre.length < 4"
                            type="button"
                            @click="addImageInput"
                            class="flex h-24 w-24 items-center justify-center rounded border text-gray-500"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Mouvement -->
                <div>
                    <label class="block font-medium">Mouvement</label>
                    <input v-model="form.mouvement_montre" type="text" class="w-full rounded border p-2" />
                </div>

                <!-- Vente -->
                <div class="flex items-center gap-2">
                    <input v-model="form.en_vente" type="checkbox" id="vente" />
                    <label for="vente">Toujours en vente</label>
                </div>

                <div v-if="form.en_vente">
                    <label class="block font-medium">Lien de vente</label>
                    <input v-model="form.lien_vente" type="url" class="w-full rounded border p-2" />
                </div>

                <!-- Taille -->
                <div>
                    <label class="block font-medium">Taille</label>
                    <input v-model="form.taille_montre" type="text" class="w-full rounded border p-2" />
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-medium">Description</label>
                    <textarea v-model="form.description" class="w-full rounded border p-2" rows="5"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button class="rounded bg-yellow-600 px-4 py-2 text-white">Mettre à jour</button>
                    <Link :href="route('admin.articles.index')" class="rounded bg-gray-300 px-4 py-2">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
