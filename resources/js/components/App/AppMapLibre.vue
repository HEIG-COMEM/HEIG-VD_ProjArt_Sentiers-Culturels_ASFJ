<script setup>
import "maplibre-gl/dist/maplibre-gl.css";
import { MapboxOverlay } from "@deck.gl/mapbox";
import { ScatterplotLayer } from "@deck.gl/layers";
import { PathLayer } from "deck.gl";
import { Map, NavigationControl } from "maplibre-gl";
import { reactive, onMounted, computed } from "vue";

import { store } from "@/Stores/userPosition";
import { useGeolocation } from "@vueuse/core";

const props = defineProps({
    interestPoints: {
        type: Object,
        required: true,
    },
});

const { coords, locatedAt, error, resume, pause } = useGeolocation();

const emit = defineEmits(["clickOnPoint"]);

const IP_COLOR = [255, 0, 255];

const ipDataReact = reactive([]);

props.interestPoints.forEach((ip) => {
    ipDataReact.push({
        position: [+ip.long, +ip.lat],
        color: IP_COLOR,
    });
});

onMounted(() => {
    const map = new Map({
        container: "map",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json",
        center: [6.647398, 46.781334],
        zoom: 15,
    });

    const layer = new ScatterplotLayer({
        id: "point-layer",
        data: ipDataReact,
        getPosition: (d) => d.position,
        getRadius: 10,
        getFillColor: (d) => d.color,
    });

    const mapboxOverlay = new MapboxOverlay({ layers: [layer] });

    map.addControl(mapboxOverlay);
});
</script>

<template>
    {{ coords.latitude }}, {{ coords.longitude }}
    <div id="map"></div>
</template>

<style scoped>
#map {
    height: 100vh;
}
</style>
