<script setup>
import { computed, onMounted, onUnmounted, reactive, ref } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import MapLibreGlDirections, {
    LoadingIndicatorControl,
} from "@maplibre/maplibre-gl-directions";
import mapboxgl from "maplibre-gl";
import { defineProps } from "vue";

const props = defineProps();

const model = defineModel();

const mapInstance = ref(null);

const SWITZERLAND_BOUNDS = [
    [5.955911, 45.818028], // Southwest coordinates
    [10.492294, 47.808464], // Northeast coordinates
];

const hasLatLng = computed(() => model.value && model.value.length === 2);

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: hasLatLng.value ? [...model.value] : [6.633597, 46.519962],
        zoom: 12,
        minZoom: 8,
        maxBounds: SWITZERLAND_BOUNDS,
    });

    map.on("load", () => {
        map.doubleClickZoom.disable();
        const directions = new MapLibreGlDirections(map, {
            unit: "metric",
        });

        directions.interactive = true;

        map.addControl(new LoadingIndicatorControl(directions));

        if (hasLatLng) directions.addWaypoint([...model.value]);

        map.on("click", (e) => {
            directions.clear();
            directions.addWaypoint(e.lngLat.toArray());
            model.value = e.lngLat.toArray();
        });
    });

    mapInstance.value = map;
});

onUnmounted(() => {
    mapInstance.value.remove();
    mapInstance.value = null;
});
</script>

<template>
    <div id="mapContainer" class="map-container"></div>
</template>

<style scoped>
.map-container {
    height: 100%;
    width: 100%;
}
</style>
