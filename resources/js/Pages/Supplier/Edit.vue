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
                <form @submit.prevent="">
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Address Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.supplierName"
                                            :error="errors.supplierName"
                                            :label="$t('Name')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.vatId"
                                            :error="errors.vatId"
                                            type="text"
                                            :label="$t('VAT ID')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.addressFirst"
                                            :error="errors.addressFirst"
                                            :label="$t('Address Line 1')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.addressSecond"
                                            :error="errors.addressSecond"
                                            :label="$t('Address Line 2')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.zip"
                                            :error="errors.zip"
                                            :label="$t('Zip')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.city"
                                            :error="errors.city"
                                            :label="$t('City')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-model="form.country"
                                            :key="form.country"
                                            :error="errors.country"
                                            :floatingLabel="true"
                                            :label="$t('Country')"
                                        >
                                            <option
                                                v-for="(
                                                    country, key
                                                ) in countries"
                                                :value="country.id"
                                                :key="key"
                                            >
                                                {{ country.name }}
                                            </option>
                                        </select-input>

                                        <!-- <text-input
                                            :required="true"
                                            v-model="form.country"
                                            :error="errors.country"
                                            class=""
                                            :label="$t('Country')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        /> -->
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.state"
                                            :error="errors.state"
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.phone"
                                            :error="errors.phone"
                                            :label="$t('Phone')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.fax"
                                            :error="errors.fax"
                                            :label="$t('Fax')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.url"
                                            :error="errors.url"
                                            :label="$t('URL')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <label
                                            for=""
                                            class="input-label form-label"
                                        ></label>
                                        <input
                                            type="file"
                                            ref="file"
                                            @change="handleFileChange"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="col-span-2 flex justify-end"
                                    v-if="!!base64Image"
                                >
                                    <img
                                        :src="
                                            'data:image\/png;base64,' +
                                            base64Image?.base64
                                        "
                                        style="
                                            width: 155px !important;
                                            height: 140px !important;
                                            margin-right: 10px;
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Payment Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div
                                    class="pb-8 pr-6 w-full lg:w-1/2"
                                    :class="[
                                        'grid',
                                        `grid-cols-[${
                                            form.termsOfPayment
                                                ? '5fr,1fr'
                                                : '100%'
                                        }]`,
                                        'gap-2',
                                    ]"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <MultiSelectInput
                                                v-model="form.termsOfPayment"
                                                :textLabel="
                                                    $t('Terms Of Payment')
                                                "
                                                :error="
                                                    errors['termsOfPayment']
                                                "
                                                :key="form.termsOfPayment"
                                                :options="termsOfPayment.data"
                                                :multiple="false"
                                                label="name"
                                                trackBy="id"
                                                moduleName="termsOfPayment"
                                            ></MultiSelectInput>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <input
                                                v-if="form.termsOfPayment"
                                                style="
                                                    width: -webkit-fill-available;
                                                "
                                                class="py-2 pl-1 border rounded mr-2"
                                                readonly
                                                type="text"
                                                :value="
                                                    form.termsOfPayment
                                                        ?.paymentTerms
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(bankDetails, index) in form.bankDetails"
                                :key="index"
                                class="my-5"
                            >
                                <div class="card p-5">
                                    <div
                                        role="button"
                                        class="w-full flex justify-end mb-3"
                                        @click="deleteBankDetails(index)"
                                    >
                                        <CustomIcon name="DeleteIcon" />
                                    </div>
                                    <div
                                        class="grid items-center grid-cols-3 gap-6"
                                    >
                                        <div class="w-full">
                                            <div class="form-group">
                                                <text-input
                                                    :required="true"
                                                    v-model="
                                                        bankDetails.bankName
                                                    "
                                                    :error="
                                                        errors[
                                                            `bankDetails.${index}.bankName`
                                                        ]
                                                    "
                                                    :label="$t('Bank Name')"
                                                    placeholder=" "
                                                    :floatingLabel="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <div class="form-group">
                                                <text-input
                                                    :required="true"
                                                    v-model="bankDetails.swift"
                                                    :label="$t('BIC/SWIFT')"
                                                    placeholder=" "
                                                    :error="
                                                        errors[
                                                            `bankDetails.${index}.swift`
                                                        ]
                                                    "
                                                    :floatingLabel="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <div class="form-group">
                                                <text-input
                                                    :required="true"
                                                    v-model="bankDetails.iban"
                                                    :error="
                                                        errors[
                                                            `bankDetails.${index}.iban`
                                                        ]
                                                    "
                                                    :label="$t('IBAN')"
                                                    placeholder=" "
                                                    :floatingLabel="true"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center flex-wrap -mr-6 pt-4"
                                :class="[
                                    form.bankDetails &&
                                    form.bankDetails.length === 0
                                        ? 'p-4'
                                        : 'p-8',
                                ]"
                            >
                                <span
                                    :class="[
                                        form.bankDetails &&
                                        form.bankDetails.length === 0
                                            ? ''
                                            : 'mb-2',
                                    ]"
                                    class="flex items-center cursor-pointer"
                                >
                                    <svg
                                        class="fill-current text-blue-600 h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                                        />
                                    </svg>
                                    <span
                                        @click="addBankDetails"
                                        class="ml-2 font-medium text-blue-600"
                                        >{{ $t("Add bank accounts") }}</span
                                    >
                                </span>
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
                <form @submit.prevent="addLocation">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Add Location Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="locationForm.addressFirst"
                                            :error="errors.addressFirst"
                                            :label="$t('Address Line 1')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="locationForm.addressSecond"
                                            :error="errors.addressSecond"
                                            :label="$t('Address Line 2')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="locationForm.city"
                                            :error="errors.city"
                                            :label="$t('City')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="locationForm.zip"
                                            :error="errors.zip"
                                            :label="$t('Zip')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-model="form.country"
                                            :key="form.country"
                                            :error="errors.country"
                                            :floatingLabel="true"
                                            :label="$t('Country')"
                                        >
                                            <option
                                                v-for="(
                                                    country, key
                                                ) in countries"
                                                :value="country.id"
                                                :key="key"
                                            >
                                                {{ country.name }}
                                            </option>
                                        </select-input>
                                    </div>

                                    <!-- <text-input
                                            :required="true"
                                            v-model="form.country"
                                            :error="errors.country"
                                            class=""
                                            :label="$t('Country')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        /> -->
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="locationForm.state"
                                            :error="errors.state"
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex flex-row-reverse my-5">
                        <loading-button
                            :loading="isLoading"
                            class="btn-green"
                            >{{ $t("Add Location") }}</loading-button
                        >
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 1") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 2") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("City") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Country") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("State") }}</th>
                            <th class="pb-4 pt-6 px-6 text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(location, index) in locations.data"
                            :key="'location-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.addressFirst }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.addressSecond }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.city }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.countryName }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.state }}
                                </p>
                            </td>
                            <td
                                v-if="location.isHeadQuarters == 0"
                                class="w-px border-t text-center"
                            >
                                <button
                                    class="mr-2"
                                    @click.prevent="
                                        openEditLocationModal(index)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </button>
                                <button
                                    @click.prevent="removeLocation(location.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="locations.data.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">
                                {{ $t("No Locations found.") }}
                            </td>
                        </tr>
                    </table>
                </div>
                <pagination
                    :limit="10"
                    align="center"
                    :data="locations"
                ></pagination>
            </div>
        </div>
        <br />
        <div
            :class="`flex items-center justify-between px-8 py-4 bg-gray-50 border-t border-gray-100`"
        >
            <button
                v-if="activeTab === 'supplier'"
                @click="$router.push('/suppliers?page=' + returnPage)"
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
                @click="next"
                :loading="isLoading"
                class="secondary-btn"
                >{{
                    activeTab === "locations"
                        ? "Finish"
                        : activeTab === "supplier"
                        ? $t("Save and Proceed")
                        : $("Next")
                }}
            </loading-button>
        </div>
        <EditModal
            :title="$t('Edit Location')"
            v-if="toggleEditLocationModal"
            :classSize="'modal-md'"
        >
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressFirst"
                                :label="$t('Address Line 1')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressSecond"
                                :label="$t('Address Line 2')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.city"
                                :label="$t('City')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.zip"
                                :label="$t('Zip')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.country"
                                :label="$t('Country')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.state"
                                :label="$t('State')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button
                    @click="onCancel"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveLocation"
                >
                    {{ $t("Edit") }}
                </loading-button>
            </template>
        </EditModal>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import Pagination from "laravel-vue-pagination";
