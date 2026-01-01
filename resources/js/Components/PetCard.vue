<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    },
});

const placeholderImage = 'https://dog.ceo/api/breeds/image/random'; // A placeholder if no photo
</script>

<template>
    <Link :href="route('public.pets.show', { slug: pet.slug })" class="text-decoration-none">
        <v-card hover>
            <v-img
                :src="pet.image || placeholderImage"
                height="250px"
                cover
            >
                <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                    </v-row>
                </template>
            </v-img>

            <v-card-title>{{ pet.name }}</v-card-title>

            <v-card-subtitle class="d-flex align-center pb-4">
                <v-icon left>{{ pet.type === 'dog' ? 'mdi-dog' : 'mdi-cat' }}</v-icon>
                <span class="mx-2">{{ pet.type === 'dog' ? 'Perro' : 'Gato' }} &bull; {{ pet.gender }}</span>
            </v-card-subtitle>

        </v-card>
    </Link>
</template>
