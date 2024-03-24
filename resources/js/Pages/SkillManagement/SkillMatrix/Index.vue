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
                        <th @click="sort('name')" class="cursor-pointer">
                            {{ $t("Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'name'"
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
                        v-for="skill in skillsMatrix?.data"
                        :key="skill.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/skill-matrix/${skill.id}/edit?page=${page}`
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
                                {{ skill.name }}
                            </a>
                        </td>
                        <td class="w-px">
                            <div class="flex items-center">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    :to="`/skill-matrix/${skill.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="mx-2"
                                    :to="`/skill-matrix/${skill.id}/show?page=${page}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(skill.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="skills?.data.length === 0">
                        <td class="text-center" colspan="4">
                            {{ $t("No skill Matrix found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="skillsMatrix ?? []"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import Pagination from "laravel-vue-pagination";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("skillsMatrix", ["skillsMatrix"]),
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
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Skills Matrix",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Skills Matrix"),
                btn2Path: "/skill-matrix/create",
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
                    "skillsMatrix/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
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
            await this.$store.dispatch("skillsMatrix/list", {
                page: this.page,
            });
        },
        async refresh(bypass) {
            if (!this.skills?.data.length || bypass)
                await this.$store.dispatch("skillsMatrix/list", {
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
                    await this.$store.dispatch("skillsMatrix/destroy", id);
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
