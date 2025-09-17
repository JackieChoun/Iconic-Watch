<script setup>
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const { marques } = defineProps(['marques']);
</script>

<template>
    <SiteLayout>
        <div class="container pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block"> Retour </a>

            <!-- Titre -->
            <h2 class="mb-8 text-center text-6xl font-bold">MARQUES</h2>
            <div class="flex flex-wrap justify-evenly">
                <Link href="0" v-for="marque in marques" :key="marque.id_marque" class="group relative my-6 flex rounded-xl">
                    <!-- Image -->
                    <img
                        :src="`/storage/${marque.photo_marque}`"
                        :alt="marque.nom_marque"
                        class="w-100 transition-transform duration-200 group-hover:scale-110"
                    />

                    <!-- Titre -->
                    <img
                        :src="`/storage/${marque.logo_marque}`"
                        :alt="marque.nom_marque"
                        class="absolute h-[25%] w-100 self-end rounded-b-xl bg-gray-600/70 py-2 text-center text-white transition-transform duration-200 group-hover:scale-110 group-hover:self-center group-hover:rounded-none"
                    />
                </Link>
            </div>
        </div>
    </SiteLayout>
</template>
