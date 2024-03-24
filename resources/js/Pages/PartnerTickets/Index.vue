<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group">
                    <select-input v-model="form.state" :label="$t('Status')">
                        <option value="new">{{ $t("New") }}</option>
                        <option value="open">{{ $t("Open") }}</option>
                        <option value="waiting-for-response">
                            {{ $t("Waiting For Response") }}
                        </option>
                        <option value="pending">
                            {{ $t("Pending") }}
                        </option>
                        <option value="resolved">
                            {{ $t("Resolved") }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group my-[20px]" >
                    <select-input v-model="form.priority" :label="$t('Priority')" >
                        <option value="low">{{ $t("low") }}</option>
                        <option value="normal">{{ $t("normal") }}</option>
                        <option value="high">{{ $t("high") }}</option>
                    </select-input>
                </div>
                <div class="form-group">
                    <MultiSelectInput
                        class=""
                        v-model="form.assignee"
                        :options="userProfiles?.data ?? []"
                        :multiple="false"
                        :text-label="$t('Assignee')"
                        label="employee"
                        trackBy="userId"
                        moduleName="userProfile"
                    />
                </div>
                 <div class="form-group my-[20px]">
                    <select-input v-model="form.area" :label="$t('Area')">
                        <option value="customer">{{ $t("Customer") }}</option>
                        <option value="partner">{{ $t("Partners") }}</option>
                        <option value="product">
                            {{ $t("Products") }}
                        </option>
                         <option value="all">
                            {{ $t("All") }}
                        </option>
                    </select-input>
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('ticketNumber', 'tickets')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Ticket Number")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'ticketNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('company_id', 'tickets')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Customer")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'company_id'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('createdAt', 'tickets')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Last Comment Date")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'createdAt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class=" cursor-pointer"
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
                            @click="sort('state', 'tickets')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Status") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'state'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('priority', 'tickets')"
                            class=" cursor-pointer"
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
                            class=" cursor-pointer"
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
                        <th class="">{{ $t("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="item in tickets.data" :key="item.id">
                        <tr
                            class="focus-within:bg-gray-100"
                            @click.stop.right="
                                (e) => {
                                    e.preventDefault();
                                    let route = $router.resolve(
                                        `/tickets/${item.id}/edit?page=${page}`
                                    );
                                    window.open(route.href, '_blank');
                                }
                            "
                            :class="
                                item.isArchived == 1
                                    ? 'bg-red-200 focus-within:bg-red-200'
                                    : ''
                            "
                        >
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ item.ticketNumber }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ item.companyName }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{
                                        item.lastCommentDate
                                            ? $dateFormatter(
                                                  item.lastCommentDate,
                                                  globalLanguage
                                              )
                                            : ""
                                    }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ item.title }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ $t(item.state) }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ $t(item.priority) }}
                                </a>
                            </td>
                            <td class="">
                                <a
                                    class="flex items-center cursor-pointer "
                                >
                                    {{ item.type }}
                                </a>
                            </td>
                            <td class="w-px ">
                                <div class="flex items-center">
                                    <button
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.edit`
                                            )
                                        "
                                        class="px-1"
                                        @click.stop="
                                            $router.push(
                                                `/partner-management/tickets/${item.id}/edit?page=${page}`
                                            )
                                        "
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </button>
                                    <button
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.show`
                                            )
                                        "
                                        class="px-2"
                                        @click.stop="
                                            $router.push(`/partner-management/tickets/${item.id}?page=${page}`)
                                        "
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                        />
                                    </button>
                                    <button
                                        v-if="
                                            $can(
                                                `${$route.meta.permission}.delete`
                                            ) &&
                                            !item.isArchived &&
                                            item.isDeletable
                                        "
                                        @click.stop="destroy(item.id)"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-trash-can"
                                        />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="tickets.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No tickets found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="tickets"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import TerminalVue from "../Invoices/Terminal.vue";
import SelectInput from "@/Components/SelectInput.vue";

import SearchFilter from "../../Components/SearchFilter.vue";

import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("partnerTickets", ["tickets"]),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    components: {
        Pagination,
        SearchFilter,
        MultiSelectInput,
        PageHeader,
        SelectInput
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.state"(...val) {
            this.debouncedFetch(...val);
        },
        "form.priority"(...val) {
            this.debouncedFetch(...val);
        },
        "form.assignee"(...val) {
            this.debouncedFetch(...val);
        },
         "form.area"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "partnerTickets/list",
                    this.pickBy({
                        ...this.form,
                        assigneeId: this.form.assignee?.userId ?? "",
                    })
                );
            } catch (e) {}
        }, 300);
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partner-management/tickets",
                },
                {
                    text: this.$t("Tickets"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Ticket"),
                btn2Path: "/partner-management/tickets/create",
            },
            page: 1,
            form: {
                search: "",
                state: "",
                priority: "",
                assignee: "",
                area: "partner",
                type: "",
                sortBy:
                    localStorage.getItem("sort_tickets_column") ??
                    "ticketNumber",
                sortOrder: localStorage.getItem("sort_tickets_order") ?? "asc",
            },
            window,
            currentPage: 1,
        };
    },
    methods: {
        reset() {
            this.form = {
                search: "",
                state: "",
                priority: "",
                assignee: "",
                area: "",
                type: "",
                sortBy:
                    localStorage.getItem("sort_tickets_column") ??
                    "ticketNumber",
                sortOrder: localStorage.getItem("sort_tickets_order") ?? "asc",
            };
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("partnerTickets/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh() {

        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
        }
            await this.$store.dispatch(
                "partnerTickets/list",
                this.pickBy({ ...this.form, page: this.page })
            );
        },
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
                    await this.$store.dispatch("partnerTickets/destroy", id);
                    this.refresh();
                    this.$store.dispatch("tickets/openTicketsCount");
                }
            });
        },
    },
    async mounted() {
        await this.refresh();
        await this.$store.dispatch("userProfile/list");
    },
};
</script>
