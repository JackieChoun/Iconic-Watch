<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';
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

interface ArticleForm {
    affiche_film: File | null;
    images_montre: File[];
    mouvement_montre: string;
    en_vente: boolean;
    lien_vente: string;
    taille_montre: string;
    description: string;
    id_film: number | '';
    id_montre: number | '';
}

// -----------------
// Props
// -----------------
const props = defineProps<{
    films: Film[];
    montres: Montre[];
}>();

// -----------------
// Formulaire
// -----------------
const form = useForm<ArticleForm>({
    affiche_film: null,
    images_montre: [],
    mouvement_montre: '',
    en_vente: false,
    lien_vente: '',
    taille_montre: '',
    description: '',
    id_film: '',
    id_montre: '',
});

// -----------------
// Previews et gestion images
// -----------------
const affichePreview = ref<string | null>(null);
const montrePreviews = ref<string[]>([]);

function handleAffiche(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.affiche_film = target.files[0];
        affichePreview.value = URL.createObjectURL(target.files[0]);
    }
}

function addImageInput() {
    if (form.images_montre.length < 4) {
        form.images_montre.push(null as unknown as File);
    }
}

function handleImageChange(e: Event, index: number) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.images_montre[index] = target.files[0];
        montrePreviews.value[index] = URL.createObjectURL(target.files[0]);
    }
}

function removeImage(index: number) {
    form.images_montre.splice(index, 1);
    montrePreviews.value.splice(index, 1);
}

// -----------------
// Soumission
// -----------------
function submit() {
    form.post(route('admin.articles.store'));
}

// -----------------
// Breadcrumbs
// -----------------
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: route('admin.articles.index') },
    { title: 'Création article', href: route('admin.articles.create') },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Créer un article</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Film -->
                <div>
                    <label class="block font-medium">Film</label>
                    <select v-model="form.id_film" class="w-full rounded border p-2">
                        <option disabled value="">-- Sélectionner un film --</option>
                        <option v-for="film in films" :key="film.id" :value="film.id">
                            {{ film.title }}
                        </option>
                    </select>
                    <span v-if="form.errors.id_film" class="text-sm text-red-500">{{ form.errors.id_film }}</span>
                </div>

                <!-- Montre -->
                <div>
                    <label class="block font-medium">Montre</label>
                    <select v-model="form.id_montre" class="w-full rounded border p-2">
                        <option disabled value="">-- Sélectionner une montre --</option>
                        <option v-for="montre in montres" :key="montre.id_montre" :value="montre.id_montre">
                            {{ montre.info_montre }} ({{ montre.marque?.nom_marque }})
                        </option>
                    </select>
                    <span v-if="form.errors.id_montre" class="text-sm text-red-500">{{ form.errors.id_montre }}</span>
                </div>

                <!-- Affiche film -->
                <div>
                    <label class="block font-medium">Affiche du film</label>
                    <label
                        for="affiche_film"
                        class="inline-flex cursor-pointer items-center rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100"
                    >
                        Choisir une image
                    </label>
                    <input type="file" @change="handleAffiche" accept="image/*" class="hidden" id="affiche_film" />
                    <div v-if="affichePreview" class="mt-2">
                        <img :src="affichePreview" class="h-40 rounded shadow" />
                    </div>
                    <span v-if="form.errors.affiche_film" class="text-sm text-red-500">{{ form.errors.affiche_film }}</span>
                </div>

                <!-- Images montre -->
                <div>
                    <label class="block font-medium">Images de la montre (max 4)</label>
                    <div class="mt-2 flex">
                        <div v-for="(img, i) in form.images_montre" :key="i">
                            <button type="button" @click="removeImage(i)" class="mr-2 rounded bg-red-600 px-1 text-white">X</button>
                            <input type="file" accept="image/*" @change="(e) => handleImageChange(e, i)" class="mb-2" />
                            <img v-if="montrePreviews[i]" :src="montrePreviews[i]" class="h-24 w-24 rounded" />
                        </div>

                        <button
                            type="button"
                            @click="addImageInput"
                            class="flex h-24 w-24 items-center justify-center rounded border text-gray-500 hover:bg-gray-100"
                            v-if="form.images_montre.length < 4"
                        >
                            +
                        </button>
                    </div>
                    <span v-if="form.errors['images_montre']" class="text-sm text-red-500">{{ form.errors['images_montre'] }}</span>
                </div>

                <!-- Mouvement -->
                <div>
                    <label class="block font-medium">Mouvement</label>
                    <input v-model="form.mouvement_montre" type="text" class="w-full rounded border p-2" placeholder="ex: Automatique" />
                </div>

                <!-- En vente -->
                <div class="flex items-center gap-2">
                    <input v-model="form.en_vente" type="checkbox" id="vente" />
                    <label for="vente">Toujours en vente</label>
                </div>

                <!-- Lien vente -->
                <div v-if="form.en_vente">
                    <label class="block font-medium">Lien vers le site officiel</label>
                    <input v-model="form.lien_vente" type="url" class="w-full rounded border p-2" placeholder="https://..." />
                </div>

                <!-- Taille -->
                <div>
                    <label class="block font-medium">Taille</label>
                    <input v-model="form.taille_montre" type="text" class="w-full rounded border p-2" placeholder="ex: 42mm" />
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-medium">Description</label>
                    <textarea v-model="form.description" class="w-full rounded border p-2" rows="5"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        Créer
                    </button>
                    <Link :href="route('admin.articles.index')" class="rounded bg-gray-300 px-4 py-2 font-semibold hover:bg-gray-400">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
