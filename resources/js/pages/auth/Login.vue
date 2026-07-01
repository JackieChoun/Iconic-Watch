<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <SiteLayout>
        <Head title="Connexion" />

        <div class="wrapper py-10">
            <!-- Bouton retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour </a>

            <!-- Titre -->
            <h2 class="mb-3 text-center text-4xl font-bold lg:mb-8 lg:text-6xl">Connexion</h2>
            <div class="mx-auto max-w-md rounded-xl border bg-white p-8 shadow">
                <p class="mt-2 text-gray-600">Entrez votre email et votre mot de passe.</p>

                <div v-if="status" class="mt-4 rounded bg-green-50 p-3 text-sm font-medium text-green-700">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 flex flex-col gap-6">
                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium">Email</label>
                        <input
                            id="email"
                            type="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-medium">Mot de passe</label>
                            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                                Mot de passe oublié ?
                            </TextLink>
                        </div>
                        <input
                            id="password"
                            type="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            v-model="form.password"
                            placeholder="••••••••"
                            class="h-11 w-full rounded border px-3"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input id="remember" type="checkbox" v-model="form.remember" :tabindex="3" />
                        Se souvenir de moi
                    </label>

                    <button
                        type="submit"
                        class="mt-2 w-full cursor-pointer rounded bg-black py-3 font-semibold text-white"
                        :tabindex="4"
                        :disabled="form.processing"
                    >
                        Se connecter
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Pas de compte ? <TextLink :href="route('register')" :tabindex="5">Créer un compte</TextLink>
                    </div>
                </form>
            </div>
        </div>
    </SiteLayout>
</template>
