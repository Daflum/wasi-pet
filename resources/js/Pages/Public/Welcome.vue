<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PetCard from '@/Components/PetCard.vue';
import { ref, onMounted, computed } from 'vue';

const showRequirements = ref(false);
const page = usePage();

// CONFIGURACIÓN DEL HERO
// Obtenemos la imagen desde la configuración global (inyectada por Inertia/Laravel)
const heroImage = computed(() => page.props.settings?.hero_image || null);

defineProps({
    featuredPets: {
        type: Array,
        required: true,
    }
});

// Logic for Scroll Reveal Animations
onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>

<template>
    <Head title="Bienvenido" />
    <PublicLayout>
        <div class="welcome-container">
            <!-- Seccion 1: Hero Dinámico -->
            <div class="hero-section d-flex align-center justify-center">

                <!-- OPCIÓN A: Imagen Real (si existe en configuración) -->
                <v-img
                    v-if="heroImage"
                    :src="heroImage"
                    cover
                    class="hero-bg"
                >
                    <!-- Overlay de Alto Contraste: Asegura legibilidad sobre CUALQUIER foto -->
                    <div class="hero-overlay-strong"></div>
                </v-img>

                <!-- OPCIÓN B: Elegant Gradient Flow (Fallback) -->
                <div v-else class="hero-bg gradient-flow-bg">
                    <!-- Dark Overlay to ensure text readability -->
                    <div class="mesh-dark-overlay"></div>
                    <div class="mesh-overlay-texture"></div>
                </div>

                <!-- Content with Glassmorphism -->
                <v-container class="position-relative z-index-10">
                    <v-row justify="center">
                        <v-col cols="12" md="8" lg="6" class="text-center">
                            <div class="hero-content-glass mx-auto animate-on-scroll">
                                <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 text-white">
                                    Amor de cuatro patas
                                </h1>
                                <p class="text-white text-body-1 mb-6 opacity-90">
                                    Encuentra a tu compañero ideal y cambia una vida para siempre.
                                </p>
                                <Link href="/mascotas" as="div">
                                    <v-btn
                                        size="x-large"
                                        color="secondary"
                                        elevation="6"
                                        class="hero-btn px-8 text-none font-weight-bold"
                                    >
                                        Ver todos los peludos
                                    </v-btn>
                                </Link>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>

                <!-- Wave Divider (Hero -> Pets) -->
                <div class="wave-divider bottom-wave rotated">
                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                    </svg>
                </div>
            </div>

            <!-- Seccion 2: Urgentes (Featured) -->
            <div class="pets-section position-relative">
                <!-- Subtle Pattern Background -->
                <div class="pattern-overlay"></div>

                <v-container class="py-16 position-relative z-index-10">
                    <div class="text-center mb-12 animate-on-scroll">
                        <h2 class="text-h4 font-weight-bold text-primary mb-2">Tu próximo mejor amigo</h2>
                        <div class="d-flex justify-center">
                            <v-sheet width="60" height="4" color="secondary" class="rounded-pill"></v-sheet>
                        </div>
                    </div>

                    <v-row v-if="featuredPets.length > 0">
                        <v-col
                            v-for="(pet, index) in featuredPets"
                            :key="pet.id"
                            cols="12"
                            sm="6"
                            md="4"
                            class="animate-on-scroll"
                            :style="`transition-delay: ${index * 100}ms`"
                        >
                            <PetCard :pet="pet" class="h-100" />
                        </v-col>
                    </v-row>
                    <v-alert v-else type="info" variant="tonal" class="mt-4 rounded-lg animate-on-scroll">
                        Pronto tendremos nuevos amigos para mostrar. ¡Vuelve a visitarnos!
                    </v-alert>
                </v-container>

                <!-- Wave Divider (Pets -> Help) -->
                <div class="wave-divider overlap-bottom">
                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                    </svg>
                </div>
            </div>

            <!-- Seccion 3: Como Ayudar (Modern Cards) -->
            <v-sheet class="py-16 position-relative overflow-hidden" style="background-color: var(--section-bg);">
                <!-- Decorative Blob Background -->
                <div class="position-absolute top-0 left-0 w-100 h-100" style="opacity: 0.05; pointer-events: none;">
                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.1,-19.2,95.8,-5.3C93.5,8.6,82.2,21.5,70.6,32.3C59,43.1,47.1,51.8,34.8,58.6C22.5,65.4,9.8,70.3,-1.9,73.6C-13.6,76.9,-25.3,78.6,-36.4,74.1C-47.5,69.6,-58,58.9,-66.6,46.8C-75.2,34.7,-81.9,21.2,-83.1,7.2C-84.3,-6.8,-80,-21.3,-71.8,-33.5C-63.6,-45.7,-51.5,-55.6,-38.7,-63.5C-25.9,-71.4,-12.4,-77.3,1.3,-79.5C15,-81.8,30.5,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
                    </svg>
                </div>

                <v-container class="position-relative pt-16">
                    <div class="text-center mb-12 animate-on-scroll">
                        <h2 class="text-h4 font-weight-bold text-primary mb-2">¿Cómo puedes ayudar?</h2>
                        <p class="text-body-1 text-medium-emphasis">Pequeñas acciones generan grandes cambios</p>
                    </div>

                    <v-row justify="center" align="stretch">
                        <!-- Adopt Card -->
                        <v-col cols="12" md="5" class="d-flex animate-on-scroll delay-100">
                            <v-card class="flex-grow-1 elevation-2 card-hover-effect action-card" color="surface">
                                <v-card-text class="text-center pa-8 d-flex flex-column align-center h-100">
                                    <div class="icon-circle bg-primary-lighten-5 mb-6">
                                        <v-icon size="48" color="primary">mdi-home-heart</v-icon>
                                    </div>
                                    <h3 class="text-h5 font-weight-bold mb-4">Adoptar</h3>
                                    <p class="text-body-1 mb-6 text-medium-emphasis flex-grow-1">
                                        Abrir tu hogar a una mascota es un acto de amor que cambia dos vidas: la tuya y la de tu nuevo mejor amigo.
                                    </p>
                                    <v-btn
                                        color="primary"
                                        variant="flat"
                                        class="action-btn px-6"
                                        @click="showRequirements = true"
                                    >
                                        Ver Requisitos
                                    </v-btn>
                                </v-card-text>
                            </v-card>
                        </v-col>

                        <!-- Donate Card -->
                        <v-col cols="12" md="5" class="d-flex animate-on-scroll delay-200">
                            <v-card class="flex-grow-1 elevation-2 card-hover-effect action-card" color="surface">
                                <v-card-text class="text-center pa-8 d-flex flex-column align-center h-100">
                                    <div class="icon-circle bg-secondary-lighten-5 mb-6">
                                        <v-icon size="48" color="secondary">mdi-gift-outline</v-icon>
                                    </div>
                                    <h3 class="text-h5 font-weight-bold mb-4">Donar</h3>
                                    <p class="text-body-1 mb-6 text-medium-emphasis flex-grow-1">
                                        Tu contribución nos ayuda a cubrir gastos de alimentación, medicinas y cuidado para que más mascotas estén listas.
                                    </p>
                                    <Link href="/donar" as="div">
                                        <v-btn
                                            color="secondary"
                                            variant="flat"
                                            class="action-btn px-6"
                                        >
                                            Ayudar Ahora
                                        </v-btn>
                                    </Link>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-sheet>

            <!-- Requirements Modal -->
            <v-dialog v-model="showRequirements" max-width="500">
                <v-card class="modal-card">
                    <v-card-title class="text-h5 font-weight-bold text-primary pa-6 pb-2 text-wrap">
                        Requisitos para Adoptar
                    </v-card-title>
                    <v-card-text class="pa-6 pt-2">
                        <v-list density="compact" class="bg-transparent">
                            <v-list-item class="px-0 mb-2">
                                <template v-slot:prepend>
                                    <v-icon color="secondary" class="mr-3">mdi-check-circle</v-icon>
                                </template>
                                <v-list-item-title class="text-wrap text-body-1">Ser mayor de 18 años y presentar DNI vigente.</v-list-item-title>
                            </v-list-item>
                            <v-list-item class="px-0 mb-2">
                                <template v-slot:prepend>
                                    <v-icon color="secondary" class="mr-3">mdi-check-circle</v-icon>
                                </template>
                                <v-list-item-title class="text-wrap text-body-1">Contar con el respaldo de todos los miembros de la familia.</v-list-item-title>
                            </v-list-item>
                            <v-list-item class="px-0 mb-2">
                                <template v-slot:prepend>
                                    <v-icon color="secondary" class="mr-3">mdi-check-circle</v-icon>
                                </template>
                                <v-list-item-title class="text-wrap text-body-1">Solvencia económica para alimentación y salud.</v-list-item-title>
                            </v-list-item>
                            <v-list-item class="px-0 mb-2">
                                <template v-slot:prepend>
                                    <v-icon color="secondary" class="mr-3">mdi-check-circle</v-icon>
                                </template>
                                <v-list-item-title class="text-wrap text-body-1">Espacio seguro y tiempo para la mascota.</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                    <v-card-actions class="justify-end pa-6 pt-0">
                        <v-btn
                            color="primary"
                            variant="tonal"
                            class="action-btn px-6"
                            @click="showRequirements = false"
                        >
                            Entendido
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </PublicLayout>
</template>

