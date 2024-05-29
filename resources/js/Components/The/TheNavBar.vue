<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

import { Home1, Star, Map, Discover, User4 } from "@iconsans/vue/linear";

const currentRoute = ref(window.location.href);

const routes = {
    home: {
        route: route("home"),
        icon: Home1,
    },
    discovery: {
        route: route("discovery"),
        icon: Discover,
    },
    map: {
        route: route("map"),
        icon: Map,
    },
    favorite: {
        route: route("favorite"),
        icon: Star,
    },
    collection: {
        route: route("profile"),
        icon: User4,
    },
};

const active = (route) => {
    return currentRoute.value === route;
};
</script>

<template>
    <div class="btm-nav btm-nav-md" id="btm-nav">
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
