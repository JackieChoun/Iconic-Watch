<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    user: any;
    commentaires: any[];
}>();

function deleteComment(id: number) {
    if (!confirm('Supprimer ce commentaire ?')) return;

    router.delete(route('admin.comments.destroy', id));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Utilisateurs', href: route('admin.users.index') },
    { title: 'Commentaires', href: route('admin.users.comments', props.user.id) },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-bold">Commentaires de {{ user.name }}</h1>

            <div v-if="!commentaires.length">Aucun commentaire</div>

            <div v-else class="space-y-4">
                <div v-for="c in commentaires" :key="c.id" class="rounded border p-4">
                    <p>{{ c.content }}</p>
                    <div class="text-sm text-gray-500">
                        {{ new Date(c.created_at).toLocaleDateString() }}
                    </div>

                    <button @click="deleteComment(c.id)" class="mt-2 text-sm text-red-500">Supprimer</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
