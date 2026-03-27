<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';

interface Role {
    id: number;
    nom_role: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    id_role: number;
    role?: Role;
    created_at: string;
}

defineProps<{
    title?: string;
    users: User[];
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Utilisateurs', href: route('admin.users.index') },
];

function roleLabel(u: User) {
    return u.role?.nom_role ?? (u.id_role === 2 ? 'admin' : 'utilisateur');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Utilisateurs</h1>
            </div>

            <div class="overflow-x-auto rounded border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted">
                        <tr>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Rôle</th>
                            <th class="px-4 py-3">Créé</th>
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="u in users" :key="u.id" class="border-t">
                            <td class="px-4 py-3">{{ u.name }}</td>
                            <td class="px-4 py-3">{{ u.email }}</td>
                            <td class="px-4 py-3">{{ roleLabel(u) }}</td>
                            <td class="px-4 py-3">{{ new Date(u.created_at).toLocaleDateString('fr-FR') }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route('admin.users.comments', u.id)" class="rounded bg-yellow-600 px-3 py-1.5 text-white">
                                    Commentaires
                                </Link>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route('admin.users.edit', u.id)" class="rounded bg-blue-600 px-3 py-1.5 text-white"> Modifier </Link>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="5" class="text-muted-foreground px-4 py-6 text-center">Aucun utilisateur.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
