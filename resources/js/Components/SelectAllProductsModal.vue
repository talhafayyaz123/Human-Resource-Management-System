<template>
    <div>
        <div
            class="custom-modal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-overlay"></div>

            <div class="modal-content">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div class="modal-bg modal-lg">
                        <div class="modal-header">
                            <div>
                                <h2 class="">
                                    {{ $t("Add" + " " + typeOfItem) }}
                                </h2>
                                <slot name="warning"></slot>
                            </div>
                            <div class="cursor-pointer" @click="onCancel">
                                <CustomIcon name="xCircleIcon" />
                            </div>
                        </div>
                        <div class="modal-body">
                            <div
                                class="grid items-center grid-cols-3 gap-6 mb-4"
                            >
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.productPriceId"
                                            :options="productPrices?.data"
                                            :multiple="false"
                                            :textLabel="$t('Price List')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'productPrice'"
                                            :taggable="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.productSoftwareId"
                                            :options="softwares.data"
                                            :multiple="false"
                                            :textLabel="$t('Software')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'Software'"
                                            :taggable="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <search-filter
                                            v-model="form.search"
                                            @reset="reset"
                                        >
                                            <div class="form-group">
                                                <select-input
                                                    v-model="form.status"
                                                    :label="$t('Status')"
                                                >
                                                    <option value="active">
                                                        {{ $t("active") }}
                                                    </option>
                                                    <option value="inactive">
                                                        {{ $t("inactive") }}
                                                    </option>
                                                </select-input>
                                            </div>
                                            <div class="form-group">
                                                <MultiSelectInput
                                                    class="w-full my-[20px]"
                                                    v-model="
                                                        form.product_group_id
                                                    "
                                                    :options="groups.data"
                                                    :multiple="false"
                                                    textLabel="Product Group"
                                                    label="name"
                                                    :trackBy="'id'"
                                                    :moduleName="'groups'"
                                                    :taggable="true"
                                                />
                                            </div>
                                            <div class="form-group">
                                                <select-input
                                                    class="w-full"
                                                    v-model="form.type"
                                                    :label="$t('Type')"
                                                >
                                                    <option value="">
                                                        {{ $t("All") }}
                                                    </option>
                                                    <option value="software">
                                                        {{ $t("Software") }}
                                                    </option>
                                                    <option value="service">
                                                        {{ $t("Service") }}
                                                    </option>
                                                    <option value="pauschal">
                                                        {{ $t("Pauschal") }}
                                                    </option>
                                                    <option value="ams">
                                                        {{ $t("AMS") }}
                                                    </option>
                                                    <option value="hosting">
                                                        {{ $t("Hosting") }}
                                                    </option>
                                                    <option
                                                        value="cloud-software"
                                                    >
                                                        {{
                                                            $t("Cloud Software")
                                                        }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </search-filter>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="doc-table">
                                    <thead>
                                        <tr>
                                            <th class="">
                                                {{ $t("Article number") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Name") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Product Group") }}
                                            </th>

                                            <th class="">
                                                {{ $t("Status") }}
                                            </th>

                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Sale price") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType === 'hosting' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Price Per Period") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Discount") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Profit") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Listing price") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Tax") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in dataProducts?.data"
                                            :key="index"
                                        >
                                            <td class="">
                                                <div class="flex items-center">
                                                    <input
                                                        @click="
                                                            onSelected(
                                                                $event,
                                                                item,
                                                                index
                                                            )
                                                        "
                                                        :checked="
                                                            isInputChecked(item)
                                                        "
                                                        type="checkbox"
                                                        class="border-gray-300 rounded h-5 w-5"
                                                    />
                                                    &nbsp;

                                                    {{ item.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="">
                                                {{ item.name }}
                                            </td>
                                            <td class="">
                                                {{ item.productGroup }}
                                            </td>
                                            <td class="">
                                                {{ item.status }}
                                            </td>
                                            <td
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{
                                                    $formatter(
                                                        item?.salePrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                v-if="productType === 'hosting'"
                                                class=""
                                            >
                                                {{
                                                    $formatter(
                                                        item?.pricePerPeriod ??
                                                            item?.salePrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td class="">
                                                {{ item.discount }}
                                            </td>
                                            <td :class="` flex`">
                                                <div
                                                    v-if="
                                                        isInputChecked(item) &&
                                                        isEdit
                                                    "
                                                >
                                                    {{
                                                        selectedProducts.find(
                                                            (selected) =>
                                                                selected.id ===
                                                                item.id
                                                        ).quantity
                                                    }}
                                                </div>
                                                <input
                                                    class="form-control"
                                                    type="number"
                                                    @change="
                                                        addChangeQuantity(item)
                                                    "
                                                    v-model="item.quantity"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                />
                                            </td>
                                            <td
                                                class=""
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                            >
                                                {{
                                                    $formatter(
                                                        item.profit,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class=""
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                            >
                                                {{
                                                    $formatter(
                                                        item.listingPrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td class="">
                                                {{
                                                    $formatter(
                                                        item.tax,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4">
                                <pagination
                                    :limit="10"
                                    align="center"
                                    :data="dataProducts"
                                    @pagination-change-page="searchProduct"
                                ></pagination>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                @click="onCancel"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                type="button"
                                class="secondary-btn"
                                @click="onAdded"
                            >
                                {{ $t("Add") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import Modal from "./EditModal.vue";
import NumberInput from "../Pages/Surveys/NumberInput.vue";
import SearchFilter from "./SearchFilter.vue";
import MultiSelectInput from "./MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SelectInput from "./SelectInput.vue";

import { toRaw } from "vue";

export default {
    computed: {
        ...mapGetters("groups", ["groups"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("productprice", ["productPrices"]),
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        const productResponse = await this.$store.dispatch(
                            "products/productsWithQuantity",
                            {
                                ...this.form,
                                productGroupId:
                                    this.form.product_group_id?.id ?? "",
                                productSoftwareId:
                                    this.form.productSoftwareId?.id ?? "",
                                productPriceId:
                                    this.form.productPriceId?.id ?? "",
                            }
                        );
                        this.dataProducts = productResponse?.data?.products ?? {
                            data: [],
                        };
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    props: {
        productType: { type: String, default: "software" },
        questions: { type: Array, default: () => [] },
        products: { type: Object, default: () => ({}) },
        selectedItems: { type: Array, default: () => [] },
        typeOfItem: { default: "Products", type: String },
        isEdit: { default: false, type: Boolean },
        fromOffers: { default: false, type: Boolean },
        fromOffersEdit: { default: false, type: Boolean },
        groupedBy: { default: "nothing", type: String },
        formulaInput: { default: false, type: Boolean },
        options: { default: [], type: Array },
        option: { default: {}, type: Object },
        defaultCategory: { default: "", type: String },
    },
    components: {
        pagination,
        Modal,
        NumberInput,
        SearchFilter,
        MultiSelectInput,
        SelectInput,
    },
    data() {
        return {
            page: 1,
            selectedProduct: null,
            toggleModal: false,
            selectedProducts: [],
            search: "",
            dataProducts: toRaw(this.products), //to remove reactivity
            success: false,
            form: {
                product_group_id: "",
                status: "",
                search: "",
                productSoftwareId: "",
                type: "",
                productPriceId: "",
            },
        };
    },
    mounted() {
        this.modifySelectProducts(); // sync products listing with selectedItems
        if (!this.groups.data.length) {
            this.$store.dispatch("groups/list");
        }
        if (!this.softwares.data.length) {
            this.$store.dispatch("softwares/list");
        }
        this.$store.dispatch("productprice/list", {
            status: "active",
        });
        const clonedValue = this.cloneDeep(this.selectedItems); //added lodash because dont want two way data binding
        this.selectedProducts = clonedValue;
    },

    methods: {
        /**
         * modifies the products listing based on the selected products received as props
         * the quantity, tax, discount, maintenanceRate and salePrice can be different in selectedItems
         * hence we need to sync the products listing from API and the selected items
         */
        modifySelectProducts() {
            this.selectedItems.forEach((product) => {
                const productIndex = this.dataProducts.data.findIndex(
                    (pr) =>
                        pr.id ==
                        (this.fromOffersEdit
                            ? product?.productId ?? product.id
                            : product.id)
                );
                if (productIndex > -1) {
                    const foundProduct = {
                        ...(this.fromOffers
                            ? product?.product
                                ? {
                                      ...product.product,
                                      quantity: product.quantity,
                                      tax: product.tax,
                                      discount: product.discount,
                                      maintenanceRate: product.maintenanceRate,
                                      salePrice: product.salePrice,
                                  }
                                : product
                            : product),
                    };
                    this.dataProducts.data[productIndex] = {
                        ...foundProduct,
                        status:
                            typeof foundProduct.status === "string"
                                ? foundProduct.status
                                : foundProduct.status == 1
                                ? "active"
                                : "inactive",
                        quantity: foundProduct.quantity,
                    };
                }
            });
            /**
             * to preserve the changed quantity of a selected product when swtiching between pages
             * we find the product in selectedProducts, because selectedProducts contains the latest modified
             * quanity of the product. The products in dataProducts changes when the page changes, so the quantity is
             * reset and we are left with the previous quantity received from selecteItems. This preserved the user
             * entered quantity even when the pages are swtiched
             */
            this.selectedProducts.forEach((product) => {
                const productIndex = this.dataProducts.data.findIndex(
                    (pr) => pr.id == product.id
                );
                if (productIndex > -1) {
                    const foundProduct = this.dataProducts.data[productIndex];
                    const productInSelectedProducts =
                        this.selectedProducts.find(
                            (pr) => pr.id == foundProduct.id
                        );
                    if (productInSelectedProducts) {
                        foundProduct.quantity =
                            productInSelectedProducts.quantity;
                    }
                    this.dataProducts.data[productIndex] = { ...foundProduct };
                }
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        onSelected(event, item) {
            if (event.target.checked) {
                this.selectedProducts.push(item);
            } else {
                this.selectedProducts = this.selectedProducts.filter(
                    (product) => product.id !== item.id
                );
            }
        },
        addChangeQuantity(item) {
            try {
                this.selectedProducts.find(
                    (selected) => selected.id === item.id
                ).quantity = item.quantity;
            } catch (e) {}
        },
        isInputChecked(product) {
            const found = this.selectedProducts.some((el) => {
                if (this.fromOffers) {
                    return (
                        (el?.product ? el?.product?.id : el.id) === product.id
                    );
                } else {
                    return el.id === product.id;
                }
            });
            return found;
        },
        onAdded() {
            this.$emit(
                "selected",
                this.selectedProducts.map((product) => {
                    const foundProduct =
                        this.dataProducts.data.find((pr) => {
                            return (
                                pr.id ==
                                (this.fromOffersEdit
                                    ? product?.productId ?? product.id
                                    : product.id)
                            );
                        }) ?? product;
                    return {
                        ...foundProduct,
                    };
                })
            );
        },
        onCancel() {
            this.$emit("cancelled");
        },
        async searchProduct(page = 1) {
            this.page = page;
            try {
                const productResponse = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        page: page,
                        search: this.form.search,
                        productPriceId: this.form.productPriceId?.id ?? "",
                        productGroupId: this.form.product_group_id?.id ?? "",
                    }
                );
                this.dataProducts = productResponse?.data?.products ?? {
                    data: [],
                };
                this.modifySelectProducts(); // sync products listing with selectedItems
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
</style>
