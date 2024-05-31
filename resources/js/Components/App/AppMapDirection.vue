<script setup>
import { onMounted, onUnmounted, reactive, ref } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import MapLibreGlDirections, {
    LoadingIndicatorControl,
} from "@maplibre/maplibre-gl-directions";
import mapboxgl from "maplibre-gl";
import { defineProps } from "vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    isBackoffice: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const mapInstance = ref(null);
const route = reactive(props.route);

const SWITZERLAND_BOUNDS = [
    [5.955911, 45.818028], // Southwest coordinates
    [10.492294, 47.808464], // Northeast coordinates
];

const getCoords = (route) => {
    return route.interest_points.map((interestPoint) => [
        interestPoint.long,
        interestPoint.lat,
    ]);
};

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: getCoords(route)[(route.interest_points.length / 2).toFixed(0)],
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

    map.on("load", function () {
        setTimeout(() => {
            // geolocateControl.trigger();
        }, 200);
    });

    map.on("load", () => {
        const directions = new MapLibreGlDirections(map, {
            unit: "metric",
            api: "https://routing.openstreetmap.de/routed-foot/route/v1",
        });

        map.addControl(new LoadingIndicatorControl(directions));

        // Set the waypoints programmatically
        directions.setWaypoints(getCoords(route));
    });

    // inspect a cluster on click
    map.on("click", "clusters", async (e) => {
        const features = map.queryRenderedFeatures(e.point, {
            layers: ["clusters"],
        });
        const currentZoom = map.getZoom();
        const zoom = currentZoom + 2;

        map.easeTo({
            center: features[0].geometry.coordinates,
            zoom,
        });
    });

    map.on("click", "interestPoints", (e) => {
        if (e.features[0].properties.uuid === undefined) return;
        const url = props.isBackoffice
            ? `/backoffice/interest-points/${e.features[0].properties.uuid}`
            : `/interest-point/${e.features[0].properties.uuid}`;
        window.location.href = url;
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
