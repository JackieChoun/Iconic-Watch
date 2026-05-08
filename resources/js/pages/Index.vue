<script setup>
import LastMovieCard from '@/components/LastMovieCard.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <SiteLayout>
        <div id="Accueil" class="wrapper text-center">
            <div class="py-3 lg:px-[10%] lg:py-10">
                <p class="text-xl font-bold tracking-wide lg:text-3xl">
                    Dans l’univers du cinéma, la montre est l’accessoire discret et pourtant emblématique d’une époque et de la personnalité de son
                    protagoniste. La montre sublime le caractère, du chic à la James Bond au futurisme du Lieutenant Ellen Ripley de Alien. Ce site
                    retrace les montres portées par tous ces héros dans cette infinité d’univers que nous offrent films et séries.
                    <br />
                    Bienvenue et bonne navigation sur
                    <span class="font-normal"> ICONIC WATCH </span>.
                </p>
            </div>
            <div id="categorie" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:gap-10">
                <Link :href="route('marques')" class="group relative flex justify-center">
                    <h2
                        class="absolute z-10 w-full bg-black/70 py-2 text-2xl font-bold text-[#fdfdfd] lg:hidden lg:py-3 lg:text-7xl lg:group-hover:block"
                    >
                        MARQUES
                    </h2>
                    <img src="/image/cateMarque.webp" class="h-auto w-auto" />
                </Link>

                <Link :href="route('films')" class="group relative flex justify-center">
                    <h2
                        class="absolute z-10 w-full bg-black/70 py-2 text-2xl font-bold text-[#fdfdfd] lg:hidden lg:py-3 lg:text-7xl lg:group-hover:block"
                    >
                        FILMS
                    </h2>
                    <img src="/image/cateFilm.webp" class="h-auto w-auto" />
                </Link>
            </div>
            <LastMovieCard />
        </div>
    </SiteLayout>
</template>
