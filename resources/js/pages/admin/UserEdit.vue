<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';

interface Role {
    id: number;
    nom_role: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    id_role: number;
}

const props = defineProps<{
    title?: string;
    user: User;
    roles: Role[];
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    id_role: props.user.id_role,
});

function submit() {
    form.put(route('admin.users.update', props.user.id));
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Utilisateurs', href: route('admin.users.index') },
    { title: 'Modifier', href: route('admin.users.edit', props.user.id) },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">Modifier un utilisateur</h1>

            <form @submit.prevent="submit" class="max-w-xl space-y-5">
                <div>
                    <label class="block text-sm font-medium">Nom</label>
                    <input v-model="form.name" type="text" class="mt-1 w-full rounded border p-2" />
                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 w-full rounded border p-2" />
                    <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Rôle</label>
                    <select v-model.number="form.id_role" class="mt-1 w-full rounded border p-2">
                        <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.nom_role }}</option>
                    </select>
                    <div v-if="form.errors.id_role" class="mt-1 text-sm text-red-600">{{ form.errors.id_role }}</div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded bg-yellow-600 px-4 py-2 text-white" :disabled="form.processing">
                        Enregistrer
                    </button>
                    <Link :href="route('admin.users.index')" class="rounded bg-gray-300 px-4 py-2">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
