<script setup>
import Footer from '@/layouts/Footer.vue';
import Header from '@/layouts/Header.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head :title="$page.props.title" />
    <Header />
    <main class="bg-[#fdfdfd]">
        <slot />
    </main>
    <Footer />
</template>
