<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Enter Fleet Drivers Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.carType"
                                    class=""
                                    :label="$t('Car Type')"
                                    :error="errors?.carType ?? ''"
                                >
                                    <!-- :error="form.errors.subType" -->
                                    <option value="rent_car">
                                        {{ $t("Rent Car") }}
                                    </option>
                                    <option value="pool_car">
                                        {{ $t("Pool Car") }}
                                    </option>
                                    <option value="leasing_car">
                                        {{ $t("Leasing Car") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full" v-if="
                                        isApiCalled &&
                                        form.carType == 'leasing_car'
                                    ">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    
                                    :required="true"
                                    :textLabel="$t('Select a fleet car')"
                                    v-model="form.fleetCarId"
                                    :options="filteredFleetCars"
                                    :multiple="true"
                                    label="licenceNumber"
                                    trackBy="id"
                                    moduleName="fleetCars"
                                    :error="errors?.fleetCarId"
                                />
                            </div>
                        </div>
                        <div class="w-full" v-if="isApiCalled">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    
                                    v-model="form.userId"
                                    :showLabels="false"
                                    :options="users"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Driver')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors?.userId"
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
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/fleet-drivers" class="primary-btn mr-3">
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
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: { SelectInput, MultiSelectInput, LoadingButton, PageHeader },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fleet Drivers",
                    to: "/fleet-drivers",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                carType: "",
                fleetCarId: "",
                userId: "",
            },
            isApiCalled: false,
        };
    },
    methods: {
        async store() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("fleetDrivers/create", {
                ...this.form,
                userId: this.form.userId?.id ?? null,
                fleetCarId: this.form.fleetCarId ?? null,
            });
            await this.$store.dispatch("fleetDrivers/list");
            this.$router.push("/fleet-drivers");
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("fleetCars", {
            filteredFleetCars: "fleetCarsFilteredByDriverIdNull",
        }),
        ...mapGetters("auth", {
            users: "users",
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
        this.isApiCalled = true;
    },
};
</script>

<style></style>
