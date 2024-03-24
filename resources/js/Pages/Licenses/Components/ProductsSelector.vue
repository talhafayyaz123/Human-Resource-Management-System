<template>
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="card-title">{{ $t("Products") }}</h3>
            <button
                v-if="!readonly"
                type="button"
                @click="toggleProductsModal"
                class="secondary-btn"
            >
                    {{ $t("Add Products") }}
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="w-full doc-table">
                    <thead>
                        <tr >
                            <th
                                
                                class="text-left"
                            >
                                {{ $t("Article number") }}
                            </th>
                            <th
                                
                                class=""
                            >
                                {{ $t("Product name") }}
                            </th>
                            <th
                                
                                class=""
                            >
                                {{ $t("Quantity") }}
                            </th>
                            <th
                                
                                class=""
                            >
                                {{ $t("Product price") }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(product, index) in selectedProducts"
                            :key="index"
                            :tabindex="index"
                            class=""
                        >
                            <td class="">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        :class="{
                                            Perror: errors[
                                                `products.${index}.articleNumber`
                                            ],
                                        }"
                                        readonly
                                        v-model="product.articleNumber"
                                    />
                                </div>
                            </td>
                            <td class="">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        :class="{
                                            Perror: errors[
                                                `products.${index}.name`
                                            ],
                                        }"
                                        readonly
                                        v-model="product.name"
                                    />
                                </div>
                            </td>
                            <td class="">
                                <div class="form-group">
                                    <input
                                        :readonly="readonly"
                                        class="form-control"
                                        type="number"
                                        name="quantity"
                                        :class="{
                                            Perror: errors[
                                                `products.${index}.quantity`
                                            ],
                                        }"
                                        v-model="product.quantity"
                                    />
                                </div>
                            </td>
                            <td class="">
                                <div class="text-center">
                                    {{
                                        $formatter(
                                            product.salePrice,
                                            globalLanguage
                                        )
                                    }}
                                </div>
                            </td>
                        </tr>
                        <tr
                            class="flex justify-center"
                            v-if="!selectedProducts.length"
                        >
                            <p class="text-sm">{{ $t("No Products Added") }}</p>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <select-product-modal
        v-if="productToggle"
        @selected="addProducts"
        @cancelled="productToggle = false"
        :selectedItems="selectedProducts"
        :products="products"
    ></select-product-modal>
</template>

<script>
import SelectProductModal from "@/Components/SelectProductModal.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        readonly: {
            type: Boolean,
            default: false,
        },
        selectedProductsParent: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            products: {
                data: [],
                links: [],
            },
            selectedProducts: [],
            productToggle: false,
        };
    },
    components: {
        SelectProductModal,
    },
    computed: {
        ...mapGetters(["errors"]),
    },
    watch: {
        selectedProductsParent() {
            this.syncSelectedProducts();
        },
    },
    mounted() {
        this.syncSelectedProducts();
    },
    methods: {
        // sync the selectedProducts with props value
        syncSelectedProducts() {
            this.selectedProducts = this.selectedProductsParent;
        },
        /**
         * sets the selectedProducts from the products received and toggles the modal off
         * @param {products} the selected products from the modal
         */
        addProducts(products) {
            this.selectedProducts = products;
            this.productToggle = false;
        },
        // checks if the products exists, if not call the products list API and set products
        // toggles the products modal
        async toggleProductsModal() {
            if (!this.products?.data?.length) {
                const response = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        type: "software",
                    }
                );
                // sets the products from the API response
                this.products = response?.data?.products ?? {
                    data: [],
                    links: [],
                };
            }
            this.productToggle = true;
        },
    },
};
</script>
