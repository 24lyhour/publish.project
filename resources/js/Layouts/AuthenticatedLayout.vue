<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const menu = page.props.menu;

const drawer = ref(true);
</script>

<template>
    <v-app>
        <v-navigation-drawer v-model="drawer">
            <v-list nav>
                <Link v-for="(item, i) in menu" :key="i" :href="route(item.route)" as="div">
                <v-list-item :prepend-icon="item.icon" :title="item.label" :active="route().current(item.route)" link />
                </Link>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Dashboard</v-toolbar-title>
        </v-app-bar>

        <v-main style="min-height: 100vh;" class="bg-grey-lighten-4">
            <slot />
        </v-main>
    </v-app>
</template>