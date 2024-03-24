<template>
    <Modal
        @toggleModal="$emit('cancel', true)"
        :title="$t('Edit License')"
        v-if="toggleEditModal"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :isReadonly="true"
                            v-model="license.articleNumber"
                            class=""
                            :label="$t('Article Number')"
                            placeholder=" "
                            :error="errors.articleNumber"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :isReadonly="!license.isProductNameEdit"
                            v-model="license.name"
                            class=""
                            :label="$t('Name')"
                            placeholder=" "
                            :error="errors.name"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :isReadonly="true"
                            v-model="license.software"
                            class=""
                            :label="$t('Software Type')"
                            placeholder=" "
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            @update:model-value="
                                quantityChanged(index, 'license', this)
                            "
                            v-model="license.quantity"
                            type="number"
                            class=""
                            :label="$t('Quantity')"
                            placeholder=" "
                            :error="errors.quantity"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            @update:model-value="
                                salePriceChanged(index, 'license', this)
                            "
                            v-model="license.salePrice"
                            class=""
                            :label="$t('Sale Price Per Unit')"
                            placeholder=" "
                            :error="errors.salePrice"
                            :maximumFractionDigits="2"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            @update:model-value="
                                salePriceTotalChanged(index, 'license', this)
                            "
                            v-model="license.salePriceTotal"
                            class=""
                            :label="$t('Sale Price')"
                            placeholder=" "
                            :error="errors.salePriceTotal"
                            :maximumFractionDigits="2"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            @update:model-value="
                                maintenancePriceChanged(index, 'license', this)
                            "
                            v-model="license.maintenancePrice"
                            class=""
                            :label="$t('Maintenance Rate Per Unit')"
                            placeholder=" "
                            :error="errors.maintenancePrice"
                            :maximumFractionDigits="2"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            @update:model-value="
                                maintenancePriceTotalChanged(
                                    index,
                                    'license',
                                    this
                                )
                            "
                            v-model="license.maintenancePriceTotal"
                            class=""
                            :label="$t('Maintenance Rate')"
                            placeholder=" "
                            :error="errors.maintenancePriceTotal"
                            :maximumFractionDigits="2"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="checkbox-group">
                        <input
                                id="license-evaluation"
                                type="checkbox"
                                class="checkbox-input"
                                v-model="license.isEvaluation"
                            />
                            <label class="checkbox-label" for="license-evaluation"
                                >{{ $t("Evaluation License") }}:</label
                            >
                            
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="$emit('cancel', true)"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <loading-button
                :loading="isLoading"
                type="button"
                class="secondary-btn"
                @click="update"
            >
                {{ $t("Save") }}
            </loading-button>
            
        </template>
    </Modal>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import Modal from "@/Components/EditModal.vue";
import { mapGetters } from "vuex";
import licensesCalculationMixin from "@/Mixins/licensesCalculationMixin";

export default {
    mixins: [licensesCalculationMixin],
    emits: ["cancel", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    data() {
        return {
            license: {},
        };
    },
    watch: {
        licenseToBeEdited: {
            handler: function (val) {
                this.license = { ...val };
            },
            deep: true,
        },
    },
    props: {
        toggleEditModal: {
            type: Boolean,
            default: false,
        },
        licenseToBeEdited: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        Modal,
        TextInput,
        NumberInput,
        LoadingButton,
    },
    methods: {
        /**
         * triggers the update API for the license
         */
        async update() {
            try {
                this.$store.commit("isLoading", true);
                const payload = {
                    ...this.license,
                    companyId: this.license?.company?.id,
                    quantity: +this.license?.quantity,
                    salePrice: +this.license?.salePrice,
                    salePriceTotal: +this.license?.salePriceTotal,
                    maintenancePrice: +this.license?.maintenancePrice,
                    maintenancePriceTotal: +this.license?.maintenancePriceTotal,
                    isEvaluation: this.license?.isEvaluation ? 1 : 0,
                };
                await this.$store.dispatch("licenses/update", {
                    id: this.license?.id,
                    data: payload,
                });
                this.$emit("cancel", true); // close the modal
                this.$emit("refresh", true); // refetch the latest licenses listing
            } catch (e) {
                console.log(e);
            }
        },
    },
    mounted() {
        this.license = { ...this.licenseToBeEdited };
    },
};
</script>

<style scoped></style>
