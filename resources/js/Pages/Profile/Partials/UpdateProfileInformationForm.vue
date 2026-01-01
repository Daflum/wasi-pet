<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-h6 font-weight-bold text-high-emphasis">
                Información del Perfil
            </h2>
            <p class="text-body-2 text-medium-emphasis">
                Actualiza la información de tu perfil y dirección de correo electrónico.
            </p>
        </header>

        <v-form @submit.prevent="form.patch(route('admin.profile.update'))">
            <v-text-field
                v-model="form.name"
                label="Nombre"
                :error-messages="form.errors.name"
                required
                autocomplete="name"
                class="mb-4"
            ></v-text-field>

            <v-text-field
                v-model="form.email"
                label="Correo Electrónico"
                type="email"
                :error-messages="form.errors.email"
                required
                autocomplete="username"
                class="mb-4"
            ></v-text-field>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mb-4">
                <p class="text-body-2 text-medium-emphasis">
                    Tu correo no está verificado.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-decoration-underline text-primary"
                    >
                        Haz clic aquí para reenviar el correo de verificación.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-caption text-success"
                >
                    Un nuevo enlace de verificación ha sido enviado a tu correo.
                </div>
            </div>

            <div class="d-flex align-center">
                <v-btn
                    type="submit"
                    color="primary"
                    :loading="form.processing"
                >
                    Guardar
                </v-btn>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-body-2 text-success mb-0 ml-4"
                    >
                        Guardado.
                    </p>
                </Transition>
            </div>
        </v-form>
    </section>
</template>
