<template>
    <div>
        <div class="card pb-6">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">{{ $t("Add Additional Services") }}</h3>
                <!-- <div class="form-group w-1/4">
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
                </div> -->
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Installations") }}</h3>
            </div>
            <div class="card-body">
                <div
                    v-for="(installation, index) in form.installations"
                    :key="index"
                    class="grid items-center grid-cols-4 gap-6 mb-3"
                >
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="installation.subject"
                                :label="$t('Subject')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="installation.time"
                                :label="$t('Time needed(working days)')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="installation.detail"
                                :label="$t('Details')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <button
                                @click="form.installations.splice(index, 1)"
                                style="color: red"
                            >
                                <font-awesome-icon
                                    ref="icon"
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    :class="[form.installations?.length == 0 ? 'pt-8' : '']"
                    class="mt-4"
                >
                    <button
                        @click="
                            form.installations.push({
                                subject: '',
                                time: '',
                                detail: '',
                            })
                        "
                        class="secondary-btn"
                    >
                        {{ $t("Add") }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Configurations") }}</h3>
            </div>
            <div class="card-body">
                <div
                    v-for="(configuration, index) in form.configurations"
                    :key="index"
                    class="grid items-center grid-cols-4 gap-6 mb-3"
                >
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="configuration.subject"
                                :label="$t('Subject')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="configuration.time"
                                :label="$t('Time needed(working days)')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="configuration.detail"
                                :label="$t('Details')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <button
                                @click="form.configurations.splice(index, 1)"
                                style="color: red"
                            >
                                <font-awesome-icon
                                    ref="icon"
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    :class="[form.configurations?.length == 0 ? 'pt-8' : '']"
                    class="mt-4"
                >
                    <button
                        @click="
                            form.configurations.push({
                                subject: '',
                                time: '',
                                detail: '',
                            })
                        "
                        class="secondary-btn"
                    >
                        {{ $t("Add") }}
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-end">
            <button
                class="primary-btn gap-2 mr-3"
                @click="$emit('back', 'childrens')"
            ><CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button>
            <button class="primary-btn gap-2 mr-3" @click="$emit('services', form)">
                {{ $t("Skip") }}
            </button>
            <loading-button
                :loading="isLoading"
                @click="$emit('services', form)"
                class="secondary-btn gap-2"
                ><CustomIcon name="nextIcon"/>{{ $t("Next") }}</loading-button
            >
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["isLoading"]),
    },
    components: { SelectInput, LoadingButton, TextInput },
    props: {
        productServices: Object,
        selectedVersion: Object,
        versions: Array,
    },
    mounted() {
        this.version = { ...this.selectedVersion };
    },
    watch: {
        productServices(val) {
            this.form = { ...val };
        },
        selectedVersion(val) {
            this.version = { ...val };
        },
        form: {
            handler: function (val, oldVal) {
                this.$emit("valueChanged", val);
            },
            deep: true,
        },
    },
    data() {
        return {
            version: null,
            form: this.productServices,
        };
    },
    methods: {
        selected() {
            setTimeout(() => {
                this.$emit("selectedVersion", this.version);
            }, 1);
        },
    },
};
</script>

<style></style>
