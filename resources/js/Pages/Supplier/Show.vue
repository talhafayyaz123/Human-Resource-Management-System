<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'supplier'"
                        :class="
                            activeTab === 'supplier'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Supplier") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'locations'"
                        :class="
                            activeTab === 'locations'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Locations") }}
                    </a>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div
                v-if="activeTab === 'supplier'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <form>
                    <div class="w-full flex justify-end mb-2">
                        <router-link
                            class="secondary-btn"
                            :to="'/suppliers/' + supplier.id + '/edit?page='+returnPage"
                        >
                            <span>{{ $t("Edit") }}</span>
                        </router-link>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Address Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Supplier Number") }}:</label
                                    >
                                    <p>{{ form.supplierNumber }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Name") }}:</label
                                    >
                                    <p>{{ form.supplierName }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("URL") }}:</label
                                    >
                                    <a
                                        class="hover:text-blue-500"
                                        :href="
                                            form.url ? customerLink(form.url) : '#'
                                        "
                                        :target="
                                            customerLink(form.url) ? '_blank' : null
                                        "
                                    >
                                        {{ form.url }}</a
                                    >
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address First") }}:</label
                                    >
                                    <p>{{ form.addressFirst }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address Second") }}:</label
                                    >
                                    <p>{{ form.addressSecond }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("City") }}:</label
                                    >
                                    <p>{{ form.city }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("ZIP") }}:</label
                                    >
                                    <p>{{ form.zip }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Country") }}:</label
                                    >
                                    <p>{{ form.country }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("State") }}:</label
                                    >
                                    <p>{{ form.state }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("VAT ID") }}:</label
                                    >
                                    <p>{{ form.vatId }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Phone") }}:</label
                                    >
                                    <p>{{ form.phone }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Fax") }}:</label
                                    >
                                    <p>{{ form.fax }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Payment Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Terms Of Payment") }}:</label
                                    >
                                    <p>{{ form.termsOfPayment?.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="form.bankDetails?.length > 0"
                        class="w-full bg-white rounded-md shadow margin-bottom-3rem"
                    >

                        <div class="card my-5">
                            <div class="card-header">
                                <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                            </div>
                            <div class="card-body">
                                <div
                                    v-for="(bankDetail, index) in form.bankDetails"
                                    :key="index"
                                    class="flex flex-wrap"
                                >
                                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                                        <label class="form-label font-bold"
                                            >{{ $t("Bank Name") }}:</label
                                        >
                                        <p>{{ bankDetail.bankName }}</p>
                                    </div>
                                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                                        <label class="form-label font-bold"
                                            >{{ $t("Swift/Bic") }}:</label
                                        >
                                        <p>{{ bankDetail.swift }}</p>
                                    </div>
                                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                                        <label class="form-label font-bold"
                                            >{{ $t("IBAN") }}:</label
                                        >
                                        <p>{{ bankDetail.iban }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div
                v-else-if="activeTab === 'locations'"
                class="p-4"
                id="settings"
                role="tabpanel"
                aria-labelledby="settings-tab"
            >
                <form
                    v-for="(location, index) in locations ?? []"
                    :key="'location-' + index"
                >
                    <div class="card my-5">
                        <div class="card-header flex justify-between">
                            <h3 class="card-title">{{ $t("Location") }} {{ index + 1 }}</h3>
                            <router-link
                                class="px-4 self-center"
                                :to="
                                    location.isHeadQuarters
                                        ? `/suppliers/${supplier.id}/edit`
                                        : `/suppliers/${supplier.id}/edit?tab=locations&action=edit&id=${location.id}`
                                "
                            >
                                <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                            </router-link>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address First") }}:</label
                                    >
                                    <p>{{ location.addressFirst }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address Second") }}:</label
                                    >
                                    <p>{{ location.addressSecond }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("City") }}:</label
                                    >
                                    <p>{{ location.city }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("ZIP") }}:</label
                                    >
                                    <p>{{ location.zip }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Country") }}:</label
                                    >
                                    <p>{{ location.country }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("State") }}:</label
                                    >
                                    <p>{{ location.state }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import Pagination from "../../Components/Pagination.vue";
import { mapGetters } from "vuex";
import Quill from "quill";
import PageHeader from "@/Components/Layouts/Page-header.vue";


export default {
    mounted() {
        this.refresh();
        if(this.$route.query.page){
            this.returnPage=this.$route.query.page; 
        }
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        EditModal,
        PageHeader
    },
    computed: {
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
    },
    data() {
        return {
            currentPage: 1,
            start: 0,
            returnPage:'',
            perPage: 10,
            locations: [],
            systems: [],
            invoices: [],
            projects: [],
            reports: [],
            tickets: [],
            supplier: {},
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "supplier",
            form: {
                supplierName: "",
                supplierNumber: "",
                vatId: "",
                url: "",
                phone: "",
                fax: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/suppliers",
                },
                {
                    text: "Suppliers",
                    to: "/suppliers?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
        };
    },
    methods: {
        customerLink(incommingUrl) {
            if (incommingUrl) {
                let url = "";
                url = incommingUrl.trim();
                if (!/^https?:\/\//i.test(url)) {
                    url = `https://${url}`;
                }
                return url;
            }
            return incommingUrl;
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "suppliers/show",
                    this.$route.params.id
                );
                this.supplier = response?.data?.modelData ?? {};
                await this.$store.dispatch("termsOfPayment/list", {
                    perPage: 25,
                    page: 1,
                });
                const locationsResponse = await this.$store.dispatch(
                    "suppliers/locationsList",
                    this.$route.params.id
                );
                this.locations = locationsResponse?.data?.locations ?? [];
                let supplierFormData = {
                    id: this.supplier.id,
                    supplierName: this.supplier.supplierName,
                    supplierNumber: this.supplier.supplierNumber,
                    vatId: this.supplier.vatId,
                    url: this.supplier.url,
                    phone: this.supplier.phone,
                    fax: this.supplier.fax,
                    type: this.supplier.type,
                    termsOfPayment: "",
                    bankDetails: this.supplier.bankDetails.map((bankDetail) => {
                        return {
                            ...bankDetail,
                        };
                    }),
                };
                supplierFormData = {
                    ...supplierFormData,
                    ...(this.locations.find((location) => {
                        return location.isHeadQuarters == 1;
                    }) ?? {}),
                };
                this.form = { ...supplierFormData };
                this.form.termsOfPayment =
                    this.termsOfPayment.data.find(
                        (terms) => terms.id === this.supplier.termsOfPayment
                    ) ?? {};
            } catch (e) {}
            finally{
                this.$store.commit("showLoader", false);
            }
        },
        htmlFromText(text) {
            try {
                let article = document.createElement("article");
                let quill = new Quill(article, { readOny: true });
                quill.setContents(JSON.parse(text));
                return quill.root.innerHTML;
            } catch (err) {
                return "";
            }
        },
        getLocationString(id) {
            const location = this.locations.find(
                (location) => location.id == id
            );
            return `${location?.addressFirst ?? ""}${
                location?.addressSecond ? "," : ""
            } ${location?.addressSecond ?? ""}${location?.city ? "," : ""} ${
                location?.city ?? ""
            }${location?.zip ? "," : ""} ${location?.zip ?? ""}${
                location?.state ? "," : ""
            } ${location?.state ?? ""}${location?.country ? "," : ""} ${
                location?.country ?? ""
            }`;
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}
.link {
    text-decoration: underline;
    color: blue;
}
</style>
