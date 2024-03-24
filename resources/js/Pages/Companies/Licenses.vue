<template>
    <LicenseStatistics
        :companyId="companyId"
        :licenseTimelineStatisticsByYear="licenseTimelineStatisticsByYear"
        :netTotalInvoices="netTotalInvoices"
        :maintenanceRateTotalSum="maintenanceRateTotalSum"
        :salePriceTotalSum="salePriceTotalSum"
        :softwareStatistics="softwareStatistics"
    />
    <div class="">
        <div class="flex items-center justify-between mb-3">
            <h3 class="card-title">{{ $t("License Overview") }}</h3>

            <button
                v-if="!isShow"
                @click="openModal('add')"
                class="secondary-btn"
            >
                Add License
            </button>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="">
                        <th
                            style="width: 8.88%"
                            @click="sort('Product.articleNumber', 'licenses')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Article Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Product.articleNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('Product.name', 'licenses')"
                            style="width: 20%"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Product Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Product.name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            style="width: 8.88%"
                            class="pb-4 pt-6 px-6"
                        >
                            {{ $t("Software Type") }}
                        </th>
                        <th
                            @click="sort('quantity', 'licenses')"
                            style="width: 8.88%"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Quantity") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'quantity'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('salePrice', 'licenses')"
                            style="width: 8.88%"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Sale Price Per Unit") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'salePrice'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th style="width: 8.88%" class="pb-4 pt-6 px-6">
                            {{ $t("Sale Price") }}
                        </th>
                        <th
                            @click="
                                sort('maintenancePrice_numeric', 'licenses')
                            "
                            style="width: 8.88%"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Maintenance Per Unit") }}
                            <font-awesome-icon
                                v-if="
                                    form.sortBy === 'maintenancePrice_numeric'
                                "
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            style="width: 8.88%"
                            @click="sort('Product.maintanenceRate', 'licenses')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Maintenance Rate") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Product.maintanenceRate'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            v-if="!isShow"
                            style="width: 8.88%"
                            class="pb-4 pt-6 px-6 text-center"
                        >
                            {{ $t("Actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(license, index) in licenses.data"
                        :key="'license-' + index"
                        :style="
                            license.isEvaluation
                                ? 'background-color: rgba(237, 125, 49, 0.3)'
                                : ''
                        "
                    >
                        <td class="">
                            <p class="">
                                {{ license.articleNumber }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{ license.name }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{ license.software?.name }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{ license.quantity }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{
                                    $formatter(
                                        license.salePrice,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{
                                    $formatter(
                                        license.salePriceTotal,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{
                                    $formatter(
                                        license.maintenancePrice,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td class="">
                            <p class="">
                                {{
                                    $formatter(
                                        license.maintenancePriceTotal,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td v-if="!isShow" class="w-px text-center">
                            <button
                                class="mr-2"
                                @click.prevent="openModal('edit', index)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </button>
                            <button @click.prevent="removeLicense(license.id)">
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr
                        v-if="licenses.data.length"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="">
                            <p class=""></p>
                        </td>
                        <td class="">
                            <p class=""></p>
                        </td>
                        <td class="">
                            <p class=""></p>
                        </td>
                        <td class="">
                            <p class=""></p>
                        </td>
                        <td class="">
                            <p class="">{{ $t("Sum") }}:</p>
                        </td>
                        <td class="">
                            <p class="font-bold bg-gray-300">
                                {{
                                    $formatter(
                                        salePriceTotalSum,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td class="">
                            <p class="font-bold font-bold bg-gray-300"></p>
                        </td>
                        <td class="">
                            <p class="font-bold bg-gray-300">
                                {{
                                    $formatter(
                                        maintenanceRateTotalSum,
                                        globalLanguage
                                    )
                                }}
                            </p>
                        </td>
                        <td v-if="!isShow" class="">
                            <p class=""></p>
                        </td>
                    </tr>
                    <tr v-if="licenses.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No Licenses found.") }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <pagination
        :limit="10"
        align="center"
        :data="licenses"
        @pagination-change-page="fetchData"
    ></pagination>
    <select-product-modal
        v-if="toggleLicenseModal"
        :productType="['software', 'cloud-software', 'pauschal']"
        :isLicense="true"
        @selected="addLicenses"
        @cancelled="toggleLicenseModal = false"
        :selectedItems="[]"
        :products="products"
    ></select-product-modal>
    <EditLicenseModal
        :toggleEditModal="toggleEditModal"
        @cancel="toggleEditModal = false"
        :licenseToBeEdited="licenseToBeEdited"
        @refresh="fetchData"
    />
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SelectProductModal from "@/Components/SelectProductModal.vue";
import EditLicenseModal from "./EditLicenseModal.vue";
import LicenseStatistics from "./LicenseStatistics.vue";

export default {
    props: {
        isShow: {
            type: Boolean,
            default: false,
        },
        companyId: {
            type: String,
            default: () => "",
            required: true,
        },
    },
    data() {
        return {
            netTotalInvoices: 0,
            maintenanceRateTotalSum: 0,
            salePriceTotalSum: 0,
            softwareStatistics: [],
            licenseTimelineStatisticsByYear: {}, // contains salePrice and maintenanceRate by year and month
            page: 1,
            licenseToBeEdited: {},
            toggleLicenseModal: false,
            toggleEditModal: false,
            licenses: {
                data: [],
                links: [],
            },
            products: [],
            form: {
                sortBy: localStorage.getItem("sort_licences_column"),
                sortOrder: localStorage.getItem("sort_licences_order") ?? "asc",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    this.fetchData();
                }, 150)();
            },
        },
    },
    components: {
        Pagination,
        SelectProductModal,
        EditLicenseModal,
        LicenseStatistics,
    },
    async mounted() {
        this.fetchData();
    },
    methods: {
        async fetchLicenseStatistics() {
            try {
                const response = await this.$store.dispatch(
                    "licenses/licenseStatistics",
                    this.companyId
                );
                this.salePriceTotalSum =
                    response?.data?.data?.salePriceTotal ?? 0;
                this.maintenanceRateTotalSum =
                    response?.data?.data?.maintenancePriceTotal ?? 0;
                this.softwareStatistics =
                    response?.data?.data?.softwareTypes ?? [];
                this.netTotalInvoices =
                    response?.data?.data?.netTotalInvoices ?? 0;
                this.licenseTimelineStatisticsByYear =
                    response?.data?.data?.statisticsByYear;
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * calls the licenses store API and created licenses
         * @param {licenses} the selected licenses to be added from the modal
         */
        async addLicenses(licenses) {
            this.toggleLicenseModal = false;
            for (let i = 0; i < licenses?.length; i++) {
                const payload = {
                    companyId: this.companyId,
                    productId: licenses[i]?.id,
                    quantity: licenses[i]?.quantity,
                    salePrice: licenses[i]?.salePrice,
                    maintenancePrice: licenses[i]?.maintenancePrice,
                    isEvaluation: licenses[i]?.isEvaluation ? 1 : 0,
                    name: licenses[i]?.name ?? "",
                };
                await this.$store.dispatch("licenses/create", payload);
            }
            this.fetchData();
        },
        /**
         * opens up the modal through which a license can be added or edited
         * @param {type} type of the modal to be opened, possible values (add, edit)
         * @param {index} index of the license to be edited, can be null if the 'type' is 'add'
         */
        async openModal(type, index) {
            if (type === "add") {
                if (!this.products?.length) {
                    try {
                        const response = await this.$store.dispatch(
                            "products/productsWithQuantity",
                            {
                                type: ["software", "cloud-software"],
                            }
                        );
                        this.products = response?.data?.products ?? {
                            data: [],
                            links: [],
                        };
                    } catch (e) {
                        console.log(e);
                    }
                }
                this.toggleLicenseModal = true;
            } else if (type === "edit") {
                this.licenseToBeEdited = {
                    ...(typeof this.licenses?.data?.[index] === "object"
                        ? this.licenses.data[index]
                        : {}),
                    software:
                        this.licenses?.data?.[index]?.software?.name ?? "",
                };
                this.toggleEditModal = true;
            }
        },
        /**
         * calls the delete API for a license based on id
         * @param {id} id of the license to be deleted
         */
        async removeLicense(id) {
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
                        await this.$store.dispatch("licenses/destroy", id);
                        this.fetchData(); // reload the licenses upen deletion success
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        },
        /**
         * fetches and sets the licenses based on the companyId
         */
        async fetchData(page = 1) {
            this.page = page;
            try {
                const response = await this.$store.dispatch("licenses/list", {
                    ...this.form,
                    companyId: this.companyId,
                    page: this.page,
                });
                this.licenses = response?.data ?? this.licenses;
                // check if the data property of 'licenses' object is an array
                if (this.licenses?.data instanceof Array) {
                    // the 'salePriceTotal' and 'maintenancePriceTotal' must be calculated on the run, we are not retrieving it from the backend
                    this.licenses.data = this.licenses.data.map((license) => {
                        // compute total sum for salePriceTotal and maintenanceRateTotal
                        const salePriceTotal =
                            license.quantity * license.salePrice;
                        const maintenancePriceTotal =
                            license.quantity * license.maintenancePrice;
                        return {
                            ...license,
                            salePriceTotal: salePriceTotal,
                            maintenancePriceTotal: maintenancePriceTotal,
                        };
                    });
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.fetchLicenseStatistics();
            }
        },
    },
};
</script>

<style scoped></style>
