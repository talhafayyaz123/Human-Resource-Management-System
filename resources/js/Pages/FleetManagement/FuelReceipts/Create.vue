<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Enter Fuel Receipt Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-if="isApiCalled"
                                    :required="true"
                                    :textLabel="$t('Select a fleet car')"
                                    v-model="form.fleetCar"
                                    :options="fleetCars?.data"
                                    :multiple="false"
                                    label="licenceNumber"
                                    trackBy="id"
                                    moduleName="fleetCars"
                                    :error="errors.fleetCarId"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-if="isApiCalled"
                                    v-model="form.manager"
                                    :showLabels="false"
                                    :options="users"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Manager')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors.managerId"
                                    :isDisabled="true"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <p>
                                            {{ props.option.first_name }}
                                            {{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
                                        >
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.first_name }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.last_name }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.product"
                                    class=""
                                    :label="$t('Produkt')"
                                    :error="errors?.product ?? ''"
                                >
                                    <!-- :error="form.errors.subType" -->
                                    <option value="diesel">
                                        {{ $t("Diesel") }}
                                    </option>
                                    <option value="electronic">
                                        {{ $t("Electronic") }}
                                    </option>
                                    <option value="gasoline">
                                        {{ $t("Gasoline") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.actualMileage"
                                    :error="errors?.actualMileage"
                                    class=""
                                    :label="$t('Actual Mileage')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <date-input
                                    v-model="form.deliveryDate"
                                    :error="errors?.deliveryDate"
                                    class=""
                                    :label="$t('Delivery date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.quantity"
                                    class=""
                                    :label="$t('Quantity')"
                                    type="number"
                                    :showPrefix="false"
                                    placeholder=" "
                                    :error="errors.quantity"
                                    :required="true"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.unit"
                                    :error="errors?.unit"
                                    class=""
                                    :label="$t('Unit')"
                                    :isReadonly="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.pricePerUnit"
                                    class=""
                                    :label="$t('Price per unit')"
                                    type="number"
                                    placeholder=" "
                                    :error="errors.pricePerUnit"
                                    :required="true"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.totalNetto"
                                    class=""
                                    :label="$t('Total netto')"
                                    type="number"
                                    placeholder=" "
                                    :error="errors.totalNetto"
                                    :required="true"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.tax"
                                    class=""
                                    :label="$t('Tax')"
                                    type="number"
                                    placeholder=" "
                                    :error="errors.tax"
                                    :required="true"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.totalBrutto"
                                    class=""
                                    :label="$t('Total brutto')"
                                    type="number"
                                    placeholder=" "
                                    :error="errors.totalBrutto"
                                    :required="true"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/fuel-monitoring" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import DateInput from "@/Components/DateInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        MultiSelectInput,
        SelectInput,
        TextInput,
        DateInput,
        NumberInput,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("fleetCars", {
            fleetCars: "fleetCars",
        }),
        ...mapGetters("auth", ["user"]),
    },
    async mounted() {
        await this.$store.dispatch("fleetCars/list");
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        this.form.manager = this.users.find((user) => user.id == this.user.id);
        this.isApiCalled = true;
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fuel Receipts",
                    to: "/fuel-monitoring",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            isApiCalled: false,
            form: {
                actualMileage: "",
                fleetCar: "",
                manager: "",
                product: "",
                quantity: "",
                deliveryDate: "",
                pricePerUnit: "",
                totalNetto: "",
                totalBrutto: "",
                tax: "",
                unit: "Liter",
            },
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async store() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("fuelReceipts/create", {
                ...this.form,
                managerId: this.form.manager?.id ?? null,
                fleetCarId: this.form.fleetCar?.id ?? null,
            });
            await this.$store.dispatch("fuelMonitoring/list");
            this.$router.push("/fuel-monitoring");
        },
    },
};
</script>

<style></style>
