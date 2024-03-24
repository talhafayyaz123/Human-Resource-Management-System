<template>
    <!--    <CreateEditRequestFormVue :storeId="$route.params.id " />-->
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

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
                                <text-input
                                    :required="true"
                                    type="number"
                                    v-model="form.itemNumber"
                                    :error="errors.itemNumber"
                                    class=""
                                    :label="$t('Item Number')"
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
                                    id="partner"
                                />
                                <label for="partner" class="checkbox-label">
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
                                    :content-type="'html'"
                                    :required="false"
                                    @delta="form.longDescription = $event"
                                    :label="$t('Product Description')"
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <label class="form-label"
                                >&nbsp;{{ $t("Product Images") }}:</label
                            >
                            <file-inputs
                                @file-changed="addFiles"
                                :productFiles="imageFiles"
                                :showPreview="true"
                            />
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import FileInputs from "@/Components/DraggableFileInputs.vue";
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
                    to: "/store-entries?page=" + this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                itemNumber: "",
                productTitle: "",
                isVisibleForPartner: false,
                isVisibleForCustomer: false,
                longDescription: "",
                shortDescription: "",
                sellPrice: "",
                products: [],
                authorId: "",
            },
            imageFiles: {
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
            this.$store.commit("showLoader", true);
            if (!this.products?.data?.length)
                await this.$store.dispatch("products/list");

            if (!this.partners?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
            await this.setTenantByUser();
            const response = await this.$store.dispatch(
                "storeEntries/show",
                this.$route.params.id
            );
            const responseData = response?.data?.data ?? {};
            document.title = "Edit Store Entries " + responseData?.productTitle;
            this.assignValues(responseData);
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        assignValues(response) {
            this.form.productTitle = response?.productTitle;
            this.form.shortDescription = response?.shortDescription;
            this.form.itemNumber = response?.itemNumber;
            this.form.longDescription = response?.longDescription;
            this.form.products = response?.products;
            this.form.isVisibleForCustomer = response?.isVisibleToCustomer;
            this.form.isVisibleForPartner = response?.isVisibleToPartner;
            this.form.authorId = response?.author;
            this.form.sellPrice = response?.sellPrice;
            const productImages = response?.productImages;

            if (productImages) {
                const requiredImages = productImages.map((prodImage) => {
                    return {
                        name: prodImage.viewableName,
                        base64: prodImage.url.split(",")[1],
                        size: prodImage.size,
                    };
                });
                this.imageFiles.files = requiredImages;
                this.filesConvertedToBase64 = requiredImages;
            }
        },
        /**
         * converts the files to base64 and saves then in 'filesConvertedToBase64'
         * @param {files} selected files, which need to be converted to base64
         */
        addFiles(files) {
            this.filesConvertedToBase64 = [];
            files.forEach((file) => {
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        //const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        const requiredData = reader.result.split(",");
                        const data = {
                            name: file.name,
                            size: file.size,
                            base64: requiredData[1],
                        }
                        this.filesConvertedToBase64.push(data);

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
                await this.$store.dispatch("storeEntries/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        authorId: this.form.authorId?.id ?? "",
                        productImages: this.filesConvertedToBase64,
                        products:
                            this.form?.products?.map((product) => {
                                return product.id;
                            }) ?? "",
                    },
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
