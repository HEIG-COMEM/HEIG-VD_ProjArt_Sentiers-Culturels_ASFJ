<script setup lang="ts">
import "maplibre-gl/dist/maplibre-gl.css";
import { Map, NavigationControl } from "maplibre-gl";
import { onMounted } from "vue";

onMounted(() => {
    const map = new Map({
        container: "map",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json",
        center: [6.647398, 46.781334],
        zoom: 15,
    });

    map.addControl(
        new NavigationControl({
            visualizePitch: true,
        }),
    );
});
</script>

<template>
    <div id="map"></div>
</template>

<style scoped>
#map {
    height: 100vh;
}
</style>
