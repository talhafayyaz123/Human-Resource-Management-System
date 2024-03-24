<template>
    <div @click.stop="isModalOpen = false">
        <PageHeader :items="breadcrumbItems" />

        <div class="flex items-center justify-start mb-3">
            <loading-button
                v-if="$can(`${$route.meta.permission}.create`)"
                class="primary-btn"
                @click.stop="isModalOpen = true"
                :loading="importLoading"
            >
                <span>{{ $t("Import Product Csv") }}</span>
            </loading-button>
        </div>
        <input @input="uploadFile" ref="fileInput" class="hidden" type="file" />
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Article Number") }}
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Name") }}
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Elo Version") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Description") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Product Group") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Listing Price") }}
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Manufacture Price") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Maintanence Rate") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Manufacturer Discount") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Action") }}
                    </th>
                </tr>
                <tr
                    v-for="(product, index) in form.products ?? []"
                    :key="index"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <div
                            class="flex items-center whitespace-normal px-6 py-4 focus:text-indigo-500"
                        >
                            {{ product.articleNumber }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ product.productName }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ product.eloVersion }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ product.productDescription }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ product.productGroup }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            :class="`flex whitespace-normal items-center px-6 py-4`"
                            tabindex="-1"
                        >
                            {{ product.listingPrice }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            :class="`flex whitespace-normal items-center px-6 py-4`"
                            tabindex="-1"
                        >
                            {{ product.maintainencePrice }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="whitespace-normal" tabindex="-1">
                            {{ product.maintainenceRate }}%
                        </div>
                    </td>
                    <td class="border-t">
                        <div tabindex="-1" class="whitespace-normal">
                            {{ product.manufactureDiscount }}%
                        </div>
                    </td>

                    <td class="w-px border-t text-center">
                        <!--  <font-awesome-icon icon="fa-regular fa-pen-to-square"/> -->

                        <button @click="form?.products.splice(index, 1)">
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(form.products?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="10">
                        {{ $t("No products imported") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <loading-button
                v-if="(form.products?.length ?? 0) != 0"
                :loading="isLoading"
                style="cursor: pointer"
                class="Secondary-btn"
                @click="createProducts"
                >Save</loading-button
            >
        </div>
        <div
            style="margin-bottom: 60px"
            v-if="(form.products?.length ?? 0) != 0"
            class="mb-4"
        ></div>
        <!-- Modal -->
        <div class="custom-modal" v-if="isModalOpen">
            <div class="modal-overlay"></div>
                                
            <div class="modal-content" >
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div class="modal-bg modal-md" @click.stop>
                        <div class="modal-header">
                            <h2 class="">
                                {{ $t("Select an Option") }}
                            </h2>
                        </div>
                        <div class="modal-body">
                            <div
                                class="grid items-center grid-cols-2 gap-6"
                            >
                                    <div class="form-group">
                                      <select-input
                                                :label="$t('Select a Product Type')"
                                                v-model="form.productType"
                                            >
                                                <option value="software">
                                                    {{ $t("Software") }}
                                                </option>
                                        </select-input>
                                    </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            class=""
                                            :error="errors.productPrice"
                                            :required="true"
                                            v-model="form.productPrice"
                                            :options="productPrices?.data ?? []"
                                            :multiple="false"
                                            :textLabel="$t('Price List')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'productprice'"
                                            :key="form.productPrice?.id"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.paymentPeriod"
                                            :error="errors.paymentPeriod"
                                            :key="form.paymentPeriod"
                                            :required="true"
                                            class=""
                                            :textLabel="$t('Payment Period')"
                                            :options="periods?.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                            moduleName="periods"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.unit"
                                            :error="errors.unit"
                                            :key="form.unit"
                                            :required="true"
                                            class=""
                                            :textLabel="$t('Unit')"
                                            :options="units?.data"
                                            moduleName="units"
                                            :multiple="false"
                                            label="name"
                                            :trackBy="'id'"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            class=""
                                            :error="errors.productSoftware"
                                            v-model="form.productSoftware"
                                            :options="softwares.data"
                                            :multiple="false"
                                            :textLabel="$t('Software')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'softwares'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.version"
                                            :error="errors.version"
                                            class=""
                                            :required="true"
                                            label="Enter version"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <loading-button
                                @click.stop="importFile"
                                class="secondary-btn"
                            >
                                <span>{{ $t("Import") }}</span>
                            </loading-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import MultiSelect from "@/Components/MultiSelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import SelectInput from "@/Components/SelectInput.vue";
export default {
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("productprice", ["productPrices"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("units", ["units"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    components: {
        SearchFilter,
        LoadingButton,
        MultiSelect,
        TextInput,
        PageHeader,
        SelectInput
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/products",
                },
                {
                    text: "Products",
                    to: "/products",
                },
                {
                    text: "Import Product Csv",
                    active: true,
                },
            ],

            form: {
                search: "",
                products: [],
                productType: "software",
                productPrice: "",
                version: "",
                paymentPeriod: "",
                unit: "",
                productSoftware: "",
            },
            isModalOpen: false,
            importLoading: false,
            window,
        };
    },
    async mounted() {
        await this.$store.dispatch("productprice/list");
        await this.$store.dispatch("periods/list");
        await this.$store.dispatch("units/list");
        await this.$store.dispatch("softwares/list");
    },
    methods: {
        async importFile() {
            const form = this.form;
            // Check if all required fields are filled
            if (
                form.productType &&
                form.productPrice &&
                form.version &&
                form.paymentPeriod &&
                form.unit &&
                form.productSoftware
            ) {
                this.$refs.fileInput.click();
            } else {
                // Display error message
                this.$store.commit("flashMessage", {
                    show: true,
                    message: "Please fill all the fields to continue",
                    type: "error",
                });
            }
        },
        async uploadFile(event) {
            try {
                this.importLoading = true;
                const file = event?.target?.files?.[0] ?? null;
                const formData = new FormData();
                formData.set("csv", file);
                const response = await this.$store.dispatch(
                    "products/importProducts",
                    formData
                );
                const products = response?.data?.data ?? [];
                this.form.products = [];
                (products instanceof Array ? products : []).forEach(
                    (record) => {
                        const modifiedRecord = {
                            productName: record.productName,
                            productDescription: record.productDescription,
                            productGroup: record.productGroup,
                            eloVersion: record.eloVersion,
                            maintainenceRate: record.maintainenceRate,
                            maintainencePrice: record.maintainencePrice,
                            articleNumber: record.articleNumber,
                            manufactureDiscount: record.manufactureDiscount,
                            listingPrice: record.productListingPrice,
                        };

                        this.form.products = [
                            ...this.form.products,
                            { ...modifiedRecord },
                        ];
                    }
                );
            } catch (e) {
                console.log(e);
            } finally {
                this.importLoading = false;
                this.isModalOpen = false;
            }
        },
        async createProducts() {
            await this.$nextTick();
            let wasCreateSuccessfull = false;
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("products/storeImportedProducts", {
                    products: this.form.products,
                    productType: this.form.productType,
                    productPrice: this.form.productPrice["id"] ?? "",
                    productVersion: this.form.version,
                    paymentPeriod: this.form.paymentPeriod["id"] ?? "",
                    productUnit: this.form.unit["id"] ?? "",
                    productSoftware: this.form.productSoftware["id"] ?? "",
                });
                wasCreateSuccessfull = true;
            } catch (e) {
                console.log(e);
            }
            if (wasCreateSuccessfull) {
                this.$router.push("/products");
            }
        },
    },
};
</script>

<style></style>
