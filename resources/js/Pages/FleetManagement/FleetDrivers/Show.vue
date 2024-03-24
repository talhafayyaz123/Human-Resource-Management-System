<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="driver"
                                :label="$t('Driver')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="fleetDriver.lastCheck"
                                :label="$t('Last Checkup')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="fleetDriver.status"
                                :label="$t('Status')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th class="">
                            {{ $t("ID") }}
                        </th>
                        <th class="">
                            {{ $t("Check Type") }}
                        </th>
                        <th class="">
                            {{ $t("Licence Valid Until") }}
                        </th>
                        <th class="">
                            {{ $t("Manager") }}
                        </th>
                        <th class="">
                            {{ $t("Created At") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="driver in fleetDriver?.driverCheckList"
                        :key="driver.id"
                    >
                        <!-- Ticket content here -->

                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ driver.id }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ driver.checkType }}
                            </a>
                        </td>

                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ driver.licenceValid }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ managerName(driver.managerId) }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                <!-- {{ driver.createdAt }} -->
                                {{ $dateFormatter(driver.createdAt, globalLanguage) }}
                            </a>
                        </td>
                    </tr>

                    <tr
                        v-if="(fleetDriver?.driverCheckList?.length ?? 0) === 0"
                    >
                        <td class=" " colspan="4">
                            {{ $t("No Drivers Check List found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
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
                    text: "Show",
                    active: true,
                },
            ],
            driver: "",
            fleetDriver: [],
            manager: "",
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        const response = await this.$store.dispatch(
            "fleetLicenceCheck/show",
            this.$route.params.id
        );
        this.fleetDriver = response?.data?.data;
        this.driver = this.users.find((user) => {
            return user.id === this.fleetDriver.userId;
        });
        this.driver = this.driver?.first_name + " " + this.driver?.last_name;
        this.$store.commit("showLoader", false);
    },
    methods: {
        managerName(id) {
            const manager = this.users.find((user) => user.id === id);
            return manager ? `${manager.first_name} ${manager.last_name}` : "";
        },
    },
    components: { TextInput, MultiSelectInput, PageHeader },
};
</script>

<style></style>
