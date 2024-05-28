<script setup>
import { onMounted, onUnmounted, reactive, ref, watch } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import mapboxgl from "maplibre-gl";
import { defineProps, defineModel } from "vue";
import { MapboxOverlay } from "@deck.gl/mapbox";
import { ScatterplotLayer } from "@deck.gl/layers";
import { useGeolocation } from "@vueuse/core";

const INTEREST_POINT_COLOR = [255, 0, 255];
const INTEREST_POINT_SIZE = 10;

const props = defineProps({
    interestPoints: {
        type: Object,
        required: true,
    },
});

const mapInstance = ref(null);
const interestPoints = ref(props.interestPoints);
const interestPointsMarkers = ref([]);

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

    const navigationControl = new mapboxgl.NavigationControl();

    map.addControl(geolocateControl, "bottom-right");
    map.addControl(navigationControl, "bottom-right");

    map.on("load", function () {
        setTimeout(() => {
            geolocateControl.trigger();
        }, 200);
    });

    map.on("load", () => {
        interestPoints.value.forEach((interestPoint) => {
            const interestPointMarker = new mapboxgl.Marker({
                color: INTEREST_POINT_COLOR,
                draggable: false,
            })
                .setLngLat([interestPoint.long, interestPoint.lat])
                .addTo(map);

            interestPointsMarkers.value.push(interestPointMarker);
        });
    });

    // Trop lourd pour le moment (fait ralentire le zoom et le déplacement de la carte)
    // TODO: à optimiser
    // map.on("move", updateLocation);
    // map.on("zoom", updateLocation);
    // map.on("rotate", updateLocation);
    // map.on("pitch", updateLocation);

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
