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
                                                            {{ $t("Type") }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{
                                                                $t("Server IP")
                                                            }}
                                                        </th>
                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Server Port"
                                                                )
                                                            }}
                                                        </th>

                                                        <th
                                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            {{ $t("Username") }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="(
                                                            item, index
                                                        ) in dataSystems?.data"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex capitalize"
                                                        >
                                                            <input
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
                                                            {{ item.type }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.serverIp }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{
                                                                item.serverPort
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                        >
                                                            {{ item.username }}
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
                            <pagination
                                :limit="10"
                                align="center"
                                :data="dataSystems"
                                @pagination-change-page="searchSystem"
                            ></pagination>
                            <br />
                            <br />
                        </div>
                        <div
                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-lg"
                        >
                        <button
                                @click="onAdded"
                                type="button"
                                class="secondary-btn"
                            >
                                <div class="mr-1">
                                    <CustomIcon name="addIcon" />
                                </div>
                                {{ $t("Select") }}
                            </button>
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
export default {
    props: {
        systems: {
            type: Object,
            default: () => ({
                data: [],
                links: [],
            }),
        },
        selectedItem: { type: Object, default: () => ({}) },
        typeOfItem: { default: "Systems", type: String },
    },
    components: {
        pagination,
    },
    data() {
        return {
            page: 1,
            selectedSystem: {},
            dataSystems: this.systems,
        };
    },
    mounted() {
        this.selectedSystem = this.selectedItem;
    },

    methods: {
        onSelected(item) {
            this.selectedSystem = item;
        },
        isInputChecked(system) {
            return this.selectedSystem?.id === system.id;
        },
        onAdded() {
            this.$emit("selected", this.selectedSystem);
        },
        onCancel() {
            this.$emit("cancelled");
        },
        searchSystem(page = 1) {
            this.page = page;
            const res = axios
                .post("/systems-search?page=" + page, { search: this.search })
                .then((res) => {
                    this.dataSystems = res?.data;
                });
        },
    },
};
</script>

<style></style>
