<template>
    <div>
        <div
            class="relative z-10"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            ></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div
                        style="width: 70%"
                        class="relative transform rounded-lg bg-white text-left shadow-xl transition-all"
                    >
                        <div>
                            <div class="container mx-auto px-4 sm:px-8">
                                <div class="py-8">
                                    <div class="flex justify-between">
                                        <h2
                                            class="text-2xl font-semibold leading-tight"
                                        >
                                            {{ $t("Add") }} {{ typeOfItem }}
                                        </h2>
                                        <div class="mb-3">
                                            <div
                                                class="input-group relative flex flex-wrap items-stretch w-60 mb-4 rounded"
                                            >
                                                <input
                                                    type="search"
                                                    class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                                    placeholder="Search by article or name"
                                                    aria-label="Search"
                                                    v-model="search"
                                                    aria-describedby="button-addon2"
                                                    @keyup.enter="searchProduct"
                                                />
                                                <span
                                                    class="input-group-text flex items-center px-3 py-1.5 text-base font-normal text-gray-700 text-center whitespace-nowrap rounded"
                                                    id="basic-addon2"
                                                >
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                                    >
                                        <div
                                            class="inline-block min-w-full shadow-md rounded-lg overflow-hidden"
                                        >
                                            <table
                                                class="min-w-full leading-normal"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Article number"
                                                                )
                                                            }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Name") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Status") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Quantity") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{
                                                                $t("Sale price")
                                                            }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Discount") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Profit") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Listing price"
                                                                )
                                                            }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
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
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            <div class="flex">
                                                                <input
                                                                    @click="
                                                                        onSelected(
                                                                            $event,
                                                                            item,
                                                                            index
                                                                        )
                                                                    "
                                                                    :checked="
                                                                        isInputChecked(
                                                                            item
                                                                        )
                                                                    "
                                                                    type="checkbox"
                                                                    class="border-gray-300 rounded h-5 w-5"
                                                                />
                                                                &nbsp;

                                                                {{
                                                                    item.articleNumber
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.name }}
                                                        </td>

                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.status }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            <input
                                                                class="appearance-none border-2 w-24 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                                type="number"
                                                                v-model="
                                                                    item.quantity
                                                                "
                                                            />
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            €{{
                                                                item.salePrice?.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            €{{ item.discount }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            €{{
                                                                item.profit?.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            €{{
                                                                item.listingPrice?.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{
                                                                item.tax?.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}%
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <br />
                            <br />
                            <pagination
                                :limit="10"
                                align="center"
                                :data="dataProducts"
                                @pagination-change-page="searchProduct"
                            ></pagination>
                        </div>
                        <div
                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                        >
                            <button
                                type="button"
                                class="secondary-btn"
                                @click="onAdded"
                            >
                                {{ $t("Add") }}
                            </button>
                            <button
                                @click="onCancel"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import pagination from "laravel-vue-pagination";
import Modal from "./EditModal.vue";
import NumberInput from "../Pages/Surveys/NumberInput.vue";

export default {
    props: {
        products: { type: Object, default: () => ({}) },
        selectedItems: { type: Array, default: () => [] },
        typeOfItem: { default: "Products", type: String },
        isEdit: { default: false, type: Boolean },
        formulaInput: { default: false, type: Boolean },
        options: { default: [], type: Array },
        option: { default: {}, type: Object },
    },
    components: {
        pagination,
        Modal,
        NumberInput,
    },
    data() {
        return {
            page: 1,
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
            const productIndex = this.dataProducts.data.findIndex(
                (pr) => pr.id == product.id
            );
            if (productIndex !== -1) {
                this.dataProducts.data[productIndex].quantity =
                    product.quantity ?? 1;
            }
        });
        const clonedValue = this.cloneDeep(this.selectedItems); //added lodash because dont want two way data binding
        this.selectedProducts = clonedValue;
    },

    methods: {
        openModal(item) {
            this.selectedProduct = { ...item };
            this.selectedProduct["quantity"] = this.isJson(
                this.selectedProduct["quantity"]
            )
                ? JSON.parse(this.selectedProduct["quantity"])
                : [
                      {
                          type: "single",
                          operator: null,
                          value: "",
                          parenthesis: [],
                      },
                  ];
            this.toggleModal = true;
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
        isInputChecked(product) {
            const found = this.selectedProducts.some(
                (el) => el.id === product.id
            );
            return found;
        },
        onAdded() {
            this.$emit(
                "selected",
                this.selectedProducts.map((product) => {
                    const foundProduct = this.dataProducts.data.find(
                        (pr) => pr.id == product.id
                    );
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
                    { page: page, search: this.search, type: "software" }
                );
                this.dataProducts = productResponse?.data;
                console.log(this.dataProducts);
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
</style>
