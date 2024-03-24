<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center cursor-pointer justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th class="cursor-pointer" @click="sort('name')">
                            {{ $t("Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="cursor-pointer" @click="sort('street')">
                            {{ $t("Street") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'street'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('zipCode')" class="cursor-pointer">
                            {{ $t("Zip Code") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'zipCode'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('city')" class="cursor-pointer">
                            {{ $t("City") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'city'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('state')" class="cursor-pointer">
                            {{ $t("State") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'state'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('country')" class="cursor-pointer">
                            {{ $t("Country") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'country'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="cursor-pointer">
                            {{ $t("Action") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in locations.data"
                        :key="item.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/user/locations/${item.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.name }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.street }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.zipCode }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.city }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.state }}
                            </a>
                        </td>
                        <td class="">
                            <a class="flex items-center cursor-pointer">
                                {{ item.country }}
                            </a>
                        </td>
                        <td class="w-px">
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/user/locations/${item.id}/edit?page=${page}`"
                                class="px-2"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                class="cursor-pointer"
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(item.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="locations.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No Location found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="locations"
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
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("userLocations", ["locations"]),
    },
    components: {
        Pagination,
        SearchFilter,
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
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
                    to: "/user/locations",
                },
                {
                    text: this.$t("Locations"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Location"),
                btn2Path: "/user/locations/create",
            },
            page: 1,
            form: {
                search: "",
                sortBy: "name",
                sortOrder: "asc",
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
                await this.$store.dispatch(
                    "userLocations/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        this.refresh();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("userLocations/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            await this.$store.dispatch("userLocations/list", {
                page: this.page,
            });
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
                    await this.$store.dispatch("userLocations/destroy", id);
                    this.refresh(true);
                }
            });
        },
        reset() {
            this.form = { search: "", sortBy: "", sortOrder: "asc" };
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
