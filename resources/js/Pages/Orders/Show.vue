<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Configuration") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-3 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <label class="form-label font-bold"
                                >{{ $t("Partner") }}:</label
                            >
                        {{ form.partner?.companyName }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Receiver") }}:</label
                            >
                        {{ form.company?.companyName }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Terms Of Payment") }}:</label
                            >
                        {{ form.term?.name }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Project") }}:</label
                            >
                        {{ form.project?.name ?? '-' }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Expiry Date") }}:</label
                            >
                        {{ $dateFormatter(form.expiryDate, globalLanguage) }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Status") }}:</label
                            >
                        {{ form.status }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Payment Terms") }}:</label
                            >
                        {{ form.paymentTerms }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("External Order Number") }}:</label
                            >
                        {{ form.externalOrderNumber }}
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                         <label class="form-label font-bold"
                                >{{ $t("Identifier") }}:</label
                            >
                        {{ form.identifier }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header" style="padding-top: 0; padding-bottom: 0">
            <h3 class="card-title">{{ $t("Components") }}</h3>
        </div>
        <div class="card-body">
            <div class="flex items-center">
                <div class="checkbox-group mr-4">
                    <input
                        class="checkbox-input"
                        v-model="partnerPriceListToggle"
                        type="checkbox"
                        id="partnerPriceLists"
                    />
                    <label class="checkbox-label" for="partnerPriceLists">{{
                        $t("Partner Price List")
                    }}</label>
                </div>
                <div class="checkbox-group">
                    <input
                        class="checkbox-input"
                        v-model="ownPriceListToggle"
                        type="checkbox"
                        id="ownPriceLists"
                    />
                    <label class="checkbox-label" for="ownPriceLists">{{
                        $t("Own Price List")
                    }}</label>
                </div>
            </div>
        </div>
    </div>
    <template v-if="form.company?.companyName">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Company Details") }}</h3>
            </div>
            <div class="card-body">
                <span>
                    {{ form.company?.companyName }}
                    <div v-if="form.contactPerson">
                        {{ form.contactPerson?.first_name }}
                        {{ form.contactPerson?.last_name }}
                        <br />
                    </div>
                    <br v-else />
                    {{ form.company?.address }}
                    <br />
                    {{ form.company?.code }}&nbsp;{{ form.company?.city }}
                    <br />
                    {{ form.company?.state }}
                    <br />
                    {{ form.company?.country }}
                </span>
            </div>
            <div class="card-header">
            <h3 class="card-title flex justify-between">
                <h1 class="secondary-color text-2xl">
                   ORDER {{
                        this.modelData?.orderNumber
                    }} / {{
                    this.form?.partner?.companyNumber
                    }} / {{
                        this.form?.company?.companyNumber
                    }}
                </h1>
                <h6>
                    {{
                        $dateFormatter(
                            formatDateLite(new Date()),
                            globalLanguage
                        )
                    }}
                </h6>
            </h3>
        </div>
       <div class="card-body">
            <div
                class="grid gap-2"
                style="grid-template-columns: repeat(auto-fit, 500px)"
            >
                <div class="mb-8" style="color: #2957a4">
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Partner Nr.") }}</b>
                        <p>{{ form?.partner?.companyNumber ?? "-" }}</p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Customer Nr.") }}</b>
                        <p>{{ form?.company?.companyNumber ?? "-" }}</p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{
                            $t(
                                `Order Nr.`
                            )
                        }}</b>
                        <p>
                            {{
                                this.modelData.orderNumber
                            }}
                        </p>
                    </div>
                   
                    
                </div>
                <div class="mb-8" style="color: #2957a4">
                    <div
                        v-if="user"
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Created By") }}:</b>
                        <p>{{ user?.first_name }} {{ user?.last_name }}</p>
                    </div>
                    <div
                        v-if="form?.expiryDate"
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Create Date") }}:</b>
                        <p>
                            {{
                                $dateFormatter(
                                    formatDateLite(new Date(modelData?.createdAt)),
                                    globalLanguage
                                )
                            }}
                        </p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                        >
                        <b>{{ $t("Project Nr.") }}</b>
                        <p>{{ form?.project?.projectId ?? "-" }}</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </template>

    <div>
        <div class="mb-8 pb-4 product-border" v-if="partnerPriceListToggle">
            <div class="page-header">
                <div class="page-title">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <span class="">{{
                                    $t("Partner Price List")
                                }}</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div
                class="w-full col-span-3"
            >
                <div class="form-group">
                    <ProductSelector
                        :key="partnerProducts.length"
                        :type="'partner_price_list'"
                        :propProducts="partnerProducts"
                        ref="priceProductSelector"
                    />
                </div>
            </div>
        </div>
        <div class="mb-8 pb-4 product-border" v-if="ownPriceListToggle && form.partner">
            <div class="page-header">
                <div class="page-title">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <span class="">{{
                                    $t("Own Price List")
                                }}</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div
                class="w-full col-span-3"
            >
                <div class="form-group">
                    <ProductSelector
                        :key="ownProducts.length"
                        :type="'own_price_list'"
                        :propProducts="ownProducts"
                        :partnerId="form.partner"
                        ref="ownProductSelector"
                    />
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import SelectInput from "../../Components/SelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectProductModal from "../../Components/SelectProductModal.vue";
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import SelectAmsModal from "../../Components/SelectAmsModal.vue";
import ProductSelector from "./Components/ProductSelector.vue";

import { mapGetters } from "vuex";
import LoadingButton from "../../Components/LoadingButton.vue";
import { v4 as uuid } from "uuid";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partner-management/orders",
                },
                {
                    text: this.$t("Partner Orders"),
                    to: "/partner-management/orders",
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            modelData: {},
            ownProducts: [],
            partnerProducts: [],
            ownPriceListToggle: false,
            partnerPriceListToggle: false,
            form: {
                contactPerson: "",
                paymentTerms: "",
                expiryDate: new Date(),
                term: "",
                company: null,
                project: null,
                status: "entwurf",
                externalOrderNumber: "",
                identifier: "",
            }
        };
    },
    async mounted() {
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        await this.fetchOrderData(); // get the offer show data
        try {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });
            await this.$store.dispatch("termsOfPayment/list", {
                perPage: 25,
                page: 1,
            });
        } catch (e) {}
    },
    computed: {
        ...mapGetters("companyEmployees", ["users"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        formattedDate() {
            const dateObject = new Date(this.form.expiryDate);
            const year = dateObject.getFullYear();
            const month = String(dateObject.getMonth() + 1).padStart(2, '0');
            const day = String(dateObject.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
    },
    methods: {

        /**
         * fetch projects based on customerId
         */
        
        async fetchOrderData() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.$store.commit("showLoader", true);
                    const response = await this.$store.dispatch(
                        `orders/show`,
                        this.$route.params.id
                    );
                    this.modelData = response?.data?.data ?? {};
                    this.form.partner = this.modelData.partner
                    this.form.company = this.modelData.receiver
                    this.form.term = this.modelData.termOfPayment
                    this.form.status = this.modelData.status
                    this.form.expiryDate = this.modelData.expiryDate
                    this.form.project = this.modelData.project
                    this.form.paymentTerms = this.modelData.paymentTerms
                    this.form.externalOrderNumber = this.modelData.externalOrderNumber
                    this.form.identifier = this.modelData.identifier
                    this.modelData.products.forEach((product) => { 
                        if(product.componentType == 'own_price_list')
                            this.ownProducts.push(product)
                        if(product.componentType == 'partner_price_list')
                            this.partnerProducts.push(product)
                    });
                    if (this.modelData.partnerPriceCheck == true) {
                        this.partnerPriceListToggle = true
                    }
                    if (this.modelData.ownPriceCheck == true) {
                        this.ownPriceListToggle = true
                    }
                    document.title = "Show Order " + this.modelData.orderNumber
                    resolve();
                } catch (e) {
                    reject(e);
                } finally {
                    this.$store.commit("showLoader", false);
                }
            });
        },
        
        /**
         * formats the option label in multiselect input
         * @param {props} the options received from the multiselect input
         * @returns the formated label
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * triggered when a company is selected
         * fetches the company employees related to the selected company
         */
        
        async addPaymentTerms() {
            // await this.$nextTick();
            // this.form.paymentTerms = this.form?.term?.offerText;
        },
        async create() {
            const products = []
            if (this.partnerPriceListToggle == true) {
                this.$refs.priceProductSelector.selectedProducts.forEach((product) => { 
                    products.push(product)
                });
            } 
            if (this.ownPriceListToggle && this.form.partner) {
                this.$refs.ownProductSelector.selectedProducts.forEach((product) => { 
                    products.push(product)
                });
            }
            const payload = {
                partnerId: this.form.partner?.id,
                receiverId: this.form.company?.id,
                contactPerson: this.form?.contactPerson?.id,
                termId: this.form.term?.id,
                paymentTerms: this.form.paymentTerms,
                expiryDate: this.formattedDate ?? null,
                projectId: this.form.project?.id,
                status: this.form?.status,
                externalOrderNumber: this.form.externalOrderNumber,
                identifier: this.form.identifier,
                createdBy: this.modelData.createdBy,
                products: products
                
            };

            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    `orders/update`,
                    {
                        id: this.$route.params.id,
                        data: payload,
                    }
                );
                this.$router.push(
                    `/partner-management/orders`
                );
            } catch (e) {}
        },
    },
    components: {
        PageHeader,
        NumberInput,
        SelectInput,
        SelectAmsModal,
        SelectProductModal,
        SelectServiceModal,
        MultiSelectInput,
        TextAreaInput,
        LoadingButton,
        TextInput,
        ProductSelector
    },
};
</script>

<style lang="scss" scoped>
li {
    list-style: none;
}

.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: black;
    overflow: hidden;

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
        max-height: 100vh;
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

.table-align {
    width: 250px !important;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;

    &:hover {
        text-overflow: inherit;
        white-space: wrap;
    }
}

</style>
