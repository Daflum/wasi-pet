<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const drawer = ref(null);
const snackbar = ref(false);
const snackbarText = ref('');
const snackbarColor = ref('info');

const page = usePage();

watch(() => page.props.flash, (flash) => {
    if (flash && flash.success) {
        snackbarText.value = flash.success;
        snackbarColor.value = 'success';
        snackbar.value = true;
    }
    if (flash && flash.error) {
        snackbarText.value = flash.error;
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
}, { deep: true });

watch(() => page.props.errors, (errors) => {
    const errorValues = Object.values(errors);
    if (errorValues.length > 0) {
        snackbarText.value = errorValues[0];
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
}, { deep: true });

</script>

<template>
  <v-app>
    <v-navigation-drawer v-model="drawer" app>
      <v-list>
        <Link :href="route('admin.dashboard')" as="div">
          <v-list-item link :active="route().current('admin.dashboard')">
            <template v-slot:prepend>
              <v-icon>mdi-view-dashboard</v-icon>
            </template>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item>
        </Link>
        <Link :href="route('admin.pets.index')" as="div">
          <v-list-item link :active="route().current('admin.pets.*')">
            <template v-slot:prepend>
              <v-icon>mdi-paw</v-icon>
            </template>
            <v-list-item-title>Mascotas</v-list-item-title>
          </v-list-item>
        </Link>
        <Link :href="route('admin.donations.index')" as="div">
          <v-list-item link :active="route().current('admin.donations.*')">
              <template v-slot:prepend>
                  <v-icon>mdi-gift</v-icon>
              </template>
            <v-list-item-title>Donaciones</v-list-item-title>
          </v-list-item>
        </Link>
        <Link :href="route('admin.adoption-requests.index')" as="div">
          <v-list-item link :active="route().current('admin.adoption-requests.*')">
              <template v-slot:prepend>
                  <v-icon>mdi-home-heart</v-icon>
              </template>
            <v-list-item-title>Solicitudes</v-list-item-title>
          </v-list-item>
        </Link>
          <Link :href="route('admin.bingo.index')" as="div">
              <v-list-item link :active="route().current('admin.bingo.*')">
                  <template v-slot:prepend>
                      <v-icon>mdi-ticket</v-icon>
                  </template>
                  <v-list-item-title>Bingo</v-list-item-title>
              </v-list-item>
          </Link>
        <Link :href="route('admin.settings.index')" as="div">
          <v-list-item link :active="route().current('admin.settings.*')">
              <template v-slot:prepend>
                  <v-icon>mdi-cog</v-icon>
              </template>
            <v-list-item-title>Configuración</v-list-item-title>
          </v-list-item>
        </Link>
      </v-list>

      <template v-slot:append>
        <v-divider></v-divider>
        <div class="pa-2">
            <Link :href="route('admin.profile.edit')" as="div">
                <v-list-item link :active="route().current('admin.profile.edit')">
                    <template v-slot:prepend>
                        <v-icon>mdi-account</v-icon>
                    </template>
                    <v-list-item-title>Mi Perfil</v-list-item-title>
                </v-list-item>
            </Link>
            <Link :href="route('logout')" method="post" as="div">
                <v-list-item link>
                    <template v-slot:prepend>
                        <v-icon>mdi-logout</v-icon>
                    </template>
                    <v-list-item-title>Cerrar Sesión</v-list-item-title>
                </v-list-item>
            </Link>
        </div>
      </template>
    </v-navigation-drawer>

    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer" class="d-lg-none" />
      <v-toolbar-title>Admin WasiPet</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <slot />
      </v-container>
    </v-main>

    <v-snackbar
        v-model="snackbar"
        :color="snackbarColor"
        :timeout="5000"
        location="top right"
    >
        {{ snackbarText }}
        <template v-slot:actions>
            <v-btn icon @click="snackbar = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </template>
    </v-snackbar>
  </v-app>
</template>
