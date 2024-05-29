<script setup>
import { onMounted, onUnmounted, reactive, ref, watch } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import mapboxgl from "maplibre-gl";

const mapInstance = ref(null);

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: [6.633597, 46.519962],
        zoom: 9,
    });

    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true,
        },
        // When active the map will receive updates to the device's location as it changes.
        trackUserLocation: true,
        // Draw an arrow next to the location dot to indicate which direction the device is heading.
        showUserHeading: true,
        showUserLocation: true,
    });

    map.addControl(geolocateControl, "bottom-right");

    map.on("load", function () {
        setTimeout(() => {
            geolocateControl.trigger();
        }, 200);
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
    flex: 1;
    height: 97vh;
}
</style>
