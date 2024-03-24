<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Configuration") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    class=""
                                    :required="true"
                                    v-model="form.receiverType"
                                    :key="form.receiverType"
                                    @update:model-value="
                                        form.contactPerson = ''
                                    "
                                    :error="errors.receiverType"
                                    :label="$t('Receiver Type')"
                                >
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="lead">
                                        {{ $t("Lead") }}
                                    </option>
                                    <option value="partner">
                                        {{ $t("Partner") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.company"
                                    :key="form.company"
                                    :required="true"
                                    class=""
                                    :textLabel="$t('Receiver')"
                                    :placeholder="$t('Select Receiver')"
                                    :options="
                                        form.receiverType === 'lead'
                                            ? leads.data
                                            : form.receiverType === 'partner'
                                            ? partners.data
                                            : companies.data
                                    "
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                    :error="errors.receiverId"
                                    :query="{ customerType: form.receiverType }"
                                    :countStore="
                                        form.receiverType === 'lead'
                                            ? 'leadCount'
                                            : form.receiverType === 'partner'
                                            ? 'partnerCount'
                                            : 'count'
                                    "
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="'Contract Type'"
                                    class=""
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.contractTypeId"
                                    moduleName="contractTypes"
                                    :search-param-name="'search'"
                                    :options="contractTypes?.data ?? []"
                                    :error="errors.contractTypeId"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.personInCharge"
                                    :required="true"
                                    :key="form.personInCharge"
                                    :error="errors['personInCharge']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('Person In Charge')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <div class="flex justify-between">
                                    <label>{{ $t("Attachments") }}:</label>
                                    <button
                                        @click.prevent="toggleAttachmentModal"
                                        class="secondary-btn"
                                    >
                                        {{ $t("Add Attachments") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <div
                                    v-for="(attachment, index) in attachments"
                                    :key="'attachment-' + attachment.id"
                                    class="flex py-1"
                                >
                                    <button
                                        @click.prevent="
                                            attachments.splice(index, 1)
                                        "
                                        class="mr-2"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-trash-can"
                                        />
                                    </button>
                                    <label
                                        >{{
                                            $t("Attachment") +
                                            " " +
                                            (index + 1)
                                        }}.&nbsp;<span>{{
                                            attachment.name
                                        }}</span></label
                                    >
                                </div>
                                <div v-if="!attachments.length">
                                    <p class="text-xs text-gray-500">
                                        {{ $t("No attachments found") }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
            <ProductAccordion
                        v-for="(attachment, index) in attachments"
                        :key="'attachment-' + attachment.contractAttachmentId"
                        :attachment="attachment"
                        :companyId="form.company?.id ?? ''"
                        :productCategories="productCategories"
                        :attachment-index="index"
                        :ref="`product-accordion-${index}`"
                        class="mt-3"
                    />
            <div class="card mt-3" v-if="form.company?.companyName">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Company Details") }}</h3>
                </div>
                <div class="card-body">
                    <span>
                        {{ form.company?.companyName }}
                        <br />
                        {{ form.company?.address }}
                        <br />
                        {{ form.company?.code }}&nbsp;{{ form.company?.city }}
                        <br />
                        {{ form.company?.country }}
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/outbounded-contracts"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
    <SelectAttachmentsModal
        v-if="toggleModal"
        :contractTypeId="form.contractTypeId?.id"
        @cancelled="toggleModal = false"
        @selected="fetchAttachments"
    />
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import ProductAccordion from "./Components/ProductAccordion.vue";
import SelectAttachmentsModal from "@/Components/SelectAttachmentsModal.vue";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "leads", "partners"]),
        ...mapGetters("contractTypes", ["contractTypes"]),
        ...mapGetters("attachments", {
            storeAttachments: "attachments",
        }),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        MultiSelectInput,
        ProductAccordion,
        SelectAttachmentsModal,
        PageHeader,
    },
    async mounted() {
        // save the products in amsProductsByCategory by category id as the key
        this.$emitter.on("amsProductsByCategory", (productsByCategory) => {
            if (productsByCategory?.attachment) {
                if (
                    !this.amsProductsByCategory[productsByCategory.attachment]
                ) {
                    this.amsProductsByCategory[productsByCategory.attachment] =
                        {};
                }
                this.amsProductsByCategory[productsByCategory.attachment][
                    productsByCategory.category
                ] = productsByCategory?.products ?? [];
            }
        });
        try {
            // fetch the contract type listing
            await this.$store.dispatch("contractTypes/list");
            // fetch the companies listing
            await this.$store.dispatch("companies/list");
            // fetch the userprofile listing
            await this.$store.dispatch("userProfile/list");
            // fetch the product category listing of where product type is "ams"
            this.$store
                .dispatch("productCategory/getByProductType", {
                    productType: "ams",
                })
                .then((res) => {
                    this.productCategories = res?.data?.data ?? [];
                });
            // fetch the leads listing
            this.$store.dispatch("companies/list", {
                customerType: "lead",
            });
            // fetch the partners listing
            this.$store.dispatch("companies/list", {
                customerType: "partner",
            });
        } catch (e) {
            console.log(e);
        }
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Contract Managment"),
                    to: "/outbounded-contracts",
                },
                {
                    text: this.$t("Outbounded Contracts"),
                    to: "/outbounded-contracts",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],

            toggleModal: false,
            amsProductsByCategory: {},
            attachments: [],
            productCategories: [],
            form: {
                receiverType: "customer",
                company: "",
                status: "draft",
                contractTypeId: "",
                personInCharge: "",
            },
        };
    },
    methods: {
        async toggleAttachmentModal() {
            try {
                if (!this.storeAttachments?.data?.length) {
                    await this.$store.dispatch("attachments/list", {
                        contractTypeId: this.form.contractTypeId?.id ?? "",
                    });
                }
                this.toggleModal = true;
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * maps product to desired format accepted by the API
         * @param {product} product to be mapped
         */
        mapProduct(product) {
            return {
                productId: product.id,
                type: product.type,
                quantity: product.quantity,
                name: product.name,
                isProductNameEdit: product.isProductNameEdit,
                hourlyRate: product.hourlyRate,
                salePrice: product.salePrice,
                maintenancePrice: product.maintenancePrice,
                tax: product.tax,
                discount: product.discount,
                totalNetto: product.nettoTotal,
                totalAmount: product.salePrice,
                paymentPeriod:
                    product.paymentPeriod?.id ?? product.paymentPeriod,
                productCategoryId: product.category?.id,
                pricePerPeriod: product.pricePerPeriod,
                duration: product.duration,
                additionalDescription: product.additionalDescription,
                serviceHours: product.serviceHours,
                slaServiceTimeId: "",
                slaLevelIncidentId: "",
                slaLevelId: "",
                slaLevelChangeId: "",
            };
        },
        // fetch the attachments based on contractTypeId
        async fetchAttachments(attachments) {
            await this.$nextTick();
            try {
                this.attachments = [
                    ...this.attachments,
                    ...attachments.map((attachment) => {
                        return {
                            ...attachment,
                            contractAttachmentId: uuid(),
                            // add a value property in contract fields
                            contractFields: attachment.contractFields.map(
                                (field) => {
                                    return {
                                        ...field,
                                        value:
                                            field.type === "products" ? [] : "",
                                    };
                                }
                            ),
                            slaServiceLevelId: "",
                            slaLevelIncidentId: "",
                            slaLevelId: "",
                            slaLevelChangeId: "",
                            isFixedPrice: false,
                            totalPrice: 0,
                            hourlyRate: 0,
                            hoursPerYear: 0,
                            userId: "",
                            startDate: "",
                            terminationDate: "",
                            signedDate: "",
                        };
                    }),
                ];
                this.toggleModal = false;
            } catch (e) {
                console.log(e);
            }
        },
        getValue(field, attachment) {
            if (field?.type === "time") {
                return `${field.value.hours}:${field.value.minutes}:${field.value.seconds}`;
            } else if (
                field?.type === "customer" ||
                field?.type === "invoice" ||
                field?.type === "offer" ||
                field?.type === "offer-confirmation" ||
                field?.type === "performance-record"
            ) {
                if (
                    field?.type === "offer" &&
                    !!attachment.addProductsToCustomerLicenses
                ) {
                    return attachment.offerProducts?.map((product) => {
                        return {
                            ...product,
                            totalNetto: product.nettoTotal,
                        };
                    });
                } else if (
                    field?.type === "offer-confirmation" &&
                    !!attachment.addProductsToCustomerLicenses
                ) {
                    return attachment.orderConfirmationProducts?.map(
                        (product) => {
                            return {
                                ...product,
                                totalNetto: product.nettoTotal,
                            };
                        }
                    );
                }
                return field?.value?.id;
            } else if (field?.type === "products") {
                return field.value instanceof Array
                    ? field.value.map((product) => this.mapProduct(product))
                    : [];
            } else {
                return field?.value ?? "";
            }
        },
        // hit the outbounded contratcs create API and redirect to listing page upon success
        async store() {
            try {
                this.$store.commit("isLoading", true);
                let attachments = this.attachments.map((attachment, index) => {
                    let slaHoursCost = 0;
                    let slaHoursCostFixedPrice = 0;
                    let amsHours = 0;

                    let products = [];

                    let contingentProductsCount = 0;

                    // map the products by category
                    Object.values(
                        this.amsProductsByCategory?.[
                            attachment.contractAttachmentId
                        ] ?? {}
                    ).forEach((categoryProducts) => {
                        // map products
                        products = [
                            ...products,
                            ...categoryProducts.map((product) =>
                                this.mapProduct(product)
                            ),
                        ];
                        categoryProducts.forEach((product) => {
                            if (!!product?.category?.serviceContingent) {
                                slaHoursCost += +product.hourlyRate;
                                amsHours += +product.quantity;
                                contingentProductsCount += 1;
                            }
                            slaHoursCostFixedPrice += +product.fixedPrice;
                        });
                    });

                    let overallFactor =
                        (+(attachment?.slaServiceTimeId?.factor ?? 0) * 2 +
                            +(attachment?.slaLevelId?.factor ?? 0)) /
                        4;

                    let totalPrice =
                        +slaHoursCost / +contingentProductsCount +
                        +slaHoursCostFixedPrice * +overallFactor;

                    return {
                        id: attachment.id,
                        slaServiceTimeId:
                            attachment?.slaServiceTimeId?.id ?? "",
                        slaLevelIncidentId:
                            attachment?.slaLevelIncidentId?.id ?? "",
                        slaLevelId: attachment?.slaLevelId?.id ?? "",
                        slaLevelChangeId:
                            attachment?.slaLevelChangeId?.id ?? "",
                        isFixedPrice: !!attachment?.isFixedPrice ? 1 : 0,
                        totalPrice: totalPrice ?? 0,
                        hourlyRate: +slaHoursCost / +contingentProductsCount,
                        hoursPerYear: amsHours,
                        products: products,
                        userId: attachment?.userId["id"] ?? "",
                        startDate: attachment.startDate,
                        terminationDate: attachment.terminationDate,
                        signedDate: attachment.signedDate,
                        contractFields: attachment.contractFields.map(
                            (field) => {
                                return {
                                    ...field,
                                    value: this.getValue(field, attachment),
                                };
                            }
                        ),
                    };
                });
                await this.$store.dispatch("outboundedContracts/create", {
                    ...this.form,
                    receiverId: this.form.company?.id,
                    contractTypeId: this.form.contractTypeId?.id,
                    attachments: attachments,
                    personInCharge: this.form.personInCharge?.id,
                });
                await this.$store.dispatch("outboundedContracts/list");
                this.$router.push("/outbounded-contracts");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
