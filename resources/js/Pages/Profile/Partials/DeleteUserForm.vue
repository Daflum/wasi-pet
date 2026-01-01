<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const confirmingUserDeletion = ref(false);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    form.delete(route('admin.profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-h6 font-weight-bold text-high-emphasis">
                Eliminar Cuenta
            </h2>
            <p class="text-body-2 text-medium-emphasis">
                Una vez que elimines tu cuenta, todos sus recursos y datos se borrarán permanentemente.
            </p>
        </header>

        <v-btn color="error" @click="confirmUserDeletion">
            Eliminar Cuenta
        </v-btn>

        <v-dialog v-model="confirmingUserDeletion" max-width="500">
            <v-card>
                <v-card-title class="text-h5 bg-error text-white pa-4">
                    ¿Estás seguro?
                </v-card-title>
                <v-card-text class="pa-4">
                    <p class="mb-4 text-body-1">
                        Una vez que elimines tu cuenta, todos sus recursos y datos se borrarán permanentemente. Por favor, introduce tu contraseña para confirmar.
                    </p>

                    <v-text-field
                        v-model="form.password"
                        label="Contraseña"
                        type="password"
                        placeholder="Contraseña"
                        :error-messages="form.errors.password"
                        @keyup.enter="deleteUser"
                        variant="outlined"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions class="justify-end pa-4">
                    <v-btn variant="text" @click="closeModal">
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="error"
                        :loading="form.processing"
                        @click="deleteUser"
                    >
                        Eliminar Cuenta
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </section>
</template>
