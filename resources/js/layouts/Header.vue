<script setup>
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Link } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';

const isActive = (name) => route().current(name);
</script>

<template>
    <header class="sticky top-0 z-20 bg-[#fdfdfd]">
        <div class="flex items-center justify-between">
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
                <div class="hidden items-center md:flex">
                    <input type="search" maxlength="100" minlength="2" class="h-[50px] w-30 border border-stone-700 bg-white md:mr-[30px] md:w-3xs" />
                    <label>
                        <img src="/image/logo/Search.svg" alt="search" class="h-[5vh] w-auto md:h-[7vh]" />
                    </label>
                </div>

                <!-- Icône utilisateur (cachée sur mobile) href="<?php echo isset($_SESSION['id_utilisateur']) ? 'controllerCompteUtilisateur.php' : 'controllerConnexion.php'; ?>"-->
                <Link :href="route('login')" class="hidden md:block">
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
                                    :class="{ 'underline underline-offset-5': isActive('marque') }"
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
                                    href="/"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('connexion') }"
                                    >Connexion</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('compte') }"
                                    >Compte</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/"
                                    class="rounded px-5 py-3 md:hover:bg-[gainsboro]"
                                    :class="{ 'underline underline-offset-5': isActive('deco') }"
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
