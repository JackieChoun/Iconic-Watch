<script setup>
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const isActive = (name) => route().current(name);

const page = usePage();
const user = computed(() => page.props?.auth?.user ?? null);
const isAdmin = computed(() => (user.value?.id_role ?? 1) === 2);

const q = ref('');
function submitSearch() {
    const value = q.value.trim();
    router.get(route('search'), { q: value }, { preserveState: false });
}
</script>

<template>
    <header class="sticky top-0 z-20 bg-[#fdfdfd]">
        <div class="wrapper flex items-center justify-between">
            <!-- Logo + Titre -->
            <div class="flex items-center">
                <Link :href="route('accueil')" title="Accueil">
                    <img src="/image/logo/logo.svg" alt="logo" class="h-auto w-[100px] md:w-[80%]" />
                </Link>
                <!-- Titre (caché sur mobile) -->
                <div class="hidden font-bold md:block">
                    <h1 class="mb-[-20px]">ICONIC WATCH</h1>
                    <h2 class="text-2xl">LES MONTRES AU CINÉMA</h2>
                </div>
            </div>

            <!-- Icônes + menu -->
            <div class="mr-[5%] flex items-center gap-[10px] md:mr-[3%] md:gap-[30px]">
                <!-- Barre de recherche -->
                <form class="hidden items-center md:flex" @submit.prevent="submitSearch">
                    <input
                        v-model="q"
                        type="search"
                        maxlength="100"
                        minlength="2"
                        class="h-[50px] w-30 border border-stone-500 bg-white px-3 md:mr-[20px] md:w-3xs"
                    />
                    <button type="submit" aria-label="Rechercher">
                        <img src="/image/logo/Search.svg" alt="search" class="h-[5vh] w-auto md:h-[7vh]" />
                    </button>
                </form>

                <!-- Icône utilisateur (cachée sur mobile) -->
                <Link :href="!user ? route('login') : isAdmin ? route('dashboard') : route('profile')" class="hidden md:block">
                    <img src="/image/logo/utilisateur.svg" alt="utilisateur" class="h-[5vh] w-auto md:h-[7vh]" />
                </Link>

                <!-- Menu burger -->
                <Sheet>
                    <SheetTrigger class="hidden cursor-pointer text-2xl font-bold md:block">Menu</SheetTrigger>
                    <SheetTrigger class="lg:hidden"><Menu class="size-12" /></SheetTrigger>
                    <SheetContent>
                        <SheetHeader>
                            <SheetTitle>Menu</SheetTitle>
                        </SheetHeader>
                        <ul class="list-none space-y-10 bg-[#fdfdfd] py-5 text-center text-base md:text-xl">
                            <li>
                                <Link
                                    :href="route('accueil')"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('accueil') }"
                                    >Accueil</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('marques')"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('marques') }"
                                    >Marques</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('films')"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('films') }"
                                    >Films</Link
                                >
                            </li>
                            <li>
                                <Link
                                    v-if="!user"
                                    :href="route('login')"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('login') }"
                                    >Connexion</Link
                                >
                                <Link
                                    v-else
                                    :href="isAdmin ? route('dashboard') : route('profile')"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('dashboard') }"
                                    >Compte</Link
                                >
                            </li>
                            <li v-if="user">
                                <Link
                                    method="post"
                                    as="button"
                                    :href="route('logout')"
                                    class="cursor-pointer rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    >Déconnexion</Link
                                >
                            </li>
                        </ul>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </header>
</template>
