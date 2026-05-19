<script setup>
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

// --- état ---
const films = ref([]);
const selectedLetter = ref(''); // '' = Tous

// --- helpers ---
function formatTitle(title) {
    return title.replace(/\s+/g, '-').toLowerCase();
}

// --- chargement des films ---
const loading = ref(true);

async function fetchMovies() {
    loading.value = true;

    try {
        const res = await fetch('/films/movies');
        films.value = await res.json();
    } catch (e) {
        console.error('Impossible de charger les films :', e);
    } finally {
        loading.value = false;
    }
}

/* Normalise une chaîne : majuscules + suppression des accents
   → "Été" devient "ETE"  */
function normalize(str) {
    return str
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '')
        .toUpperCase();
}

// --- filtre alphabétique ---
const filteredFilms = computed(() =>
    selectedLetter.value === '' ? films.value : films.value.filter((film) => normalize(film.title).startsWith(selectedLetter.value)),
);

// --- récupération des films ---
onMounted(fetchMovies);
</script>

<template>
    <SiteLayout>
        <div class="wrapper pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour </a>

            <!-- Titre -->
            <h2 class="mb-3 text-center text-4xl font-bold lg:mb-8 lg:text-6xl">FILMS</h2>

            <!-- Alphabet -->
            <nav class="pointer mb-8 flex flex-wrap justify-center gap-2">
                <!-- Bouton "Tous" -->
                <button
                    @click="selectedLetter = ''"
                    :class="['rounded px-2 py-1 text-sm font-semibold', selectedLetter === '' ? 'bg-black text-white' : 'hover:bg-gray-200']"
                >
                    Tous
                </button>

                <!-- Lettres A → Z -->
                <template v-for="letter in [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']" :key="letter">
                    <button
                        @click="selectedLetter = letter"
                        :class="['rounded px-2 py-1 text-sm font-semibold', selectedLetter === letter ? 'bg-black text-white' : 'hover:bg-gray-200']"
                    >
                        {{ letter }}
                    </button>
                </template>
            </nav>

            <!-- Liste filtrée -->
            <section id="articleFilm" class="">
                <div v-for="film in filteredFilms" :key="film.id" class="py-4 lg:mr-[20%]">
                    <Link
                        :href="route('articles.film', { tmdbId: film.id, slug: formatTitle(film.title) })"
                        class="flex flex-col items-center justify-center gap-4 lg:flex-row lg:items-start"
                    >
                        <img
                            :src="`https://image.tmdb.org/t/p/w300${film.poster_path}`"
                            :alt="`Affiche de ${film.title}`"
                            loading="lazy"
                            class="transition-transform duration-200 ease-in-out hover:scale-110 lg:float-left lg:mr-[1%]"
                        />

                        <div>
                            <h3 class="mb-3 text-center text-2xl font-semibold md:text-start md:text-4xl">{{ film.title }}</h3>
                            <p class="text-center md:text-start md:text-xl">{{ film.overview }}</p>
                        </div>
                    </Link>
                </div>

                <!-- Message si aucun film -->
                <div v-if="loading" class="flex justify-center py-20">
                    <div class="h-12 w-12 animate-spin rounded-full border-4 border-black border-t-transparent"></div>
                </div>

                <p v-else-if="!filteredFilms.length">Aucun film</p>
            </section>
        </div>
    </SiteLayout>
</template>
