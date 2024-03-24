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
                            <h2 class="text-2xl font-semibold leading-tight">
                                {{ $t("Add" + " " + typeOfItem) }}
                            </h2>
                            <div @click="onCancel">
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
                                            class=""
                                            v-model="productPriceId"
                                            :options="productPrices?.data"
                                            :multiple="false"
                                            @update:model-value="filterProducts"
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
                                            class=""
                                            v-model="productSoftwareId"
                                            :options="softwares.data"
                                            :multiple="false"
                                            @update:model-value="filterProducts"
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
                                            v-model="search"
                                            class="mr-4 w-full max-w-md"
                                            @reset="reset"
                                            @update:model-value="
                                                searchProduct(1)
                                            "
                                        >
                                            <div class="form-group">
                                                <select-input
                                                    v-model="type"
                                                    @input="searchProduct(1)"
                                                    :label="$t('Type')"
                                                >
                                                    <option value="service">
                                                        {{ $t("Service") }}
                                                    </option>
                                                    <option value="pauschal">
                                                        {{ $t("Pauschal") }}
                                                    </option>
                                                </select-input>
                                            </div>
                                            <div class="form-group mt-[20px]">
                                                <MultiSelectInput
                                                    v-model="productGroupId"
                                                    :options="groups.data"
                                                    @update:modelValue="
                                                        searchProduct(1)
                                                    "
                                                    :multiple="false"
                                                    :textLabel="
                                                        $t('Product Group')
                                                    "
                                                    label="name"
                                                    :trackBy="'id'"
                                                    :moduleName="'groups'"
                                                    :taggable="true"
                                                />
                                            </div>
                                        </search-filter>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive modal-table">
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

                                            <th class="">
                                                {{ $t("Sale price") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Unit") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Daily Rate") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Type") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in dataProducts?.data"
                                            :key="'product-' + index"
                                        >
                                            <td class="">
                                                <div class="flex">
                                                    <input
                                                        name="service"
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
                                                        :type="
                                                            singleSelect
                                                                ? 'radio'
                                                                : 'checkbox'
                                                        "
                                                        class="border-gray-300 rounded h-5 w-5"
                                                    />
                                                    &nbsp;

                                                    {{ item.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="">
                                                {{ item.name }}
                                            </td>
                                            <td class="capitalize">
                                                {{ item?.productGroup }}
                                            </td>
                                            <td class="">
                                                {{ item.status }}
                                            </td>
                                            <td
                                                v-if="
                                                    typeof item?.salePrice ===
                                                    'string'
                                                "
                                                class=""
                                            >
                                                {{
                                                    item?.salePrice?.replace(
                                                        /\B(?=(\d{3})+(?!\d))/g,
                                                        ","
                                                    )
                                                }}
                                            </td>
                                            <td v-else class="">
                                                {{ item?.salePrice ?? "" }}
                                            </td>
                                            <td class="">
                                                {{ item?.unit?.name }}
                                            </td>
                                            <td class="">
                                                <div>
                                                    {{
                                                        selectedProducts?.find(
                                                            (selected) =>
                                                                selected.id ===
                                                                item.id
                                                        )?.quantity ??
                                                        item.quantity
                                                    }}
                                                </div>
                                            </td>
                                            <td class="">
                                                {{ item?.hourlyRate }}
                                            </td>
                                            <td class="">
                                                {{ item?.dailyRate }}
                                            </td>
                                            <td class="capitalize">
                                                {{ item?.type }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-5">
                                <pagination
                                    :limit="10"
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
                                <div class="mr-1">
                                    <CustomIcon name="cancelIcon" />
                                </div>
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                @click="onAdded"
                                type="button"
                                class="secondary-btn"
                            >
                                <div class="mr-1">
                                    <CustomIcon name="addIcon" />
                                </div>
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
import SearchFilter from "./SearchFilter.vue";
import MultiSelectInput from "./MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SelectInput from "./SelectInput.vue";
export default {
    name: "SelectServiceModal",
    computed: {
        ...mapGetters("groups", ["groups"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("productprice", ["productPrices"]),
    },
    props: {
        singleSelect: { default: false, type: Boolean },
        fromOffers: { default: false, type: Boolean },
        fromOffersEdit: { default: false, type: Boolean },
        products: { type: Object, default: () => ({}) },
        selectedItems: { type: Array, default: () => [] },
        typeOfItem: { default: "Products", type: String },
        isEdit: { default: false, type: Boolean },
        defaultCategory: { default: "", type: String },
    },
    components: {
        MultiSelectInput,
        SearchFilter,
        pagination,
        Modal,
        SelectInput,
    },
    data() {
        return {
            page: 1,
            type: "",
            productGroupId: "",
            productPriceId: "",
            productSoftwareId: "",
            selectedProduct: null,
            toggleModal: false,
            selectedProducts: [],
            search: "",
            dataProducts: JSON.parse(JSON.stringify(this.products)), //to remove reactivity
            success: false,
        };
    },
    mounted() {
        this.selectedItems.forEach((product) => {
            const productIndex = this.dataProducts.data.findIndex((pr) => {
                return (
                    pr.id ==
                    (this.fromOffersEdit
                        ? product?.product?.id ?? product.id
                        : product.id)
                );
            });
            if (productIndex > -1) {
                const foundProduct = {
                    ...(this.fromOffers
                        ? product?.product
                            ? {
                                  ...product.product,
                                  quantity: product.quantity,
                                  unit: product.unit,
                                  type: product.type,
                                  tax: "" + product.tax,
                                  discount: "" + product.discount,
                                  hourlyRate: "" + product.hourlyRate,
                                  salePrice: "" + product.salePrice,
                              }
                            : product
                        : product),
                };
                this.dataProducts.data[productIndex] = {
                    ...foundProduct,
                };
            }
        });
        const clonedValue = this.cloneDeep(this.selectedItems); //added lodash because dont want two way data binding
        this.selectedProducts = clonedValue;
        if (!this.groups.data.length) {
            this.$store.dispatch("groups/list");
        }
        // fetch the software listing
        if (!this.softwares?.data?.length) {
            this.$store.dispatch("softwares/list");
        }
        // fetch the product price listing
        if (!this.productPrices?.data?.length) {
            this.$store.dispatch("productprice/list", {
                status: "active",
            });
        }
    },

    methods: {
        reset() {
            this.search = "";
            this.productGroupId = "";
            this.type = "";
            this.searchProduct(1);
        },
        onSelected(event, item) {
            if (event.target.checked) {
                if (this.singleSelect) {
                    this.selectedProducts = [item];
                } else {
                    this.selectedProducts.push({ ...item });
                }
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
        /**
         * sets the products in dataProducts by hitting the products list API with filters
         */
        async filterProducts() {
            await this.$nextTick();
            try {
                const productResponse = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        page: this.page,
                        search: this.search,
                        productGroupId: this.productGroupId?.id,
                        productPriceId: this.productPriceId?.id,
                        productSoftwareId: this.productSoftwareId?.id,
                        type: this.type ? this.type : ["service", "pauschal"],
                    }
                );
                this.dataProducts = productResponse?.data?.products ?? {
                    data: [],
                };
            } catch (e) {
                console.log(e);
            }
        },
        searchProduct(page = 1) {
            this.page = page;
            setTimeout(async () => {
                this.filterProducts();
            }, 100);
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
.margin-top-3rem {
    margin-top: 3rem;
}
</style>
