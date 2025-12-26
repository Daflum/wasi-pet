<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'dog',
    breed: '',
    age: '',
    size: 'Medium',
    description: '',
    status: 'available',
    image: null,
});

const submit = () => {
    form.post(route('admin.pets.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Nueva Mascota" />

    <AppLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Nueva Mascota</h1>

            <v-card class="pa-6">
                <v-form @submit.prevent="submit">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.name"
                                label="Nombre"
                                :error-messages="form.errors.name"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.type"
                                :items="[
                                    { title: 'Perro', value: 'dog' },
                                    { title: 'Gato', value: 'cat' }
                                ]"
                                label="Especie"
                                :error-messages="form.errors.type"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.age"
                                label="Edad (Años/Meses aprox)"
                                type="number"
                                :error-messages="form.errors.age"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.size"
                                :items="[
                                    { title: 'Pequeño', value: 'Pequeño' },
                                    { title: 'Mediano', value: 'Mediano' },
                                    { title: 'Grande', value: 'Grande' }
                                ]"
                                label="Tamaño"
                                :error-messages="form.errors.size"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.status"
                                :items="[
                                    { title: 'En Adopción', value: 'available' },
                                    { title: 'Adoptado', value: 'adopted' },
                                    { title: 'En Tratamiento', value: 'treatment' }
                                ]"
                                label="Estado"
                                :error-messages="form.errors.status"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12">
                            <v-file-input
                                v-model="form.image"
                                label="Foto"
                                accept="image/*"
                                prepend-icon="mdi-camera"
                                :error-messages="form.errors.image"
                                @input="form.image = $event.target.files[0]"
                            ></v-file-input>
                        </v-col>

                        <v-col cols="12">
                            <v-textarea
                                v-model="form.description"
                                label="Historia / Descripción"
                                rows="4"
                                :error-messages="form.errors.description"
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" class="d-flex justify-end gap-4">
                            <v-btn
                                color="grey-lighten-2"
                                variant="flat"
                                :href="route('admin.pets.index')"
                                class="mr-4"
                            >
                                Cancelar
                            </v-btn>
                            <v-btn
                                type="submit"
                                color="primary"
                                :loading="form.processing"
                            >
                                Guardar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card>
        </v-container>
    </AppLayout>
</template>
