<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    },
});

const placeholderImage = 'https://dog.ceo/api/breeds/image/random';

// Helper to determine gender colors/icons
const genderConfig = computed(() => {
    const isMale = props.pet.gender === 'Macho';
    return {
        icon: isMale ? 'mdi-gender-male' : 'mdi-gender-female',
        color: isMale ? 'blue-darken-2' : 'pink-darken-2', // Darker text for contrast
        bgColor: 'white',
    };
});
</script>

<template>
    <Link :href="route('public.pets.show', { slug: pet.slug })" class="text-decoration-none d-flex h-100">
        <v-card
            class="flex-grow-1 pet-card-modern d-flex flex-column"
            elevation="2"
        >
            <div class="image-container">
                <v-img
                    :src="pet.image || placeholderImage"
                    height="280"
                    cover
                    class="pet-image transition-swing"
                >
                    <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                    </template>

                    <div class="image-overlay"></div>
                </v-img>

                <!-- Floating Gender Icon (High Contrast) -->
                <v-chip
                    class="gender-chip position-absolute top-0 right-0 ma-3"
                    :color="genderConfig.bgColor"
                    variant="elevated"
                    elevation="2"
                    size="small"
                    label
                >
                    <v-icon start size="small" :color="genderConfig.color">{{ genderConfig.icon }}</v-icon>
                    <span :class="`text-${genderConfig.color} font-weight-bold`">{{ pet.gender }}</span>
                </v-chip>
            </div>

            <v-card-item class="pt-5 px-5">
                <v-card-title class="text-h5 font-weight-bold text-primary mb-1">
                    {{ pet.name }}
                </v-card-title>

                <v-card-subtitle class="d-flex align-center px-0 opacity-80">
                    <!-- Species Icon (Bone/Fish) -->
                    <v-icon size="small" color="secondary" class="mr-1">
                        {{ pet.species_icon }}
                    </v-icon>
                    <span class="text-body-2 font-weight-medium">{{ pet.species_label }}</span>

                    <span class="mx-2">•</span>

                    <span class="text-body-2">{{ pet.age_label }}</span>
                </v-card-subtitle>
            </v-card-item>

            <v-card-text class="px-5 pb-2 flex-grow-1">
                <div class="d-flex gap-2 mt-2">
                    <!-- Size Chip -->
                    <v-chip size="small" variant="tonal" color="grey-darken-3" class="font-weight-medium">
                        <v-icon start size="x-small">mdi-ruler</v-icon>
                        {{ pet.size_label }}
                    </v-chip>
                </div>
            </v-card-text>

            <v-card-actions class="px-5 pb-5 pt-0">
                <v-btn
                    block
                    variant="tonal"
                    color="secondary"
                    class="rounded-pill font-weight-bold"
                >
                    Conóceme
                    <v-icon end>mdi-arrow-right</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
    </Link>
</template>

<style scoped>
.pet-card-modern {
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.pet-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}

.image-container {
    position: relative;
    overflow: hidden;
}

.pet-card-modern:hover .pet-image {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 30%;
    background: linear-gradient(to top, rgba(0,0,0,0.05), transparent);
    pointer-events: none;
}

.gender-chip {
    /* Ensure high contrast and visibility over any image */
    background-color: white !important;
}
</style>
