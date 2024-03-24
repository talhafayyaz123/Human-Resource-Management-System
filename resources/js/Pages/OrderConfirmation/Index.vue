<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn1="downloadCSV"
            @csvBtn2="downloadLatestCsv"
        />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <MultiSelectInput
                    v-model="form.company"
                    :options="companies"
                    :multiple="false"
                    :textLabel="$t('Company/Lead')"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                />
                <select-input v-model="form.type" :label="$t('Offer Type')">
                    <option value="budget">{{ $t("Budget") }}</option>
                    <option value="offer">{{ $t("Offer") }}</option>
                </select-input>
                <select-input
                    v-model="form.orderStatus"
                    :key="form.orderStatus"
                    :label="$t('Status')"
                >
                    <option value="">{{ $t("All") }}</option>
                    <option value="draft">{{ $t("Draft") }}</option>
                    <option value="approved">{{ $t("Approved") }}</option>
                    <option value="sent">{{ $t("Sent") }}</option>
                </select-input>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('saleOfferNumber', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Offer Confirmation Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'saleOfferNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('offerType', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'offerType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="
                            sort('Company.companyName', 'orderConfirmation')
                        "
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Customer") + "/" + $t("Lead")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="
                            sort('TermsOfPayment.name', 'orderConfirmation')
                        "
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Terms Of Payment")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'TermsOfPayment.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('identifier', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Identifier")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'identifier'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('receiverType', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Receiver Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'receiverType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Project.name', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Project")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Project.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('orderStatus', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'orderStatus'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('nettoTotal', 'orderConfirmation')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Netto Total")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'nettoTotal'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="order in orderConfirmation?.data ?? []"
                    :key="order.id"
                    :class="`hover:bg-gray-100 focus-within:bg-gray-100 ${
                        order.orderStatus === 'done' ? 'bg-green-200' : ''
                    }`"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/order-confirmation/${order.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ order.offerNumber }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ order.type }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ order.company }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order.terms }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order.identifier }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $t(order.receiverType) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order.project }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $t(order.orderStatus) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{
                                $formatter(
                                    order?.totalNetto.toFixed(2),
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td @click.stop class="w-px border-t">
                        <div class="flex items-center gap-x-2">
                            <font-awesome-icon
                                v-if="order.orderStatus === 'approved'"
                                :title="$t('Generate Email')"
                                class="cursor-pointer"
                                @click.stop="toggleMailModal(order)"
                                icon="fa-envelope"
                            />
                            <font-awesome-icon
                                :title="$t('Generate Document')"
                                class="cursor-pointer"
                                @click.stop="generateDocument(order.id)"
                                icon="fa-print"
                            />
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/order-confirmation/${order.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/order-confirmation/${order.id}/edit?page=${page}`"
                                @click.stop=""
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="
                                    $can(`${$route.meta.permission}.delete`) &&
                                    order.orderStatus === 'draft'
                                "
                                @click.stop="destroy(order.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(orderConfirmation?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No offer confirmation found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="orderConfirmation ?? []"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <MailModal
        :toggleModal="toggleModal"
        @refresh="refresh"
        v-if="toggleModal"
        @toggle-modal="toggleModal = $event"
        :invoice="order"
        :fromOpenPost="true"
        :invoiceData="orderData"
        :invoiceTemplate="orderTemplate"
        :processing="processing"
        :isOrder="true"
    />
</template>

<script>
import Icon from "../../Components/Icon.vue";
import Pagination from "laravel-vue-pagination";

