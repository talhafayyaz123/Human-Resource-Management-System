<template>
    <div>
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">{{ $t("Add Setup Parameters") }}</h3>
                <div class="form-group w-1/4">
                    <SelectInput
                        v-if="version"
                        :label="$t('Version')"
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
                <div
                    v-for="(parameter, index) in form.parameters"
                    :key="index"
                    class="grid items-center grid-cols-4 gap-6"
                >
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-model="parameter.type"
                                :label="$t('Type')"
                                :error="errors[`parameters.${index}.type`]"
                            >
                                <option value="text">{{ $t("Text") }}</option>
                                <option value="number">
                                    {{ $t("Number") }}
                                </option>
                                <option value="file">{{ $t("File") }}</option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="parameter.key"
                                :error="errors[`parameters.${index}.key`]"
                                :label="$t('Key')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full" v-if="parameter.type == 'file'">
                        <div class="form-group">
                            <text-input
                                v-model="parameter.file"
                                :error="errors[`parameters.${index}.value`]"
                                :label="$t('Value')"
                                @click.stop="clickFile(index)"
                                style="cursor: pointer"
                                :isReadonly="true"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full" v-else>
                        <div class="form-group">
                            <text-input
                                :error="errors[`parameters.${index}.value`]"
                                :label="$t('Value')"
                                v-model="parameter.value"
                                :type="parameter.type"
                            ></text-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <input
                                :id="`fileRef${index}`"
                                style="display: none"
                                @change="checkFileUpload($event, index)"
                                type="file"
                            />
                            <button
                                @click="form.parameters.splice(index, 1)"
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
                    :class="[form.parameters.length == 0 ? 'pt-8' : '']"
                    class="mt-4"
                >
                    <button
                        @click="
                            form.parameters.push({
                                type: 'text',
                                key: '',
                                file: null,
                                value: '',
                            })
                        "
                        class="secondary-btn"
                    >
                        {{ $t("Add") }}
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button
                class="primary-btn gap-2 mr-3"
                @click="$emit('back', 'childrens')"
            >
            <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button>
            <button class="primary-btn gap-2 mr-3"  @click="$emit('parameters', form)">
                {{ $t("Skip") }}
            </button>
            <loading-button
                @click="$emit('parameters', form)"
                :loading="isLoading"
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
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
    },
    watch: {
        productParameters(val) {
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
    props: {
        productParameters: Object,
        selectedVersion: Object,
        versions: Array,
    },
    data() {
        return {
            version: null,
            form: this.productParameters,
        };
    },
    mounted() {
        this.version = { ...this.selectedVersion };
    },
    methods: {
        selected() {
            setTimeout(() => {
                this.$emit("selectedVersion", this.version);
            }, 1);
        },
        clickFile(index) {
            document.getElementById(`fileRef${index}`).click();
        },
        checkFileUpload(e, index) {
            if (this.form.parameters[index].type === "file") {
                let files = e.target.files;
                if (!files.length) return;
                this.readFile(files[0], index);
            }
        },
        readFile(file, index) {
            let reader = new FileReader();
            reader.onloadend = (e) => {
                let varo = String(e.target.result);
                this.form.parameters[index].value = varo;
            };
            reader.readAsText(file);
            this.form.parameters[index].file = file.name;
        },
    },
};
</script>

<style></style>
