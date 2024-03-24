<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Edit Fleet Drivers Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full" v-if="isApiCalled">
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
                        <div
                            class="w-full"
                            v-if="isApiCalled && form.carType == 'leasing_car'"
                        >
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
                                    :isDisabled="true"
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
                                    :error="errors?.driverId"
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
                <router-link :to="`/fleet-drivers`" class="primary-btn mr-3">
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
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
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("fleetCars", {
            filteredFleetCars: "fleetCarsFilteredByDriverIdNull",
        }),
    },
    data() {
        return {
            form: {
                carType: "",
                fleetCarId: "",
                userId: "",
            },
            isApiCalled: false,
            fleetDriver: [],
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fleet Drivers",
                    to: `/fleet-drivers`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("fleetCars/list");
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            const response = await this.$store.dispatch(
                "fleetDrivers/show",
                this.$route.params.id
            );
            this.fleetDriver = response?.data ?? {};
            this.form = this.fleetDriver?.data;
            this.form.userId =
                this.users?.find((user) => user.id == this.form.userId) ?? "";

            this.isApiCalled = true;
        } catch (e) {
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("fleetDrivers/update", {
                id: this.$route.params.id,
                data: {
                    ...this.form,
                    userId: this.form?.userId?.id,
                },
            });
            await this.$store.dispatch("fleetDrivers/list");
            this.$router.push(`/fleet-drivers`);
        },
    },
};
</script>

<style></style>
