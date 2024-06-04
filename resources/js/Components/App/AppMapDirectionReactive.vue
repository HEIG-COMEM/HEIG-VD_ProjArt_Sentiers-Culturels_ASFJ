<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
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

const getCoords = (interestpoints) => {
    return interestpoints.map((interestpoint) => {
        return [interestpoint.long, interestpoint.lat];
    });
};

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: [6.6322734, 46.5196535],
        zoom: 12,
        minZoom: 8,
        maxBounds: SWITZERLAND_BOUNDS,
    });

    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true,
        },
        trackUserLocation: true,
        showUserHeading: true,
        showUserLocation: true,
    });

    const navigationControl = new mapboxgl.NavigationControl();

    map.addControl(geolocateControl, "bottom-right");
    map.addControl(navigationControl, "bottom-right");

    map.on("load", () => {
        const directions = new MapLibreGlDirections(map, {
            unit: "metric",
            api: "https://routing.openstreetmap.de/routed-foot/route/v1",
        });

        map.addControl(new LoadingIndicatorControl(directions));

        if (model.value.length > 0) {
            directions.setWaypoints(getCoords(model.value));
            map.flyTo({
                center: getCoords(model.value).at(0),
                zoom: 12,
            });
        }

        watch(model.value, (newValue) => {
            directions.clear();
            directions.setWaypoints(getCoords(newValue));
            map.flyTo({
                center: getCoords(newValue).at(0),
                zoom: 12,
            });
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
    flex: 1;
    height: 100%;
}
</style>
