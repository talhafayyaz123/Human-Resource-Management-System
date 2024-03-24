<template>
    <div class="flex items-center justify-end mb-6">
        <search-filter
            :isFilter="false"
            v-model="form.search"
            class="ml-4 w-full max-w-md"
            @reset="reset"
        >
        </search-filter>
    </div>

    <div class="table-responsive">
        <table class="doc-table">
            <tr class="text-left">
                <th
                    @click="sort('departureCity', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("Departure City") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'departureCity'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th
                    @click="sort('departureCountry', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("Departure Country") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'departureCountry'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th
                    @click="sort('arrivalCity', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("Arrival City") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'arrivalCity'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th
                    @click="sort('arrivalCountry', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("Arrival Country") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'arrivalCountry'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th
                    @click="sort('fromDate', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("From Date") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'fromDate'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th
                    @click="sort('toDate', 'travel_expenses')"
                    class="cursor-pointer"
                >
                    {{ $t("To Date") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'toDate'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>

                <th class="">{{ $t("Status") }}</th>

                <th class="text-center">{{ $t("Action") }}</th>
            </tr>
            <tr
                v-for="item in travelExpenses.data"
                :key="item.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.departureCity }}
                    </router-link>
                </td>
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.departureCountry }}
                    </router-link>
                </td>
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.arrivalCity }}
                    </router-link>
                </td>
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.arrivalCountry }}
                    </router-link>
                </td>
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.fromDate }}
                    </router-link>
                </td>
                <td class="">
                    <router-link
                        :to="`/travel-expenses/${item.id}/edit`"
                        class="flex items-center  focus:text-indigo-500"
                    >
                        {{ item.toDate }}
                    </router-link>
                </td>
                <td class="">
                    <p class="capitalize">
                        {{ item.status }}
                    </p>
                </td>

                <td class="">
                    <div class="flex justify-center gap-2">
                        <font-awesome-icon
                        :title="$t('Send For Approval')"
                        @click="sendApprovalRequest(item.id)"
                        v-if="!item.isApprovalSent"
                        class="cursor-pointer ml-2"
                        :icon="'fa-solid fa-paper-plane'"
                    />
                    <router-link
                        class="px-1"
                        :to="`/travel-expenses/reports/${item.id}`"
                    >
                        <font-awesome-icon icon="fa-solid fa-eye" />
                    </router-link>
                    <router-link
                        v-if="$can(`${$route.meta.permission}.edit`)"
                        class="px-1"
                        :to="`/travel-expenses/${item.id}/edit`"
                    >
                        <font-awesome-icon icon="fa-regular fa-pen-to-square" />
                    </router-link>
                    <button
                        v-if="$can(`${$route.meta.permission}.delete`)"
                        @click="destroy(item.id)"
                    >
                        <font-awesome-icon icon="fa-regular fa-trash-can" />
                    </button>
                    </div>

                    <!-- ADD ACTION FOR APPROVED FOR REPORTS -->
                </td>
            </tr>
            <tr v-if="travelExpenses.data.length === 0">
                <td class=" " colspan="7">
                    {{ $t("No travel expense found") }}.
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 3rem" class="flex justify-center">
        <pagination
            :limit="10"
            align="center"
            :data="travelExpenses"
            @pagination-change-page="changePageOrSearch"
        ></pagination>
        <br />
        <br />
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";

export default {
    computed: {
        ...mapGetters("travelExpense", ["travelExpenses"]),
    },
    components: {
        Pagination,
        SearchFilter,
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_travel_expenses_column") ??
                    "departureCity",
                sortOrder:
                    localStorage.getItem("sort_travel_expenses_order") ?? "asc",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "travelExpense/list",
                            this.form
                        );
                    } catch (e) {
                        console.log(e);
                    }
                }, 150)();
            },
        },
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async sendApprovalRequest(id) {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("travelExpense/sendForApproval", id);
                this.refresh(true);
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("travelExpense/list", {
                page: page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            if (!this.roles?.data?.length || bypass)
                await this.$store.dispatch(
                    "travelExpense/list",
                    this.pickBy(this.form)
                );
            await this.$store.dispatch("userProfile/getApprovalPermissions");
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
                    try {
                        await this.$store.dispatch("travelExpense/destroy", id);
                        this.refresh(true);
                    } catch (e) {}
                }
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
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
