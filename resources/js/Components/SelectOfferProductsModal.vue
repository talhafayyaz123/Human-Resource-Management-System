<template>
    <div>
        <div
            class="custom-modal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-overlay"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div class="modal-bg modal-lg">
                        <div class="modal-header">
                            <div>
                                <h2 class="">
                                    {{
                                        $t(
                                            `Add ${
                                                type === "offer"
                                                    ? "Offer"
                                                    : "Offer Confirmation"
                                            } Products`
                                        )
                                    }}
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
                                            v-model="offer"
                                            @open="fetchOffersListing"
                                            :options="
                                                type === 'offer'
                                                    ? offers?.data ?? []
                                                    : orderConfirmation?.data ??
                                                      []
                                            "
                                            @update:model-value="fetchOffer"
                                            :multiple="false"
                                            :textLabel="
                                                $t(
                                                    type === 'offer'
                                                        ? 'Offers'
                                                        : 'Offer Confirmation'
                                                )
                                            "
                                            label="offerNumber"
                                            :trackBy="'id'"
                                            :query="{
                                                offerType:
                                                    type === 'offer'
                                                        ? 'offer'
                                                        : 'order',
                                            }"
                                            :moduleName="
                                                type === 'offer'
                                                    ? 'offers'
                                                    : 'orderConfirmation'
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group"></div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group"></div>
                                </div>
                            </div>
                            <OfferSelectorAccordions
                                v-if="offerSelected"
                                ref="offerSelectorAccordions"
                                :softwareLicences="softwareLicences"
                                :softwareMaintenance="softwareMaintenance"
                                :cloud="cloud"
                                :additionalDescriptionToggled="
                                    additionalDescriptionToggled
                                "
                            />
                            <p class="text-center" v-else>
                                {{ $t("No Offer Selected") }}
                            </p>
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
import MultiSelectInput from "./MultiSelectInput.vue";
import NumberInput from "./NumberInput.vue";
import TextInput from "./TextInput.vue";
import TextAreaInput from "./TextareaInput.vue";
import SelectInput from "./SelectInput.vue";
import OfferSelectorAccordions from "./OfferSelectorAccordions.vue";

export default {
    emits: ["toggle-modal", "selected"],
    props: {
        type: {
            type: String,
            required: true,
            default: "offer",
        },
        companyId: {
            type: String,
        },
    },
    components: {
        MultiSelectInput,
        NumberInput,
        TextInput,
        SelectInput,
        TextAreaInput,
        OfferSelectorAccordions,
    },
    computed: {
        ...mapGetters("offers", ["offers"]),
        ...mapGetters("orderConfirmation", ["orderConfirmation"]),
    },
    data() {
        return {
            offerSelected: false,
            additionalDescriptionToggled: {
                softwareLicences: {},
                softwareMaintenance: {},
                cloud: {},
            },
            offer: "",
            softwareLicences: [],
            softwareMaintenance: [],
            softwareLicencesTax: [],
            softwareMaintenanceTax: [],
            cloud: [],
            groupToggler: {
                cloud: {},
            },
        };
    },
    unmounted() {
        this.$store.commit("offers/offers", {
            data: [],
            links: [],
        });
        this.$store.commit("orderConfirmation/orderConfirmation", {
            data: [],
            links: [],
        });
    },
    methods: {
        /**
         * gets offer/order confirmation listing upon dropdown open event
         */
        async fetchOffersListing() {
            try {
                if (this.type === "offer") {
                    // fetch sla service time listing
                    if (!this.offers?.data?.length) {
                        await this.$store.dispatch("offers/list", {
                            offerType: "offer",
                            customerId: this.companyId,
                        });
                    }
                } else {
                    // fetch sla service time listing
                    if (!this.orderConfirmation?.data?.length) {
                        await this.$store.dispatch("orderConfirmation/list", {
                            offerType: "order",
                            customerId: this.companyId,
                        });
                    }
                }
            } catch (e) {
                console.log(e);
            }
        },
        fetchOffer(offer) {
            if (!offer) {
                this.offerSelected = false;
                return;
            }
            const module =
                this.type === "offer" ? "offers" : "orderConfirmation";
            this.$store
                .dispatch(module + "/show", offer?.id)
                .then((res) => {
                    this.softwareLicences.splice(
                        0,
                        this.softwareLicences.length
                    );
                    this.softwareMaintenance.splice(
                        0,
                        this.softwareMaintenance.length
                    );
                    this.cloud.splice(0, this.cloud.length);
                    const components = res?.data?.data?.components ?? [];
                    components.forEach((component) => {
                        if (component.type === "license") {
                            this.additionalDescriptionToggled[
                                "softwareLicences"
                            ][component.id] =
                                component?.additionalDescription?.length;
                            this.softwareLicences = [
                                ...this.softwareLicences,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                },
                            ];
                            console.log(this.softwareLicences);
                            this.softwareLicencesToggle = true;
                        } else if (component.type === "maintenance") {
                            this.additionalDescriptionToggled[
                                "softwareMaintenance"
                            ][component.id] =
                                component?.additionalDescription?.length;
                            this.softwareMaintenance = [
                                ...this.softwareMaintenance,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                    totalSalePriceAdjustedForQuantity:
                                        +component.salePrice *
                                        +component.quantity,
                                },
                            ];
                            this.softwareMaintenanceToggle = true;
                        } else if (component.type === "cloud") {
                            this.additionalDescriptionToggled["cloud"][
                                component.id
                            ] = component?.additionalDescription?.length;
                            const totalWithoutDiscount =
                                +component.quantity *
                                (+component.salePrice *
                                    (+component.duration ?? 1));
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.cloud = [
                                ...this.cloud,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    salePrice: component.salePrice,
                                    quantity: component.quantity,
                                    paymentPeriod: component.paymentPeriod,
                                    tax: +component.tax,
                                    duration: +component.duration,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    children: component.children.map(
                                        (child) => {
                                            return {
                                                ...child,
                                                productId: child.product.id,
                                                articleNumber:
                                                    child.product.articleNumber,
                                                productSoftware:
                                                    child.product
                                                        .productSoftware,
                                                name: child.product.name,
                                                unit: child.product.unit,
                                                pricePerPeriod:
                                                    child.pricePerPeriod,
                                                quantity: child.quantity,
                                                paymentPeriod:
                                                    child.paymentPeriod,
                                                tax: +child.tax,
                                                totalPricePeriod:
                                                    +child.quantity *
                                                    (+child.duration ?? 1) *
                                                    +child.salePrice,
                                            };
                                        }
                                    ),
                                },
                            ];
                            this.globalPeriodCloud =
                                this.cloud?.[0]?.paymentPeriod;
                            this.cloudToggle = true;
                            this.groupToggler["cloud"][component.id] =
                                component?.children?.length > 0;
                        }
                    });
                    this.offerSelected = true;
                })
                .catch((e) => console.log(e));
        },
        onAdded() {
            this.$emit(
                "selected",
                this.$refs?.offerSelectorAccordions?.selectedProducts ?? []
            );
        },
        onCancel() {
            this.$emit("toggle-modal", false);
        },
    },
};
</script>
