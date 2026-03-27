<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

type PageProps = {
    auth?: {
        user?: {
            id: number;
            name: string;
            email: string;
        } | null;
    };
};

const page = usePage<PageProps>();
const comments = ref<Comment[]>([]);
const newComment = ref('');
const loading = ref(false);
const sending = ref(false);
const props = defineProps<{ article: Article; movie: Movie | null; title?: string }>();
const errorMessage = ref('');

const isAuthenticated = computed(() => {
    return !!page.props.auth?.user;
});

onMounted(fetchComments);

type Comment = {
    id: number;
    content: string;
    user_name: string;
    created_at: string;
    user_id: number;
};

const currentUserId = computed(() => {
    return page.props.auth?.user?.id ?? null;
});

async function fetchComments() {
    loading.value = true;
    try {
        const res = await axios.get<Comment[]>(`/articles/${props.article.id_article}/comments`);
        comments.value = res.data;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
}

async function submitComment() {
    if (!newComment.value.trim()) return;

    sending.value = true;

    try {
        const res = await axios.post<Comment>(`/articles/${props.article.id_article}/comments`, {
            content: newComment.value,
        });

        comments.value.unshift(res.data);
        newComment.value = '';
    } catch (error) {
        console.error('Erreur envoi commentaire :', error);
        errorMessage.value = "Impossible d'envoyer le commentaire.";
    } finally {
        sending.value = false;
    }
}

type Movie = {
    id: number;
    title: string;
    overview?: string;
    poster_path?: string;
    release_date?: string;
};

type Marque = { id_marque: number; nom_marque: string; logo_marque?: string | null };
type Montre = { id_montre: number; info_montre: string; marque?: Marque | null };
type Film = { id: number; tmdb_id: number; title?: string | null };

type Article = {
    id_article: number;
    mouvement_montre?: string | null;
    en_vente: boolean;
    lien_vente?: string | null;
    taille_montre?: string | null;
    description?: string | null;
    affiche_film_url?: string | null;
    images_montre_urls?: string[];
    film?: Film;
    montre?: Montre;
};

const posterUrl = computed(() => {
    if (props.article.affiche_film_url) return props.article.affiche_film_url;
    if (props.movie?.poster_path) return `https://image.tmdb.org/t/p/w500${props.movie.poster_path}`;
    return null;
});

const gallery = computed(() => props.article.images_montre_urls ?? []);

async function deleteComment(id: number) {
    try {
        await axios.delete(`/comments/${id}`);
        comments.value = comments.value.filter((c) => c.id !== id);
    } catch (error) {
        console.error('Erreur suppression :', error);
    }
}

// Lightbox simple
const selectedImage = ref<string | null>(null);
function openImage(url: string) {
    selectedImage.value = url;
}
function closeImage() {
    selectedImage.value = null;
}

const filmTitle = computed(() => props.movie?.title ?? props.article.film?.title ?? 'Film');
const watchTitle = computed(() => props.article.montre?.info_montre ?? 'Montre');
const brandTitle = computed(() => props.article.montre?.marque?.nom_marque ?? 'Marque');
</script>

<template>
    <SiteLayout>
        <div>
            <div class="absolute z-10">
                <a href="javascript:history.back()" class="ml-50 hidden text-4xl underline lg:block">← Retour </a>
                <div class="text-right text-sm text-gray-500" v-if="movie?.release_date">
                    Sortie: {{ new Date(movie.release_date).toLocaleDateString('fr-FR') }}
                </div>
            </div>
            <!-- Poster -->
            <div>
                <img v-if="posterUrl" :src="posterUrl" :alt="`Affiche ${filmTitle}`" class="h-auto w-full" />
                <div v-else class="flex h-96 items-center justify-center text-gray-500">Pas d’affiche</div>
            </div>

            <!-- Galerie -->
            <div class="wrapper">
                <div v-if="gallery.length" class="mt-8 flex w-full justify-between gap-4 overflow-x-auto">
                    <button
                        v-for="(img, i) in gallery"
                        :key="i"
                        type="button"
                        class="* overflow-hidden"
                        @click="openImage(img)"
                        :title="'Voir l\'image'"
                    >
                        <img :src="img" class="h-auto w-auto object-cover transition hover:scale-105" />
                    </button>
                </div>
                <!-- Contenu -->
                <div class="lg:col-span-2">
                    <h1 class="text-4xl font-bold">{{ watchTitle }}</h1>
                    <p class="mt-2 text-xl text-gray-600">
                        Dans <span class="font-semibold">{{ filmTitle }}</span>
                    </p>

                    <div class="mt-6 grid gap-4 rounded-xl border p-5 sm:grid-cols-2">
                        <div>
                            <div class="text-sm text-gray-500">Marque</div>
                            <div class="text-lg font-semibold">{{ brandTitle }}</div>
                        </div>
                        <div v-if="article.mouvement_montre">
                            <div class="text-sm text-gray-500">Mouvement</div>
                            <div class="text-lg font-semibold">{{ article.mouvement_montre }}</div>
                        </div>
                        <div v-if="article.taille_montre">
                            <div class="text-sm text-gray-500">Taille</div>
                            <div class="text-lg font-semibold">{{ article.taille_montre }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Disponibilité</div>
                            <div class="text-lg font-semibold">
                                <span v-if="article.en_vente">Toujours en vente</span>
                                <span v-else>Plus en vente</span>
                            </div>
                        </div>
                        <div v-if="article.en_vente && article.lien_vente" class="sm:col-span-2">
                            <a :href="article.lien_vente" target="_blank" rel="noopener" class="inline-block rounded bg-black px-4 py-2 text-white">
                                Voir sur le site officiel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-8">
                    <p v-if="article.description" class="px-40 text-center text-xl leading-relaxed font-semibold whitespace-pre-line">
                        {{ article.description }}
                    </p>
                    <p v-else class="text-gray-500">Description à venir.</p>
                </div>
            </div>

            <!-- Commentaires -->
            <div class="wrapper my-10">
                <h2 class="mb-6 text-2xl font-bold">Commentaires</h2>

                <div v-if="loading">Chargement...</div>

                <div v-else-if="!comments.length" class="text-gray-500">Aucun commentaire pour le moment.</div>
                <p v-if="errorMessage" class="mt-2 text-red-500">
                    {{ errorMessage }}
                </p>

                <div v-if="!loading && comments.length" class="space-y-4">
                    <div v-for="c in comments" :key="c.id" class="rounded border p-4">
                        <div class="text-sm text-gray-500">{{ c.user_name }} le {{ new Date(c.created_at).toLocaleDateString('fr-FR') }}</div>
                        <p>{{ c.content }}</p>
                        <div v-if="isAuthenticated && c.user_id === currentUserId">
                            <button @click="deleteComment(c.id)" class="text-sm text-red-500">Supprimer</button>
                        </div>
                    </div>
                </div>
                <div class="mt-6" v-if="isAuthenticated">
                    <textarea v-model="newComment" class="h-50 w-full border bg-black p-5 text-white" placeholder="Votre commentaire"></textarea>

                    <button
                        @click="submitComment"
                        :disabled="sending || !newComment.trim()"
                        class="mt-2 bg-black px-4 py-2 text-white disabled:opacity-50"
                    >
                        {{ sending ? 'Envoi...' : 'Publier' }}
                    </button>
                </div>
                <div v-else class="mt-6 text-center text-gray-500">
                    <p>Vous devez être connecté pour laisser un commentaire.</p>
                    <a href="/login" class="underline">Se connecter</a>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <div v-if="selectedImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80" @click.self="closeImage">
            <img :src="selectedImage" alt="Image" class="max-h-[85vh] max-w-[92vw] shadow" />
            <button @click="closeImage" class="absolute top-6 right-6 rounded bg-white/10 px-3 py-2 text-white">✕</button>
        </div>
    </SiteLayout>
</template>
