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
                                            {{ $t("Installed Products") }}
                                        </h2>
                                        <div class="mb-3">
                                            <div
                                                class="input-group relative flex flex-wrap items-stretch w-60 mb-4 rounded"
                                            >
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
                                                            {{ $t("Article number")}}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Name") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{  $t("Status") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{  $t("Quantity") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Sale price") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Discount") }}
                                                        </th>

                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Listing price") }}
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
                                                        ) in products"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            <div class="flex">
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
                                                            {{ item.quantity }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{
                                                                item.salePrice.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.discount }}
                                                        </td>

                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{
                                                                item.listingPrice.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{
                                                                item.tax.replace(
                                                                    /\B(?=(\d{3})+(?!\d))/g,
                                                                    ","
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div
                                class="px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
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
    },
    components: {
        pagination,
        Modal,
        NumberInput,
    },
    data() {
        return {};
    },
    mounted() {},

    methods: {
        onCancel() {
            this.$emit("cancelled");
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
</style>
