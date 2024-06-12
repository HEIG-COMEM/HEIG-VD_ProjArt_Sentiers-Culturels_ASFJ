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
        map.addSource("interestPointFirst", {
            type: "geojson",
            data: {
                type: "FeatureCollection",
                features: interestpoints.slice(0, 1).map((interestPoint) => ({
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
        map.addSource("interestPoints", {
            type: "geojson",
            data: {
                type: "FeatureCollection",
                features: interestpoints.slice(1, -1).map((interestPoint) => ({
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
        map.addSource("interestPointLast", {
            type: "geojson",
            data: {
                type: "FeatureCollection",
                features: interestpoints.slice(-1).map((interestPoint) => ({
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
        const svgStartImage = new Image(50, 50);
        const svgFinishImage = new Image(50, 50);
        svgImage.onload = () => {
            map.addImage("locationPin", svgImage);
            map.addImage("startLocationPin", svgStartImage);
            map.addImage("finishLocationPin", svgFinishImage);
        };
        const svgStringToImageSrc = (svgString) =>
            `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svgString)}`;

        const LOCATION_SVG = `<svg xmlns="http://www.w3.org/2000/svg" fill="#62b537" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.49 18.79a3.001 3.001 0 0 1-5 0c-4-5.87-3.69-8.71-3.69-8.71a6.18 6.18 0 1 1 12.36 0s.37 2.84-3.67 8.71Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12.07a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path></svg>`;
        const LOCATION_START_SVG = `<svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 448 512"><path d="M64 32C64 14.3 49.7 0 32 0S0 14.3 0 32V64 368 480c0 17.7 14.3 32 32 32s32-14.3 32-32V352l64.3-16.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L64 48V32z"/></svg>`;
        const LOCATION_FINISH_SVG = `<svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 448 512"><path d="M32 0C49.7 0 64 14.3 64 32V48l69-17.2c38.1-9.5 78.3-5.1 113.5 12.5c46.3 23.2 100.8 23.2 147.1 0l9.6-4.8C423.8 28.1 448 43.1 448 66.1V345.8c0 13.3-8.3 25.3-20.8 30l-34.7 13c-46.2 17.3-97.6 14.6-141.7-7.4c-37.9-19-81.3-23.7-122.5-13.4L64 384v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V400 334 64 32C0 14.3 14.3 0 32 0zM64 187.1l64-13.9v65.5L64 252.6V318l48.8-12.2c5.1-1.3 10.1-2.4 15.2-3.3V238.7l38.9-8.4c8.3-1.8 16.7-2.5 25.1-2.1l0-64c13.6 .4 27.2 2.6 40.4 6.4l23.6 6.9v66.7l-41.7-12.3c-7.3-2.1-14.8-3.4-22.3-3.8v71.4c21.8 1.9 43.3 6.7 64 14.4V244.2l22.7 6.7c13.5 4 27.3 6.4 41.3 7.4V194c-7.8-.8-15.6-2.3-23.2-4.5l-40.8-12v-62c-13-3.8-25.8-8.8-38.2-15c-8.2-4.1-16.9-7-25.8-8.8v72.4c-13-.4-26 .8-38.7 3.6L128 173.2V98L64 114v73.1zM320 335.7c16.8 1.5 33.9-.7 50-6.8l14-5.2V251.9l-7.9 1.8c-18.4 4.3-37.3 5.7-56.1 4.5v77.4zm64-149.4V115.4c-20.9 6.1-42.4 9.1-64 9.1V194c13.9 1.4 28 .5 41.7-2.6l22.3-5.2z"/></svg>`;

        svgImage.src = svgStringToImageSrc(LOCATION_SVG);
        svgStartImage.src = svgStringToImageSrc(LOCATION_START_SVG);
        svgFinishImage.src = svgStringToImageSrc(LOCATION_FINISH_SVG);

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

        map.addLayer({
            id: "interestPointFirst",
            type: "symbol",
            source: "interestPointFirst",
            layout: {
                "icon-image": "startLocationPin",
                "icon-size": 0.5,
                "icon-allow-overlap": true,
            },
        });

        map.addLayer({
            id: "interestPointLast",
            type: "symbol",
            source: "interestPointLast",
            layout: {
                "icon-image": "finishLocationPin",
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

        const clickEvent = (e) => {
            const feature = e.features[0];
            const interestPoint = interestpoints.find(
                (ip) => ip.uuid === feature.properties.uuid,
            );

            if (interestPoint) {
                props.currentInterestPointIndex.value =
                    interestpoints.indexOf(interestPoint);
            }
        };

        map.on("click", "interestPoints", (e) => {
            clickEvent(e);
        });

        map.on("click", "interestPointFirst", (e) => {
            clickEvent(e);
        });

        map.on("click", "interestPointLast", (e) => {
            clickEvent(e);
        });
    });

    mapInstance.value = map;
});
</script>

<template>
    <div id="mapContainer" class="h-full"></div>

    <!-- Screen reader only -->
    <div class="sr-only" aria-live="polite">
        <ol
            v-for="(segment, index) in segments"
            :key="segment.id"
            :aria-details="`Ségment numéro ${index + 1} du parcours`"
        >
            <li v-for="step in segment.steps">
                {{ step.instruction }}
            </li>
        </ol>
    </div>
</template>

<style scoped></style>
