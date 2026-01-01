<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-h6 font-weight-bold text-high-emphasis">
                Actualizar Contraseña
            </h2>
            <p class="text-body-2 text-medium-emphasis">
                Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
            </p>
        </header>

        <v-form @submit.prevent="updatePassword">
            <v-text-field
                ref="currentPasswordInput"
                v-model="form.current_password"
                label="Contraseña Actual"
                type="password"
                :error-messages="form.errors.current_password"
                autocomplete="current-password"
                class="mb-4"
            ></v-text-field>

            <v-text-field
                ref="passwordInput"
                v-model="form.password"
                label="Nueva Contraseña"
                type="password"
                :error-messages="form.errors.password"
                autocomplete="new-password"
                class="mb-4"
            ></v-text-field>

            <v-text-field
                v-model="form.password_confirmation"
                label="Confirmar Contraseña"
                type="password"
                :error-messages="form.errors.password_confirmation"
                autocomplete="new-password"
                class="mb-4"
            ></v-text-field>

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
