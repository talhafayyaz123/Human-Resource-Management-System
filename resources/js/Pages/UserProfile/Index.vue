<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <MultiSelectInput
                    :show-labels="false"
                    v-model="form.location"
                    :options="locations.data"
                    :multiple="false"
                    :textLabel="$t('Location')"
                    label="address"
                    trackBy="id"
                    moduleName="locations"
                    :showLoadMoreText="true"
                >
                    <template #beforeList>
                        <div
                            class="grid p-2 gap-2"
                            style="
                                grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                            "
                        >
                            <p class="font-bold">{{ $t("Name") }}</p>
                            <p class="font-bold">{{ $t("Street") }}</p>
                            <p class="font-bold">{{ $t("City") }}</p>
                            <p class="font-bold">{{ $t("ZIP") }}</p>
                            <p class="font-bold">{{ $t("State") }}</p>
                            <p class="font-bold">{{ $t("Country") }}</p>
                        </div>
                        <hr />
                    </template>
                    <template #singleLabel="{ props }">
                        {{ props.option.name }}
                    </template>
                    <template #option="{ props }">
                        <div
                            class="grid gap-2"
                            style="
                                grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                            "
                        >
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.name }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.street }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.city }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.zipCode }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.state }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.country }}
                            </p>
                        </div>
                    </template>
                </MultiSelectInput>

                <MultiSelectInput
                    v-model="form.department"
                    :options="departments.data"
                    :multiple="false"
                    :textLabel="$t('Department')"
                    label="name"
                    trackBy="id"
                    moduleName="departments"
                />

                <MultiSelectInput
                    v-model="form.team"
                    :options="teams.data"
                    :multiple="false"
                    :textLabel="$t('Team')"
                    label="name"
                    trackBy="id"
                    moduleName="teams"
                />
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('personalNumber', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Personal number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'personalNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('employee', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Employee") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'employee'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('jobTitle', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Job Title") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'jobTitle'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('teams', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Teams") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'teams'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('departments', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Departments") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'departments'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('location', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Location") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'location'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('dateOfBirth', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Date of birth") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'dateOfBirth'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('joinDate', 'employees')"
                            class="cursor-pointer"
                        >
                            {{ $t("Join date") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'joinDate'"
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
                    <tr
                        v-for="item in userProfiles.data"
                        :key="item.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/user-profiles/${item.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.personalNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.employee }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.jobTitle }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.teams }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.departments }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.location }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{
                                    $dateFormatter(
                                        item.dateOfBirth,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{
                                    $dateFormatter(
                                        item.joinDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="w-px">
                            <div class="flex items-center gap-x-2">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    :to="`/user-profiles/${item.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square py-4"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(item.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can py-4"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="userProfiles.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No users found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="userProfiles"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";

import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userDepartments", ["departments"]),
        ...mapGetters("userLocations", ["locations"]),
        ...mapGetters("userTeams", ["teams"]),
    },
    components: {
        Pagination,
        SearchFilter,
        MultiSelectInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/user-profiles",
                },
                {
                    text: this.$t("User Profiles"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create New Employee"),
                btn2Path: "/user-profiles/create",
            },
            page: 1,
            form: {
                search: "",
                location: "",
                team: "",
                department: "",
                sortBy:
                    localStorage.getItem("sort_employees_column") ??
                    "personalNumber",
                sortOrder:
                    localStorage.getItem("sort_employees_order") ?? "asc",
            },
            window,
            currentPage: 1,
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.location"(...val) {
            this.debouncedFetch(...val);
        },
        "form.team"(...val) {
            this.debouncedFetch(...val);
        },
        "form.department"(...val) {
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
                await this.$store.dispatch("userProfile/list", {
                    ...this.form,
                    location: this.form.location?.id,
                    department: this.form.department?.id,
                    team: this.form.team?.id,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        reset() {
            this.form = this.mapValues(this.form, () => null);
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
                    await this.$store.dispatch("userProfile/destroy", id);
                    await this.refresh(true);
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("userProfile/list", {
                page: this.page,
                ...this.form,
            });
        },
        async refresh(bypass) {
            try {
                await this.$store.dispatch("userProfile/list", {
                    ...this.form,
                    page: this.page,
                    location: this.form.location?.id,
                    department: this.form.department?.id,
                    team: this.form.team?.id,
                });
                if (!this.departments?.data?.length)
                    await this.$store.dispatch("userDepartments/list", {
                        page: 0,
                        perPage: 25,
                    });
                if (!this.locations?.data?.length)
                    await this.$store.dispatch("userLocations/list", {
                        page: 0,
                        perPage: 25,
                    });
                if (!this.teams?.data?.length)
                    await this.$store.dispatch("userTeams/list", {
                        page: 0,
                        perPage: 25,
                    });
            } catch (e) {}
        },
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
};
</script>

<style scoped>
.location-length {
    max-width: 10ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

.location-length:hover {
    text-overflow: clip;
    white-space: normal;
}
</style>
