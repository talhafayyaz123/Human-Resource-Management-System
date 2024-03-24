<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            :page="page"
        />
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th  style="width: 250px;"
                            @click="sort('surveyNumber', 'surveys')"
                            class="cursor-pointer"
                        >
                            <div class="flex items-center">
                                {{ $t("Survey Number")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'surveyNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                            </div>
                        </th>
                        <th
                            @click="sort('name', 'surveys')"
                            class="cursor-pointer"
                        >
                            {{ $t("Name")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="text-center">
                            {{ $t("Questions Amount") }}
                        </th>
                        <th class="text-center">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="survey in surveys?.data"
                        :key="survey.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/surveys/${survey.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <div
                            >
                                {{ survey.surveyNumber }}
                            </div>
                        </td>
                        <td class="">
                            <div
                            >
                                {{ survey.name }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div
                            >
                                {{ survey.questionCount }}
                            </div>
                        </td>
                        <td class=" text-center">
                            <div class="flex items-center justify-center gap-x-2">
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.show`)
                                    "
                                    @click.stop="
                                        $router.push(
                                            `/surveys/${survey.id}?page=${page}`
                                        )
                                    "
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </button>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="cursor-pointer"
                                    @click.stop="
                                        $router.push(
                                            `/surveys/${survey.id}/edit?page=${page}`
                                        )
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </button>
                                <button
                                    class="cursor-pointer"
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(survey.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="(surveys?.data?.length ?? 0) === 0">
                        <td class="" colspan="4">No surveys found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="surveys"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";

import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";

import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("surveys", ["surveys"]),
    },
    components: {
        Icon,
        Pagination,
        PageHeader,
    },
    props: {
        filters: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/surveys",
                },
                {
                    text: "Surveys",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Survey"),
                btn2Path: "/surveys/create",
            },
            page: 1,
            window,
            form: {
                sortBy:
                    localStorage.getItem("sort_surveys_column") ??
                    "surveyNumber",
                sortOrder: localStorage.getItem("sort_surveys_order") ?? "asc",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch("surveys/list", {
                            ...this.pickBy(this.form),
                            page: this.page,
                        });
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        this.$store.commit("surveys/id", null);
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                await this.$store.dispatch("surveys/list", {
                    ...this.pickBy(this.form),
                    page: this.page,
                });
            } catch (e) {}
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("surveys/list", {
                page: this.page,
            });
        },
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
                    await this.$store.dispatch("surveys/destroy", id);
                    this.refresh();
                }
            });
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
