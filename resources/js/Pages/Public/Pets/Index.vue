<script setup>
import { reactive, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PetCard from '@/Components/PetCard.vue';
import debounce from 'lodash.debounce';

const props = defineProps({
    pets: Object,
    filters: Object,
});

const speciesOptions = [
    { title: 'Perros', value: 'dog' },
    { title: 'Gatos', value: 'cat' },
];

const genderOptions = [
    { title: 'Macho', value: 'Macho' },
    { title: 'Hembra', value: 'Hembra' },
];

const sizeOptions = [
    { title: 'Pequeño', value: 'Pequeño' },
    { title: 'Mediano', value: 'Mediano' },
    { title: 'Grande', value: 'Grande' },
];

const filterForm = reactive({
    search: props.filters.search || '',
    species: props.filters.species || null,
    gender: props.filters.gender || null,
    size: props.filters.size || null,
    ageRange: props.filters.ageRange || [0, 15],
});

watch(filterForm, debounce(() => {
    router.get(
        route('public.pets.index'),
        filterForm,
        {
            preserveState: true,
            replace: true,
        }
    );
}, 300), { deep: true });

const clearFilters = () => {
    filterForm.search = '';
    filterForm.species = null;
    filterForm.gender = null;
    filterForm.size = null;
    filterForm.ageRange = [0, 15];
};

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
            <v-expansion-panels class="mb-8" variant="accordion">
                <v-expansion-panel>
                    <v-expansion-panel-title class="text-h6">
                        <v-icon start icon="mdi-filter-variant"></v-icon>
                        Filtros de Búsqueda
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-row align="center">
                            <!-- Search Field -->
                            <v-col cols="12">
                                <v-text-field
                                    v-model="filterForm.search"
                                    label="Buscar por nombre..."
                                    prepend-inner-icon="mdi-magnify"
                                    variant="outlined"
                                    hide-details
                                    clearable
                                />
                            </v-col>

                            <!-- Species Filter -->
                            <v-col cols="12" sm="6">
                                <p class="text-subtitle-1 font-weight-medium mb-2">Especie</p>
                                <v-chip-group v-model="filterForm.species" filter mandatory>
                                    <v-chip v-for="s in speciesOptions" :key="s.value" :value="s.value" label>
                                        {{ s.title }}
                                    </v-chip>
                                </v-chip-group>
                            </v-col>

                            <!-- Gender Filter -->
                            <v-col cols="12" sm="6">
                                <p class="text-subtitle-1 font-weight-medium mb-2">Género</p>
                                <v-chip-group v-model="filterForm.gender" filter mandatory>
                                    <v-chip v-for="g in genderOptions" :key="g.value" :value="g.value" label>
                                        {{ g.title }}
                                    </v-chip>
                                </v-chip-group>
                            </v-col>

                            <!-- Size Filter -->
                            <v-col cols="12">
                                <p class="text-subtitle-1 font-weight-medium mb-2">Tamaño</p>
                                <v-chip-group v-model="filterForm.size" filter mandatory>
                                    <v-chip v-for="s in sizeOptions" :key="s.value" :value="s.value" label>
                                        {{ s.title }}
                                    </v-chip>
                                </v-chip-group>
                            </v-col>

                            <!-- Age Range Filter -->
                            <v-col cols="12">
                                <p class="text-subtitle-1 font-weight-medium mb-2">Rango de Edad (años)</p>
                                <v-range-slider
                                    v-model="filterForm.ageRange"
                                    :max="15"
                                    :min="0"
                                    :step="1"
                                    thumb-label="always"
                                    hide-details
                                ></v-range-slider>
                            </v-col>
                        </v-row>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="clearFilters" color="primary" variant="text">
                                Limpiar Filtros
                            </v-btn>
                        </v-card-actions>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>

            <!-- Grid -->
            <v-row>
                <v-col v-for="pet in pets.data" :key="pet.id" cols="12" sm="6" md="4">
                    <PetCard :pet="pet" />
                </v-col>
            </v-row>

            <!-- Empty State -->
            <div v-if="pets.data.length === 0" class="text-center py-16">
                <v-icon icon="mdi-paw-off" size="80" class="text-grey-lighten-1"></v-icon>
                <h2 class="text-h5 text-grey-darken-1 mt-4">No hay resultados</h2>
                <p class="text-body-1 text-medium-emphasis mt-2">
                    No encontramos mascotas que coincidan con tus filtros.
                    <br>
                    Intenta ajustando o <a href="#" @click.prevent="clearFilters">limpiando los filtros</a>.
                </p>
            </div>

            <!-- Pagination -->
            <v-row class="mt-8" v-if="pets.links.length > 3">
                <v-col>
                    <div class="d-flex justify-center">
                        <v-pagination :total-visible="5" :length="pets.last_page">
                            <template #prev>
                                <Link v-if="pets.prev_page_url" :href="pets.prev_page_url" preserve-scroll>
                                    <v-btn icon="mdi-chevron-left" variant="text"></v-btn>
                                </Link>
                            </template>
                            <template #next>
                                <Link v-if="pets.next_page_url" :href="pets.next_page_url" preserve-scroll>
                                    <v-btn icon="mdi-chevron-right" variant="text"></v-btn>
                                </Link>
                            </template>
                            <template #item="{ page, isActive }">
                                <Link :href="pets.links.find(link => link.label == page)?.url" preserve-scroll>
                                    <v-btn :variant="isActive ? 'tonal' : 'text'" :active="isActive" color="primary">
                                        {{ page }}
                                    </v-btn>
                                </Link>
                            </template>
                        </v-pagination>
                    </div>
                </v-col>
            </v-row>

        </v-container>
    </PublicLayout>
</template>
