<script setup>
import SiteLayout from '@/layouts/SiteLayout.vue';
import { defineProps, ref } from 'vue';

const props = defineProps({
    marque: Object,
    montres: Array,
});

// Pour la lightbox
const selectedImage = ref(null);
function openImage(image) {
    selectedImage.value = image;
}
function closeImage() {
    selectedImage.value = null;
}
</script>

<template>
    <SiteLayout>
        <div class="container pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour</a>

            <!-- Titre -->
            <h2 class="mb-8 text-center text-6xl font-bold uppercase">{{ marque.nom_marque }}</h2>

            <!-- Grille 2 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div v-for="montre in montres" :key="montre.id_montre" class="flex flex-col items-center">
                    <img
                        v-if="montre.image_montre"
                        :src="montre.image_montre"
                        :alt="montre.info_montre"
                        class="h-auto w-[90%] cursor-pointer transition hover:scale-105"
                        @click="openImage(montre.image_montre)"
                    />
                    <p class="mt-1 mb-5 text-6xl font-bold">{{ montre.info_montre }}</p>
                </div>
            </div>

            <!-- Lightbox -->
            <div v-if="selectedImage" class="bg-opacity-80 fixed inset-0 z-50 flex items-center justify-center bg-black">
                <img :src="selectedImage" alt="Montre" class="max-h-[80vh] max-w-[90vw] rounded shadow-lg" />
                <button @click="closeImage" class="absolute top-6 right-6 text-3xl text-white">✕</button>
            </div>
        </div>
    </SiteLayout>
</template>
