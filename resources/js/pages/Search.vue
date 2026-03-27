<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

type Marque = { id_marque: number; nom_marque: string; logo_marque?: string | null };
type Montre = { id_montre: number; info_montre: string; marque?: { nom_marque: string } };
type Film = { id: number; tmdb_id: number; title: string };

const props = defineProps<{
    title?: string;
    results: {
        q: string;
        marques: Marque[];
        montres: Montre[];
        films: Film[];
    };
}>();

const q = ref(props.results.q ?? '');

// petite protection anti-refreshs en boucle
let t: number | null = null;
watch(
    q,
    (val) => {
        if (t) window.clearTimeout(t);
        t = window.setTimeout(() => {
            router.get(route('search'), { q: val }, { preserveState: true, replace: true, preserveScroll: true });
        }, 250);
    },
    { flush: 'post' },
);

const hasResults = computed(() => props.results.marques.length + props.results.montres.length + props.results.films.length > 0);
</script>

<template>
    <SiteLayout>
        <div class="wrapper py-8">
            <h1 class="mb-6 text-center text-5xl font-bold">RECHERCHE</h1>

            <div class="mx-auto mb-10 max-w-2xl">
                <input
                    v-model="q"
                    type="search"
                    minlength="2"
                    maxlength="100"
                    class="w-full rounded-xl border p-4 text-lg"
                    placeholder="Film, marque, montre…"
                />
                <p class="mt-2 text-sm text-gray-500">Tape au moins 2 caractères.</p>
            </div>

            <div v-if="q.length < 2" class="text-center text-gray-500">Commence ta recherche.</div>
            <div v-else-if="!hasResults" class="text-center text-gray-500">Aucun résultat.</div>

            <div v-else class="mx-auto grid max-w-4xl gap-10">
                <section v-if="results.films.length">
                    <h2 class="mb-3 text-2xl font-bold">Films</h2>
                    <ul class="space-y-2">
                        <li v-for="f in results.films" :key="f.id">
                            <Link
                                :href="route('articles.film', { tmdbId: f.tmdb_id, slug: f.title?.toLowerCase().replace(/\s+/g, '-') })"
                                class="underline"
                            >
                                {{ f.title }}
                            </Link>
                        </li>
                    </ul>
                </section>

                <section v-if="results.marques.length">
                    <h2 class="mb-3 text-2xl font-bold">Marques</h2>
                    <ul class="space-y-2">
                        <li v-for="m in results.marques" :key="m.id_marque" class="flex items-center gap-3">
                            <img v-if="m.logo_marque" :src="m.logo_marque" :alt="m.nom_marque" class="h-8 w-20 object-contain" />
                            <Link :href="route('marques.show', m.id_marque)" class="underline">{{ m.nom_marque }}</Link>
                        </li>
                    </ul>
                </section>

                <section v-if="results.montres.length">
                    <h2 class="mb-3 text-2xl font-bold">Montres</h2>
                    <ul class="space-y-2">
                        <li v-for="w in results.montres" :key="w.id_montre">
                            <Link :href="route('articles.montre', w.id_montre)" class="underline">
                                {{ w.info_montre }}
                                <span class="text-gray-500" v-if="w.marque?.nom_marque">({{ w.marque.nom_marque }})</span>
                            </Link>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </SiteLayout>
</template>
