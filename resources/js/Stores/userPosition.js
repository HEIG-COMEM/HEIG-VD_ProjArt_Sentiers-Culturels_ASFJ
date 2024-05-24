// store.js
import { reactive } from 'vue'

export const store = reactive({
    position: null,
    get() {
        return this.position;
    },
    set(position) {
        this.position = position;
    },
})