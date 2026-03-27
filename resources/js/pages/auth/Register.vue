<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <SiteLayout>
        <Head title="Inscription" />

        <div class="wrapper py-10">
            <div class="mx-auto max-w-md rounded-xl border bg-white p-8 shadow">
                <h1 class="text-3xl font-bold">Créer un compte</h1>
                <p class="mt-2 text-gray-600">Quelques infos et c’est bon.</p>

                <form @submit.prevent="submit" class="mt-8 flex flex-col gap-6">
                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-medium">Pseudo</label>
                        <input
                            id="name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            v-model="form.name"
                            placeholder="Marty"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium">Email</label>
                        <input
                            id="email"
                            type="email"
                            required
                            :tabindex="2"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <label for="password" class="text-sm font-medium">Mot de passe</label>
                        <input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="••••••••"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <label for="password_confirmation" class="text-sm font-medium">Confirmation</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="••••••••"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <button type="submit" class="mt-2 w-full rounded bg-black py-3 font-semibold text-white" tabindex="5" :disabled="form.processing">
                        Créer le compte
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Déjà un compte ? <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Connexion</TextLink>
                    </div>
                </form>
            </div>
        </div>
    </SiteLayout>
</template>
