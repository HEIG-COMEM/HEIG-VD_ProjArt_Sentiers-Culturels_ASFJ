<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const currentRoute = ref(window.location.href);

const routes = {
    home: route("home"),
    map: route("map"),
    profile: route("profile"),
};

const active = (route) => currentRoute.value === route;
</script>

<template>
    <div class="btm-nav btm-nav-md" id="btm-nav">
        <template v-for="route in routes">
            <Link
                :href="route"
                :class="{
                    active: active(route),
                }"
            >
                <button>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                    </svg>
                </button>
            </Link>
        </template>
    </div>
</template>

<style scoped>
.btm-nav {
    z-index: 1000;
}
</style>
