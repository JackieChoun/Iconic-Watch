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
    <section class="my-10 text-left">
        <h2 class="mb-6 text-6xl font-bold">Dernier ajout</h2>

        <p v-if="loading" class="text-gray-500">Chargement…</p>
        <p v-else-if="error" class="text-red-600">{{ error }}</p>

        <Link v-else :href="`${slugify(movie.title)}Controller.php`" class="flex gap-4">
            <img
                :src="`https://image.tmdb.org/t/p/w300${movie.poster_path}`"
                :alt="`Affiche de ${movie.title}`"
                loading="lazy"
                class="transition hover:scale-110"
            />
            <div>
                <h3 class="mb-2 text-3xl font-bold">{{ movie.title }}</h3>
                <p class="text-xl">{{ movie.overview }}</p>
            </div>
        </Link>
    </section>
</template>
