<script setup lang="ts">
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SiteLayout from '@/layouts/SiteLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

type PageProps = {
    auth?: {
        user?: {
            id: number;
            name: string;
            email: string;
            id_role?: number;
        } | null;
    };
};

const page = usePage<PageProps>();
const user = page.props.auth?.user;

const form = useForm({
    name: user?.name ?? '',
    email: user?.email ?? '',
});
const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Profil de ${user?.name}`" />
    <SiteLayout>
        <div class="wrapper py-4">
            <a href="javascript:history.back()" class="absolute z-10 ml-50 hidden text-4xl underline lg:block">← Retour </a>
            <h2 v-if="user" class="mb-8 text-center text-6xl font-bold">Bienvenue {{ user.name }}</h2>

            <!-- Infos -->
            <div class="mx-auto max-w-3xl space-y-6">
                <HeadingSmall title="Informations" description="Modifier vos informations personnelles" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Label>Nom</Label>
                        <Input v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label>Email</Label>
                        <Input type="email" v-model="form.email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing"> Enregistrer </Button>
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Enregistrer (nice!).</p>
                        </Transition>
                    </div>
                </form>

                <!-- Suppression -->
                <DeleteUser />
            </div>
        </div>
    </SiteLayout>
</template>
