<template>
    <div class="tabs">
        <div class="relative tab">
            <input
                class="tab-checkbox"
                type="checkbox"
                :id="`product-${attachment.contractAttachmentId}-accordion`"
                :checked="false"
            />
            <label
                style="background-color: #2957a4"
                class="rounded tab-label styles-configurator-tab-label flex justify-between p-3 text-white"
                :for="`product-${attachment.contractAttachmentId}-accordion`"
            >
                <p v-if="attachment.attachmentNumber" class="self-center">
                    {{ attachment.attachmentNumber }}
                </p>
                <p v-else class="self-center">
                    {{ attachment.prefix }}202310-49000X
                </p>
                <font-awesome-icon
                    v-if="$route.name === 'OutboundedContractsEdit'"
                    @click.prevent="generateDocument"
                    class="cursor-pointer self-center"
                    icon="fa-solid fa-print"
                />
                <div class="">
                    <CustomIcon name="downwhiteIcon" />
                </div>
            </label>
            <div class="tab-content border px-4 pt-6 pb-6">
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full" v-if="attachment.selectUser">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="false"
                                v-model="attachment.userId"
                                :key="attachment.userId"
                                :error="errors.userId"
                                :options="users"
                                :customLabel="customLabel"
                                :multiple="false"
                                :textLabel="$t('User')"
                                label="first_name"
                                trackBy="id"
                                :moduleName="'Users'"
                                class=""
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="font-bold">
                                            {{ $t("First Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Last Name") }}
                                        </p>
                                    </div>
                                    <hr />
                                </template>
                                <template #singleLabel="{ props }">
                                    <div
                                        class="grid gap-2"
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                    </div>
                                </template>
                                <template #option="{ props }">
                                    <div
                                        class="grid"
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                ><span class="text-red-500">*</span>&nbsp;{{
                                    $t("Start Date")
                                }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="attachment.startDate"
                                :class="
                                    errors[
                                        `attachments.${attachmentIndex}.startDate`
                                    ]
                                        ? 'error'
                                        : ''
                                "
                            />
                            <div
                                v-if="
                                    errors[
                                        `attachments.${attachmentIndex}.startDate`
                                    ]
                                "
                                class="form-error"
                            >
                                {{
                                    errors[
                                        `attachments.${attachmentIndex}.startDate`
                                    ]
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                >{{ $t("Termination Date") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="attachment.terminationDate"
                                :class="
                                    errors[
                                        `attachments.${attachmentIndex}.terminationDate`
                                    ]
                                        ? 'error'
                                        : ''
                                "
                            />
                            <div
                                v-if="
                                    errors[
                                        `attachments.${attachmentIndex}.terminationDate`
                                    ]
                                "
                                class="form-error"
                            >
                                {{
                                    errors[
                                        `attachments.${attachmentIndex}.terminationDate`
                                    ]
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                >{{ $t("Signed Date") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="attachment.signedDate"
                                :class="
                                    errors[
                                        `attachments.${attachmentIndex}.signedDate`
                                    ]
                                        ? 'error'
                                        : ''
                                "
                            />
                            <div
                                v-if="
                                    errors[
                                        `attachments.${attachmentIndex}.signedDate`
                                    ]
                                "
                                class="form-error"
                            >
                                {{
                                    errors[
                                        `attachments.${attachmentIndex}.signedDate`
                                    ]
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <div
                        v-for="(
                            contractField, index
                        ) in attachment.contractFields"
                        :key="contractField.id"
                        class="grid items-center grid-cols-3 gap-6 mt-5"
                    >
                        <div
                            class="w-full"
                            v-if="
                                contractField.type === 'text' ||
                                contractField.type === 'number'
                            "
                        >
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
                                    v-model="contractField.key"
                                    :type="'text'"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractField.${index}.key`
                                        ]
                                    "
                                    :label="$t('Key')"
                                ></text-input>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="
                                contractField.type !== 'text' ||
                                contractField.type !== 'number'
                            "
                        >
                            <div class="form-group">
                                <div>
                                    <h3 class="card-title">
                                        {{ contractField.key }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-if="contractField.type === 'text'"
                        >
                            <div class="form-group">
                                <text-input
                                    :isReadonly="approved"
                                    v-model="contractField.value"
                                    :required="true"
                                    :type="'text'"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :label="$t('Value')"
                                ></text-input>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'number'"
                        >
                            <div class="form-group">
                                <text-input
                                    :isReadonly="approved"
                                    v-model="contractField.value"
                                    :required="true"
                                    :type="'number'"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :label="$t('Value')"
                                ></text-input>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'date'"
                        >
                            <div class="form-group">
                                <div class="">
                                    <label class="input-label"
                                        ><span class="text-red-500"
                                            >*&nbsp;</span
                                        >{{ $t("Value") }}:</label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :disabled="approved"
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="contractField.value"
                                        :class="
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                                ? 'error'
                                                : ''
                                        "
                                    />
                                    <div
                                        v-if="
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                        "
                                        class="form-error"
                                    >
                                        {{
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'time'"
                        >
                            <div class="form-group">
                                <div class="">
                                    <label class="input-label"
                                        ><span class="text-red-500"
                                            >*&nbsp;</span
                                        >{{ $t("Value") }}:</label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :disabled="approved"
                                        :clearable="false"
                                        auto-apply
                                        time-picker
                                        :close-on-auto-apply="false"
                                        v-model="contractField.value"
                                        :class="
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                                ? 'error'
                                                : ''
                                        "
                                    />
                                    <div
                                        v-if="
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                        "
                                        class="form-error"
                                    >
                                        {{
                                            errors[
                                                `attachments.${attachmentIndex}.contractFields.${index}.value`
                                            ]
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'customer'"
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="contractField.value"
                                    :options="companies?.data ?? []"
                                    :multiple="false"
                                    :required="true"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :text-label="$t('Customer')"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                    @open="fetchCompanies"
                                />
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'invoice'"
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="contractField.value"
                                    :options="invoices?.data ?? []"
                                    :multiple="false"
                                    :required="true"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :text-label="$t('Invoice')"
                                    label="invoiceNumber"
                                    trackBy="id"
                                    moduleName="invoices"
                                    @open="fetchInvoices"
                                />
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="contractField.type === 'offer'"
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="
                                        !attachment.addProductsToCustomerLicenses
                                    "
                                    v-model="contractField.value"
                                    :options="offers?.data ?? []"
                                    :multiple="false"
                                    :required="true"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :text-label="$t('Offer')"
                                    label="offerNumber"
                                    trackBy="id"
                                    moduleName="offers"
                                    @open="fetchOffers"
                                />

                                <OfferProducts
                                    @selected="
                                        offerProductsSelected($event, 'offer')
                                    "
                                    :companyId="companyId"
                                    type="offer"
                                    v-else
                                />

                                <div class="w-full">
                                    <OfferSelectorAccordions
                                        v-if="offerSelected"
                                        :softwareLicences="
                                            filterProductsByType(
                                                'license',
                                                attachment.offerProducts
                                            )
                                        "
                                        :softwareMaintenance="
                                            filterProductsByType(
                                                'maintenance',
                                                attachment.offerProducts
                                            )
                                        "
                                        :cloud="
                                            filterProductsByType(
                                                'cloud',
                                                attachment.offerProducts
                                            )
                                        "
                                        :additionalDescriptionToggled="
                                            additionalDescriptionToggled
                                        "
                                        :readonly="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="
                                contractField.type === 'offer-confirmation'
                            "
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="
                                        !attachment.addProductsToCustomerLicenses
                                    "
                                    v-model="contractField.value"
                                    :options="orderConfirmation?.data ?? []"
                                    :multiple="false"
                                    :required="true"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :text-label="$t('Offer Confirmation')"
                                    label="offerNumber"
                                    trackBy="id"
                                    moduleName="orderConfirmation"
                                    @open="fetchOfferConfirmation"
                                    :query="{
                                        offerType: 'order',
                                    }"
                                />
                                <OfferProducts
                                    @selected="
                                        offerProductsSelected(
                                            $event,
                                            'orderConfirmation'
                                        )
                                    "
                                    type="orderConfirmation"
                                    v-else
                                />
                                <div class="w-full">
                                    <OfferSelectorAccordions
                                        v-if="orderConfirmationSelected"
                                        :softwareLicences="
                                            filterProductsByType(
                                                'license',
                                                attachment.orderConfirmationProducts
                                            )
                                        "
                                        :softwareMaintenance="
                                            filterProductsByType(
                                                'maintenance',
                                                attachment.orderConfirmationProducts
                                            )
                                        "
                                        :cloud="
                                            filterProductsByType(
                                                'cloud',
                                                attachment.orderConfirmationProducts
                                            )
                                        "
                                        :additionalDescriptionToggled="
                                            additionalDescriptionToggled
                                        "
                                        :readonly="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-else-if="
                                contractField.type === 'performance-record'
                            "
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="contractField.value"
                                    :options="performanceRecords?.data ?? []"
                                    :multiple="false"
                                    :required="true"
                                    :error="
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    "
                                    :text-label="$t('Performance Record')"
                                    label="performanceNumber"
                                    trackBy="id"
                                    moduleName="performanceRecords"
                                    @open="fetchPerformanceRecords"
                                />
                            </div>
                        </div>
                        <div
                            class="w-full col-span-3"
                            v-else-if="contractField.type === 'products'"
                        >
                            <div class="form-group">
                                <ProductSelector
                                    :contractField="contractField"
                                    :company-id="companyId"
                                />
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-if="contractField.type === 'products'"
                        >
                            <div class="form-group">
                                <p class="text-red-600">
                                    {{
                                        errors[
                                            `attachments.${attachmentIndex}.contractFields.${index}.value`
                                        ]
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-6"/>
                    <div
                        v-if="attachment.allowToAddSlas == 1"
                        class="grid items-center grid-cols-4 py-3"
                    >
                        <div
                            v-for="(category, index) in productCategories"
                            :key="'product-category-' + category.id"
                            class="checkbox-group"
                        >
                            <input
                                @input="selectCategory($event, category)"
                                class="checkbox-input"
                                :id="'product-category-' + category.id"
                                type="checkbox"
                                :checked="
                                    selectedCategories.some(
                                        (selectedCategory) =>
                                            selectedCategory.id == category.id
                                    )
                                "
                            />
                            <label
                                class="checkbox-label"
                                :for="'product-category-' + category.id"
                                >{{ category.name }}</label
                            >
                        </div>
                    </div>

                    <div v-if="attachment.allowToAddSlas == 1">
                        <div class="mb-5">
                            <!-- AMS products selector -->
                            <AMSSelector
                                v-for="category in selectedCategories"
                                :key="'selected-category-' + category.id"
                                :category="category"
                                :attachmentId="attachment.contractAttachmentId"
                                :products="
                                    productsFilteredByCategory(category.id)
                                "
                                :company-id="companyId"
                            />

                            <p class="text-red-600">
                                {{
                                    errors[
                                        `attachments.${attachmentIndex}.products`
                                    ]
                                }}
                            </p>
                        </div>
                        <div class="grid items-center grid-cols-3 gap-6 pb-5">
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="attachment.slaServiceTimeId"
                                        :key="attachment.slaServiceTimeId"
                                        :options="slaServiceTimes ?? []"
                                        :multiple="false"
                                        :required="true"
                                        :error="
                                            errors[
                                                `attachments.${attachmentIndex}.slaServiceTimeId`
                                            ]
                                        "
                                        :text-label="$t('SLA Service Time')"
                                        label="name"
                                        trackBy="id"
                                        moduleName="slaServiceTimes"
                                        @open="fetchSlaServiceTimes"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="attachment.slaLevelId"
                                        :options="slaLevels?.data ?? []"
                                        :multiple="false"
                                        :required="true"
                                        :error="
                                            errors[
                                                `attachments.${attachmentIndex}.slaLevelId`
                                            ]
                                        "
                                        :text-label="$t('SLA Level')"
                                        label="name"
                                        trackBy="id"
                                        moduleName="slaLevel"
                                        @open="fetchSlaLevels"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import AMSSelector from "./AMSSelector.vue";
import ProductSelector from "./ProductSelector.vue";
import OfferSelectorAccordions from "@/Components/OfferSelectorAccordions.vue";
import OfferProducts from "./OfferProducts.vue";
import { mapGetters } from "vuex";
import generateDocumentMixin from "../Mixins/generateDocumentMixin";

export default {
    mixins: [generateDocumentMixin],
    components: {
        OfferProducts,
        AMSSelector,
        TextInput,
        MultiSelectInput,
        NumberInput,
        ProductSelector,
        OfferSelectorAccordions,
    },
    props: {
        contractData: {
            type: Object,
            required: false,
            default: () => ({}),
        },
        attachment: {
            type: Object,
            required: true,
            default: () => ({}),
        },
        attachmentIndex: {
            type: Number,
            required: true,
            default: 0,
        },
        approved: {
            type: Boolean,
            default: false,
        },
        companyId: {
            type: String,
            default: "",
        },
        productCategories: {
            type: Array,
            default: [],
        },
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("slaServiceTimes", ["slaServiceTimes"]),
        ...mapGetters("slaLevel", ["slaLevels"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("invoices", ["invoices"]),
        ...mapGetters("performanceRecords", ["performanceRecords"]),
        ...mapGetters("offers", ["offers"]),
        ...mapGetters("orderConfirmation", ["orderConfirmation"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    data() {
        return {
            additionalDescriptionToggled: {
                softwareLicences: {},
                softwareMaintenance: {},
                cloud: {},
            },
            orderConfirmationSelected: false,
            offerSelected: false,
            orderConfirmationSelected: false,
            selectedCategories: [],
        };
    },
    watch: {
        productCategories() {
            this.syncSelectedCategories();
        },
    },
    methods: {
        async fetchCompanies() {
            try {
                // fetch sla service time listing
                if (!this.companies?.data?.length) {
                    await this.$store.dispatch("companies/list");
                }
            } catch (e) {}
        },
        async fetchInvoices() {
            try {
                // fetch sla service time listing
                if (!this.invoices?.data?.length) {
                    await this.$store.dispatch("invoices/list");
                }
            } catch (e) {}
        },
        async fetchOffers() {
            try {
                // fetch sla service time listing
                if (!this.offers?.data?.length) {
                    await this.$store.dispatch("offers/list");
                }
            } catch (e) {}
        },
        async fetchOfferConfirmation() {
            try {
                // fetch sla service time listing
                if (!this.orderConfirmation?.data?.length) {
                    await this.$store.dispatch("orderConfirmation/list", {
                        offerType: "order",
                    });
                }
            } catch (e) {}
        },
        async fetchPerformanceRecords() {
            try {
                // fetch sla service time listing
                if (!this.performanceRecords?.data?.length) {
                    await this.$store.dispatch("performanceRecords/list");
                }
            } catch (e) {}
        },
        async fetchSlaServiceTimes() {
            try {
                // fetch sla service time listing
                if (!this.slaServiceTimes?.length) {
                    await this.$store.dispatch("slaServiceTimes/list");
                }
            } catch (e) {}
        },
        async fetchSlaLevels() {
            try {
                // fetch sla level incident listing
                if (!this.slaLevels?.data?.length) {
                    await this.$store.dispatch("slaLevel/list");
                }
            } catch (e) {}
        },
        /**
         * generates pdf generation for the attachment
         */
        async generateDocument() {
            this.generateProcessTemplate(
                this.attachment,
                this.contractData,
                "pdf"
            );
        },
        /**
         * filters products based on type
         * @param {type} can be license/maintenance/cloud
         * @param {products} products listing
         */
        filterProductsByType(type, products) {
            return products.filter((product) => product.type == type);
        },
        /**
         * triggered when products are selected from modal
         * @param {products} selected products
         * @param {type} can be offer/order confirmation
         */
        offerProductsSelected(products, type) {
            if (type === "offer") {
                this.attachment.offerProducts = products;
                this.offerSelected = !!products.length;
                return;
            }
            this.attachment.orderConfirmationProducts = products;
            this.orderConfirmationSelected = !!products.length;
        },
        productsFilteredByCategory(id) {
            return (
                this.attachment?.products?.filter(
                    (product) => product?.category?.id == id
                ) ?? []
            );
        },
        syncSelectedCategories() {
            this.selectedCategories.splice(0, this.selectedCategories.length);
            this.productCategories.forEach((category) => {
                if (
                    this.attachment?.products?.some(
                        (product) => product?.category?.id == category.id
                    )
                ) {
                    this.selectedCategories.push(category);
                }
            });
        },
        async selectCategory(event, category) {
            if (!!event.target.checked) {
                this.selectedCategories.push(category);
            } else {
                let selectedCategoryIndex = this.selectedCategories.findIndex(
                    (cat) => cat.id == category.id
                );
                this.selectedCategories.splice(selectedCategoryIndex, 1);
                this.$emitter.emit("amsProductsByCategory", {
                    attachment: this.attachment.id,
                    category: category?.id,
                    products: [],
                });
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        if (this.attachment?.userId) {
            this.attachment.userId =
                this.users?.find(
                    (user) => user.id == this.attachment?.userId
                ) ?? "";
        }
        this.orderConfirmationSelected =
            !!this.attachment?.orderConfirmationProducts?.length;
        this.offerSelected = !!this.attachment?.offerProducts?.length;
        try {
            this.syncSelectedCategories();
        } catch (e) {
            console.log(e);
        }
    },
};
</script>

<style lang="scss" scoped>
li {
    list-style: none;
}

.tab {
    width: 100%;
    color: black;
    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}
.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    ~ .tab-content {
        display: block;
        max-height: 100%;
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
</style>
