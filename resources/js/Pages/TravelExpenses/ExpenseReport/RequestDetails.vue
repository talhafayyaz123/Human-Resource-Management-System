<template>
    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
        {{ $t("User Details") }}
    </h6>
    <div class="bg-white rounded-md shadow margin-bottom-3rem">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Personal Number") }}</span
                >:
                <span>{{ personalNumber ?? $t("N/A") }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Name") }}</span
                >:
                <span>{{ user.first_name + " " + user.last_name }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Email") }}</span
                >: <span>{{ user.email }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Travel No") }}</span
                >: <span>{{ $t("N/A") }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Company") }}</span
                >:
                <span>{{ company?.companyName ?? $t("N/A") }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Team") }}</span
                >: <span>{{ teamName }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Department") }}</span
                >: <span>{{ departmentName }}</span>
            </div>
            <div v-if="location" class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Location") }}</span
                >: <span>{{ getLocationDetails(location) }}</span>
            </div>
        </div>
    </div>
    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
        {{ $t("Travel Expense Details") }}
    </h6>
    <div class="bg-white rounded-md shadow margin-bottom-3rem">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Departure City") }}</span
                >:
                <span>{{
                    travelExpenseDetail?.departureCity ?? $t("N/A")
                }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Arrival Country") }}</span
                >:
                <span>{{ travelExpenseDetail?.arrivalCity ?? $t("N/A") }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Arrival City") }}</span
                >:
                <span>{{ travelExpenseDetail?.arrivalCity ?? $t("N/A") }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Arrival Country") }}</span
                >:
                <span>{{
                    travelExpenseDetail?.arrivalCountryName ?? $t("N/A")
                }}</span>
            </div>
            <div class="pb-8 pr-6 w-full lg:w-1/2">
                <span class="font-bold">{{ $t("Request Type:") }}</span
                >:
                <span>{{
                    travelExpenseDetail?.requestTypeName ?? $t("N/A")
                }}</span>
            </div>
            <div class="pb-8 pr-6 w-full">
                <span class="font-bold">{{ $t("Description:") }}</span
                >:
                <span>{{ travelExpenseDetail?.description ?? $t("N/A") }}</span>
            </div>
        </div>
        <div class="bg-white rounded-md shadow margin-bottom-3rem">
            <div class="flex flex-wrap">
                <div class="p-8 w-1/2 rounded-md shadow">
                    <h6 class="text-xl font-bold mt-0 mb-2">
                        {{ $t("From Date and Time information") }}
                    </h6>
                    <!-- Date/time information from where the travel begins -->
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Date") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.fromDate ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Departure Time") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.fromDepartureTime ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Arrival Time") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.fromArrivalTime ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Discrepancy") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.fromDiscrepancy ?? $t("N/A")
                        }}</span>
                    </div>
                </div>
                <div class="p-8 w-1/2 rounded-md shadow">
                    <h6 class="text-xl font-bold mt-0 mb-2">
                        {{ $t("To Date and Time information") }}
                    </h6>
                    <!-- Date/time information to where the travel ends -->
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Date") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.toDate ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Departure Time") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.toDepartureTime ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Arrival Time") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.toArrivalTime ?? $t("N/A")
                        }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Discrepancy") }}</span
                        >:
                        <span>{{
                            travelExpenseDetail?.toDiscrepancy ?? $t("N/A")
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
        {{ $t("Bill Details") }}
    </h6>
    <div class="bg-white rounded-md shadow margin-bottom-3rem">
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Invoice Type") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Location") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("From Date") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("To Date") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Description") }}
                </th>
            </tr>
            <template v-for="item in bills" :key="item.id">
                <tr class="focus-within:bg-gray-100">
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.invoiceTypeId?.name ?? "" }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.location }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(item.fromDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(item.toDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.description }}
                        </a>
                    </td>
                </tr>
            </template>
            <tr v-if="bills.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                    {{ $t("No bill details found") }}.
                </td>
            </tr>
        </table>
    </div>
    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
        {{ $t("Private Transportation Details") }}
    </h6>
    <div class="bg-white rounded-md shadow margin-bottom-3rem">
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("License Number") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Driven Kilometers") }}
                </th>
            </tr>
            <template v-for="item in privateTransportations" :key="item.id">
                <tr class="focus-within:bg-gray-100">
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.licenseNumber }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.drivenKilometers }}
                        </a>
                    </td>
                </tr>
            </template>
            <tr v-if="bills.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                    {{ $t("No transportation details found") }}.
                </td>
            </tr>
        </table>
    </div>

    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
        {{ $t("Day Specifications Details") }}
    </h6>
    <div class="bg-white rounded-md shadow margin-bottom-3rem">
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Date") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Is Active") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("From Time") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("To Time") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Breakfast") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Lunch") }}
                </th>
                <th class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Dinner") }}
                </th>
            </tr>
            <template v-for="item in daySpecifications" :key="item.id">
                <tr class="focus-within:bg-gray-100">
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(item.date, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.isActive == 1 }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.fromTime ?? "-" }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.toTime ?? "-" }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.breakfast == 1 }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.lunch == 1 }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.dinner == 1 }}
                        </a>
                    </td>
                </tr>
            </template>
            <tr v-if="bills.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                    {{ $t("No transportation details found") }}.
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    props: ["travelExpenseDetail"],
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters("travelExpenseBills", ["bills"]),
        ...mapGetters("travelExpenseDaySpecifications", ["daySpecifications"]),
        ...mapGetters("travelExpensePrivateTransportations", [
            "privateTransportations",
        ]),
        teamName() {
            const teams = this.profileResponse?.data?.data?.teams || [];
            const uniqueTeamNames = [
                ...new Set(teams.map((team) => team.name)),
            ];
            return uniqueTeamNames.join(", ");
        },
        departmentName() {
            const teams = this.profileResponse?.data?.data?.teams || [];
            const uniqueDepartmentNames = [
                ...new Set(
                    teams
                        .filter((team) => team.department !== null) // Filter out teams with null department
                        .map((team) => team.department.name)
                ),
            ];
            return uniqueDepartmentNames.join(", ");
        },
        personalNumber() {
            const personalNumber =
                this.profileResponse?.data?.data?.personalNumber || "";
            return personalNumber;
        },
        location() {
            return this.profileResponse?.data?.data?.location || null;
        },
    },
    components: {},
    data() {
        return {
            company: null,
            profileResponse: null, // Assign the profile response dataset here
        };
    },
    mounted() {
        this.refresh();
    },
    watch: {
        async user() {
            // fetch user job data when the user is set
            if (!this.profileResponse) {
                this.profileResponse = await this.$store.dispatch(
                    "userProfile/showJobByUser",
                    this.user.id
                );
            }
        },
    },
    methods: {
        async refresh() {
            try {
                // Fetch the profile response and assign it to the data property
                if (this.user.id) {
                    this.profileResponse = await this.$store.dispatch(
                        "userProfile/showJobByUser",
                        this.user.id
                    );
                }
            } catch (e) {
            } finally {
                this.isLoaded = true;
            }
        },
        /**
         * Retrieves and formats location details
         * @param {Object} location - The location object
         * @returns {string} The formatted location details
         */
        getLocationDetails(location) {
            const address = location.address || "";
            const street = location.street || "";
            const city = location.city || "";
            const state = location.state || "";
            const country = location.country || "";

            const locationDetails = [address, street, city, state, country]
                .filter((value) => value) // Filter out empty values
                .join(", ");

            return locationDetails;
        },
    },
};
</script>
