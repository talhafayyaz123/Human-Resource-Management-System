<template>
    <div>
        <div class="flex justify-end">
            <number-input
                :showPrefix="false"
                :required="true"
                class="pr-6 w-1/5 self-center"
                :label="$t('Service Time')"
                :isReadonly="true"
                v-model="serviceHours"
            />
        </div>

        <h1 class="header mb-8 text-3xl font-bold secondary-color">
            {{ $t("Tickets") }}
        </h1>

        <Teleport to="#ams-ticket-filter">
            <div class="flex items-center">
                <search-filter
                    v-model="form.search"
                    class="mr-4 max-w-md"
                    @reset="reset"
                >
                   <div class="form-group">
                    <select-input
                         :label="$t('Status')"
                        v-model="form.state"
                    >
                        <option value="closed">{{ $t("closed") }}</option>
                        <option value="open">{{ $t("open") }}</option>
                        <option value="pending-closed">
                            {{ $t("pending closed") }}
                        </option>
                        <option value="pending-reminder">
                            {{ $t("pending reminder") }}
                        </option>
                    </select-input>
                   </div>
                   <div class="form-group mt-[20px]">
                        <select-input
                            :label="$t('Priority')"
                            v-model="form.priority"
                        >
                            <option value="low">{{ $t("low") }}</option>
                            <option value="normal">{{ $t("normal") }}</option>
                            <option value="high">{{ $t("high") }}</option>
                        </select-input>
                    </div>
                </search-filter>
                <loading-button
                    @click="generateServiceReport"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <font-awesome-icon
                        :title="$t('Generate Service Report')"
                        class="cursor-pointer mr-1"
                        icon="fa-print"
                    />
                    {{ $t("Generate Service Report") }}
                </loading-button>
            </div>
        </Teleport>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('ticketNumber', 'tickets')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Ticket Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ticketNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class="pb-4 pt-6 px-6 cursor-pointer"
                        @click="sort('title', 'tickets')"
                    >
                        {{ $t("Title") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'title'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status', 'tickets')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('priority', 'tickets')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Priority") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'priority'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('type', 'tickets')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'type'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('visibility', 'tickets')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Visibility") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'visibility'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Accounted Time") }}
                    </th>
                </tr>
                <template v-for="item in tickets" :key="item.id">
                    <tr
                        class="focus-within:bg-gray-100"
                        @click="$router.push(`/tickets/${item.id}`)"
                        :class="
                            item.isArchived == 1
                                ? 'bg-red-200 focus-within:bg-red-200'
                                : ''
                        "
                    >
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ item.ticketNumber }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ item.title }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ $t(item.state) }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ $t(item.priority) }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ item.type }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{
                                    item.visibility
                                        ? item.visibility === "internal"
                                            ? "Internal Only"
                                            : "Public"
                                        : "N/A"
                                }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ item.totalAccountedTime }}
                            </a>
                        </td>
                    </tr>
                </template>
                <tr v-if="tickets?.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No tickets found") }}.
                    </td>
                </tr>
                <br />
            </table>
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import LoadingButton from "@/Components/LoadingButton.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";

export default {
    emits: ["remainingHours"],
    props: {
        ams: {
            type: Object,
            default: () => ({}),
        },
    },
    computed: {
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters(["isLoading"]),
    },
    components: {
        Pagination,
        SearchFilter,
        MultiSelectInput,
        LoadingButton,
        NumberInput,
        SelectInput
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        var response = await this.$store.dispatch(
                            "ams/getTickets",
                            {
                                id: this.$route.params.id,
                                queryParams: {
                                    search: this.form.search,
                                    state: this.form.state,
                                    priority: this.form.priority,
                                    assignee: this.form.assignee,
                                    type: this.form.type,
                                    sortBy: this.form.sortBy,
                                    sortOrder: this.form.sortOrder,
                                },
                            }
                        );
                        var dataResponse = response?.data;
                        this.tickets = dataResponse?.tickets;
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    data() {
        return {
            page: 1,
            form: {
                search: "",
                state: "",
                priority: "",
                assignee: "",
                type: "",
                sortBy:
                    localStorage.getItem("sort_tickets_ams_column") ??
                    "ticketNumber",
                sortOrder:
                    localStorage.getItem("sort_tickets_ams_order") ?? "asc",
            },
            tickets: [],
            currentPage: 1,
            serviceHours: 0,
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async generateServiceReport() {
            try {
                const templateId = await this.$store.dispatch(
                    "globalSettings/getTemplateByName",
                    "serviceReportTemplateId"
                );

                //Define PROCESS TEMPLATE payload
                if (templateId != "") {
                    this.$store.commit("isLoading", true);
                    const filename =
                        "service-report-" +
                        (this.ams?.amsNumber ?? "") +
                        ".pdf";
                    const response = await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: { id: templateId, ...this.ams },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // check if the response of the process template is of type Blob
                    if (response instanceof Blob) {
                        // convert blob to base64
                        const base64 = await this.convertBlobToBase64(response);

                        // send the elo request
                        await this.$store.dispatch(
                            "globalSettings/sendEloApiRequest",
                            {
                                content: {
                                    moduleAction: "generateServiceReport",
                                    data: {
                                        ...this.ams,
                                        id: templateId,
                                        base64:
                                            (base64 ?? "").split(
                                                ";base64,"
                                            )?.[1] ?? "",
                                    },
                                },
                            }
                        );
                    }
                }
            } catch (e) {
                console.log(e);
            }
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async refresh() {
            var response = await this.$store.dispatch("ams/getTickets", {
                id: this.$route.params.id,
            });
            var dataResponse = response?.data;
            this.tickets = dataResponse?.tickets;
            this.$emit("remainingHours", dataResponse.remainingHours);
            this.serviceHours = dataResponse.serviceTime;
        },
    },
};
</script>
