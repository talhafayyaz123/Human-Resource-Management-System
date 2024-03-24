<template>
    <div>
        <PageHeader :items="breadcrumbItems">
            <template #filter>
                <div id="ams-ticket-filter"></div>
            </template>
        </PageHeader>
        <div
            class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700"
        >
            <div class="flex flex-wrap mx-auto">
                <a
                    :class="
                        activeTab === 'detail' ? activeClasses : inactiveClasses
                    "
                    @click="activeTab = `detail`"
                >
                    {{ $t("Ams Details") }}
                </a>
                <a
                    :class="
                        activeTab === 'account'
                            ? activeClasses
                            : inactiveClasses
                    "
                    @click="activeTab = `account`"
                >
                    {{ $t("Time Account") }}
                </a>
            </div>
        </div>
        <div v-if="activeTab === 'detail'">
            <div class="w-full bg-white rounded-md shadow margin-bottom-3rem">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        :required="true"
                        class="pb-8 pr-6 w-1/5"
                        :label="$t('Company')"
                        :isReadonly="true"
                        v-model="form.customer"
                    />
                    <text-input
                        :required="true"
                        class="pb-8 pr-6 w-1/5"
                        :label="$t('Software')"
                        :isReadonly="true"
                        v-model="form.software"
                    />
                    <number-input
                        :showPrefix="false"
                        :required="true"
                        class="pb-8 pr-6 w-1/5"
                        :label="$t('Service hour years')"
                        :isReadonly="true"
                        v-model="form.serviceHours"
                    />
                    <number-input
                        :showPrefix="false"
                        :required="true"
                        class="pb-8 pr-6 w-1/5"
                        :label="$t('Hourly Rate')"
                        :isReadonly="true"
                        v-model="form.hourlyRate"
                    />
                    <number-input
                        :showPrefix="false"
                        :required="true"
                        class="pb-8 pr-6 w-1/5"
                        :label="$t('Monthly Amount')"
                        :isReadonly="true"
                        v-model="form.monthlyAmount"
                    />
                    <!-- :error="form.errors.subject" -->
                </div>
                <br />
                <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <number-input
                        :showPrefix="false"
                        :required="true"
                        class="pr-6 w-1/5"
                        :label="$t('Remaining Hours')"
                        :isReadonly="true"
                        v-model="form.remainingHours"
                    />
                </div>
            </div>
            <h1 class="header mb-8 text-3xl font-bold secondary-color">
                {{ $t("Outbounded Contracts") }}
            </h1>
            <div class="bg-white rounded-md shadow">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-4">
                            {{ $t("Contract Number") }}
                        </th>
                        <th class="pb-4 pt-6 px-4">
                            {{ $t("Customer Name") }}
                        </th>
                        <th class="pb-4 pt-6 px-4">
                            {{ $t("Contract Type") }}
                        </th>
                        <th class="pb-4 pt-6 px-4">
                            {{ $t("Create Date") }}
                        </th>
                        <th class="pb-4 pt-6 px-4">
                            {{ $t("Signed Date") }}
                        </th>
                        <th class="">{{ $t("Actions") }}</th>
                    </tr>
                    <template
                        v-for="contract in form.outboundedContracts"
                        :key="contract.id"
                    >
                        <tr>
                            <td class="border-t">
                                <a href="javascript:void(0)" class="px-4 py-4">
                                    <font-awesome-icon
                                        class="cursor-pointer text-xs text-gray-600 mr-2"
                                        @click="
                                            contract.expanded =
                                                !contract.expanded
                                        "
                                        :icon="[
                                            'fa-solid',
                                            contract.expanded
                                                ? 'fa-chevron-down'
                                                : 'fa-chevron-right',
                                        ]"
                                    />
                                    {{ contract.contractNumber }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-4 py-4"
                                >
                                    {{ contract.customer?.companyName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-4 py-4"
                                >
                                    {{ contract.contractType?.name }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-4 py-4"
                                >
                                    {{
                                        $dateFormatter(
                                            contract.createdAt,
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-4 py-4"
                                >
                                </a>
                            </td>
                            <td class="w-px text-center">
                                <div class="flex items-center gap-3">
                                    <router-link
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.show`
                                            )
                                        "
                                        :to="`/outbounded-contracts/${contract.id}`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                        />
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                        <!-- show the children of contracts when expanded is set to true -->
                        <template
                            v-if="contract.expanded"
                            v-for="software in contract.expanded
                                ? contract.children
                                : []"
                            :key="software.id"
                        >
                            <tr>
                                <td class="border-t pl-5">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        <font-awesome-icon
                                            v-if="!!contract.id"
                                            class="cursor-pointer text-xs text-gray-600 mr-2"
                                            @click="
                                                software.expanded =
                                                    !software.expanded
                                            "
                                            :icon="[
                                                'fa-solid',
                                                software.expanded
                                                    ? 'fa-chevron-down'
                                                    : 'fa-chevron-right',
                                            ]"
                                        />
                                        {{ software.name }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="w-px border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                            </tr>
                            <tr
                                v-if="software?.expanded"
                                class="text-left font-bold"
                            >
                                <th class="pb-4 px-4">
                                    {{ $t("Contract Number") }}
                                </th>
                                <th class="pb-4 px-4">
                                    {{ $t("Contract Category") }}
                                </th>
                                <th class="pb-4 px-4">
                                    {{ $t("Price") }}/{{ $t("Month") }}
                                </th>
                                <th class="pb-4 px-4">
                                    {{ $t("Price") }}/{{ $t("Year") }}
                                </th>
                                <th class="pb-4 px-4">
                                    {{ $t("Create Date") }}
                                </th>
                                <th class="pb-4 px-4">
                                    {{ $t("Sign Date") }}
                                </th>
                            </tr>
                            <!-- show the contract fields when the software expanded is set to true -->
                            <tr
                                v-if="software?.expanded"
                                v-for="attachment in software?.expanded
                                    ? software?.children ?? []
                                    : []"
                                :key="attachment.id"
                            >
                                <td class="border-t pl-8">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4 pl-6"
                                    >
                                        {{ attachment.attachmentNumber }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        {{ attachment.contractCategory }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        v-if="!!attachment?.pricePerMonth"
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        {{
                                            $formatter(
                                                (
                                                    attachment?.pricePerMonth ??
                                                    0
                                                ).toFixed(2),
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        v-if="!!attachment?.pricePerMonth"
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        {{
                                            $formatter(
                                                (
                                                    (attachment?.pricePerMonth ??
                                                        0) * 12
                                                ).toFixed(2),
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        {{
                                            $dateFormatter(
                                                attachment.createdAt,
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="w-px border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                        {{
                                            $dateFormatter(
                                                attachment.signedDate,
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="software?.expanded">
                                <td class="border-t pl-8">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4 pl-6 font-bold"
                                    >
                                        {{ $t("Summary") }}:
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4 font-bold"
                                    >
                                        {{
                                            $formatter(
                                                getTotalPricePerMonth(
                                                    software?.children ?? []
                                                ).toFixed(2),
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4 font-bold"
                                    >
                                        {{
                                            $formatter(
                                                getTotalPricePerYear(
                                                    software?.children ?? []
                                                ).toFixed(2),
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                                <td class="w-px border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-4 py-4"
                                    >
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <tr v-if="(form.outboundedContracts?.length ?? 0) === 0">
                        <td class="px-4 py-4 border-t" colspan="4">
                            {{ $t("No outbounded contracts found") }}.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div v-if="activeTab === 'account'">
            <show-ticket-ams
                @remainingHours="remainingHours = $event"
                :ams="form"
            ></show-ticket-ams>
            <h1 class="header mb-8 text-3xl font-bold secondary-color mt-5">
                {{ $t("Time Tracker Entries") }}
            </h1>
            <time-tracker-table
                :fromAMS="true"
                :timeTrackerListing="timeTrackerListing"
            ></time-tracker-table>
            <div
                class="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-100"
            >
                <number-input
                    :showPrefix="false"
                    :required="true"
                    class="pr-6 w-1/5"
                    :label="$t('Remaining Hours')"
                    :isReadonly="true"
                    v-model="remainingHours"
                />
            </div>
        </div>
    </div>
</template>

<script>
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import ShowTicketAms from "./Components/ShowTicketAms.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TimeTrackerTable from "@/Pages/FinanceDashboard/Components/TimeTrackerTable.vue";

export default {
    computed: {
        ...mapGetters("ams", ["showRevamp"]),
    },
    components: {
        SearchFilter,
        TextInput,
        NumberInput,
        ShowTicketAms,
        PageHeader,
        TimeTrackerTable,
    },
    data() {
        return {
            remainingHours: "",
            timeTrackerListing: {
                data: [],
                links: [],
            },
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Support"),
                    to: "/ams",
                },
                {
                    text: this.$t("Application Management Services"),
                    to: "/ams?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            form: {
                amsNumber: "",
                customer: "",
                software: "",
                serviceHours: "",
                hourlyRate: "",
                monthlyAmount: "",
                remainingHours: "",
                outboundedContracts: [],
            },
            activeClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            inactiveClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
            activeTab: "detail",
        };
    },
    watch: {},
    async mounted() {
        // fetch the contract types listing
        this.refresh();
    },
    methods: {
        /**
         * calculates sum of price per year of all the attachments
         * @param {attachments} attachments
         */
        getTotalPricePerYear(attachments) {
            return attachments.reduce((acc, curr) => {
                return acc + +(curr?.pricePerMonth ?? 0) * 12;
            }, 0);
        },
        /**
         * calculates sum of price per month of all the attachments
         * @param {attachments} attachments
         */
        getTotalPricePerMonth(attachments) {
            return attachments.reduce((acc, curr) => {
                return acc + +curr.pricePerMonth;
            }, 0);
        },
        /**
         * creates a tree like structure for outbounded contracts by grouping the contract fields with software
         */
        groupContracts() {
            // map the outboundedContracts to group the contract fields by attachment software
            const contracts = this.form.outboundedContracts.map((contract) => {
                const softwares = [];
                // loop through the attachments
                contract.attachments.forEach((attachment) => {
                    // add software to softwares object if not already present
                    if (
                        !softwares.some(
                            (software) => software.id === attachment.software.id
                        )
                    ) {
                        softwares.push({
                            ...attachment.software,
                            expanded: false,
                            children: [],
                        });
                    }
                    const softwareIndex = softwares.findIndex(
                        (software) => software.id === attachment.software.id
                    );
                    // only add attachments with allowToAddSlas set to 1
                    if (attachment.allowToAddSlas == 1) {
                        // map the contract fields based on software
                        softwares[softwareIndex].children = [
                            ...softwares[softwareIndex].children,
                            {
                                ...attachment,
                                contractCategory: `Attachment ${attachment.name}`,
                                expanded: false,
                            },
                        ];
                    }
                });
                return {
                    ...contract,
                    expanded: false,
                    children: softwares,
                };
            });
            this.form.outboundedContracts = [...contracts]; // set the contractTree
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "ams/showRevamp",
                    this.$route.params.id
                );
                var responseData = response.data.data;
                this.form.amsNumber = responseData?.amsNumber;
                this.form.customer = responseData?.customer?.companyName;
                this.form.software = responseData?.software?.name;
                this.form.hourlyRate = responseData?.hourlyRate;
                this.form.monthlyAmount = responseData?.monthlyAmount;
                this.form.serviceHours = responseData?.serviceHoursYear;
                this.form.outboundedContracts =
                    responseData?.outboundedContracts;
                this.form.remainingHours = responseData?.remainingHours;
                this.form.moduleHistory = responseData?.moduleHistory ?? [];
                this.groupContracts();
            } catch (e) {
                console.log(e);
            }
            try {
                const response = await this.$store.dispatch(
                    "ams/timeTrackerRecordsByAMS",
                    {
                        amsId: this.$route.params.id,
                    }
                );
                this.timeTrackerListing = {
                    data: response?.data?.data ?? [],
                    links: [],
                };
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>
