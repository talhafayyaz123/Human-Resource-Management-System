<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Product Store Entries Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.productTitle"
                                    :error="errors.productTitle"
                                    class=""
                                    :label="$t('Product Title')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.products"
                                    :key="form.products"
                                    :options="products.data"
                                    :multiple="true"
                                    :textLabel="$t('Product')"
                                    label="name"
                                    :error="errors.product_id"
                                    trackBy="id"
                                    moduleName="products"
                                    @select="addProductPrice"
                                    @remove="removeProductPrice"
                                    class=""
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <TextAreaInput
                                    v-model="form.shortDescription"
                                    :error="errors.shortDescription"
                                    label="Short Description"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="checkbox-group">
                                <input
                                    v-model="form.isVisibleForPartner"
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="partners"
                                />
                                <label for="partners" class="checkbox-label">
                                    {{ $t("Visible for Partners") }}:
                                </label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="checkbox-group">
                                <input
                                    v-model="form.isVisibleForCustomer"
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="customer"
                                />
                                <label for="customer" class="checkbox-label">
                                    {{ $t("Visible for Customers") }}:
                                </label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.sellPrice"
                                    :error="errors.sellPrice"
                                    class=""
                                    :label="$t('Sell Price')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.authorId"
                                    :textLabel="$t('Author')"
                                    :error="errors['authorId']"
                                    :key="form.authorId"
                                    :required="true"
                                    :options="partners?.data ?? []"
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="partner"
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <QuillTextEditor
                                    class=""
                                    :content="form.longDescription"
                                    :required="false"
                                    :content-type="'html'"
                                    @delta="form.longDescription = $event"
                                    :label="$t('Product Description')"
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                                <div class="w-full">
                                    <label class="form-label"
                                        >&nbsp;{{
                                            $t("Product Images")
                                        }}:</label
                                    >
                                    <file-inputs
                                        @file-changed="addFiles"
                                        :productFiles="files"
                                    />
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/store-entries" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
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
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import FileInputs from "@/Components/DraggableFileInputs.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import { mapGetters } from "vuex";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["partners"]),
        ...mapGetters("products", ["products"]),
        ...mapGetters("auth", ["user"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        QuillTextEditor,
        SelectInput,
        TextAreaInput,
        FileInputs,
        PageHeader,
    },
    props: {
        recordId: {
            type: Number,
            required: false,
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Store Entries",
                    to: "/store-entries",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                productTitle: "",
                isVisibleForPartner: false,
                isVisibleForCustomer: false,
                longDescription: "",
                shortDescription: "",
                sellPrice: 0,
                products: [],
                authorId: "",
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
            if (!this.products?.data?.length)
                await this.$store.dispatch("products/list");

            if (!this.partners?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
            await this.setTenantByUser();
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async removeProductPrice(values) {
            this.form.sellPrice = 0;
            if (!!this.form.products?.length > 0) {
                this.form.products?.map((row) => {
                    this.form.sellPrice =
                        Number(this.form.sellPrice) +
                        Number(row?.salePrice || 0);
                });
            }
            this.form.sellPrice =
                Number(this.form.sellPrice) - Number(values?.salePrice || 0);
        },
        addProductPrice(values) {
            this.form.sellPrice = 0;
            if (!!this.form.products?.length > 0) {
                this.form.products?.map((row) => {
                    this.form.sellPrice =
                        Number(this.form.sellPrice) +
                        Number(row?.salePrice || 0);
                });
            }
            this.form.sellPrice =
                Number(this.form.sellPrice) + Number(values?.salePrice || 0);
        },
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
                    const requiredData = reader.result.split(",");
                    this.filesConvertedToBase64.push({
                        name: file.name,
                        size: file.size,
                        base64: requiredData[1],
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
                this.form.authorId = this.partners?.data?.find(
                    (company) => company.id == this.user.tenant_id
                );
                if (!this.form.authorId) {
                    const response = await this.$store.dispatch(
                        "companies/show",
                        this.user.tenant_id
                    );
                    this.form.authorId = response?.data?.modelData ?? "";
                }
            }
        },
        /**
         * hits the licenses create API
         */
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("storeEntries/create", {
                    ...this.form,
                    authorId: this.form.authorId?.id ?? "",
                    productImages: this.filesConvertedToBase64,
                    products:
                        this.form?.products?.map((product) => {
                            return product.id;
                        }) ?? "",
                });
                await this.$store.dispatch("storeEntries/list");
                this.$router.push("/store-entries");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
