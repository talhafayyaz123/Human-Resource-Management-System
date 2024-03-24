<template>
    <div>
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">{{ $t("Fill Install Routines") }}</h3>
                <div class="form-group w-1/4">
                    <SelectInput
                    v-if="version"
                    label="Version"
                    class=""
                    @input="selected"
                    v-model="version.name"
                >
                    <option
                        v-for="(version, index) in versions"
                        :key="version.name + '-' + index"
                        :value="version.name"
                    >
                        {{ version.name }}
                    </option>
                </SelectInput>
                </div>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <div
                    id="accordion-color"
                    style="width: 100%; padding-right: 1.5rem"
                    data-accordion="collapse"
                    data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                >
                    <h2
                        @click="isWindowsInstall = !isWindowsInstall"
                        :style="[isWindowsInstall ? '' : 'margin-bottom:20px']"
                        id="accordion-color-heading-1"
                    >
                        <button
                            type="button"
                            class="flex items-center justify-between w-full p-5 font-medium text-left border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-gray-800 bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                            data-accordion-target="#accordion-color-body-1"
                            aria-expanded="true"
                            aria-controls="accordion-color-body-1"
                        >
                            <span>{{
                                $t("Text Area for windows install routines")
                            }}</span>
                            <svg
                                data-accordion-icon=""
                                :class="[isWindowsInstall ? '' : 'rotate-180']"
                                class="w-6 h-6 shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </h2>
                    <div
                        id="accordion-color-body-1"
                        :class="[isWindowsInstall ? '' : 'hidden']"
                        style="margin-bottom: 50px"
                        aria-labelledby="accordion-color-heading-1"
                    >
                        <div
                            class="p-5 font-light border border-t-0 border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                                <text-area-input
                                    v-model="form.windowsPremiseRoutine"
                                    :error="errors.windowsPremiseRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Windows premise setup routine')"
                                />
                                <text-area-input
                                    v-model="form.windowsPrivateRoutine"
                                    :error="errors.windowsPrivateRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Windows public setup routine')"
                                />
                                <text-area-input
                                    v-model="form.windowsPublicRoutine"
                                    :error="errors.windowsPublicRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Windows private routine')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <div
                    id="accordion-color"
                    style="width: 100%; padding-right: 1.5rem"
                    data-accordion="collapse"
                    data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                >
                    <h2
                        @click="isLinuxInstall = !isLinuxInstall"
                        :style="[isLinuxInstall ? '' : 'margin-bottom:20px']"
                        id="accordion-color-heading-1"
                    >
                        <button
                            type="button"
                            class="flex items-center justify-between w-full p-5 font-medium text-left border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-gray-800 bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                            data-accordion-target="#accordion-color-body-1"
                            aria-expanded="true"
                            aria-controls="accordion-color-body-1"
                        >
                            <span>{{
                                $t("Text Area for linux install routines")
                            }}</span>
                            <svg
                                data-accordion-icon=""
                                :class="[isLinuxInstall ? '' : 'rotate-180']"
                                class="w-6 h-6 shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </h2>
                    <div
                        id="accordion-color-body-1"
                        :class="[isLinuxInstall ? '' : 'hidden']"
                        style="margin-bottom: 50px"
                        aria-labelledby="accordion-color-heading-1"
                    >
                        <div
                            class="p-5 font-light border border-t-0 border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                                <text-area-input
                                    v-model="form.linuxPremiseRoutine"
                                    :error="errors.linuxPremiseRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Linux premise setup routine')"
                                />
                                <text-area-input
                                    v-model="form.linuxPrivateRoutine"
                                    :error="errors.linuxPrivateRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Linux public setup routine')"
                                />
                                <text-area-input
                                    v-model="form.linuxPublicRoutine"
                                    :error="errors.linuxPublicRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Linux private routine')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <div
                    id="accordion-color"
                    style="width: 100%; padding-right: 1.5rem"
                    data-accordion="collapse"
                    data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                >
                    <h2
                        @click="isMacInstall = !isMacInstall"
                        :style="[isMacInstall ? '' : 'margin-bottom:20px']"
                        id="accordion-color-heading-1"
                    >
                        <button
                            type="button"
                            class="flex items-center justify-between w-full p-5 font-medium text-left border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-gray-800 bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white"
                            data-accordion-target="#accordion-color-body-1"
                            aria-expanded="true"
                            aria-controls="accordion-color-body-1"
                        >
                            <span>{{
                                $t("Text Area for mac install routines")
                            }}</span>
                            <svg
                                data-accordion-icon=""
                                :class="[isLinuxInstall ? '' : 'rotate-180']"
                                class="w-6 h-6 shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </h2>
                    <div
                        id="accordion-color-body-1"
                        :class="[isMacInstall ? '' : 'hidden']"
                        style="margin-bottom: 50px"
                        aria-labelledby="accordion-color-heading-1"
                    >
                        <div
                            class="p-5 font-light border border-t-0 border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                                <text-area-input
                                    v-model="form.macPremiseRoutine"
                                    :error="errors.macPremiseRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Mac premise setup routine')"
                                />
                                <text-area-input
                                    v-model="form.macPrivateRoutine"
                                    :error="errors.macPrivateRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Mac public setup routine')"
                                />
                                <text-area-input
                                    v-model="form.macPublicRoutine"
                                    :error="errors.macPublicRoutine"
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :label="$t('Mac private routine')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="flex justify-end items-center mt-4">
            <button
                class="primary-btn gap-2 mr-3"
                @click="$emit('back', 'dependencies')"
            >
            <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button>
            <button class="primary-btn gap-2 mr-3" @click="$emit('install', form)">
                {{ $t("Skip") }}
            </button>
            <loading-button
                @click="$emit('install', form)"
                :loading="isLoading"
                class="secondary-btn gap-2"
                > <CustomIcon name="nextIcon"/>{{ $t("Next") }}</loading-button
            >
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        SelectInput,
        LoadingButton,
        TextAreaInput,
    },
    props: {
        installRoutines: Object,
        selectedVersion: Object,
        versions: Array,
    },
    watch: {
        selectedVersion(val) {
            this.version = { ...val };
        },
        installRoutines(val) {
            this.form = { ...val };
        },
        form: {
            handler: function (val, oldVal) {
                this.$emit("valueChanged", val);
            },
            deep: true,
        },
    },
    methods: {
        selected() {
            setTimeout(() => {
                this.$emit("selectedVersion", this.version);
            }, 1);
        },
    },
    mounted() {
        this.version = { ...this.selectedVersion };
    },
    data() {
        return {
            version: null,
            isWindowsInstall: false,
            isLinuxInstall: false,
            isMacInstall: false,
            form: this.installRoutines,
        };
    },
};
</script>

<style></style>
