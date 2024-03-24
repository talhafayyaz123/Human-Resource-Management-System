<template>
    <div>
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">
                    {{ $t("Add Products Dependencies") }}
                </h3>
                <div class="form-group w-1/4">
                    <SelectInput
                    v-if="version"
                    :label="$t('Version')"
                    class=""
                    @update:modelValue="selected"
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
                <div class="flex">
                    <treeselect
                        v-model="dependentForm.dependentProducts"
                        :multiple="true"
                        :options="options"
                        :valueConsistsOf="'LEAF_PRIORITY'"
                        class="pr-6 w-full lg:w-1/2"
                    >
                        <template #value-label="props">
                            {{ getLabelWithProductName(props) }}
                        </template>
                    </treeselect>
                </div>
            </div>
        </div>
        <div class="mt-4 flex justify-end items-center">
            <button class="primary-btn gap-2 mr-3" @click="$emit('back', 'products')">
                <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button>
            <button class="primary-btn mr-3" @click="$emit('dependencies', dependentForm)">

                {{ $t("Skip") }}
            </button>
            <loading-button
                @click="$emit('dependencies', dependentForm)"
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Treeselect from "@tkmam1x/vue3-treeselect";
import "@tkmam1x/vue3-treeselect/dist/vue3-treeselect.css";
import { v4 as uuidv4 } from "uuid";
import { mapGetters } from "vuex";

export default {
    components: {
        LoadingButton,
        TextAreaInput,
        MultiSelectInput,
        Treeselect,
        SelectInput,
    },
    watch: {
        form(val) {
            this.dependentForm.dependentProducts =
                val?.dependentProducts?.filter(
                    (product) => typeof product === "string"
                ) ?? null;
        },
        async selectedVersion(val) {
            this.version = { ...val };
        },
        dependentForm: {
            handler: function (val, oldVal) {
                this.$emit("valueChanged", val);
            },
            deep: true,
        },
    },
    props: {
        dependencies: Array,
        form: Object,
        selectedVersion: Object,
        versions: Array,
    },
    computed: {
        ...mapGetters(["isLoading"]),
        options() {
            const options = this.createDependencyTree(this.dependencies);
            this.$emit("options", options);
            return options;
        },
    },
    methods: {
        getLabelWithProductName(props) {
            const productNode =
                props.node.ancestors[props.node.ancestors.length - 2]; //because the product node will always be at level 1, we can do this
            return `${productNode?.raw?.label ?? ""} ${props.node.raw.label}`;
        },
        createDependencyTree(dependencies) {
            const dependencyTree = [];
            if (dependencies instanceof Array) {
                dependencies.forEach((dependency) => {
                    dependencyTree.push({
                        id: dependency.id,
                        label: dependency.value,
                        productId: dependency.productId,
                        versionId: dependency.versionId,
                    });
                });
            } else {
                for (let key in dependencies) {
                    dependencyTree.push({
                        id: uuidv4(),
                        label: key,
                        children: this.createDependencyTree(dependencies[key]),
                    });
                }
            }
            return dependencyTree;
        },
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
            dependentForm: this.form,
        };
    },
};
</script>

<style></style>