import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import MailModal from "@/Pages/Invoices/MailModal.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("orderConfirmation", ["orderConfirmation"]),
    },
    components: {
        MultiSelectInput,
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
        MailModal,
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/order-confirmation",
                },
                {
                    text: "Order Confirmantion",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Order Confirmantion"),
                btn2Path: "/order-confirmation/create",
            },
            window,
            processing: false,
            toggleModal: false,
            orderTemplate: "",
            orderData: {},
            order: {
                name: "",
                base64: "",
            },
            page: 1,
            form: {
                search: "",
                company: "",
                orderStatus: "",
                type: "",
                sortBy:
                    localStorage.getItem("sort_orderConfirmation_column") ??
                    "saleOfferNumber",
                sortOrder:
                    localStorage.getItem("sort_orderConfirmation_order") ??
                    "asc",
            },
            companies: [],
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
            this.debouncedFetch(...val);
        },
        "form.orderStatus"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("orderConfirmation/list", {
                    ...this.form,
                    offerType: "order",
                    company: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        var isPaginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
             isPaginate=true;
             
        }
        await this.refresh(isPaginate);
        try {
            let response = await this.$store.dispatch(
                "mailTemplates/mailTemplateByModule",
                {
                    module: "orderConfirmationTemplate",
                }
            );
            this.orderTemplate = response?.data?.data ?? "";
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * gets the invoice and performance record process template payloads and then gets the blobs by hitting the process template API
         * and converts that blob to base64 and sends as props to the MailModal and toggles the MailModal
         * @param {orderData} the details of the selected invoice
         **/
        async toggleMailModal(orderData) {
            this.orderData = orderData; //set invoiceData prop value
            // toggle the MailModal
            this.toggleModal = true;
            try {
                this.processing = true;
                // get the invoice, performanceRecord details
                const response = await this.$store.dispatch(
                    "orderConfirmation/downloadGeneratedOffer",
                    orderData.id
                );
                const order = response?.data?.offer;
                const offerTemplateId = response?.data?.offerTemplateId;
                // if there is invoice details, then generate the document
                if (order) {
                    const filename =
                        "offer-confirmation-" +
                        (order.offerNumber == null
                            ? "draft"
                            : order.offerNumber) +
                        ".pdf";
                    const offerResponse = await this.$store.dispatch(
                        "documentService/processTemplateBase64",
                        {
                            data: { ...order, id: offerTemplateId },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // generate and set base64 and filename for invoice prop
                    var reader = new FileReader();
                    reader.readAsDataURL(offerResponse);
                    reader.onloadend = () => {
                        this.order = {
                            name: filename,
                            base64: reader.result,
                        };
                    };
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.processing = false;
            }
        },
        /**
         *  fetches the offer and the template id and generates the document
         *  and downloads the files
         *  @param {id} id of the offer
         **/
        async generateDocument(id) {
            try {
                this.$store.commit("showLoader", true);
                // get the invoice, performanceRecord details
                const response = await this.$store.dispatch(
                    "orderConfirmation/downloadGeneratedOffer",
                    id
                );
                const offer = response?.data?.offer;
                const offerTemplateId = response?.data?.offerTemplateId;
                // if there is invoice details, then generate the document
                if (offer) {
                    const filename =
                        "offer-confirmation-" +
                        (offer.offerNumber == null
                            ? "draft"
                            : offer.offerNumber) +
                        ".pdf";
                    const offerBlob = await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: { ...offer, id: offerTemplateId },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // check if the response of the process template is of type Blob
                    if (offerBlob instanceof Blob) {
                        // convert blob to base64
                        const base64 = await this.convertBlobToBase64(
                            offerBlob
                        );
                        const eloRequestDataResponse =
                            await this.$store.dispatch(
                                "offers/eloRequestData",
                                offer.id
                            );
                        // send the elo request
                        await this.$store.dispatch(
                            "globalSettings/sendEloApiRequest",
                            {
                                content: {
                                    moduleAction: "updatePDFOrderConfirmation",
                                    data: {
                                        ...(eloRequestDataResponse?.data
                                            ?.data ?? {}),
                                        base64:
                                            (base64 ?? "").split(
                                                ";base64,"
                                            )?.[1] ?? "",
                                    },
                                },
                            }
                        );
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async refresh(bypass) {
            try {
                if (!this.orderConfirmation?.data?.length || bypass)
                    await this.$store.dispatch("orderConfirmation/list", {
                        ...this.form,
                        offerType: "order",
                        page: this.page,
                    });
                this.$store
                    .dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                    })
                    .then((res) => {
                        this.companies = res?.data?.data ?? [];
                    });
            } catch (e) {}
        },
        async destroy(id) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    await this.$store.dispatch("offers/destroy", id);
                    this.refresh(true);
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("orderConfirmation/list", {
                page: this.page,
                offerType: "order",
                search: this.form.search,
                ...this.form,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>
