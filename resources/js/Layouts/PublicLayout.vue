<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const drawer = ref(false);
const page = usePage();
const snackbar = ref(false);
const snackbarText = ref('');

// Scroll Logic for Dynamic Navbar
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Route Logic: Check if we are on the Home page
const isHomePage = computed(() => page.url === '/');

// Navbar State Logic
// If NOT home page, always treat as "scrolled" (solid color)
// If IS home page, rely on scroll position
const showSolidNavbar = computed(() => !isHomePage.value || isScrolled.value);

// Dynamic Classes
const navbarColor = 'transparent';
const navbarElevation = computed(() => showSolidNavbar.value ? 4 : 0);

// Text always white for consistency
const logoColor = 'white';

watch(() => page.props.flash, (flash) => {
    if (flash && flash.success) {
        snackbarText.value = flash.success;
        snackbar.value = true;
    }
});
</script>

<template>
    <v-app>
        <!-- Public Navigation Drawer (Mobile) -->
        <v-navigation-drawer v-model="drawer" temporary>
            <v-list>
                <Link href="/" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-home" title="Inicio"></v-list-item>
                </Link>
                <Link href="/mascotas" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-paw" title="Ver Mascotas"></v-list-item>
                </Link>
                 <Link href="/donar" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-heart" title="Donar"></v-list-item>
                </Link>
            </v-list>
        </v-navigation-drawer>

        <!-- Public App Bar (Dynamic) -->
        <v-app-bar
            :color="navbarColor"
            :elevation="navbarElevation"
            class="transition-all"
            :class="{ 'glass-navbar': showSolidNavbar }"
        >
            <v-app-bar-nav-icon
                @click="drawer = !drawer"
                class="d-md-none"
                :color="logoColor"
            ></v-app-bar-nav-icon>

            <Link href="/" class="text-decoration-none d-flex align-center ml-4 text-white">
                <v-icon icon="mdi-paw" class="mr-2" :color="logoColor"></v-icon>
                <v-toolbar-title class="font-weight-bold">Adra Uni</v-toolbar-title>
            </Link>

            <v-spacer></v-spacer>

            <!-- Desktop Menu -->
            <div class="d-none d-md-flex align-center ga-2 mr-4">
                <Link href="/" as="div">
                    <v-btn variant="text" :color="logoColor" class="font-weight-medium">Inicio</v-btn>
                </Link>
                <Link href="/mascotas" as="div">
                    <v-btn variant="text" :color="logoColor" class="font-weight-medium">Mascotas</v-btn>
                </Link>
                <Link href="/donar" as="div">
                    <v-btn
                        :variant="showSolidNavbar ? 'elevated' : 'outlined'"
                        :color="showSolidNavbar ? 'white' : 'white'"
                        :class="showSolidNavbar ? 'text-primary' : ''"
                        class="rounded-pill px-6 font-weight-bold"
                    >
                        Donar
                    </v-btn>
                </Link>
            </div>
        </v-app-bar>

        <!-- Main Content -->
        <!-- Add padding-top ONLY if we are NOT on home page, to prevent content hiding behind fixed navbar -->
        <v-main class="bg-grey-lighten-4" :class="{ 'pt-0': isHomePage, 'pt-16': !isHomePage }">
            <slot />

            <v-snackbar
                v-model="snackbar"
                :timeout="5000"
                color="success"
                location="bottom right"
            >
                {{ snackbarText }}
                <template v-slot:actions>
                    <v-btn color="white" variant="text" @click="snackbar = false">
                        Cerrar
                    </v-btn>
                </template>
            </v-snackbar>
        </v-main>

        <v-footer class="bg-grey-darken-4 text-white text-center d-flex flex-column py-6">
            <div>
                <v-btn
                    v-if="$page.props.settings?.facebook_url"
                    :href="$page.props.settings.facebook_url"
                    target="_blank"
                    icon="mdi-facebook"
                    variant="text"
                    class="mx-2"
                ></v-btn>
                <v-btn
                    v-if="$page.props.settings?.instagram_url"
                    :href="$page.props.settings.instagram_url"
                    target="_blank"
                    icon="mdi-instagram"
                    variant="text"
                    class="mx-2"
                ></v-btn>
                <v-btn
                    v-if="$page.props.settings?.tiktok_url"
                    :href="$page.props.settings.tiktok_url"
                    target="_blank"
                    icon="mdi-music-note"
                    variant="text"
                    class="mx-2"
                ></v-btn>
                <v-btn
                    v-if="$page.props.settings?.x_url"
                    :href="$page.props.settings.x_url"
                    target="_blank"
                    icon
                    variant="text"
                    class="mx-2"
                >
                    <v-icon>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </v-icon>
                </v-btn>
                <v-btn
                    v-if="$page.props.settings?.youtube_url"
                    :href="$page.props.settings.youtube_url"
                    target="_blank"
                    icon="mdi-youtube"
                    variant="text"
                    class="mx-2"
                ></v-btn>
            </div>

            <div class="pt-2 text-grey-lighten-1">
                © 2025 Adra Uni • Potenciado por
                <Link href="/acerca-de" class="text-white font-weight-bold text-decoration-none">WasiPet</Link>
            </div>
        </v-footer>
    </v-app>
</template>

<style scoped>
.v-list-item--link {
    cursor: pointer;
}

.transition-all {
    transition: background-color 0.4s ease, box-shadow 0.4s ease !important;
}

/* Glassmorphism for Scrolled Navbar (Primary Color) */
.glass-navbar {
    /* Uses the primary theme color but with opacity */
    background-color: rgba(var(--v-theme-primary), 0.85) !important;
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
</style>