<style scoped>
.welcome-container {
    /* --- CSS VARIABLES CONFIGURATION --- */

    /* Layout & Sizes */
    --hero-height-desktop: 550px;
    --hero-height-mobile: 400px;

    /* Design Tokens (Theming) */
    --card-radius: 24px;
    --btn-radius: 9999px;

    /* Colors */
    --divider-color: rgb(var(--v-theme-surface));
    --section-bg: #FAFAFA;

    /* Glassmorphism */
    --glass-bg: rgba(255, 255, 255, 0.15);
    --glass-border: rgba(255, 255, 255, 0.3);
    --glass-blur: 12px;
    --glass-radius: 24px;
}

/* --- HERO STYLES --- */

.hero-section {
    position: relative;
    height: var(--hero-height-desktop);
    width: 100%;
    overflow: hidden;
    background-color: rgb(var(--v-theme-surface)); /* Fallback color */
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

/* Option A: Strong Overlay for Images */
.hero-overlay-strong {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* Stronger gradient to ensure text readability on ANY image */
    background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
}

/* Option B: Gradient Flow (Clean & Elegant) */
.gradient-flow-bg {
    /*
       Usamos un gradiente lineal con 4 colores clave:
       1. Primary (Tu color principal)
       2. Secondary (Tu color secundario)
       3. Primary (Repetimos para suavizar)
       4. Secondary (Cerramos el ciclo)
    */
    background: linear-gradient(-45deg,
        rgb(var(--v-theme-primary)),
        rgb(var(--v-theme-secondary)),
        rgb(var(--v-theme-primary)),
        rgb(var(--v-theme-secondary))
    );
    background-size: 400% 400%;
    animation: gradient-flow 15s ease infinite;
    width: 100%;
    height: 100%;
}

.mesh-dark-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3); /* Overlay suave */
}

