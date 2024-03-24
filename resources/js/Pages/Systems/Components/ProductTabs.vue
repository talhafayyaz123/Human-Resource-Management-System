<template>
    <div>
        <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
            {{ $t("Products") }}
        </h6>
        <div class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div
                    class="relative m-4 overflow-x-auto shadow-md sm:rounded-lg"
                >
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                   {{ $t('Article Number') }}
                                </th>
                                <th scope="col" class="px-6 py-3">{{ $t('Name') }}</th>
                                <th scope="col" class="px-6 py-3">{{ $t('Quantity') }}</th>
                                <th scope="col" class="px-6 py-3">
                                  {{ $t('Sale Price') }} 
                                </th>
                                <th scope="col" class="px-6 py-3">
                                   {{ $t('Listing Price') }} 
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                v-for="(item, index) in systemProducts"
                                :key="index"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    {{ item.articleNumber }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ item.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-4">{{ item.salePrice }}</td>
                                <td class="px-6 py-4">
                                    {{ item.listingPrice }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        @click="removeObjectFromArray(item.id)"
                                        href="#"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                        >Remove</a
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button
                    @click="productToggle = true"
                    style="background-color: rgb(41, 87, 164)"
                    class="text-white font-bold py-2 px-4 rounded m-4"
                    tabindex="-1"
                    type="button"
                >
                    {{ $t("Add Products") }}
                </button>
            </div>

            <br />
        </div>
        <system-select-products
            v-if="productToggle"
            @selected="addProducts"
            @cancelled="productToggle = false"
            :selectedItems="systemProducts"
            :products="products"
        ></system-select-products>
    </div>
</template>

<script>
import SystemSelectProducts from "@/Components/SystemSelectProducts.vue";
import TextInput from "@/Components/TextInput.vue";
export default {
    components: { TextInput, SystemSelectProducts },
    props: { products: Object, selectedProducts: { default: [], type: Array } },
    data() {
        return {
            productToggle: "",
            systemProducts: [],
        };
    },
    mounted() {
        this.systemProducts = this.selectedProducts;
    },
    methods: {
        addProducts(products) {
            this.systemProducts = products;
            this.productToggle = false;
            this.$emit("selected", this.systemProducts);
        },
        removeObjectFromArray(id) {
            var index = this.systemProducts.findIndex((obj) => obj.id === id);
            if (index !== -1) {
                this.systemProducts.splice(index, 1);
            }
        },
    },
};
</script>

<style></style>
