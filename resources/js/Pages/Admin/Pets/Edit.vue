<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    pet: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.pet.name,
    type: props.pet.type,
    age: props.pet.age,
    size: props.pet.size,
    description: props.pet.description,
    status: props.pet.status,
    image: null,
    gender: props.pet.gender,
});

const submit = () => {
    form.post(route('admin.pets.update', props.pet.slug));
};
</script>

<template>
    <Head title="Editar Mascota" />

    <AdminLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Editar Mascota</h1>

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
                                label="Edad (Años)"
                                type="number"
                                :error-messages="form.errors.age"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.size"
                                :items="['Pequeño', 'Mediano', 'Grande']"
                                label="Tamaño"
                                :error-messages="form.errors.size"
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                             <v-select
                                v-model="form.gender"
                                :items="['Macho', 'Hembra']"
                                label="Género"
                                :error-messages="form.errors.gender"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.status"
                                :items="['Disponible', 'En Proceso', 'Adoptado']"
                                label="Estado"
                                :error-messages="form.errors.status"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12">
                             <div v-if="pet.image" class="mb-4">
                                <p class="text-caption mb-2">Imagen Actual:</p>
                                <v-img :src="pet.image" max-width="200" rounded></v-img>
                            </div>
                            <v-file-input
                                label="Cambiar Foto"
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

                        <v-col cols="12" class="d-flex justify-end">
                            <Link :href="route('admin.pets.index')" class="mr-4">
                                <v-btn>Cancelar</v-btn>
                            </Link>
                            <v-btn
                                type="submit"
                                color="primary"
                                :loading="form.processing"
                            >
                                Actualizar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card>
        </v-container>
    </AdminLayout>
</template>
