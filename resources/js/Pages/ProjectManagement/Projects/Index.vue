<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group mt-1">
                    <select-input v-model="form.status" :label="$t('Status')">
                        <option value="new">{{ $t("new") }}</option>
                        <option value="done">{{ $t("done") }}</option>
                        <option value="in-work">{{ $t("in-work") }}</option>
                        <option value="in-review">{{ $t("in-review") }}</option>
                        <option value="blocked">{{ $t("blocked") }}</option>
                    </select-input>
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('projectNumber', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Project Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'projectNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('name', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Project Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('companyName', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Company Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('plannedStartDate', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Planned Start Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'plannedStartDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('plannedFinishedDate', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Planned Finished Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'plannedFinishedDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('estimatedTime', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Estimated time")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'estimatedTime'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('neededTime', 'projects')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Needed time")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'neededTime'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="project in projects?.data ?? []"
                    :key="project.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/project-management?id=${project.id}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ project.projectNumber }}
                        </a>
                    </td>
                    <td class="border-t">
                        <router-link
                            click.stop=""
                            :is="
                                $can('project-management-show')
                                    ? 'router-link'
                                    : 'span'
                            "
                            :to="`/project-management?id=${project.id}`"
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500 link overflow-text-project"
                        >
                            {{ project.name }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ project.companyName }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ project.status }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(project?.plannedStartDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(project?.plannedFinishedDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ project.estimatedTime }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ project.neededTime }}
                        </a>
                    </td>
                    <td class="w-px border-t text-center">
                        <router-link
                            click.stop=""
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-2"
                            :to="`/project-management/projects/${project.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(project.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(projects?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No projects found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="projects"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce"
export default {
    computed: {
        ...mapGetters("projects", ["projects"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput
    },
    props: {
        filters: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Project Management"),
                    to: "/project-management/projects",
                },
                {
                    text: this.$t("Projects"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Project"),
                btn2Path: "/project-management/projects/create",
            },
            page: 1,
            window,
            form: {
                search: "",
                status: "",
                sortBy:
                    localStorage.getItem("sort_projects_column") ??
                    "projectNumber",
                sortOrder: localStorage.getItem("sort_projects_order") ?? "asc",
            },
        };
    },
    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.status'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "projects/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        var isPginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            isPginate=true;
        }
        this.refresh(isPginate);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("projects/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            try {
                if (!this.projects.length || bypass)
                    await this.$store.dispatch(
                        "projects/list",
                      {
                        ...this.pickBy(this.form),
                        page: this.page,
                      }
                    );
            } catch (e) {}
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
                    await this.$store.dispatch("projects/destroy", id);
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
.link {
    text-decoration: underline;
    color: blue;
}
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
.overflow-text-project {
    width: 30ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}
.overflow-text-project:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
</style>
