<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <SiteLayout>
        <Head title="Mot de passe oublié" />

        <div class="wrapper py-10">
            <!-- Retour -->
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block"> ← Retour </a>

            <!-- Titre -->
            <h2 class="mb-3 text-center text-4xl font-bold lg:mb-8 lg:text-6xl">Mot de passe oublié</h2>

            <div class="mx-auto max-w-md rounded-xl border bg-white p-8 shadow">
                <p class="text-gray-600">
                    Saisissez votre adresse email. Si un compte y est associé, vous recevrez un lien permettant de réinitialiser votre mot de passe.
                </p>

                <div v-if="status" class="mt-4 rounded bg-green-50 p-3 text-sm font-medium text-green-700">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 flex flex-col gap-6">
                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium"> Email </label>

                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            autocomplete="email"
                            autofocus
                            placeholder="email@example.com"
                            class="h-11 w-full rounded border px-3"
                        />

                        <InputError :message="form.errors.email" />
                    </div>

                    <button
                        type="submit"
                        class="mt-2 w-full cursor-pointer rounded bg-black py-3 font-semibold text-white"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Envoi...' : 'Envoyer le lien de réinitialisation' }}
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-600">
                    <span>Retour à la </span>
                    <TextLink :href="route('login')"> connexion </TextLink>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>
