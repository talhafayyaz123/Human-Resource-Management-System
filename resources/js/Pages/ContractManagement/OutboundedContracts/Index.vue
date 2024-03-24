<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('contractNumber', 'outbounded')"
                            class="cursor-pointer"
                        >
                            {{ $t("Contract Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'contractNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('Company.companyName', 'outbounded')"
                            class="cursor-pointer"
                        >
                            {{ $t("Customer Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Company.companyName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('ContractType.name', 'outbounded')"
                        >
                            {{ $t("Contract Type") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'ContractType.name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('createdAt', 'outbounded')"
                            class="cursor-pointer"
                        >
                            {{ $t("Create Date") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'createdAt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <!--                    <th-->
                        <!--                        @click="sort('signDate', 'outbounded')"-->
                        <!--                        class=" cursor-pointer"-->
                        <!--                    >-->
                        <!--                        {{ $t("Signed Date") }}-->
                        <!--                        <font-awesome-icon-->
                        <!--                            v-if="form.sortBy === 'signDate'"-->
                        <!--                            class="cursor-pointer ml-2"-->
                        <!--                            :icon="`fa-solid fa-sort-${-->
                        <!--                                form.sortOrder === 'asc' ? 'up' : 'down'-->
                        <!--                            }`"-->
                        <!--                        />-->
                        <!--                    </th>-->
                        <th class="">{{ $t("Action") }}</th>
                        <th class=""></th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="contract in contractTree"
                        :key="contract.id"
                    >
                        <tr>
                            <td class="border-t">
                                <div
                                    @click="
                                        contract.expanded = !contract.expanded
                                    "
                                    class="flex items-center cursor-pointer"
                                >
                                    <font-awesome-icon
                                        class="text-xs text-gray-600 mr-2"
                                        :icon="[
                                            'fa-solid',
                                            contract.expanded
                                                ? 'fa-chevron-down'
                                                : 'fa-chevron-right',
                                        ]"
                                    />
                                    {{ contract.contractNumber }}
                                </div>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center"
                                >
                                    {{ contract.customer?.companyName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center"
                                >
                                    {{ contract.contractType?.name }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center"
                                >
                                    {{
                                        $dateFormatter(
                                            contract.createdAt,
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <!--                        <td class="border-t">-->
                            <!--                            <a-->
                            <!--                                href="javascript:void(0)"-->
                            <!--                                class="flex items-center"-->
                            <!--                            >-->
                            <!--                            </a>-->
                            <!--                        </td>-->
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
                                    <router-link
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.edit`
                                            )
                                        "
                                        class="mx-2"
                                        :to="`/outbounded-contracts/${contract.id}/edit`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </router-link>
                                    <button
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.delete`
                                            )
                                        "
                                        @click.stop="destroy(contract.id)"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-trash-can"
                                        />
                                    </button>
                                </div>
                            </td>
                            <td class="w-px"></td>
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
                                <td class="">
                                    <div
                                        class="flex items-center cursor-pointer"
                                        v-if="!!contract.id"
                                        @click="
                                            software.expanded =
                                                !software.expanded
                                        "
                                    >
                                        <font-awesome-icon
                                            class="text-xs text-gray-600 mr-2"
                                            :icon="[
                                                'fa-solid',
                                                software.expanded
                                                    ? 'fa-chevron-down'
                                                    : 'fa-chevron-right',
                                            ]"
                                        />
                                        {{ software.name }}
                                    </div>
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
                    <tr v-if="(outboundedContracts?.data?.length ?? 0) === 0">
                        <td class="px-4 py-4 border-t text-center" colspan="4">
                            {{ $t("No outbounded contracts found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("outboundedContracts", ["outboundedContracts"]),
    },
    components: {
        SearchFilter,
        PageHeader,
    },
    data() {
        return {
            page: 1,
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Contract Managment"),
                    to: "/outbounded-contracts",
                },
                {
                    text: this.$t("Outbounded Contracts"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Outbounded Contract"),
                btn2Path: "/outbounded-contracts/create",
            },
            contractTree: [], // keeps the modifled true structured outbounded contracts
            form: {
                search: "",
                sortBy: localStorage.getItem("sort_outbounded_column"),
                sortOrder:
                    localStorage.getItem("sort_outbounded_order") ?? "asc",
            },
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
        "outboundedContracts.data"(val) {
            this.contractTree = val; //set the contractTree array on change
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "outboundedContracts/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        // fetch the contract types listing
        await this.refresh();
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
            const contracts = this.contractTree.map((contract) => {
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
                    // map the contract fields based on software
                    softwares[softwareIndex].children = [
                        ...softwares[softwareIndex].children,
                        {
                            ...attachment,
                            contractCategory: `Attachment ${attachment.name}`,
                            expanded: false,
                        },
                    ];
                });
                return {
                    ...contract,
                    expanded: false,
                    children: softwares,
                };
            });
            this.contractTree = [...contracts]; // set the contractTree
        },
        /**
         * get the contract types listing
         */
        async refresh() {
            try {
                await this.$store.dispatch("outboundedContracts/list", {
                    page: this.page,
                    search: this.form.search,
                });
                this.groupContracts(); // create grouping after fetching data
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * hit the contract type delete API
         * @param {id} id of contract type to be deleted
         */
        async destroy(id) {
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
                    await this.$store.dispatch(
                        "outboundedContracts/destroy",
                        id
                    );
                    this.refresh();
                }
            });
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
