<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Add Driver Licence Check Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="isApiCalled"
                                    :required="true"
                                    class=""
                                    :textLabel="`Drivers`"
                                    v-model="form.userId"
                                    :options="fleetDrivers?.data"
                                    :multiple="false"
                                    label="name"
                                    trackBy="id"
                                    moduleName="fleetDrivers"
                                    :error="errors?.driverId"
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
                                    :error="errors?.managerId"
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
                                <MultiSelectInput
                                    v-if="isApiCalled"
                                    :required="true"
                                    class=""
                                    :textLabel="`Vehicle Classes`"
                                    v-model="form.vehicleClasses"
                                    :options="vehicleClasses?.data"
                                    :multiple="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="sources"
                                    :error="errors?.vehicleClasses"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.checkType"
                                    class=""
                                    :label="$t('Check Type')"
                                    :error="errors?.checkType ?? ''"
                                >
                                    <option value="online">
                                        {{ $t("Online Meeting") }}
                                    </option>
                                    <option value="personal">
                                        {{ $t("Personal Meeting") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <date-input
                                    v-model="form.licenceValidUntil"
                                    :error="errors.licenceValidUntil"
                                    class=""
                                    :label="$t('Licence valid date')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <date-input
                                    v-model="form.createdAt"
                                    :error="errors.createdAt"
                                    class=""
                                    :label="$t('Last license check')"
                                    :required="true"
                                />
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
                <loading-button :loading="isLoading" class="secondary-btn">
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
import DateInput from "@/Components/DateInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        SelectInput,
        MultiSelectInput,
        LoadingButton,
        DateInput,
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
                    text: "Fleet Drivers",
                    to: "/fleet-drivers",
                },
                {
                    text: "Driver Licence Check",
                    active: true,
                },
            ],
            form: {
                userId: "",
                manager: "",
                checkType: "",
                licenceValidUntil: "",
                createdAt: new Date().toISOString().substr(0, 10),
                vehicleClasses: [],
            },
            isApiCalled: false,
            vehicleClasses: [],
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("fleetDrivers", {
            fleetDrivers: "fleetDrivers",
        }),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("auth", ["user"]),
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },

        async store() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("fleetLicenceCheck/create", {
                ...this.form,
                fleetDriverId: this.form.userId?.id ?? null,
                managerId: this.form.manager?.id ?? null,
            });

            await this.$store.dispatch("fleetDrivers/list");
            this.$router.push("/fleet-drivers");
        },
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        await this.$store.dispatch("fleetDrivers/list");
        const response = await this.$store.dispatch(
            "fleetDrivers/vehicleClasses"
        );
        this.vehicleClasses = response?.data;
        this.form.manager = this.users.find((user) => user.id == this.user.id);
        this.isApiCalled = true;
    },
};
</script>

<style></style>
