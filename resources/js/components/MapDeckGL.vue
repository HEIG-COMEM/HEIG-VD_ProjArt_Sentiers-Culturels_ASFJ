<script setup>
import "maplibre-gl/dist/maplibre-gl.css";
import { MapboxOverlay } from "@deck.gl/mapbox";
import { ScatterplotLayer } from "@deck.gl/layers";
import { PathLayer } from "deck.gl";
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

    const layer = new ScatterplotLayer({
        id: "point-layer",
        data: [
            { position: [6.647398, 46.781334], color: [255, 0, 0] },
            { position: [6.646, 46.782], color: [0, 255, 0] },
            { position: [6.648, 46.78], color: [0, 0, 255] },
        ],
        getPosition: (d) => d.position,
        getRadius: 10,
        getColor: (d) => d.color,
    });

    const pathLayer = new PathLayer({
        id: "path-layer",
        data: [
            {
                path: [
                    [6.647398, 46.781334],
                    [6.646, 46.782],
                    [6.648, 46.78],
                ],
            },
        ],
        getPath: (d) => d.path,
        getColor: [255, 0, 0],
        getWidth: 10,
    });

    // Both
    const mapboxOverlay = new MapboxOverlay({ layers: [layer, pathLayer] });

    map.addControl(mapboxOverlay);
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
