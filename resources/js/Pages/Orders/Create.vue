<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="tab-header">
        <ul class="">
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="activeTab = 'contractData'"
                    :class="
                        activeTab === 'contractData'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Contract Data") }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="activeTab = 'licenses'"
                    :class="
                        activeTab === 'licenses'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Licenses") }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="activeTab = 'systemConfiguration'"
                    :class="
                        activeTab === 'systemConfiguration'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("System Configuration") }}
                </a>
            </li>
        </ul>
    </div>

    <div id="myTabContent">
        <div
            v-show="activeTab === 'contractData'"
            id="contractData"
            role="tabpanel"
            aria-labelledby="contractData"
        >
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Configuration") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-3 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.partner"
                                    :key="form.partner"
                                    :required="true"
                                    @update:model-value="
                                        fetchReciverCompany(true);
                                        fetchCompanyByPartner();
                                    "
                                    :textLabel="$t('Partner')"
                                    :placeholder="$t('Select Partner')"
                                    :options="partners?.data"
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                    :error="errors.partner"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :customLabel="customLabel"
                                    v-model="form.contactPartnerPerson"
                                    :key="form.contactPartnerPerson"
                                    :textLabel="$t('Contact Person')"
                                    :options="partnerUsers"
                                    :multiple="false"
                                    trackBy="id"
                                    moduleName="partnerEmployees"
                                    :error="errors.contactPartnerPerson"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.status"
                                    :key="form.status"
                                    :error="errors.status"
                                    :label="$t('Status')"
                                >
                                    <option value="entwurf">
                                        {{ $t("Entwurf") }}
                                    </option>
                                    <option value="versendet">
                                        {{ $t("Versendet") }}
                                    </option>
                                    <option value="beauftragt">
                                        {{ $t("Beauftragt") }}
                                    </option>
                                    <option value="abgelehnt">
                                        {{ $t("Abgelehnt") }}
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
                                    @update:model-value="
                                        fetchCompanyLeadEmployees(true);
                                        fetchProjectsByCustomer();
                                    "
                                    :textLabel="$t('Receiver')"
                                    :placeholder="$t('Select Receiver')"
                                    :options="companies.data"
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="receiver"
                                    :error="errors.companyId"
                                    :countStore="'count'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :customLabel="customLabel"
                                    v-model="form.contactPerson"
                                    :key="form.contactPerson"
                                    :textLabel="$t('Contact Person')"
                                    :options="users"
                                    :multiple="false"
                                    trackBy="id"
                                    moduleName="companyEmployees"
                                    :error="errors.contactPerson"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-3 gap-6 mt-8">
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    v-model="form.identifier"
                                    :error="errors.identifier"
                                    :label="$t('Identifier')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    v-model="form.externalOrderNumber"
                                    :error="errors.externalOrderNumber"
                                    :label="$t('External Order Number')"
                                />
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <label class="input-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("Expiry Date")
                                    }}:</label
                                >
                                <datepicker
                                    class="form-control"
                                    :style="dropdownStyles"
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    v-model="form.expiryDate"
                                    :class="errors.expiryDate ? 'error' : ''"
                                />
                                <div
                                    v-if="errors?.expiryDate"
                                    class="form-error"
                                >
                                    {{ errors.expiryDate }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="form.term"
                                    :key="form.term"
                                    :error="errors.termId"
                                    :multiple="false"
                                    trackBy="id"
                                    label="name"
                                    :options="termsOfPayment.data"
                                    moduleName="termsOfPayment"
                                    :textLabel="$t('Terms Of Payment')"
                                    @update:model-value="addPaymentTerms"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-model="form.project"
                                :key="form.project"
                                :textLabel="$t('Project')"
                                :placeholder="$t('Select Project')"
                                :options="projects.data"
                                :multiple="false"
                                label="name"
                                trackBy="id"
                                moduleName="projects"
                            />
                        </div>
                    </div> -->
                    <div class="w-full col-span-3 mt-4">
                        <div class="form-group">
                            <text-area-input
                                v-model="form.paymentTerms"
                                :label="$t('Payment Terms')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <template v-if="form.company?.companyName">
                <div class="card my-6">
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
                            {{ form.company?.code }}&nbsp;{{
                                form.company?.city
                            }}
                            <br />
                            {{ form.company?.state }}
                            <br />
                            {{ form.company?.country }}
                        </span>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title flex justify-between">
                            <h1 class="secondary-color text-2xl">
                                {{ $t(`Draft Order`) }}
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
                            style="
                                grid-template-columns: repeat(auto-fit, 500px);
                            "
                        >
                            <div class="mb-8" style="color: #2957a4">
                                <div
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            200px
                                        );
                                    "
                                >
                                    <b>{{ $t("Customer Nr.") }}</b>
                                    <p>
                                        {{
                                            form?.company?.companyNumber ?? "-"
                                        }}
                                    </p>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            200px
                                        );
                                    "
                                >
                                    <b>{{ $t("Project Nr.") }}</b>
                                    <p>{{ form?.project?.projectId ?? "-" }}</p>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            200px
                                        );
                                    "
                                >
                                    <b>{{ $t(`Order Nr.`) }}</b>
                                    <p>
                                        {{ $t(`Draft Order`) }}
                                    </p>
                                </div>
                            </div>
                            <div class="mb-8" style="color: #2957a4">
                                <div
                                    v-if="user"
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            200px
                                        );
                                    "
                                >
                                    <b>{{ $t("Created By") }}:</b>
                                    <p>
                                        {{ user?.first_name }}
                                        {{ user?.last_name }}
                                    </p>
                                </div>
                                <div
                                    v-if="form?.expiryDate"
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            200px
                                        );
                                    "
                                >
                                    <b>{{ $t("Expiry Date") }}:</b>
                                    <p>
                                        {{
                                            $dateFormatter(
                                                formatDateLite(
                                                    new Date(form?.expiryDate)
                                                ),
                                                globalLanguage
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div
            v-show="activeTab === 'licenses'"
            id="licenses"
            role="tabpanel"
            aria-labelledby="licenses"
        >
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Components") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-6">
                        <div class="checkbox-group">
                            <input
                                class="checkbox-input"
                                v-model="partnerPriceListToggle"
                                type="checkbox"
                                id="partnerPriceLists"
                            />
                            <label
                                class="checkbox-label"
                                for="partnerPriceLists"
                                >{{ $t("Partner Price List") }}</label
                            >
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

            <div>
                <div
                    class="mb-8 pb-4 product-border"
                    v-if="partnerPriceListToggle"
                >
                    <div class="page-header mt-4">
                        <div class="page-title">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <span class="">{{
                                            priceListLabel?.name ??
                                            "No Price List Available"
                                        }}</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <ProductSelector
                                :contractField="contractField"
                                :type="'partner_price_list'"
                                ref="priceProductSelector"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="mb-8 pb-4 product-border"
                    v-if="ownPriceListToggle && form.partner"
                >
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
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <ProductOwnPriceListSelector
                                :type="'own_price_list'"
                                :contractField="contractField"
                                :partnerId="form.partner"
                                ref="ownProductSelector"
                            />
                        </div>
                    </div>
                </div>
                <!-- <div class="flex items-center justify-end mt-5">
                    <router-link
                        to="/partner-management/orders"
                        class="primary-btn mr-3"
                    >
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button
                        :loading="isLoading"
                        @click="create('create')"
                        class="secondary-btn"
                    >
                        <span class="mr-1">
                            <CustomIcon name="saveIcon" />
                        </span>
                        {{ $t("Save and Proceed") }}
                    </loading-button>
                </div> -->
            </div>
        </div>

        <div
            v-show="activeTab === 'systemConfiguration'"
            id="systemConfiguration"
            role="tabpanel"
            aria-labelledby="systemConfiguration"
        ></div>

        <div :class="`flex items-center justify-between py-4`">
            <button
                v-if="activeTab === 'contractData'"
                @click="$router.push('/partner-management/orders')"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <loading-button
                v-else
                @click="back"
                :loading="isLoading"
                class="primary-btn mr-3"
                >{{ $t("Back") }}
            </loading-button>
            <loading-button
                @click="create()"
                :loading="isLoading"
                class="secondary-btn"
                >{{
                    activeTab === "systemConfiguration"
                        ? $t("Save and Proceed")
                        : $t("Next")
                }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import PageHeader from "@/Components/Layouts/Page-header.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectProductModal from "../../Components/SelectProductModal.vue";
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import SelectAmsModal from "../../Components/SelectAmsModal.vue";
import ProductSelector from "./Components/ProductSelector.vue";
import ProductOwnPriceListSelector from "./Components/ProductOwnPriceListSelector.vue";

import { mapGetters } from "vuex";
import LoadingButton from "../../Components/LoadingButton.vue";
import { v4 as uuid } from "uuid";
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
                    text: "Create",
                    active: true,
                },
            ],
            ownPriceListToggle: false,
            partnerPriceListToggle: false,
            form: {
                contactPartnerPerson: "",
                contactPerson: "",
                paymentTerms: "",
                expiryDate: new Date(),
                term: "",
                company: null,
                project: null,
                status: "entwurf",
                externalOrderNumber: "",
                identifier: "",
                partner: " ",
            },
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "contractData",
        };
    },

    async mounted() {
        this.addFourWeeks();
        try {
            // await this.$store.dispatch("companies/list", {
            //     perPage: 25,
            //     page: 1,
            // });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });
            await this.$store.dispatch("termsOfPayment/list", {
                perPage: 25,
                page: 1,
            });
            this.$store.commit("companies/companies", []);
        } catch (e) {}
    },
    computed: {
        ...mapGetters("companyEmployees", ["users", "partnerUsers"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("productprice", ["priceListLabel"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        formattedDate() {
            const dateObject = new Date(this.form.expiryDate);
            const year = dateObject.getFullYear();
            const month = String(dateObject.getMonth() + 1).padStart(2, "0");
            const day = String(dateObject.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },
    },
    watch: {
        partnerPriceListToggle(...val) {
            if (!!this.partnerPriceListToggle) {
                try {
                    this.$store.dispatch(`productprice/priceListName`);
                } catch (e) {}
            }
        },
    },
    methods: {
        back() {
            if (this.activeTab === "licenses") {
                this.activeTab = "contractData";
            } else if (this.activeTab === "systemConfiguration") {
                this.activeTab = "licenses";
            }
        },
        /**
         * fetch projects based on customerId
         */
        fetchProjectsByCustomer() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.form.project = null;
                    await this.$store.dispatch("projects/list", {
                        companyId: this.form.company?.id,
                    });
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        fetchCompanyByPartner() {
            return new Promise(async (resolve, reject) => {
                try {
                    //this.form.project = null;
                    await this.$store.dispatch(
                        "companyEmployees/partnerUserList",
                        {
                            limit_start: 0,
                            limit_count: 25,
                            company_id: this.form.partner?.id,
                        }
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async fetchReciverCompany(update = false) {
            await this.$nextTick();
            return new Promise(async (resolve, reject) => {
                try {
                    this.form.contactPartnerPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companies/list", {
                        limit_start: 0,
                        limit_count: 25,
                        partnerId: this.form.partner?.id,
                    });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        async fetchCompanyLeadEmployees(update = false) {
            await this.$nextTick();
            return new Promise(async (resolve, reject) => {
                try {
                    this.form.contactPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: 0,
                        limit_count: 25,
                        company_id: this.form.company?.id,
                    });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
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
        addFourWeeks() {
            const newDate = new Date(
                this.form.expiryDate.getTime() + 30 * 24 * 60 * 60 * 1000
            );
            this.form.expiryDate = newDate;
        },
        async addPaymentTerms() {
            // await this.$nextTick();
            // this.form.paymentTerms = this.form?.term?.offerText;
        },
        async create() {
            if (this.activeTab === "contractData") {
                this.activeTab = "licenses";
                return;
            } else if (this.activeTab === "licenses") {
                this.activeTab = "systemConfiguration";
                return;
            } else {
                const products = [];
                if (this.partnerPriceListToggle == true) {
                    this.$refs.priceProductSelector.selectedProducts.forEach(
                        (product) => {
                            products.push(product);
                        }
                    );
                }
                if (this.ownPriceListToggle && this.form.partner) {
                    this.$refs.ownProductSelector.selectedProducts.forEach(
                        (product) => {
                            products.push(product);
                        }
                    );
                }
                const payload = {
                    partnerId: this.form.partner?.id,
                    receiverId: this.form.company?.id,
                    contactPerson: this.form?.contactPerson?.id,
                    contactPartnerPerson: this.form?.contactPartnerPerson?.id,
                    createdBy: this.user.id,
                    termId: this.form.term?.id,
                    paymentTerms: this.form.paymentTerms,
                    expiryDate: this.formattedDate ?? null,
                    projectId: this.form.project?.id,
                    status: this.form?.status,
                    externalOrderNumber: this.form.externalOrderNumber,
                    identifier: this.form.identifier,
                    products: products,
                };
                try {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch(`orders/create`, payload);
                    this.$router.push(`/partner-management/orders`);
                } catch (e) {}
            }
        },
    },
    components: {
        ProductOwnPriceListSelector,
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
        ProductSelector,
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
