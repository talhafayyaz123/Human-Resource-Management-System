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
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th @click="sort('jNumber')" class="cursor-pointer">
                            {{ $t("Job Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'jNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('jTitle')" class="cursor-pointer">
                            {{ $t("Job Title") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'jTitle'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('formOfContract.name')"
                            class="cursor-pointer"
                        >
                            {{ $t("Contract Type") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'formOfContract.name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('jobLevel.levelName')"
                            class="cursor-pointer"
                        >
                            {{ $t("Job Level") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'jobLevel.levelName'"
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
                        v-for="job in jobs?.data"
                        :key="job.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/job/${job.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ job.jobNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ job.jobTitle }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ job.contractType }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ job.jobLevel }}
                            </a>
                        </td>
                        <td class="w-px">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-2"
                                :to="`/job/${job.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(job.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="jobs?.data.length === 0">
                        <td class="" colspan="5">{{ $t("No job found") }}.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination
            :limit="10"
            align="center"
            :data="jobs ?? []"
            @pagination-change-page="changePageOrSearch"
        ></pagination>
    </div>
</template>

<script>
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import Pagination from "laravel-vue-pagination";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("userJobs", ["jobs"]),
    },
    components: {
        SearchFilter,
        Pagination,
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
                    to: "/job",
                },
                {
                    text: this.$t("Job"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Job"),
                btn2Path: "/job/create",
            },
            page: 1,
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
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
                    "userJobs/list",
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
        this.refresh(true);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("userJobs/list", {
                page: this.page,
            });
        },
        async refresh(bypass) {
            if (!this.jobs?.data.length || bypass)
                await this.$store.dispatch("userJobs/list", {
                    page: this.page,
                    search: this.form.search,
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
                    await this.$store.dispatch("userJobs/destroy", id);
                    await this.refresh(true);
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
