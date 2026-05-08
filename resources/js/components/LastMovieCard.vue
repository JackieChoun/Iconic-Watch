<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const movie = ref(null);
const loading = ref(true);
const error = ref(null);

function slugify(title) {
    return title
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '')
        .replace(/\s+/g, '-')
        .toLowerCase();
}

onMounted(async () => {
    try {
        const res = await fetch('/films/last');
        if (!res.ok) throw new Error(res.statusText);
        movie.value = await res.json();
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <section class="my-5 text-left lg:my-10">
        <h2 class="mb-3 text-2xl font-bold lg:mb-6 lg:text-6xl">Dernier ajout</h2>

        <p v-if="loading" class="text-gray-500">Chargement…</p>
        <p v-else-if="error" class="text-red-600">{{ error }}</p>

        <Link v-else :href="`${slugify(movie.title)}Controller.php`" class="flex gap-2 lg:gap-4">
            <img
                :src="`https://image.tmdb.org/t/p/w300${movie.poster_path}`"
                :alt="`Affiche de ${movie.title}`"
                loading="lazy"
                class="h-auto w-30 transition hover:scale-110 md:w-auto"
            />
            <div>
                <h3 class="mb-2 text-xl font-bold lg:text-3xl">{{ movie.title }}</h3>
                <p class="lg:text-xl">{{ movie.overview }}</p>
            </div>
        </Link>
    </section>
</template>