import CustomPagination from "../../Components/Pagination.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        EditModal,
        MultiSelectInput,
        CustomPagination,
        PageHeader,
    },
    async mounted() {
        await this.refresh();
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        const countries = await this.$store.dispatch(
            "countries/getAllCountries"
        );
        this.countries = countries?.data?.data;
    },
    data() {
        return {
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
                    to: "/suppliers?page=" + this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            currentPage: 1,
            returnPage: "",
            start: 0,
            perPage: 10,
            supplier: {},
            locationEditTemp: {}, //used for v-model in location edit modal
            toggleEditLocationModal: false,
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "supplier",
            locations: {
                data: [],
                links: [],
            },
            form: {
                supplierName: "",
                supplierNumber: "0",
                vatId: "",
                phone: "",
                url: "",
                fax: "",
                termsOfPayment: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                bankDetails: [],
            },
            locationForm: {
                supplierId: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            },
            countries: "",
            selectedFile: null,
            base64Image: null,
        };
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
                this.convertToBase64(file);
            }
        },
        convertToBase64(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                const requiredData = e.target?.result?.split(",");
                this.base64Image = {
                    name: file.name,
                    size: file.size,
                    base64: requiredData[1],
                };
            };
            reader.readAsDataURL(file);
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("termsOfPayment/list", {
                    perPage: 25,
                    page: 1,
                });
                const response = await this.$store.dispatch(
                    "suppliers/show",
                    this.$route.params.id
                );
                this.supplier = response?.data?.modelData ?? {};
                const attachment = this.supplier?.image;
                if (!!attachment) {
                    this.base64Image = {
                        name: attachment.viewableName,
                        base64: attachment?.url?.split(",")[1],
                    };
                }
                const locationsResponse = await this.$store.dispatch(
                    "suppliers/locationsList",
                    this.$route.params.id
                );
                this.locations.data = locationsResponse?.data?.locations ?? [];
                let supplierFormData = { ...this.supplier };
                supplierFormData = {
                    ...supplierFormData,
                    ...(this.locations.data.find((location) => {
                        return location.isHeadQuarters == 1;
                    }) ?? {}),
                };
                this.form = { ...supplierFormData };
                this.form.termsOfPayment =
                    this.termsOfPayment.data.find(
                        (terms) => terms.id === this.form.termsOfPayment
                    ) ?? {};
                document.title = "Edit Suppliers " + this.form?.supplierNumber;
                this.activeTab = this.$route.query.tab ?? "supplier";
                const id = this.$route.query.id ?? "";
                if (id && this.activeTab == "locations") {
                    const index = this.locations.data
                        .map((location) => location.id)
                        .indexOf(+id);
                    if (index >= 0) this.openEditLocationModal(index);
                }
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        openEditLocationModal(index) {
            this.locationEditTemp = { ...this.locations.data[index] };
            this.toggleEditLocationModal = true;
        },
        onCancel() {
            this.toggleEditLocationModal = false;
        },
        async fetchLocations() {
            try {
                const response = await this.$store.dispatch(
                    "suppliers/locationsList",
                    this.$route.params.id
                );
                this.locations.data = response?.data?.locations ?? [];
            } catch (e) {}
        },
        back() {
            if (this.activeTab === "locations") {
                this.activeTab = "supplier";
            }
        },
        async next() {
            if (this.activeTab === "supplier") {
                this.$store.commit("isLoading", true);
                if (this.$route.params.id) {
                    try {
                        await this.$store.dispatch("suppliers/update", {
                            id: this.$route.params.id,
                            data: {
                                ...this.form,
                                attachment: this.base64Image,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                location_id: this.locations.data?.[0].id,
                            },
                        });
                        this.activeTab = "locations";
                        this.fetchLocations();
                    } catch (e) {}
                } else {
                    try {
                        const response = await this.$store.dispatch(
                            "suppliers/create",
                            {
                                ...this.form,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                            }
                        );
                        this.activeTab = "locations";
                        this.fetchLocations();
                    } catch (e) {}
                }
            } else if (this.activeTab === "locations") {
                window.location.replace("/suppliers?page=" + this.returnPage);
            }
        },
        async removeLocation(id) {
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
                    try {
                        await this.$store.dispatch(
                            "suppliers/locationsDelete",
                            id
                        );
                        this.fetchLocations();
                    } catch (e) {}
                }
            });
        },
        async addLocation() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch("suppliers/locationsCreate", {
                    ...this.locationForm,
                    supplierId: this.$route.params.id,
                });
                this.fetchLocations();
            } catch (e) {}
            this.locationForm = {
                supplierId: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            };
        },
        async saveLocation() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch("suppliers/locationsUpdate", {
                    id: this.locationEditTemp.id,
                    data: {
                        ...this.locationEditTemp,
                        supplierId: this.$route.params.id,
                    },
                });
                this.fetchLocations();
            } catch (e) {}
            this.locationEditTemp = {};
            this.toggleEditLocationModal = false;
        },
        deleteBankDetails(index) {
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
                    this.form.bankDetails.splice(index, 1);
                }
            });
        },
        addBankDetails() {
            this.form.bankDetails.push({
                bankName: "",
                swift: "",
                iban: "",
            });
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}
</style>
