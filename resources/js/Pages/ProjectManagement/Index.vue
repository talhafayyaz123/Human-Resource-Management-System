<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link
                class="text-indigo-400 hover:text-indigo-600"
                to="/project-management/projects"
                >{{ $t("Projects") }}</router-link
            >
            <span class="text-indigo-400 font-medium">/</span>
            {{ $t("Project Management") }}
        </h1>
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap mx-auto">
                <a
                    v-if="$can(`project.edit`)"
                    @click="activeTab = 'details'"
                    :class="
                        activeTab === 'details'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Project Details") }}
                </a>
                <a
                    v-if="$can(`milestone.list`)"
                    @click="activeTab = 'milestones'"
                    :class="
                        activeTab === 'milestones'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Milestones") }}
                </a>
                <a
                    v-if="$can(`task.list`)"
                    @click="activeTab = 'tasks'"
                    :class="
                        activeTab === 'tasks' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Tasks") }}
                </a>
                <a
                    @click="activeTab = 'backlog'"
                    :class="
                        activeTab === 'backlog'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Backlog") }}
                </a>
                <a
                    v-if="$can('resources')"
                    @click="activeTab = 'resources'"
                    :class="
                        activeTab === 'resources'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Resources") }}
                </a>
                <a
                    v-if="$can(`board`)"
                    @click="activeTab = 'board'"
                    :class="
                        activeTab === 'board' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Board") }}
                </a>
                <a
                    v-if="$can(`overview`)"
                    @click="activeTab = 'overview'"
                    :class="
                        activeTab === 'overview'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Overview") }}
                </a>
            </div>
        </div>
        <div id="myTabContent">
            <div
                v-if="activeTab === 'details'"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <Details
                    :isDetails="true"
                    :modelData="modelData"
                    :companies="companies"
                    :users="users"
                />
            </div>
            <div
                v-else-if="activeTab === 'milestones'"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <Milestones />
            </div>
            <div
                v-else-if="activeTab === 'tasks'"
                id="settings"
                role="tabpanel"
                aria-labelledby="settings-tab"
            >
                <Tasks :milestones="milestones" :users="employees" />
            </div>
            <div
                v-else-if="activeTab === 'backlog'"
                id="settings"
                role="tabpanel"
                aria-labelledby="settings-tab"
            >
                <Backlog />
            </div>
            <div
                v-else-if="activeTab === 'resources'"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <Resources
                    :tasks="tasks"
                    :milestones="milestones"
                    :employees="employees"
                />
            </div>
            <div
                v-else-if="activeTab === 'board'"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <Board
                    :tasks="tasks"
                    :milestones="milestones"
                    :employees="employees"
                />
            </div>
            <div
                v-else-if="activeTab === 'overview'"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <Overview
                    :tasks="tasks"
                    :milestones="milestones"
                    :employees="employees"
                />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import Milestones from "./Milestones/index.vue";
import Tasks from "./Tasks/index.vue";
import Backlog from "./Backlog/Index.vue";
import Resources from "./Resources/Index.vue";
import Board from "./Board/Index.vue";
import Overview from "./Overview/Index.vue";
import Details from "./Projects/Edit.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("projectManagement", ["tasks", "milestones"]),
        ...mapGetters("auth", {
            employees: "users",
        }),
    },
    components: {
        LoadingButton,
        Milestones,
        Tasks,
        Resources,
        Details,
        Board,
        Backlog,
        Overview,
    },
    data() {
        return {
            activeTab: "details",
            modelData: {},
            companies: [],
            users: [],
            activeClasses:
                "inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font cursor-pointer",
            inactiveClasses:
                "inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900 cursor-pointer",
        };
    },
    async mounted() {
        this.refresh();
        let tab = this.$route.query.tab;
        if (tab) {
            this.activeTab = [
                "details",
                "milestones",
                "tasks",
                "backlog",
                "resources",
                "board",
                "overview",
            ].includes(tab)
                ? tab
                : "details";
        }
        try {
            const response = await this.$store.dispatch(
                "projects/show",
                this.$route.query.id
            );
            this.modelData = response?.data?.project ?? {};
            this.companies = response?.data?.companies ?? [];
            this.users = response?.data?.users ?? [];
        } catch (e) {}
    },
    methods: {
        async refresh() {
            try {
                await this.$store.dispatch(
                    "projectManagement/projectData",
                    this.$route.query.id
                );
                this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
            } catch (e) {}
        },
    },
};
</script>
