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
                                                    aria-describedby="button-addon2"
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
                                        class="mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
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
                                                            {{ $t("Name") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Url") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("State") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("City") }}
                                                        </th>

                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Country") }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="(
                                                            item, index
                                                        ) in customers"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="flex items-center px-5 py-5 border-b border-gray-200 bg-white text-sm flex"
                                                        >
                                                            <input
                                                                :disabled="disabled"
                                                                style="
                                                                    margin-top: 3px;
                                                                    border: 0px;
                                                                    width: 20px;
                                                                    height: 1.3em;
                                                                "
                                                                @click="
                                                                    onSelected(
                                                                        item
                                                                    )
                                                                "
                                                                type="radio"
                                                                class="form-radio"
                                                                name="accountType"
                                                                value="personal"
                                                                :checked="
                                                                    isInputChecked(
                                                                        item
                                                                    )
                                                                "
                                                            />
                                                            &nbsp;

                                                            {{ item.name }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.url }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.state }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.city }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.country }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
export default {
    props: {
        disabled: { type: Boolean, default: false },
        customers: { type: Array, default: () => [] },
        selectedItem: { type: Object, default: () => ({}) },
        typeOfItem: { default: "Customers", type: String },
    },
    data() {
        return {
            selectedCustomer: {},
        };
    },
    mounted() {
        this.selectedCustomer = this.selectedItem;
    },

    methods: {
        onSelected(item) {
            this.selectedCustomer = item;
        },
        isInputChecked(customer) {
            return this.selectedCustomer?.id === customer.id;
        },
        onAdded() {
            this.$emit("selected", this.selectedCustomer);
        },
        onCancel() {
            this.$emit("cancelled");
        },
    },
};
</script>

<style></style>
