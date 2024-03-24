<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6">
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
                        <th
                            class="cursor-pointer"
                            @click="sort('licenceNumber', 'fuelMonitoring')"
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
                            class="cursor-pointer"
                            @click="sort('vim', 'fuelMonitoring')"
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
                        <th class="">{{ $t("Driver") }}</th>
                        <th
                            class="cursor-pointer"
                            @click="sort('actualMileage', 'fuelMonitoring')"
                        >
                            {{ $t("Actual Mileage") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'actualMileage'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('totalFuel', 'fuelMonitoring')"
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
                            class="cursor-pointer"
                            @click="sort('consumeAverage', 'fuelMonitoring')"
                        >
                            {{
                                globalLanguage === "de"
                                    ? `Verbrauch`
                                    : `AVG Consume / 100KM`
                            }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'consumeAverage'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('euroAverage', 'fuelMonitoring')"
                            class="cursor-pointer"
                        >
                            {{
                                globalLanguage === "de"
                                    ? `Kosten`
                                    : `AVG Euro / 100KM`
                            }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'euroAverage'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('totalEuro', 'fuelMonitoring')"
                        >
                            {{ $t("Total EUR") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'totalEuro'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="text-center">
                            {{ $t("Actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="fleetCar in fuelMonitoring?.data ?? []"
                        :key="fleetCar.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            $can(`${$route.meta.permission}.show`)
                                ? $router.push(
                                      `fuel-monitoring/${fleetCar.id}/show`
                                  )
                                : ''
                        "
                    >
                        <td class="">
                            <div class="grid grid-cols-1 gap-1">
                                <a
                                    href="javascript:void(0)"
                                    class="licence-tag"
                                >
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
                                {{ getDriver(fleetCar?.driverId) }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        fleetCar.actualMileage,
                                        globalLanguage,
                                        "EUR"
                                    ).replace(/€/g, "")
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
                                    ).replace(/€/g, "")
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
                                        fleetCar.consumeAverage,
                                        globalLanguage,
                                        "EUR"
                                    ).replace(/€/g, "")
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
                                        fleetCar.euroAverage,
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
                        <td class="">
                            <a
                                v-if="$can(`${$route.meta.permission}.show`)"
                                href="javascript:void(0)"
                                class="text-center focus:text-indigo-500"
                            >
                                <router-link :to="`fuel-monitoring/${fleetCar.id}/show`">
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
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
import { debounce } from "@/utils/debounce";
export default {
    components: { SearchFilter, PageHeader },
    data() {
        return {
            page: 1,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fuel Monitoring",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Add Fuel Receipts"),
                btn2Path: "/fuel-receipts/create",
            },
            driver: "",
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_fuelMonitoring_column") ??
                    "licenceNumber",
                sortOrder:
                    localStorage.getItem("sort_fuelMonitoring_order") ?? "asc",
            },
        };
    },
    computed: {
        ...mapGetters("fuelMonitoring", ["fuelMonitoring"]),
        ...mapGetters("auth", {
            users: "users",
        }),
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
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("fuelMonitoring/list", {
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
                "fuelMonitoring/list",
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
