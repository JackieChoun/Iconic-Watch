<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

// On récupère les props globaux envoyés par Inertia
const page = usePage();
</script>

<template>
    <!-- Titre dynamique (si ton contrôleur envoie `title`) -->
    <Head :title="page.props.title ? page.props.title + ' (admin)' : 'Admin'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
