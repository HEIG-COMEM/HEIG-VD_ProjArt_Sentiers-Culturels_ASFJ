<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

import { Home1, Notes, Map } from "@iconsans/vue/linear";

const currentRoute = ref(window.location.href);

const routes = {
    home: {
        route: route("backoffice"),
        icon: Home1,
    },
    listing: {
        route: route("collection"), // TODO: Change to backoffice.collection
        icon: Notes,
    },
    map: {
        route: route("backoffice.map"),
        icon: Map,
    },
};

const active = (route) => {
    return currentRoute.value === route;
};
</script>

<template>
    <div class="btm-nav btm-nav-md shadow-top-sm" id="btm-nav">
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
