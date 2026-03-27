<script setup>
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const { marques } = defineProps(['marques']);
</script>

<template>
    <SiteLayout>
        <div class="wrapper pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour </a>

            <!-- Titre -->
            <h2 class="mb-8 text-center text-6xl font-bold">MARQUES</h2>
            <div class="flex flex-wrap justify-evenly">
                <Link
                    :href="route('marques.show', marque.id_marque)"
                    v-for="marque in marques"
                    :key="marque.id_marque"
                    class="group relative my-6 flex rounded-xl"
                >
                    <!-- Image -->
                    <img :src="marque.photo_marque" :alt="marque.nom_marque" class="w-100 transition-transform duration-200 group-hover:scale-110" />

                    <!-- Titre -->
                    <img
                        :src="marque.logo_marque"
                        :alt="`Logo de ${marque.nom_marque}`"
                        class="absolute bottom-0 h-[25%] w-full rounded-b-xl bg-gray-600/70 py-2 transition-all duration-300 group-hover:bottom-1/2 group-hover:translate-y-1/2 group-hover:scale-110 group-hover:rounded-none"
                    />
                </Link>
            </div>
        </div>
    </SiteLayout>
</template>
