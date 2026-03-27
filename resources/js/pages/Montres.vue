<script setup>
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    marque: Object,
    montres: Array,
});
</script>

<template>
    <SiteLayout>
        <div class="wrapper pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour</a>

            <!-- Titre -->
            <h2 class="mb-8 text-center text-6xl font-bold uppercase">{{ marque.nom_marque }}</h2>

            <!-- Grille 2 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div v-for="montre in montres" :key="montre.id_montre">
                    <Link :href="route('articles.montre', montre.id_montre)" class="flex flex-col items-center">
                        <img
                            v-if="montre.image_montre"
                            :src="montre.image_montre"
                            :alt="montre.info_montre"
                            class="h-auto w-[75%] cursor-pointer transition hover:scale-105"
                        />
                        <h3 class="mt-1 mb-5 text-3xl font-bold">{{ montre.info_montre }}</h3>
                    </Link>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>
