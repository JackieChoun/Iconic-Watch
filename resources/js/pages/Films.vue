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
onMounted(async () => {
    try {
        const res = await fetch('/films/movies');
        films.value = await res.json();
    } catch (e) {
        console.error('Impossible de charger les films :', e);
    }
});
</script>

<template>
    <SiteLayout>
        <div class="container pt-4">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block"> Retour </a>

            <!-- Titre -->
            <h2 class="mb-8 text-center text-6xl font-bold">FILMS</h2>

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
            <section id="articleFilm" class="flex flex-col">
                <div v-for="film in filteredFilms" :key="film.id" class="my-[1%] mr-[20%] flex items-start">
                    <Link :href="`${formatTitle(film.title)}Controller.php`">
                        <img
                            :src="`https://image.tmdb.org/t/p/w300${film.poster_path}`"
                            :alt="`Affiche de ${film.title}`"
                            loading="lazy"
                            class="float-left mr-[1%] transition-transform duration-200 ease-in-out hover:scale-110"
                        />

                        <div>
                            <h3 class="text-4xl font-semibold">{{ film.title }}</h3>
                            <p class="text-xl">{{ film.overview }}</p>
                        </div>
                    </Link>
                </div>

                <!-- Message si aucun film -->
                <p v-if="filteredFilms.length === 0" class="text-center text-gray-500">Aucun film pour la lettre « {{ selectedLetter || '…' }} ».</p>
            </section>
        </div>
    </SiteLayout>
</template>
