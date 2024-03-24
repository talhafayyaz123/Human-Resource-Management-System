<template>
    <!-- <EditHistory
    ref="edit-history"
    :id="$route.params.id"
    :moduleName="'OutboundedContract'"
    :displayName="'Outbounded Contract'"
  /> -->

    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Configuration") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label font-bold"
                                >{{ $t("Receiver Type") }}:</label
                            >
                            <p>{{ form.receiverType }}</p>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label font-bold"
                                >{{ $t("Receiver") }}:</label
                            >
                            <p>{{ form.company.companyName }}</p>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label font-bold"
                                >{{ $t("Status") }}:</label
                            >
                            <p>{{ form.status }}</p>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label font-bold"
                                >{{ $t("Contract Type") }}:</label
                            >
                            <p>{{ form.contractTypeId.name }}</p>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label font-bold"
                                >{{ $t("Person In Charge") }}:</label
                            >
                            <p>{{ form.personInCharge.employee }}</p>
                        </div>
                    </div>
                    <div class="w-full col-span-2">
                        <div class="flex justify-between">
                            <label class="form-label font-bold"
                                >{{ $t("Attachments") }}:</label
                            >
                            <!-- <button
                @click.prevent="toggleAttachmentModal"
                class="secondary-btn px-3 py-2 rounded"
              >
                {{ $t("Add Attachments") }}
              </button> -->
                        </div>
                        <div
                            v-for="(attachment, index) in attachments"
                            :key="'attachment-' + attachment.id"
                            class="flex py-1"
                        >
                            <!-- <button
                @click.prevent="attachments.splice(index, 1)"
                class="mr-2"
              >
                <font-awesome-icon
                  icon="fa-regular fa-trash-can"
                ></font-awesome-icon>
              </button> -->
                            <label
                                >{{
                                    $t("Attachment") + " " + (index + 1)
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
        <div class="card mt-3">
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

        <ProductAccordion
            v-for="(attachment, index) in attachments"
            :key="'attachment-' + attachment.contractAttachmentId"
            :attachment="attachment"
            :approved="modelData.status === 'approved'"
            :companyId="form.company?.id ?? ''"
            :contractData="form"
            class="mt-3"
            :productCategories="productCategories"
            :attachment-index="index"
            :ref="`product-accordion-${index}`"
        />
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
import generateDocumentMixin from "./Mixins/generateDocumentMixin";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [generateDocumentMixin],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "leads", "partners"]),
        ...mapGetters("contractTypes", ["contractTypes"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("attachments", {
            storeAttachments: "attachments",
        }),
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
            this.$store.commit("showLoader", true);
            // fetch the contract type listing
            await this.$store.dispatch("contractTypes/list");
            // fetch the companies listing
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            // fetch the leads listing
            this.$store.dispatch("companies/list", {
                customerType: "lead",
            });
            // fetch the partners listing
            this.$store.dispatch("companies/list", {
                customerType: "partner",
            });
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

            // fetch the outbounded contract using the show API
            const response = await this.$store.dispatch(
                "outboundedContracts/show",
                this.$route.params.id
            );
            // sync the form with response
            this.modelData = response?.data?.data ?? {};
            this.form = {
                receiverType: response?.data?.data?.receiverType ?? "customer",
                company: response?.data?.data?.customer ?? "",
                status: response?.data?.data?.status ?? "draft",
                contractTypeId: response?.data?.data?.contractType ?? "",
                personInCharge: response?.data?.data?.personInCharge ?? "",
            };
            // fetch and sync the attachments that are part of the contract type
            this.fetchAttachments(response?.data?.data?.attachments ?? []);
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
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
                    text: "Show",
                    active: true,
                },
            ],
            toggleModal: false,
            amsProductsByCategory: {},
            productCategories: [],
            attachments: [],
            modelData: {},
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
                        !this.amsProductsByCategory[
                            productsByCategory.attachment
                        ]
                    ) {
                        this.amsProductsByCategory[
                            productsByCategory.attachment
                        ] = {};
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
                await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                });
                // fetch the leads listing
                this.$store.dispatch("companies/list", {
                    customerType: "lead",
                });
                // fetch the partners listing
                this.$store.dispatch("companies/list", {
                    customerType: "partner",
                });
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

                // fetch the outbounded contract using the show API
                const response = await this.$store.dispatch(
                    "outboundedContracts/show",
                    this.$route.params.id
                );
                // sync the form with response
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    receiverType:
                        response?.data?.data?.receiverType ?? "customer",
                    company: response?.data?.data?.customer ?? "",
                    status: response?.data?.data?.status ?? "draft",
                    contractTypeId: response?.data?.data?.contractType ?? "",
                    personInCharge: response?.data?.data?.personInCharge ?? "",
                };
                // fetch and sync the attachments that are part of the contract type
                this.fetchAttachments(response?.data?.data?.attachments ?? []);
            } catch (e) {
                console.log(e);
            }
        },
        data() {
            return {
                breadcrumbItems: [
                    {
                        text: "Admin portal",
                        to: "/dashboard",
                    },
                    {
                        text: "Outbounded Contracts",
                        to: `/outbounded-contracts`,
                    },
                    {
                        text: "Show",
                        active: true,
                    },
                ],
                toggleModal: false,
                amsProductsByCategory: {},
                productCategories: [],
                attachments: [],
                modelData: {},
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
             * hits the create invoice API
             */
            async createInvoice() {
                this.$store.commit("isLoading", true);
                try {
                    await this.$store.dispatch(
                        "outboundedContracts/createInvoice",
                        this.$route.params.id
                    );
                } catch (e) {
                    console.log(e);
                }
            },
            /**
             * creates and generates PDF using the process template API
             * @param {documentType} the type of document to be generated e.g "pdf", "docx"
             */
            async generate(documentType) {
                try {
                    this.attachments.forEach((attachment) => {
                        this.generateProcessTemplate(
                            attachment,
                            this.form,
                            documentType
                        );
                    });
                } catch (e) {
                    console.log(e);
                }
            },
            /**
             * maps product to desired format accepted by the API
             * @param {product} product to be mapped
             * @param {type} type of produuct, e.g sla_hours, sla_infrastructure
             */
            mapProduct(product, type) {
                return {
                    productId: product.id,
                    type: type,
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
            /**
             * fetch the attachments based on contractTypeId
             * sync the attachments with the attachmed received from the show API using attachments
             * @param {attachments} attachments received that are part of this contract
             */
            async fetchAttachments(attachments) {
                await this.$nextTick();
                try {
                    this.attachments = [
                        ...this.attachments,
                        ...attachments.map((attachment) => {
                            return {
                                ...attachment,
                                contractAttachmentId:
                                    attachment.contractAttachmentId ?? uuid(),
                                // add a value property in contract fields
                                contractFields: attachment.contractFields.map(
                                    (field) => {
                                        let value =
                                            field?.value ??
                                            (field.type === "products"
                                                ? []
                                                : "");
                                        // if the type if found contract field is time then modify the value
                                        if (field?.type === "time") {
                                            // split the value based on ":"
                                            let tokens =
                                                typeof field.value === "string"
                                                    ? field.value.split(":")
                                                    : [];
                                            // set the value using tokens
                                            value = {
                                                hours: tokens[0] ?? 0,
                                                minutes: tokens[1] ?? 0,
                                                seconds: tokens[2] ?? 0,
                                            };
                                        }
                                        return {
                                            ...field,
                                            value: value,
                                        };
                                    }
                                ),
                                slaServiceTimeId:
                                    attachment?.slaServiceTimeId ?? "",
                                slaLevelIncidentId:
                                    attachment?.slaLevelIncidentId ?? "",
                                slaLevelId: attachment?.slaLevelId ?? "",
                                slaLevelChangeId:
                                    attachment?.slaLevelChangeId ?? "",
                                isFixedPrice: attachment?.isFixedPrice == 1,
                                totalPrice: attachment?.totalPrice ?? 0,
                                hourlyRate: attachment?.hourlyRate ?? 0,
                                hoursPerYear: attachment?.hoursPerYear ?? 0,
                                products: attachment?.products ?? [],
                                userId: attachment?.userId ?? "",
                                startDate: attachment?.startDate ?? "",
                                terminationDate:
                                    attachment?.terminationDate ?? "",
                                signedDate: attachment?.signedDate ?? "",
                                attachmentNumber: attachment?.attachmentNumber,
                            };
                        }),
                    ];
                    this.toggleModal = false;
                } catch (e) {
                    console.log(e);
                }
            },
            getValue(field) {
                if (field?.type === "time") {
                    return `${field.value.hours}:${field.value.minutes}:${field.value.seconds}`;
                } else if (
                    field?.type === "customer" ||
                    field?.type === "invoice" ||
                    field?.type === "offer" ||
                    field?.type === "offer-confirmation" ||
                    field?.type === "performance-record"
                ) {
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
            async update() {
                try {
                    this.$store.commit("isLoading", true);
                    let contractFields = [];
                    let attachments = this.attachments.map(
                        (attachment, index) => {
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
                                        this.mapProduct(product, "sla_hours")
                                    ),
                                ];
                                categoryProducts.forEach((product) => {
                                    if (
                                        !!product?.category?.serviceContingent
                                    ) {
                                        contingentProductsCount += 1;
                                        slaHoursCost += +product.hourlyRate;
                                        amsHours += +product.quantity;
                                    }
                                    slaHoursCostFixedPrice +=
                                        +product.fixedPrice;
                                });
                            });

                            let overallFactor =
                                (+(attachment?.slaServiceTimeId?.factor ?? 0) *
                                    2 +
                                    +(attachment?.slaLevelId?.factor ?? 0)) /
                                4;

                            let totalPrice =
                                +slaHoursCost / +contingentProductsCount +
                                +slaHoursCostFixedPrice * +overallFactor;

                            return {
                                id: attachment.id,
                                contractAttachmentId:
                                    typeof attachment.contractAttachmentId ===
                                    "number"
                                        ? attachment.contractAttachmentId
                                        : null,
                                slaServiceTimeId:
                                    attachment?.slaServiceTimeId?.id ?? "",
                                slaLevelIncidentId:
                                    attachment?.slaLevelIncidentId?.id ?? "",
                                slaLevelId: attachment?.slaLevelId?.id ?? "",
                                slaLevelChangeId:
                                    attachment?.slaLevelChangeId?.id ?? "",
                                isFixedPrice: !!attachment?.isFixedPrice
                                    ? 1
                                    : 0,
                                totalPrice: totalPrice ?? 0,
                                hourlyRate:
                                    +slaHoursCost / +contingentProductsCount,
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
                                            value: field.value
                                                ? this.getValue(field)
                                                : "",
                                        };
                                    }
                                ),
                            };
                        }
                    );
                    await this.$store.dispatch("outboundedContracts/update", {
                        id: this.$route.params.id,
                        data: {
                            ...this.form,
                            receiverId: this.form.company?.id,
                            contractTypeId: this.form.contractTypeId?.id,
                            attachments: attachments,
                            personInCharge: this.form.personInCharge?.id,
                        },
                    });
                    await this.$store.dispatch("outboundedContracts/list");
                    this.$router.push(`/outbounded-contracts`);
                } catch (e) {}
            },
        },
    },
};
</script>
