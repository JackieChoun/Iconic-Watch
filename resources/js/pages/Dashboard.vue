<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const { user } = defineProps<{ user: { name: string } }>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-center text-4xl">Bienvenue Administrateur {{ user.name }}</h1>
        </div>
    </AppLayout>
</template>
