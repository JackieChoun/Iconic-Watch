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
            <h2 class="mb-3 text-center text-4xl font-bold lg:mb-8 lg:text-6xl">MARQUES</h2>
            <div class="flex flex-col items-center justify-center lg:flex-row lg:flex-wrap lg:justify-evenly">
                <Link
                    :href="route('marques.show', marque.id_marque)"
                    v-for="marque in marques"
                    :key="marque.id_marque"
                    class="group relative my-3 flex items-center justify-center rounded-xl lg:my-6"
                >
                    <!-- Image -->
                    <img
                        :src="marque.photo_marque"
                        :alt="marque.nom_marque"
                        class="h-auto w-80 transition-transform duration-200 group-hover:scale-110 md:w-100"
                    />

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
