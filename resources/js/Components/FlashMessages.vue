<template>
    <div>
        <div
            v-if="flashMessage.type === 'success' && flashMessage.show"
            :class="`mb-8 max-w-3xl bg-green-500 rounded ${
                $route.name === 'Login' ? '' : 'flash-message'
            }`"
        >
            <div class="flex items-center justify-between">
                <div class="flex">
                    <svg
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white self-center"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
                    </svg>
                    <div class="py-4 text-white text-sm font-medium">
                        {{ flashMessage.message }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        $store.commit('flashMessage', {
                            show: false,
                            type: '',
                            message: '',
                        })
                    "
                >
                    <svg
                        class="block w-2 h-2 fill-green-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
            <a
                v-if="flashMessage.link"
                class="text-white underline p-4"
                :href="flashMessage.link"
                >{{ flashMessage.link }}</a
            >
        </div>
        <div
            v-else-if="flashMessage.type === 'error' && flashMessage.show"
            :class="`mb-8 max-w-3xl bg-red-500 rounded ${
                $route.name === 'Login' ? '' : 'flash-message'
            }`"
        >
            <div class="flex items-center justify-between">
                <div class="flex">
                    <svg
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white self-center"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"
                        />
                    </svg>
                    <div
                        v-if="flashMessage.type === 'error'"
                        class="py-4 text-white text-sm font-medium"
                    >
                        {{ flashMessage.message }}
                    </div>
                    <div v-else class="py-4 text-white text-sm font-medium">
                        <span
                            v-if="Object.keys($page.props.errors).length === 1"
                            >There is one form error.</span
                        >
                        <span v-else
                            >There are
                            {{ Object.keys($page.props.errors).length }} form
                            errors.</span
                        >
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        $store.commit('flashMessage', {
                            show: false,
                            type: '',
                            message: '',
                        })
                    "
                >
                    <svg
                        class="block w-2 h-2 fill-red-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
            <a
                v-if="flashMessage.link"
                class="text-white underline p-4"
                :href="flashMessage.link"
                >{{ flashMessage.link }}</a
            >
        </div>
        <div
            v-else-if="flashMessage.type === 'warning' && flashMessage.show"
            :class="`mb-8 max-w-3xl bg-yellow-500 rounded ${
                $route.name === 'Login' ? '' : 'flash-message'
            }`"
        >
            <div class="flex items-center justify-between">
                <div class="flex">
                    <font-awesome-icon
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white self-center"
                        icon="fas fa-triangle-exclamation"
                        color="white"
                    />
                    <div
                        v-if="flashMessage.type === 'warning'"
                        class="py-4 text-white text-sm font-medium"
                    >
                        {{ flashMessage.message }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        $store.commit('flashMessage', {
                            show: false,
                            type: '',
                            message: '',
                        })
                    "
                >
                    <svg
                        class="block w-2 h-2 text-white group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
            <a
                v-if="flashMessage.link"
                class="text-white underline p-4"
                :href="flashMessage.link"
                >{{ flashMessage.link }}</a
            >
        </div>
        <div
            v-else-if="flashMessage.type === 'info' && flashMessage.show"
            :class="`mb-8 max-w-3xl bg-blue-400 rounded ${
                $route.name === 'Login' ? '' : 'flash-message'
            }`"
        >
            <div class="flex items-center justify-between">
                <div class="flex">
                    <font-awesome-icon
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white self-center"
                        icon="fas fa-info-circle"
                        color="white"
                    />
                    <a
                        :href="flashMessage.link"
                        v-if="flashMessage.type === 'info'"
                        class="py-4 text-white text-sm font-medium"
                        style="white-space: pre"
                    >
                        <span v-html="flashMessage.message"></span>
                    </a>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        $store.commit('flashMessage', {
                            show: false,
                            type: '',
                            message: '',
                        })
                    "
                >
                    <svg
                        class="block w-2 h-2 text-white group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["flashMessage"]),
    },
};
</script>

<style scoped>
.flash-message {
    position: fixed;
    right: 2%;
    top: 15%;
    z-index: 999999999 !important;
}
</style>
