<template>
    <div>
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">{{ $t("Fill Product Files") }}</h3>
               <!--  <div class="form-group w-1/4">
                    <SelectInput
                    v-if="version"
                    :label="$t('Version')"
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
            <div class="card-body">
                <file-inputs
                    @file-changed="addFiles"
                    :productFiles="productFiles"
                />
            </div>
        </div>

        <div>

            <div class="flex justify-end mt-4">
                <button
                    class="primary-btn gap-2 mr-3"
                    @click="$emit('back', 'services')"
                >
                <CustomIcon name="backIcon"/>
                    {{ $t("Back") }}
                </button>
                <loading-button
                    @click="$emit('files', form)"
                    :loading="isLoading"
                    class="secondary-btn gap-2"
                    > <CustomIcon name="saveIcon"/>{{ update ? $t("Update") : $t("Save and Proceed") }}</loading-button
                >
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import FileInputs from "../../Components/FileInputs.vue";

export default {
    computed: {
        ...mapGetters(["isLoading"]),
    },
    components: {
        SelectInput,
        LoadingButton,
        FileInputs,
    },
    props: {
        productFiles: Object,
        update: {
            type: Boolean,
            default: false,
        },
        selectedVersion: Object,
        versions: Array,
    },
    watch: {
        productFiles(val) {
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
    mounted() {
        this.version = { ...this.selectedVersion };
    },
    data() {
        return {
            version: null,
            form: this.productFiles,
        };
    },
    methods: {
        addFiles(data) {
            this.form.files = data;
        },
        selected() {
            setTimeout(() => {
                this.$emit("selectedVersion", this.version);
            }, 1);
        },
        setFiles(event) {
            const file = event.target.files;
            if (file.length === 0) {
                return false;
            }
            for (var i = 0; i < file.length; i++) {
                //check if array already has the file so no duplication occurs
                if (
                    this.form.files.filter((e) => e.name === file[i].name)
                        .length > 0
                ) {
                    continue;
                }
                let getFile = file[i];
                this.form.files.push(getFile);
            }
        },
        uploadFiles() {
            const formData = new FormData();
            formData.append("document", this.files);
        },
    },
};
</script>

<style></style>
