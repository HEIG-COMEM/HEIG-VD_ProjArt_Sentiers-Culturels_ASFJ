<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

const currentRoute = ref(window.location.href);

const routes = {
    details: {
        route: route("Details"),
        icon: UserCircle2,
    },
    settings: {
        route: route("Settings"),
        icon: Setting,
    },
};

const active = (route) => {
    return currentRoute.value === route;
};
</script>

<template>
    <!-- TODO : Header pour toutes les pages de la section Profil (Icone : Compte & Paramètres) -->
    <div class="btm-nav btm-nav-md" id="btm-nav">
        <template>
            <ArrowLeft class="h-7 w-7" />
        </template>
        <template v-for="route in routes">
            <Link
                :href="route.route"
                :class="{
                    active: active(route.route),
                }"
            >
                <component :is="route.icon" class="h-7 w-7"></component>
            </Link>
        </template>
    </div>
</template>

<style scoped>
.btm-nav {
    z-index: 1000;
}
</style>
