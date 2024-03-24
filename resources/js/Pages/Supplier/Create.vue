<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul class="">
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
                        @click="supplier_id && (activeTab = 'locations')"
                        :class="
                            activeTab === 'locations'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (supplier_id ? '' : 'disabled')
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
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <form @submit.prevent="">
                    <div class="card">
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
                                            :label="$t('URL')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <label for="" class="input-label form-label"></label>
                                        <input type="file" ref="file"  @change="handleFileChange" class="form-control">
                                    </div>
                                </div>
                                <div class="col-span-2 flex justify-end" v-if="!!base64Image">
                                    <img  :src="'data:image\/png;base64,'+base64Image?.base64" style="width: 155px !important; height: 140px !important; margin-right: 10px">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Payment Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-3 gap-6">
                                <div class="w-full col-span-2">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.termsOfPayment"
                                            :textLabel="$t('Terms Of Payment')"
                                            :error="errors['termsOfPayment']"
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
                                            class="form-control"
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
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(bankDetails, index) in form.bankDetails"
                                :key="index"
                                class="card mb-3 p-3 pt-8 relative"
                            >
                                <div
                                    role="button"
                                    class="absolute top-2 right-2 z-10"
                                    @click="form.bankDetails.splice(index, 1)"
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
                                                v-model="bankDetails.bankName"
                                                class=""
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
                                                class=""
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
                                                class=""
                                                :label="$t('IBAN')"
                                                placeholder=" "
                                                :floatingLabel="true"
                                            />
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            v-model="locationForm.state"
                                            :error="errors.state"
                                            class=""
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="flex items-center justify-end mt-4">
                    <loading-button
                        :loading="isLoading"
                        class="secondary-btn"
                        >{{ $t("Add Location") }}</loading-button
                    >
                </div>
                <div class="table-responsive mt-3">
                    <table class="doc-table">
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
                                    {{ location.country }}
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
        <div :class="`flex items-center justify-end`">
            <button
                v-if="activeTab === 'supplier'"
                @click="$router.push('/suppliers')"
                class="primary-btn mr-3"
            >
            <div class="mr-1">
                <CustomIcon name="cancelIcon" />
            </div>
                {{ $t("Cancel") }}
            </button>
            <loading-button
                v-else
                @click="back"
                :loading="isLoading"
                class="primary-btn mr-3"
                >
                <div class="mr-1">
                <CustomIcon name="backIcon" />
            </div>{{ $t("Back") }}
            </loading-button>
            <loading-button
                @click="next"
                :loading="isLoading"
                class="secondary-btn"
                >
                <div class="mr-1">
                <CustomIcon name="saveIcon" />
            </div>
                {{
                    activeTab === "locations"
                        ? "Finish"
                        : activeTab === "supplier"
                        ? $t("Save and Proceed")
                        : $t("Next")
                }}
            </loading-button>
        </div>
        <EditModal :title="$t('Edit Location')" v-if="toggleEditLocationModal">
            <template #body>
                <div class="flex flex-wrap">
                    <text-input
                        v-model="locationEditTemp.addressFirst"
                        class=""
                        :label="$t('Address Line 1')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                    <text-input
                        v-model="locationEditTemp.addressSecond"
                        class=""
                        :label="$t('Address Line 2')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                    <text-input
                        v-model="locationEditTemp.city"
                        class=""
                        :label="$t('City')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                    <text-input
                        v-model="locationEditTemp.zip"
                        class=""
                        :label="$t('Zip')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                    <text-input
                        v-model="locationEditTemp.country"
                        class=""
                        :label="$t('Country')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                    <text-input
                        v-model="locationEditTemp.state"
                        class=""
                        :label="$t('State')"
                        placeholder=" "
                        :floatingLabel="true"
                    />
                </div>
            </template>
            <template #footer>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveLocation"
                >
                    {{ $t("Edit") }}
                </loading-button>
                <button
                    @click="onCancel"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
            </template>
        </EditModal>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import Pagination from "laravel-vue-pagination";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import CustomPagination from "../../Components/Pagination.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
    },
    components: {
        CustomPagination,
        LoadingButton,
        SelectInput,
        TextInput,
        EditModal,
        MultiSelectInput,
        PageHeader,
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
                    to: "/suppliers",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            currentPage: 1,
            start: 0,
            perPage: 10,
            supplier_id: "",
            locationEditTemp: {}, //used for v-model in location edit modal
            toggleEditLocationModal: false,
            activeClasses: "active",
            inactiveClasses: "in-active",
            activeTab: "supplier",
            locations: {
                data: [],
                links: [],
            },
            form: {
                supplierName: "",
                supplierNumber: "0",
                vatId: "",
                url: "",
                fax: "",
                phone: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                termsOfPayment: "",
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
            countries : "",
            selectedFile: null,
            base64Image: null
        };
    },
    async mounted() {
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
         const countries = await this.$store.dispatch(
            "countries/getAllCountries"
        );
        this.countries = countries?.data?.data;
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
                const requiredData = e.target.result.split(",");
                this.base64Image = {
                    name: file.name,
                    size: file.size,
                    base64: requiredData[1],
                };
            };
            reader.readAsDataURL(file);
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
                    this.supplier_id
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
            this.$store.commit("isLoading");
            if (this.activeTab === "supplier") {
                if (this.supplier_id) {
                    try {
                        await this.$store.dispatch("suppliers/update", {
                            id: this.supplier_id,
                            data: {
                                ...this.form,
                                attachment: this.base64Image,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                location_id: this.locations.data?.[0].id,
                            },
                        });
                        this.activeTab = "locations";
                        await this.fetchLocations();
                    } catch (e) {}
                } else {
                    try {
                        const response = await this.$store.dispatch(
                            "suppliers/create",
                            {
                                ...this.form,
                                attachment: this.base64Image,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                            }
                        );
                        this.supplier_id = response.data.supplier_id;
                        this.activeTab = "locations";
                        await this.fetchLocations();
                    } catch (e) {}
                }
            } else if (this.activeTab === "locations") {
                window.location.replace("/suppliers");
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
            this.$store.commit("isLoading");
            try {
                await this.$store.dispatch("suppliers/locationsCreate", {
                    ...this.locationForm,
                    supplierId: this.supplier_id,
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
        addBankDetails() {
            this.form.bankDetails.push({
                bankName: "",
                swift: "",
                iban: "",
            });
        },
        async saveLocation() {
            this.$store.commit("isLoading");
            try {
                await this.$store.dispatch("suppliers/locationsUpdate", {
                    id: this.locationEditTemp.id,
                    data: {
                        ...this.locationEditTemp,
                        supplierId: this.supplier_id,
                    },
                });
                this.fetchLocations();
            } catch (e) {}
            this.locationEditTemp = {};
            this.toggleEditLocationModal = false;
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>
