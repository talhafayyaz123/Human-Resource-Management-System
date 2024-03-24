<template>
    <div>
        <div class="table-responsive modal-table">
            <table
                :style="fromAMS ? '' : 'font-size: 0.7rem;'"
                class="w-full doc-table"
            >
                <tr class="text-left font-bold">
                    <th
                        @click="sort('date', 'finance')"
                        style="width: 13%"
                        class="cursor-pointer"
                    >
                        {{ $t("Date") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'date'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('moduleType', 'finance')"
                        style="width: 12%"
                        class="cursor-pointer"
                    >
                        {{ $t("Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'moduleType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('UserProfileData.firstName', 'finance')"
                        style="width: 20%"
                        class="cursor-pointer"
                    >
                        {{ $t("Person/Reference") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'UserProfileData.firstName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Company.companyName', 'finance')"
                        style="width: 20%"
                        class="cursor-pointer"
                    >
                        {{ $t("Company") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('description', 'finance')"
                        style="width: 25%"
                        class="cursor-pointer"
                    >
                        {{ $t("Activity Description") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'description'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        style="width: 10%"
                        @click="sort('time', 'finance')"
                        class="cursor-pointer"
                    >
                        {{ $t("Quantity") }}/{{ $t("Unit") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'time'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('isGoodwill', 'finance')"
                        style="width: 10%"
                        class="cursor-pointer"
                    >
                        {{ $t("Goodwill") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'isGoodwill'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                </tr>
                <tr
                    v-for="item in timeTrackerListing?.data ?? []"
                    :key="item.id"
                    @contextmenu.prevent="goToTimeTracker(item)"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ $dateFormatter(item.date, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ $t(item.type) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.username }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.company }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.description }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.quantity }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.isGoodwill ? $t("Yes") : $t("No") }}
                        </a>
                    </td>
                </tr>
                <tr v-if="(timeTrackerListing?.data?.length ?? 0) === 0">
                    <td class="px-6 py-2 " colspan="4">
                        {{ $t("No time tracker entries found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div
            v-if="!fromAMS"
            style="margin-top: 3rem"
            class="flex justify-center"
        >
            <pagination
                :limit="10"
                align="center"
                :data="timeTrackerListing"
                @pagination-change-page="$emit('changePage', $event)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { debounce } from "@/utils/debounce";

export default {
    emits: ["changePage", "orderTimeTrackerTable"],
    props: {
        timeTrackerListing: {
            type: Object,
            required: true,
            default: () => ({
                data: [],
                links: [],
            }),
        },
        fromAMS: {
            type: Boolean,
            default: false,
        },
    },
    watch: {
         "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                this.$emit("orderTimeTrackerTable", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    data() {
        return {
            form: {
                sortBy: "",
                sortOrder: "",
            },
        };
    },
    methods: {
        /**
         * redirects to time tracker index page with set query params
         * @param {item} time tracker company record
         */
        goToTimeTracker(item) {
            this.$router.push(
                `/time-trackers?date=${item.date}&id=${item.id}&userId=${item.userId}`
            );
        },
    },
    mounted() {
        const financeColumn =
            localStorage.getItem("sort_finance_column") ?? "date";
        const financeOrder =
            localStorage.getItem("sort_finance_order") ?? "asc";
        // Apply the stored values to the form object for 'customers' table
        if (financeColumn) {
            this.form.sortBy = financeColumn;
        }

        if (financeOrder) {
            this.form.sortOrder = financeOrder;
        }
    },
    components: {
        Pagination,
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
td > :only-child {
    max-width: 50ch;
}
</style>
