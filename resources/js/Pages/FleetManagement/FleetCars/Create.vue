<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div
            class="tab-header"
        >
            <ul class="">
                <li class="nav-item">
                    <a
                    class="nav-link"
                    :class="
                        activeTab === 'carDetails'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `carDetails`"
                >
                    {{ $t("Car Details") }}
                </a>
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    :class="
                        activeTab === 'contractDetails'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `contractDetails`"
                >
                    {{ $t("Contract Details") }}
                </a>
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    :class="
                        activeTab === 'documents'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `documents`"
                >
                    {{ $t("Documents") }}
                </a>
                </li>
               
               
                
            </ul>
        </div>
        <form @submit.prevent="store">
            <div>
                <div v-if="activeTab === `carDetails`">
                    <car-details
                                :form="form"
                                :errors="errors"
                                @next="
                                    () => {
                                        activeTab = `contractDetails`;
                                    }
                                "
                            />
                </div>

                <div v-else-if="activeTab === `contractDetails`">
                    <contract-details
                                :form="form"
                                :errors="errors"
                                @next="activeTab = `documents`"
                            ></contract-details>
                </div>

                <div v-else-if="activeTab === `documents`">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Documents") }}</h3>
                        </div>
                        <div class="card-body">
                            <file-inputs
                                @file-changed="addFiles"
                                :productFiles="form"
                            ></file-inputs>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <router-link to="/fleet-cars" class="primary-btn mr-3">
                            <span class="mr-1">
                                <CustomIcon name="cancelIcon" />
                            </span>
                            <span>{{ $t("Cancel") }}</span>
                        </router-link>
                        <loading-button
                            @click="store"
                            :loading="isLoading"
                            class="secondary-btn"
                        >
                            <span class="mr-1">
                                <CustomIcon name="saveIcon" />
                            </span>
                            {{ $t("Save and Proceed") }}
                        </loading-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import DateInput from "@/Components/DateInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import NumberInput from "@/Components/NumberInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import CarDetails from "../Components/CarDetails.vue";
import ContractDetails from "../Components/ContractDetails.vue";
import FileInputs from "@/Components/FileInputs.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        NumberInput,
        DateInput,
        MultiSelectInput,
        QuillTextEditor,
        CarDetails,
        ContractDetails,
        FileInputs,
        PageHeader,
    },
    watch: {
        "form.leasingStartDate": "calculateLeasingEndDate",
        "form.contractPeriodMonths": "calculateLeasingEndDate",
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fleet Cars",
                    to: "/fleet-cars",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                contactNumber: "",
                licenceNumber: "",
                vim: "",
                brand: "",
                model: "",
                fuelType: "",
                leasingRate: "",
                leasingStartDate: "",
                leasingEndDate: "",
                miles: "",
                totalMileage: "",
                status: "",
                driver: "",
                color: "",
                kilowatt: "",
                modelKey: "",
                modelType: "",
                modelDetail: "",
                extraEquipment: "",
                updatedAt: "",
                deliveryDate: "",
                carType: "",
                assetNumber: "",
                leasing: {
                    perAdditionalKilometers: 0.0,
                    freeExtraKilometers: 0.0,
                    perLessKilometers: 0.0,
                    freeLessKilometers: 0.0,
                },
                damageProtection: {
                    perAdditionalKilometers: 0.0,
                    freeExtraKilometers: 0.0,
                    perLessKilometers: 0.0,
                    freeLessKilometers: 0.0,
                },
                maintenanceAndAbstraction: {
                    perAdditionalKilometers: 0.0,
                    freeExtraKilometers: 0.0,
                    perLessKilometers: 0.0,
                    freeLessKilometers: 0.0,
                },
                isApiCalled: true,
                files: [],
            },
            activeTab: "carDetails",
            activeClasses:
                "active",
            inactiveClasses:
                "inactive",
        };
    },
    methods: {
        calculateLeasingEndDate() {
            const startDate = new Date(this.form.leasingStartDate);
            const periodMonths = parseInt(this.form.contractPeriodMonths);

            if (!isNaN(startDate) && !isNaN(periodMonths)) {
                const endDate = new Date(startDate);
                endDate.setMonth(startDate.getMonth() + periodMonths);
                this.form.leasingEndDate = endDate.toISOString().substr(0, 10);
            } else {
                this.form.leasingEndDate = null;
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "fleetCars/create",
                    {
                        ...this.form,
                        driverId: this.form.driver?.id ?? null,
                    }
                );
                if (this.form.files?.length > 0) {
                    const formData = new FormData();
                    this.form.files.forEach((file) => {
                        formData.append("uploadedFiles[]", file);
                    });
                    formData.append("id", response?.data?.id);
                    await this.$store.dispatch(
                        "fleetCars/uploadFiles",
                        formData
                    );
                }
                await this.$store.dispatch("fleetCars/list");
                this.$router.push("/fleet-cars");
            } catch (errors) {
                if (Object.keys(this.errors).length != 0) {
                    const errorKeys = Object.keys(this.errors);
                    const intersection = errorKeys.filter((value) =>
                        [
                            "licenceNumber",
                            "driverId",
                            "vim",
                            "fuelType",
                            "carType",
                            "assetNumber",
                            "color",
                            "kilowatt",
                            "brand",
                            "modelKey",
                            "model",
                            "modelDetail",
                            "extraEquipment",
                            "totalMileage",
                            "updatedAt",
                            "status",
                        ].includes(value)
                    );
                    if (intersection.length !== 0) {
                        this.activeTab = "carDetails";
                    } else {
                        this.activeTab = "contractDetails";
                    }
                }
            }
        },
        addFiles(data) {
            this.form.files = data;
        },
    },
};
</script>

<style></style>
