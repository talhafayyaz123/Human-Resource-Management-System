<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill License Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <text-input
                            :required="true"
                            v-model="form.name"
                            :error="errors.name"
                            class="pb-8 pr-6 w-full lg:w-1/3"
                            :label="$t('Name')"
                        />
                        <MultiSelectInput
                            v-model="form.company_id"
                            :key="form.company_id"
                            :required="true"
                            :options="companies.data"
                            :multiple="false"
                            :textLabel="$t('Company')"
                            label="companyName"
                            trackBy="id"
                            :error="errors.companyId"
                            moduleName="companies"
                            class="pb-8 pr-6 w-full lg:w-1/3"
                        />
                        <MultiSelectInput
                            v-model="form.tenant_id"
                            :key="form.tenant_id"
                            :options="partners?.data ?? []"
                            :multiple="false"
                            :textLabel="$t('Tenant')"
                            label="companyName"
                            :error="errors.tenant_id"
                            trackBy="id"
                            moduleName="companies"
                            class="pb-8 pr-6 w-full lg:w-1/3"
                            :query="{ customerType: 'partner' }"
                            :countStore="'partnerCount'"
                        />
                        <SelectInput
                            v-model="form.status"
                            :key="form.status"
                            :label="$t('Status')"
                            :error="errors.status"
                            class="pb-8 pr-6 w-full lg:w-1/3"
                        >
                            <option :value="1">{{ $t("Active") }}</option>
                            <option :value="0">{{ $t("Inactive") }}</option>
                        </SelectInput>
                    </div>
                </div>
            </div>
            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <ProductsSelector
                        :selectedProductsParent="selectedProducts"
                        ref="productSelector"
                    />
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Config") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <TextAreaInput
                            v-model="form.config"
                            :error="errors.config"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                        />
                        <file-inputs
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            @file-changed="addFiles"
                            :productFiles="files"
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/licenses`" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    @click="update"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import FileInputs from "../../Components/FileInputs.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import ProductsSelector from "./Components/ProductsSelector.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        ProductsSelector,
        SelectInput,
        TextAreaInput,
        FileInputs,
        PageHeader,
    },
    props: {},
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "License Generator",
                    to: `/licenses`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                name: "",
                company_id: "",
                tenant_id: "",
                rules: [],
                status: 1,
                config: "",
            },
            selectedProducts: [],
            files: {
                files: [],
            },
            filesConvertedToBase64: [],
        };
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "licensingLicenses/show",
                this.$route.params.id
            );
            this.form = {
                id: this.$route.params.id,
                name: response?.data?.name ?? "",
                company_id: response?.data?.company ?? "",
                tenant_id: response?.data?.tenant ?? "",
                rules: response?.data?.rules ?? [],
                status: response?.data?.status ?? 1,
            };
            if (response?.data?.config?.data) {
                this.form.config = JSON.stringify(response?.data?.config?.data);
            }
            if (response?.data?.config?.files) {
                this.files.files = response?.data?.config?.files;
                this.filesConvertedToBase64 = response?.data?.config?.files;
            }
            this.selectedProducts = response?.data?.products ?? [];
            if (!this.companies?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                });
            if (!this.partners?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        /**
         * converts the files to base64 and saves then in 'filesConvertedToBase64'
         * @param {files} selected files, which need to be converted to base64
         */
        addFiles(files) {
            this.filesConvertedToBase64 = [];
            files.forEach((file) => {
                // only convert to base64 if the file is of type Blob
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        this.filesConvertedToBase64.push({
                            name: file.name,
                            content: reader.result,
                        });
                    };
                    reader.onerror = (error) => {
                        console.log(error);
                    };
                } else {
                    this.filesConvertedToBase64.push({
                        ...file,
                    });
                }
            });
        },
        /**
         * hits the licenses create API
         */
        async update() {
            // try to JSON parse the 'config' to see if it's valid json
            let data = null;
            try {
                if (this.form.config?.length) {
                    data = JSON.parse(this.form.config);
                }
            } catch (e) {
                alert(e);
                return;
            }
            try {
                this.$store.commit("isLoading", true);
                let rules = [];
                this.$refs.productSelector.selectedProducts.forEach(
                    (product) => {
                        for (let i = 0; i < product.quantity; i++) {
                            rules = [
                                ...rules,
                                ...product.rules.map((rule) => rule.id),
                            ];
                        }
                    }
                );
                await this.$store.dispatch("licensingLicenses/update", {
                    ...this.form,
                    company_id: this.form.company_id?.id ?? "",
                    tenant_id: this.form.tenant_id?.id ?? "",
                    rules: rules,
                    product_info:
                        this.$refs.productSelector.selectedProducts.map(
                            (product) => {
                                return {
                                    id: product.id,
                                    quantity: product.quantity,
                                };
                            }
                        ),
                    config: {
                        data: data,
                        files: this.filesConvertedToBase64,
                    },
                });
                await this.$store.dispatch("licensingLicenses/list");
                this.$router.push(`/licenses`);
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
