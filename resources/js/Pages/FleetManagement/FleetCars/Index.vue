<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group">
                    <select-input v-model="form.fuelType" :label="$t('Fuel Type')">
                        <option
                            v-for="type in ['diesel', 'electronic', 'gasoline']"
                            :key="type"
                            :value="type"
                        >
                            {{ type }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group mt-[20px]">
                <select-input v-model="form.status" :label="$t('Status')">
                    <option
                        v-for="type in ['ready', 'out_of_service']"
                        :key="type"
                        :value="type"
                    >
                        {{
                            type === "out_of_service" ? "Out of service" : type
                        }}
                    </option>
                </select-input>
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            style="width: 150px"
                            @click="sort('licenceNumber', 'FleetCar')"
                            class="cursor-pointer"
                        >
                            {{ $t("Licence plate") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'licenceNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('vim', 'FleetCar')"
                            class="cursor-pointer"
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
                            @click="sort('brand', 'FleetCar')"
                            class="cursor-pointer"
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
                            @click="sort('model', 'FleetCar')"
                            class="cursor-pointer"
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
                            @click="sort('fuelType', 'FleetCar')"
                            class="cursor-pointer"
                        >
                            {{ $t("Fuel type") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'fuelType'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('leasingRate', 'FleetCar')"
                            class="cursor-pointer"
                        >
                            {{ $t("Leasing rate") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'leasingRate'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>

                        <th
                            @click="sort('miles', 'FleetCar')"
                            class="cursor-pointer"
                        >
                            {{ $t("Miles") }}/{{ $t("Year") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'miles'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('totalMileage', 'FleetCar')"
                            class="cursor-pointer"
                        >
                            {{ $t("Mileage") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'totalMileage'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('status', 'FleetCar')"
                            class="cursor-pointer"
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
                        <th class="">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="fleetCar in fleetCars?.data"
                        :key="fleetCar.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/fleet-cars/${fleetCar.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
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
                                {{ fleetCar.fuelType }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.leasingRate }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.miles }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.totalMileage }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    fleetCar.status == "ready"
                                        ? "Ready"
                                        : "Out of service"
                                }}
                            </a>
                        </td>
                        <td class="">
                            <div class="flex items-center">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="mx-2"
                                    :to="`/fleet-cars/${fleetCar.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(fleetCar.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="fleetCars?.data?.length === 0">
                        <td class="text-center" colspan="9">
                            {{ $t("No fleet cars found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="fleetCars ?? []"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon.vue";
import Pagination from "laravel-vue-pagination";
import SelectInput from "@/Components/SelectInput.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce";

import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("fleetCars", ["fleetCars"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput
    },
    mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        this.refresh(isPaginate);
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.fuelType"(...val) {
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
                await this.$store.dispatch("fleetCars/list", {
                    ...this.form,
                });
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("fleetCars/list", {
                ...this.form,
                page: this.page,
            });
        },
        async refresh() {
            await this.$store.dispatch("fleetCars/list", { ...this.form });
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
                    await this.$store.dispatch("fleetCars/destroy", id);
                    this.refresh(true);
                }
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fleet Cars",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Fleet Car"),
                btn2Path: "/fleet-cars/create",
            },
            page: 1,
            form: {
                search: "",
                fuelType: "",
                sortBy:
                    localStorage.getItem("sort_FleetCar_column") ??
                    "licenceNumber",
                sortOrder: localStorage.getItem("sort_FleetCar_order") ?? "asc",
            },
            window,
        };
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
