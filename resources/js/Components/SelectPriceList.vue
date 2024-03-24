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
                                    {{ $t("Add Default Price List") }}
                                </h2>
                            </div>
                            <div @click="onCancel" class="cursor-pointer">
                                <CustomIcon name="xCircleIcon" />
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive modal-table">
                                <table class="doc-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">
                                                {{ $t("Name") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Status") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Product Software") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Is Default") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in productPrices?.data"
                                            :key="index"
                                        >
                                            <td class="">
                                                <div
                                                    class="price-checkbox-class flex items-center"
                                                >
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

                                                    {{ item.name }}
                                                </div>
                                            </td>

                                            <td class="">
                                                {{ item.status }}
                                            </td>
                                            <td class="">
                                                {{ item.productSoftwareName }}
                                            </td>
                                            <td class="">
                                                {{
                                                    $t(
                                                        item.isDefault == 1
                                                            ? "Yes"
                                                            : "No"
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("productprice", ["productPrices"]),
    },
    data() {
        return {
            selectedPrices: [],
        };
    },
    props: {
        selectedItems: { type: Array, default: () => [] },
    },
    mounted() {
        this.$store.dispatch("productprice/list", {
            status: "active",
        });
        const clonedValue = this.cloneDeep(this.selectedItems); //added lodash because dont want two way data binding
        this.selectedPrices = clonedValue;
    },
    methods: {
        onCancel() {
            this.$emit("cancelled");
        },
        onAdded() {
            this.$emit("selected", this.selectedPrices);
        },
        onSelected(event, item, index) {
            if (event.target.checked) {
                this.selectedPrices.push(item);
            } else {
                this.selectedPrices.splice(index, 1);
            }
        },
        isInputChecked(price) {
            const found = this.selectedPrices.some((el) => {
                return el.id === price.id;
            });
            return found;
        },
    },
};
</script>

<style>
.price-checkbox-class {
    display: flex !important;
}
</style>
