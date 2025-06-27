1


<template>
    <v-app>
        <v-navigation-drawer v-model="drawer">
            <v-list nav>
                <Link v-for="(item, i) in menu" :key="i" :href="route(item.route)" as="div">
                <v-list-item :prepend-icon="item.icon" :title="item.label" :active="route().current(item.route)"
                    class="my-1" link />
                </Link>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Dashboard</v-toolbar-title>
        </v-app-bar>

        <v-main style="min-height: 100vh;" class="bg-grey-lighten-4">
            <slot />
            <v-snackbar v-model="snackbar" :timeout="3000" :color="flashColor" location="top right">
                {{ flashMessage }}
            </v-snackbar>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const menu = page.props.menu;

const drawer = ref(true);
const snackbar = ref(false);
const flashMessage = ref('');
const flashColor = ref('success');

watch(() => page.props.flash, (flash) => {
    if (flash.success) {
        flashMessage.value = flash.success;
        flashColor.value = 'success';
        snackbar.value = true;
    }
}, { deep: true });
</script>