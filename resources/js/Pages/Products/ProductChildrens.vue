<template>
    <div>
        <div class="card">
              <div class="card-header flex items-center justify-between">
                <h3 class="card-title">{{ $t("Add Products Childrens") }}</h3>
                   <!--  <SelectInput
                        v-if="version && paymentDetailsType === 'software'"
                        :label="$t('Version')"
                        @update:model-value="selected"
                        v-model="version.name"
                        class="w-1/4 form-group"
                    >
                        <option
                            v-for="(version, index) in versions"
                            :key="version.name + '-' + index"
                            :value="version.name"
                        >
                            {{ version.name }}
                        </option>
                    </SelectInput> -->
              </div>
              <div class="card-body">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <div class="pr-6 w-full lg:w-1/2">
                        <label for="">{{ $t("Software") }}:</label>
                        <treeselect
                            class="mt-1"
                            v-model="form.productChildrens"
                            :multiple="true"
                            :options="options"
                            :valueConsistsOf="'LEAF_PRIORITY'"
                        >
                            <template #value-label="props">
                                {{ getLabelWithProductName(props) }}
                            </template>
                        </treeselect>
                    </div>
                    <div class="pr-6 w-full lg:w-1/2">
                        <label for="">{{ $t("Services") }}:</label>
                        <treeselect
                            class="mt-1"
                            v-model="form.versionServiceChildrens"
                            :multiple="true"
                            :options="servicesOptions"
                            :valueConsistsOf="'LEAF_PRIORITY'"
                        >
                            <template #value-label="props">
                                {{ getLabelWithProductName(props) }}
                            </template>
                        </treeselect>
                    </div>
                </div>
              </div>
        </div>

        <div class="mt-4 flex justify-end items-center">
           <!--  <button
                class="primary-btn mr-3 gap-2"
                @click="
                    $emit(
                        'back',
                        paymentDetailsType === 'software'
                            ? 'uninstall'
                            : 'products'
                    )
                "
            >
            <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button> -->
            <button
                class="primary-btn mr-3 gap-2"
                @click="$emit('back','products')"
            >
            <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </button>
            <button class="primary-btn mr-3" @click="$emit('childrens', form)">
                {{ $t("Skip") }}
            </button>
            <loading-button
                @click="$emit('childrens', form)"
                :loading="isLoading"
                class="secondary-btn gap-2"
                ><CustomIcon name="nextIcon"/>{{
                    paymentDetailsType === "software" ||
                    paymentDetailsType === "cloud-software" ||
                    paymentDetailsType === "hosting"
                        ? $t("Next")
                        : update
                        ? $t("Update")
                        : $t("Create")
                }}</loading-button
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
        SelectInput,
        Treeselect,
    },
    watch: {
        productChildrens(val) {
            this.form.productChildrens =
                val?.productChildrens?.filter(
                    (child) => typeof child === "string"
                ) ?? null;
            this.form.versionServiceChildrens =
                val?.versionServiceChildrens?.filter(
                    (child) => typeof child === "string"
                ) ?? null;
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
        dependencies: Array,
        servicesChildrens: Array,
        products: Object,
        productChildrens: Object,
        selectedVersion: Object,
        versions: Array,
        paymentDetailsType: String,
        update: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters(["isLoading"]),
        options() {
            const options = this.createDependencyTree(this.dependencies);
            this.$emit("options", options);
            return options;
        },
        servicesOptions() {
            const servicesOptions = this.createDependencyTreeServices(
                this.servicesChildrens
            );
            this.$emit("servicesOptions", servicesOptions);
            return servicesOptions;
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
        createDependencyTreeServices(services) {
            const dependencyTree = [];
            if (services instanceof Array) {
                services.forEach((dependency) => {
                    dependencyTree.push({
                        id: dependency.articleNumber,
                        label: dependency.productName,
                        productId: dependency.productId,
                    });
                });
            } else {
                for (let key in services) {
                    dependencyTree.push({
                        id: uuidv4(),
                        label: key,
                        children: this.createDependencyTreeServices(
                            services[key]
                        ),
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
            form: this.productChildrens,
        };
    },
};
</script>

<style></style>
