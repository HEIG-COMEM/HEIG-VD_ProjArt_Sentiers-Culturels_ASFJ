<script setup>
import { ref, onMounted, onUnmounted, watch } from "vue";
import Hammer from "hammerjs";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:drawerIsOpen"]);

const isOpen = ref(props.isOpen);
const drawer = ref(null);
const drawerContent = ref(null);
let hammer = null;

const toggleDrawer = () => {
    isOpen.value = !isOpen.value;
    emit("update:drawerIsOpen", isOpen.value);
};

watch(
    () => props.isOpen,
    (newVal) => {
        isOpen.value = newVal;
    },
);

const handlePan = (event) => {
    if (event.additionalEvent === "panup" && !isOpen.value) {
        isOpen.value = true;
    } else if (
        event.additionalEvent === "pandown" &&
        isOpen.value &&
        drawerContent.value.scrollTop < 5
    ) {
        isOpen.value = false;
    }
    emit("update:drawerIsOpen", isOpen.value);
};

onMounted(() => {
    hammer = new Hammer(drawer.value);
    hammer
        .get("pan")
        .set({ direction: Hammer.DIRECTION_VERTICAL, threshold: 30 });

    hammer.on("panup pandown", handlePan);
});

onUnmounted(() => {
    if (hammer) {
        hammer.destroy();
    }
});
</script>

<template>
    <Transition>
        <div
            v-if="isOpen"
            class="absolute top-0 bottom-0 w-full bg-base-300 bg-opacity-30 z-10 cursor-pointer"
            @click="toggleDrawer"
        ></div>
    </Transition>
    <div
        ref="drawer"
        class="fixed bottom-16 left-0 w-full h-[60dvh] bg-white transform transition-transform duration-300 ease-in-out z-20 drop-shadow-2xl"
        :class="isOpen ? 'translate-y-0' : 'translate-y-full'"
    >
        <div
            ref="drawerContent"
            class="p-5 overflow-auto h-full max-w-lg mx-auto"
        >
            <slot />
        </div>
        <div
            class="fixed top-[-120px] w-full h-[120px] bg-white flex flex-col rounded-t-3xl"
        >
            <div
                class="flex py-4 justify-center items-center cursor-pointer"
                @click="toggleDrawer"
            >
                <svg
                    class="text-gray-300"
                    xmlns="http://www.w3.org/2000/svg"
                    width="48"
                    height="9"
                    viewBox="0 0 48 9.5"
                    fill="currentColor"
                >
                    <path
                        v-if="isOpen"
                        d="M48 2.88683C48 4.07797 47.1401 5.0951 45.9656 5.29331L25.664 8.7192C24.5624 8.90509 23.4376 8.90509 22.336 8.7192L2.0344 5.2933C0.85987 5.0951 -1.00021e-06 4.07797 -8.96082e-07 2.88683V2.88683C-7.64066e-07 1.37675 1.35756 0.229084 2.84659 0.480358L22.336 3.7692C23.4376 3.95509 24.5624 3.95509 25.664 3.7692L45.1534 0.480362C46.6424 0.229089 48 1.37675 48 2.88683V2.88683Z"
                    />
                    <path
                        v-else
                        d="M0 6.11317C0 4.92203 0.859869 3.9049 2.0344 3.70669L22.336 0.280796C23.4376 0.0949128 24.5624 0.0949126 25.664 0.280796L45.9656 3.70669C47.1401 3.9049 48 4.92203 48 6.11317V6.11317C48 7.62325 46.6424 8.77091 45.1534 8.51964L25.664 5.2308C24.5624 5.04491 23.4376 5.04491 22.336 5.2308L2.84659 8.51964C1.35756 8.77091 0 7.62325 0 6.11317V6.11317Z"
                    />
                </svg>
            </div>
            <div class="py-1 px-5 w-full max-w-lg mx-auto bg-white">
                <slot name="tab" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
