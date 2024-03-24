<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div
            class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700"
        >
            <div class="flex flex-wrap mx-auto">
                <a
                    :class="
                        activeTab === 'carDetails'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `carDetails`"
                >
                    {{ $t("Car Details") }}
                </a>
                <a
                    :class="
                        activeTab === 'contractDetails'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `contractDetails`"
                >
                    {{ $t("Contract Details") }}
                </a>
                <a
                    :class="
                        activeTab === 'uvv' ? activeClasses : inactiveClasses
                    "
                    @click="activeTab = `uvv`"
                >
                    {{ $t("UVV Details") }}
                </a>
                <a
                    :class="
                        activeTab === 'documents'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `documents`"
                >
                    {{ $t("Documents") }}
                </a>
            </div>
        </div>

        <form @submit.prevent="update">
            <div>
                <div v-if="activeTab === `carDetails`">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Car Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <car-details
                                :isEdit="true"
                                :form="form"
                                :errors="errors"
                                @next="
                                    () => {
                                        activeTab = `contractDetails`;
                                    }
                                "
                            ></car-details>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === `contractDetails`">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Contract Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <contract-details
                                :form="form"
                                :errors="errors"
                                @next="activeTab = `uvv`"
                            ></contract-details>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === `uvv`">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("UVV Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <uvv
                                :form="form.uvv"
                                :uvvData="form.uvvRecords"
                                @next="activeTab = `documents`"
                            ></uvv>
                        </div>
                    </div>
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
                        <router-link
                            :to="`/fleet-cars?page=${this.$route.query.page}`"
                            class="primary-btn mr-3"
                        >
                            <span class="mr-1">
                                <CustomIcon name="cancelIcon" />
                            </span>
                            <span>{{ $t("Cancel") }}</span>
                        </router-link>
                        <loading-button
                            @click="update"
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
import Uvv from "../Components/Uvv.vue";
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
        Uvv,
        PageHeader,
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        try {
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            const response = await this.$store.dispatch(
                "fleetCars/show",
                this.$route.params.id
            );
            document.title =
                "Fleet Cars Edit " + response?.data?.data?.licenceNumber;
            this.fleetCar = response?.data ?? {};
            this.form = this.fleetCar?.data;
            this.form.driver =
                this.users?.find((user) => user.id == this.form.driverId) ?? "";
            this.form.isApiCalled = true;
        } catch (e) {
        } finally {
            this.$store.commit("showLoader", false);
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
                    text: "Fleet Cars",
                    to: `/fleet-cars?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Fleet car"),
                btn2Path: "/fleet-cars/create",
            },
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
                    perAdditionalKilometers: "",
                    freeExtraKilometers: "",
                    perLessKilometers: "",
                    freeLessKilometers: "",
                },
                damageProtection: {
                    perAdditionalKilometers: "",
                    freeExtraKilometers: "",
                    perLessKilometers: "",
                    freeLessKilometers: "",
                },
                maintenanceAndAbstraction: {
                    perAdditionalKilometers: "",
                    freeExtraKilometers: "",
                    perLessKilometers: "",
                    freeLessKilometers: "",
                },
                uvv: {
                    date: "",
                    managerId: "",
                    nextUVV: "",
                    files: [],
                },
                isApiCalled: false,
            },
            activeTab: "carDetails",
            activeClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            inactiveClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
            fleetCar: {},
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "fleetCars/update",
                    {
                        id: this.$route.params.id,
                        data: {
                            ...this.form,
                            driverId: this.form.driver?.id ?? null,
                        },
                    }
                );

                const formData = new FormData();
                this.form.files.forEach((file, index) => {
                    if (!file.id) {
                        formData.append(`uploadedFiles[${index}]`, file);
                    } else {
                        formData.append(`uploadedFiles[${index}][id]`, file.id);
                        formData.append(
                            `uploadedFiles[${index}][name]`,
                            file.name
                        );
                        formData.append(
                            `uploadedFiles[${index}][storage_name]`,
                            file.storageName
                        );
                    }
                });
                formData.append("id", response?.data?.id);
                await this.$store.dispatch("fleetCars/updateFiles", formData);

                //form data for uvv

                const uvvFormData = new FormData();
                let uvv = this.form.uvv;

                if (uvv.date && uvv.managerId) {
                    if (uvv.files) {
                        uvv.files.forEach((file, index) => {
                            if (!file.id) {
                                uvvFormData.append(
                                    `uploadedFiles[${index}]`,
                                    file
                                );
                            }
                        });
                    }
                    uvvFormData.append("date", uvv.date);
                    uvvFormData.append("nextDate", uvv.nextUVV);
                    uvvFormData.append("managerId", uvv.managerId["id"] ?? "");
                    uvvFormData.append("id", response?.data?.id);

                    await this.$store.dispatch(
                        "fleetCars/createUVV",
                        uvvFormData
                    );
                }

                await this.$store.dispatch("fleetCars/list");
                this.$router.push(`/fleet-cars?page=${this.$route.query.page}`);
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
        addUvvFiles(data) {
            this.form.uvv.files = data;
        },
    },
};
</script>

<style></style>
