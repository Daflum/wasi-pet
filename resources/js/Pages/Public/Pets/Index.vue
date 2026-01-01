<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PetCard from '@/Components/PetCard.vue';

defineProps({
    pets: Object,
    filters: Object,
});
</script>

<template>
    <Head title="Nuestros Amigos" />
    <PublicLayout>
        <v-container>
            <div class="text-center mb-10">
                <h1 class="text-h3 font-weight-bold text-primary mb-2">Nuestros Amigos</h1>
                <p class="text-h6 font-weight-light text-medium-emphasis">Encuentra a tu compañero ideal</p>
            </div>

            <!-- Filters -->
            <v-row class="mb-6 justify-center">
                <v-col cols="12" md="6">
                    <v-text-field
                        label="Buscar por nombre"
                        variant="outlined"
                        prepend-inner-icon="mdi-magnify"
                        hide-details
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                    <v-select
                        label="Filtrar por especie"
                        :items="[{ title: 'Todos', value: '' }, { title: 'Perros', value: 'dog' }, { title: 'Gatos', value: 'cat' }]"
                        variant="outlined"
                        hide-details
                    ></v-select>
                </v-col>
            </v-row>

            <!-- Grid -->
            <v-row>
                <v-col v-for="pet in pets.data" :key="pet.id" cols="12" sm="6" md="4">
                   <PetCard :pet="pet" />
                </v-col>
            </v-row>

            <!-- Empty State -->
            <div v-if="pets.data.length === 0" class="text-center py-10">
                <v-icon icon="mdi-paw-off" size="64" color="grey-lighten-1"></v-icon>
                <p class="text-h6 text-grey mt-4">No encontramos mascotas con esos filtros.</p>
            </div>

            <!-- Pagination -->
            <v-row class="mt-8" v-if="pets.links.length > 3">
                <v-col>
                    <v-pagination
                        :length="pets.last_page"
                        :total-visible="7"
                        v-model="pets.current_page"
                    >
                        <template v-slot:item="{ page, isActive }">
                            <Link :href="pets.links.find(link => link.label == page)?.url">
                                <v-btn :variant="isActive ? 'tonal' : 'text'" color="primary">
                                    {{ page }}
                                </v-btn>
                            </Link>
                        </template>
                    </v-pagination>
                </v-col>
            </v-row>
        </v-container>
    </PublicLayout>
</template>
