<script setup>
import { onMounted, onUnmounted, reactive, ref, watch } from "vue";
import "maplibre-gl/dist/maplibre-gl.css";
import mapboxgl from "maplibre-gl";
import { defineProps } from "vue";

const props = defineProps({
    interestPoints: {
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

const SWITZERLAND_BOUNDS = [
    [5.955911, 45.818028], // Southwest coordinates
    [10.492294, 47.808464], // Northeast coordinates
];

onMounted(() => {
    const map = new mapboxgl.Map({
        container: "mapContainer",
        style: "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.lightbasemap.vt/style.json", // Replace with your preferred map style
        center: [6.633597, 46.519962], // Coordinates of Lausanne
        zoom: 10,
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
            geolocateControl.trigger();
        }, 200);
    });

    map.on("load", () => {
        map.addSource("interestPoints", {
            type: "geojson",
            data: {
                type: "FeatureCollection",
                features: props.interestPoints.map((interestPoint) => ({
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
            cluster: true,
            clusterMaxZoom: 14, // Max zoom to cluster points on
            clusterRadius: 50, // Radius of each cluster when clustering points (defaults to 50)
        });

        watch(
            () => props.interestPoints,
            (newValue) => {
                map.getSource("interestPoints").setData({
                    type: "FeatureCollection",
                    features: newValue.map((interestPoint) => ({
                        type: "Feature",
                        properties: {
                            id: interestPoint.id,
                            uuid: interestPoint.uuid,
                            name: interestPoint.name,
                        },
                        geometry: {
                            type: "Point",
                            coordinates: [
                                interestPoint.long,
                                interestPoint.lat,
                            ],
                        },
                    })),
                });
            },
        );

        map.addLayer({
            id: "clusters",
            type: "circle",
            source: "interestPoints",
            filter: ["has", "point_count"],
            paint: {
                // Use step expressions (https://maplibre.org/maplibre-style-spec/#expressions-step)
                // with three steps to implement three types of circles:
                //   * Blue, 20px circles when point count is less than 100
                //   * Yellow, 30px circles when point count is between 100 and 750
                //   * Pink, 40px circles when point count is greater than or equal to 750
                "circle-color": [
                    "step",
                    ["get", "point_count"],
                    "#c4eec7",
                    100,
                    "#c4eec7",
                    750,
                    "#c4eec7",
                ],
                "circle-radius": [
                    "step",
                    ["get", "point_count"],
                    30,
                    100,
                    40,
                    750,
                    50,
                ],
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
    });

    // inspect a cluster on click
    map.on("click", "clusters", async (e) => {
        const features = map.queryRenderedFeatures(e.point, {
            layers: ["clusters"],
        });
        const clusterId = features[0].properties.cluster_id;
        // const zoom = await map
        //     .getSource("interestPoints")
        //     .getClusterExpansionZoom(clusterId);
        // get the zoom level for the cluster to break apart
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