.mesh-overlay-texture {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* Noise texture for premium feel */
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
    opacity: 0.4;
}

@keyframes gradient-flow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.hero-content-glass {
    background: var(--glass-bg);
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid var(--glass-border);
    border-radius: var(--glass-radius);
    padding: 3rem;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
}

/* --- ANIMATIONS --- */

/* Scroll Reveal Base Class */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: opacity, transform;
}

/* Visible State */
.animate-on-scroll.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Stagger Delays */
.delay-100 { transition-delay: 0.1s; }
.delay-200 { transition-delay: 0.2s; }
.delay-300 { transition-delay: 0.3s; }
.delay-500 { transition-delay: 0.5s; }

/* --- COMPONENT STYLES --- */

/* Buttons */
.hero-btn, .action-btn {
    border-radius: var(--btn-radius) !important;
}

/* Cards */
.action-card, .modal-card {
    border-radius: var(--card-radius) !important;
}

/* Pets Section */
.pets-section {
    background-color: rgb(var(--v-theme-surface));
    z-index: 10;
}

.pattern-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.03;
    background-image: radial-gradient(circle, #000 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
    z-index: 1;
}

/* Wave Divider */
.wave-divider {
    position: absolute;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    z-index: 5;
    --wave-color: var(--divider-color);
}

.wave-divider.rotated {
    transform: rotate(180deg);
    bottom: 0;
}

.wave-divider.overlap-bottom {
    bottom: -79px;
    transform: none;
    z-index: 20;
}

.wave-divider svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 80px;
}

.wave-divider .shape-fill {
    fill: var(--wave-color);
}

/* Helper Classes */
.z-index-10 {
    z-index: 10;
}

.icon-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

/* Card Hover Effects */
.card-hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover-effect:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
}

.card-hover-effect:hover .icon-circle {
    transform: scale(1.1);
}

/* Mobile Adjustments */
@media (max-width: 960px) {
    .hero-section {
        height: var(--hero-height-mobile);
    }

    .hero-content-glass {
        padding: 1.5rem;
        margin: 0 1rem;
        background: rgba(0, 0, 0, 0.4);
    }

    .wave-divider svg {
        height: 50px;
    }

    .wave-divider.overlap-bottom {
        bottom: -49px;
    }
}
</style>
