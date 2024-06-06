<script setup>
import { onMounted, onUnmounted, ref, watch, reactive, computed } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import mapboxgl from "maplibre-gl";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    currentInterestPointIndex: {
        type: Object,
        required: true,
    },
});

const IP = reactive(props.route.interest_points);
const currentInterestPoint = computed(() => {
    return IP.at(props.currentInterestPointIndex.value);
});

const route = reactive(props.route);
const interestpoints = reactive(route.interest_points);

const segments = computed(
    () => JSON.parse(route.path).features.at(0).properties.segments,
);

const mapInstance = ref(null);

const SWITZERLAND_BOUNDS = [
    [5.955911, 45.818028], // Southwest coordinates
    [10.492294, 47.808464], // Northeast coordinates
];

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: [6.6322734, 46.5196535],
        zoom: 14,
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
        setTimeout(() => {
            geolocateControl.trigger();
        }, 1);
    });

    map.on("load", () => {
        map.addSource("interestPoints", {
            type: "geojson",
            data: {
                type: "FeatureCollection",
                features: interestpoints.map((interestPoint) => ({
                    type: "Feature",
                    properties: {
                        id: interestPoint.id,
                        uuid: interestPoint.uuid,
                        name: interestPoint.name,
                    },
                    geometry: {
                        type: "Point",
                        coordinates: [interestPoint.long, interestPoint.lat],
                    },
                })),
            },
        });

        map.addSource("route", {
            type: "geojson",
            data: JSON.parse(route.path),
        });

        map.addLayer({
            id: "route",
            type: "line",
            source: "route",
            layout: {
                "line-join": "round",
                "line-cap": "round",
            },
            paint: {
                "line-color": "#4C8C2B",
                "line-width": 5,
            },
        });

        const svgImage = new Image(84, 84);
        svgImage.onload = () => {
            map.addImage("locationPin", svgImage);
        };
        const svgStringToImageSrc = (svgString) =>
            `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svgString)}`;

        const LOCATION_SVG = `<svg xmlns="http://www.w3.org/2000/svg" fill="#62b537" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.49 18.79a3.001 3.001 0 0 1-5 0c-4-5.87-3.69-8.71-3.69-8.71a6.18 6.18 0 1 1 12.36 0s.37 2.84-3.67 8.71Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12.07a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path></svg>`;

        svgImage.src = svgStringToImageSrc(LOCATION_SVG);

        map.addLayer({
            id: "interestPoints",
            type: "symbol",
            source: "interestPoints",
            layout: {
                "icon-image": "locationPin",
                "icon-size": 0.5,
                "icon-allow-overlap": true,
            },
        });

        watch(currentInterestPoint, (newValue) => {
            map.flyTo({
                center: [newValue.long, newValue.lat],
                zoom: 16,
                essential: true,
            });
        });

        map.on("click", "interestPoints", (e) => {
            const feature = e.features[0];
            const interestPoint = interestpoints.find(
                (ip) => ip.uuid === feature.properties.uuid,
            );

            if (interestPoint) {
                props.currentInterestPointIndex.value =
                    interestpoints.indexOf(interestPoint);
            }
        });
    });

    mapInstance.value = map;
});
</script>

<template>
    <div id="mapContainer" class="h-full"></div>

    <!-- Screen reader only -->
    <div class="sr-only" aria-live="polite">
        <div
            v-for="(segment, index) in segments"
            :key="segment.id"
            :aria-details="`ségment numéro ${index + 1} du parcours`"
        >
            <p v-for="step in segment.steps">
                {{ step.instruction }}
            </p>
        </div>
    </div>
</template>

<style scoped></style>
