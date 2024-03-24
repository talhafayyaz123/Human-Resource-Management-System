<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-between mb-6">
            <!--  <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
            ></search-filter> -->
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            class="cursor-pointer"
                            @click="sort('name', 'fleetDriver')"
                        >
                            {{ $t("Driver") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('status', 'fleetDriver')"
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
                        <th class="">
                            {{ $t("Licence Plate") }}
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('lastCheckup', 'fleetDriver')"
                        >
                            {{ $t("Last Checkup") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'lastCheckup'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('nextCheckup', 'fleetDriver')"
                        >
                            {{ $t("Next Checkup") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'nextCheckup'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('licenseValidUntil', 'fleetDriver')"
                        >
                            {{ $t("Licence Valid until") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'licenseValidUntil'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="">
                            {{ $t("Vehicle Classes") }}
                        </th>
                        <th class="text-center">
                            {{ $t("Actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="fleetDriver in fleetDrivers?.data ?? []"
                        :key="fleetDriver.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/fleet-drivers/${fleetDriver.id}/edit`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetDriver.name }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetDriver.status }}
                            </a>
                        </td>

                        <td class="">
                            <div class="grid grid-cols-3 gap-1">
                                <span
                                    v-for="number in fleetDriver.licencePlate"
                                    :key="number"
                                    class="licence-tag"
                                >
                                    {{ number }}
                                </span>
                            </div>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetDriver.lastCheckup }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetDriver.nextCheckup }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetDriver.licenceValidUntil }}
                            </a>
                        </td>
                        <td class="">
                            <div class="grid grid-cols-3 gap-1">
                                <span
                                    v-for="vehicle in fleetDriver.vehicleClasses"
                                    :key="vehicle.id"
                                    class="tag"
                                >
                                    {{ vehicle.name }}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <router-link
                                @click.stop=""
                                class="px-1"
                                :to="`fleet-drivers/${fleetDriver.id}/show`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                @click.stop=""
                                class="mx-2"
                                :to="`fleet-drivers/${fleetDriver.id}/edit`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
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
                    text: "Fleet Drivers",
                    active: true,
                },
            ],
            optionalItems: {
                isBtnShow: true,
                btnText: this.$t("Create Fleet Drivers"),
                path: "/fleet-drivers/create",
                isBtn2Show: true,
                btn2Text: this.$t("Driver Licence Check"),
                btn2Path: "/fleet-drivers/licence-check",
            },
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_fleetDriver_column") ??
                    "fleetDriver",
                sortOrder:
                    localStorage.getItem("sort_fleetDriver_order") ?? "asc",
            },
            window,
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
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("fleetDrivers/list", {
                    ...this.form,
                });
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    computed: {
        ...mapGetters("fleetDrivers", ["fleetDrivers"]),
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            await this.$store.dispatch("fleetDrivers/list", {
                ...this.form,
            });
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
.tag {
    border-radius: 3px;
    background: #f59630;
    display: flex;
    padding: 5px 14px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    color: #fff;
    font-size: 12px;
    font-style: normal;
    font-weight: 600;
    line-height: 15.32px; /* 127.669% */
    letter-spacing: 0.6px;
}
</style>
