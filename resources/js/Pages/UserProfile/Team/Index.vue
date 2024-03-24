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
                <tr class="text-left">
                    <th @click="sort('name', 'teams')" class="cursor-pointer">
                        {{ $t("Name") }}
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
                        @click="sort('teamLead', 'teams')"
                    >
                        {{ $t("Team Lead") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'teamLead'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class="cursor-pointer"
                        @click="sort('department', 'teams')"
                    >
                        {{ $t("Department") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'department'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="item in teams.data"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/user/teams/${item.id}/edit?page=${page}`
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
                            {{ item.teamLead?.first_name }}
                            {{ item.teamLead?.last_name }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer cursor-pointer"
                        >
                            {{ item.department?.name }}
                        </a>
                    </td>
                    <td class="w-px">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            :to="`/user/teams/${item.id}/edit?page=${page}`"
                            class="px-2"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(item.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="teams.data.length === 0">
                    <td class="text-center" colspan="4">
                        {{ $t("No Team found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="teams"
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
        ...mapGetters("userTeams", ["teams"]),
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
                    to: "/user/teams",
                },
                {
                    text: this.$t("Teams"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Team"),
                btn2Path: "/user/teams/create",
            },
            page: 1,
            form: {
                search: "",
                sortBy: localStorage.getItem("sort_teams_column") ?? "name",
                sortOrder: localStorage.getItem("sort_teams_order") ?? "asc",
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
                await this.$store.dispatch("userTeams/list", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
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
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("userTeams/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            if (!this.roles?.data?.length || bypass)
                await this.$store.dispatch("userTeams/list", {
                    page: this.page,
                    ...this.pickBy(this.form),
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
                    await this.$store.dispatch("userTeams/destroy", id);
                    this.refresh(true);
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
