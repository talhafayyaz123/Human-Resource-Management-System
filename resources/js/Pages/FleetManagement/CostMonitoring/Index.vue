<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center"></div>
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
            ></search-filter>
        </div>

        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                    <th style="width: 150px;"
                        class=" cursor-pointer"
                        @click="sort('licenceNumber', 'costMonitoring')"
                    >
                        {{ $t("Licence Plate") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'licenceNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('vim', 'costMonitoring')"
                    >
                        {{ $t("VIM") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'vim'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('brand', 'costMonitoring')"
                    >
                        {{ $t("Brand") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'brand'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('model', 'costMonitoring')"
                    >
                        {{ $t("Model") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'model'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('leasingRate', 'costMonitoring')"
                    >
                        {{ $t("Leasing Rate") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'leasingRate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('totalFuel', 'costMonitoring')"
                    >
                        {{ $t("Total Fuel") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'totalFuel'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class=" cursor-pointer"
                        @click="sort('totalEuro', 'costMonitoring')"
                    >
                        {{ $t("Total") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'totalEuro'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr
                    v-for="fleetCar in costMonitoring?.data ?? []"
                    :key="fleetCar.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        <div class="grid grid-col-1 gap-1">
                            <a href="javascript:void(0)" class="licence-tag">
                                {{ fleetCar.licenceNumber }}
                            </a>
                        </div>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{ fleetCar.vim }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{ fleetCar.brand }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{ fleetCar.model }}
                        </a>
                    </td>

                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{
                                $formatter(
                                    fleetCar.leasingRate,
                                    globalLanguage,
                                    "EUR"
                                )
                            }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{
                                $formatter(
                                    fleetCar.totalFuel,
                                    globalLanguage,
                                    "EUR"
                                )
                            }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center focus:text-indigo-500"
                        >
                            {{
                                $formatter(
                                    fleetCar.totalEuro,
                                    globalLanguage,
                                    "EUR"
                                )
                            }}
                        </a>
                    </td>
                </tr>
                </tbody>
                
                
            </table>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";

import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce"
export default {
    components: { SearchFilter, PageHeader },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Cost Monitoring",
                    active: true,
                },
            ],
            driver: "",
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_costMonitoring_column") ??
                    "licenceNumber",
                sortOrder:
                    localStorage.getItem("sort_costMonitoring_order") ?? "asc",
            },
        };
    },
    computed: {
        ...mapGetters("costMonitoring", ["costMonitoring"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            this.debouncedFetch(...val);
        }
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch("costMonitoring/list", {
                    ...this.form,
                });
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            await this.$store.dispatch(
                "costMonitoring/list",
                this.pickBy(this.form)
            );
        },
        getDriver(driverId) {
            const driver = this.users?.find((user) => user.id === driverId);
            return driver ? `${driver.first_name} ${driver.last_name}` : "";
        },
    },
};
</script>

<style>
.licence-tag {
    border: 0.461px solid #1c1e1d;
    box-shadow: -0.461px -0.461px 0.461px 0px rgba(0, 0, 0, 0.25),
        0.461px 0.461px 0.461px 0px rgba(0, 0, 0, 0.25);
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    background: #e6ebf1;
    color: #181617;
    text-shadow: 0px 0.461px 0.461px rgba(0, 0, 0, 0.25),
        0px 0.461px 0.461px rgba(0, 0, 0, 0.25);
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    letter-spacing: 0.8px;
    text-align: center;
}
</style>
