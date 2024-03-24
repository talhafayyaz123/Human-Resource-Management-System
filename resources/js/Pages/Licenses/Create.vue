<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill License Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
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
                                    class=""
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
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
                                    class=""
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    v-model="form.status"
                                    :key="form.status"
                                    :label="$t('Status')"
                                    :error="errors.status"
                                    class=""
                                >
                                    <option :value="1">
                                        {{ $t("Active") }}
                                    </option>
                                    <option :value="0">
                                        {{ $t("Inactive") }}
                                    </option>
                                </SelectInput>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <ProductsSelector ref="productSelector" />
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Config") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-1 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <TextAreaInput
                                    v-model="form.config"
                                    :error="errors.config"
                                    class=""
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <file-inputs
                                    class=""
                                    @file-changed="addFiles"
                                    :productFiles="files"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/licenses" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    @click="store"
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
import FileInputs from "../../Components/FileInputs.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import ProductsSelector from "./Components/ProductsSelector.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("auth", ["user"]),
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
                    to: "/licenses",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                company_id: "",
                tenant_id: "",
                rules: [],
                product_info: "",
                status: 1,
                config: "",
            },
            files: {
                files: [],
            },
            filesConvertedToBase64: [],
        };
    },
    watch: {
        user: {
            handler: function () {
                this.setTenantByUser();
            },
            deep: true,
        },
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
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
            this.setTenantByUser();
        } catch (e) {
            console.log(e);
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
            });
        },
        // sets the tenant_id of the form from the user's tenant_id
        async setTenantByUser() {
            if (this.user.tenant_id) {
                this.form.tenant_id = this.companies?.data?.find(
                    (company) => company.id == this.user.tenant_id
                );
                if (!this.form.tenant_id) {
                    const response = await this.$store.dispatch(
                        "companies/show",
                        this.user.tenant_id
                    );
                    this.form.tenant_id = response?.data?.modelData ?? "";
                }
            }
        },
        /**
         * hits the licenses create API
         */
        async store() {
            // try to JSON parse the 'config' to see if it's valid json
            let data = null;
            try {
                if (this.form.config.length) {
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
                await this.$store.dispatch("licensingLicenses/create", {
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
                this.$router.push("/licenses");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
