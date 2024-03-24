<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Employee Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'employeeId'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Creator Name") }}
                    </th>
                    <th
                        @click="sort('createdAt')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Created At") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>

                <tr
                    class="cursor-pointer"
                    v-for="emInterview in employeeInterview?.data"
                    :key="emInterview.id"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `employee-interview/${emInterview.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ getUserName(emInterview) }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ emInterview?.creatorName }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        <a href="javascript:void(0)" class="flex items-center">
                            {{
                                $dateFormatter(
                                    emInterview.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-2"
                            :to="`employee-interview/${emInterview.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            @click="destroy(emInterview.id)"
                            v-if="$can(`${$route.meta.permission}.delete`)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="employeeInterview"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    components: {
        Pagination,
        SearchFilter,
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
    },

    computed: {
        ...mapGetters("employeeInterview", ["employeeInterview"]),
        ...mapGetters("auth", { user: "user", users: "users" }),
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
                    to: "/employee-interview",
                },
                {
                    text: this.$t("Employee Interview"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Employee Interview"),
                btn2Path: "/employee-interview-create",
            },
            window,
            page: 1,
            form: {
                search: "",
                sortBy: "",
                sortOrder: "asc",
            },
            fullEmployeeInterviewData: [],
        };
    },
    watch: {
        "form.search"(...val) {
            this.employeeInterview.data = this.fullEmployeeInterviewData;
            const searchResult = this.employeeInterview.data.filter(
                (employee) => {
                    return employee.employeeName
                        .toLowerCase()
                        .includes(this.form.search.toLowerCase());
                }
            );
            this.employeeInterview.data = searchResult;
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
                await this.$store.dispatch("employeeInterview/list", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    async mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        await this.$store.dispatch("employeeInterview/list", {
            page: this.page,
            ...this.pickBy(this.form),
        });
        this.fullEmployeeInterviewData = this.employeeInterview?.data;
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 40,
            type: "employee",
        });
    },
    methods: {
        reset() {
            this.form.search = "";
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
                    await this.$store.dispatch("employeeInterview/destroy", id);
                    await this.$store.dispatch("employeeInterview/list", {
                        page: this.page,
                    });
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("employeeInterview/list", {
                page: this.page,
                ...this.form,
            });
        },
        getUserName(employeeInterview) {
            let user =
                this.users?.find(
                    (user) => user.id == employeeInterview.employeeId
                ) ?? {};
            employeeInterview.employeeName =
                user.first_name + " " + user.last_name;
            return user.first_name + " " + user.last_name;
        },
    },
};
</script>
