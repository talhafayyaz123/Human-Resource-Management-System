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
                    :textLabel="`${$t('Company')}/${$t('Lead')}`"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    @asyncSearch="companiesSearch"
                />
                <select-input v-model="form.type" :label="$t('Offer Type')">
                    <option value="budget">{{ $t("Budget") }}</option>
                    <option value="offer">{{ $t("Offer") }}</option>
                </select-input>
                <select-input
                    v-model="form.offerStatus"
                    :key="form.offerStatus"
                    :label="$t('Status')"
                >
                    <option value="">{{ $t("All") }}</option>
                    <option value="entwurf">{{ $t("Entwurf") }}</option>
                    <option value="versendet">{{ $t("Versendet") }}</option>
                    <option value="beauftragt">{{ $t("Beauftragt") }}</option>
                    <option value="abgelehnt">{{ $t("Abgelehnt") }}</option>
                </select-input>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('saleOfferNumber', 'SaleOffer')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Offer Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'saleOfferNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('offerType', 'SaleOffer')"
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
                        @click="sort('Company.companyName', 'SaleOffer')"
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
                        @click="sort('createdAt', 'SaleOffer')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Offer Creation Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('offerStatus', 'SaleOffer')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'offerStatus'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class="pb-4 pt-6 px-6 cursor-pointer"
                        @click="sort('identifier', 'SaleOffer')"
                    >
                        {{ $t("Identifier") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'identifier'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('receiverType', 'SaleOffer')"
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
                        @click="sort('Project.name', 'SaleOffer')"
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
                        @click="sort('nettoTotal', 'SaleOffer')"
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
                    v-for="offer in offers?.data ?? []"
                    :key="offer.id"
                    :style="`background-color: ${
                        offer.offerStatus === 'beauftragt'
                            ? 'rgba(105, 255, 152, 0.3)'
                            : offer.offerStatus === 'abgelehnt'
                            ? 'rgba(255, 84, 41, 0.3)'
                            : ''
                    }`"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/offers/${offer.id}/edit?page${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ offer.offerNumber }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ offer.type }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ offer.company }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                        {{
                                $dateFormatter(
                                    offer.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ offer.offerStatus }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ offer.identifier }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $t(offer.receiverType) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ offer.project }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{
                                $formatter(
                                    offer?.totalNetto.toFixed(2),
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td @click.stop class="w-px border-t">
                        <div class="flex items-center gap-x-2">
                            <font-awesome-icon
                                v-if="offer.offerStatus === 'beauftragt'"
                                :title="$t('Generate Email')"
                                class="cursor-pointer"
                                @click.stop="toggleMailModal(offer)"
                                icon="fa-envelope"
                            />
                            <font-awesome-icon
                                :title="$t('Generate Document')"
                                class="cursor-pointer"
                                @click.stop="generateDocument(offer.id)"
                                icon="fa-print"
                            />
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/offers/${offer.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/offers/${offer.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(offer.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(offers?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No offers found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="offers ?? []"
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
        :invoice="offer"
        :fromOpenPost="true"
        :invoiceData="offerData"
        :invoiceTemplate="offerTemplate"
        :processing="processing"
        :isOffer="true"
    />
</template>

<script>
import Icon from "../../Components/Icon.vue";
import Pagination from "laravel-vue-pagination";

import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MailModal from "@/Pages/Invoices/MailModal.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("offers", ["offers"]),
    },
    components: {
        MailModal,
        MultiSelectInput,
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
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
                    to: "/offers",
                },
                {
                    text: "Offers",
                    active: true,
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Export CSV"),
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Export Latest CSV"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Offer"),
                btn2Path: "/offers/create",
            },
            window,
            processing: false,
            toggleModal: false,
            page: 1,
            offerTemplate: "",
            offerData: {},
            offer: {
                name: "",
                base64: "",
            },
            form: {
                search: "",
                company: "",
                offerStatus: "",
                type: "",
                sortBy:
                    localStorage.getItem("sort_SaleOffer_column") ??
                    "saleOfferNumber",
                sortOrder:
                    localStorage.getItem("sort_SaleOffer_order") ?? "asc",
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
        "form.offerStatus"(...val) {
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
                await this.$store.dispatch("offers/list", {
                    ...this.form,
                    company: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        this.refresh();
        try {
            let response = await this.$store.dispatch(
                "mailTemplates/mailTemplateByModule",
                {
                    module: "offerTemplate",
                }
            );
            this.offerTemplate = response?.data?.data ?? "";
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * gets the invoice and performance record process template payloads and then gets the blobs by hitting the process template API
         * and converts that blob to base64 and sends as props to the MailModal and toggles the MailModal
         * @param {offerData} the details of the selected invoice
         **/
        async toggleMailModal(offerData) {
            this.offerData = offerData; //set invoiceData prop value
            // toggle the MailModal
            this.toggleModal = true;
            try {
                this.processing = true;
                // get the invoice, performanceRecord details
                const response = await this.$store.dispatch(
                    "offers/downloadGeneratedOffer",
                    offerData.id
                );
                const offer = response?.data?.offer;
                const offerTemplateId = response?.data?.offerTemplateId;
                // if there is invoice details, then generate the document
                if (offer) {
                    const filename =
                        "offer-" +
                        (offer.offerNumber == null
                            ? "draft"
                            : offer.offerNumber) +
                        ".pdf";
                    const offerResponse = await this.$store.dispatch(
                        "documentService/processTemplateBase64",
                        {
                            data: { ...offer, id: offerTemplateId },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // generate and set base64 and filename for invoice prop
                    var reader = new FileReader();
                    reader.readAsDataURL(offerResponse);
                    reader.onloadend = () => {
                        this.offer = {
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
                    "offers/downloadGeneratedOffer",
                    id
                );
                const offer = response?.data?.offer;
                const offerTemplateId = response?.data?.offerTemplateId;
                // if there is invoice details, then generate the document
                if (offer) {
                    const filename =
                        "offer-" +
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
                                    moduleAction: "updatePDFOffer",
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
        async downloadCSV() {
            try {
                await this.$store.dispatch("offers/download");
            } catch (e) {}
        },
        async downloadLatestCsv() {
            try {
                await this.$store.dispatch("offers/downloadLatestCsv");
            } catch (e) {}
        },
        async refresh(bypass) {
             if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            }
            try {
                await this.$store.dispatch("offers/list", {
                    ...this.form,
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
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("offers/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
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
