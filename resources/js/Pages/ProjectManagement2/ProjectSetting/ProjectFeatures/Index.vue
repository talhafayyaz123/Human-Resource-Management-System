<template>
    <div>
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Admin Portal")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Project Settings")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("Project Setting")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <!---========--->
                <div class="left-side flex items-center">
                    <div class="flex items-center">
                        <div
                            class="search-close"
                            @click="closeSearch"
                            v-if="showSearch"
                        >
                            <icon name="xIcon" />
                        </div>
                        <div class="search-icon" @click="searchToggle()" v-else>
                            <img
                                src="@/assets/images/searchheader.svg"
                                alt=""
                            />
                        </div>
                        <div class="search" v-if="showSearch">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <icon name="search" class="mr-1" />
                                        </span>
                                    </div>
                                    <input
                                        v-model="searchGlobal"
                                        type="search"
                                        placeholder="Search here"
                                        class="form-control"
                                        @blur="handleSearch"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider mr-4"></div>

                    <button class="filterBtn">
                        <icon name="filters" class="mr-1" />
                        <span>{{ $t("Filters") }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="pr-feature-card orange">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="TimelineIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Timeline") }}</h3>
                        <p>
                            Your timeline is an optimized location to create and
                            manage your epics.
                            <router-link to="/"
                                >Learn more about Timeline</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Timeline" class="switch-input" checked>
                        <label for="Timeline" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
            <div class="pr-feature-card gray mt-4">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="BacklogIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Backlog") }}</h3>
                        <p>
                            Your timeline is an optimized location to create and manage your epics. 
                            <router-link to="/"
                                >Learn more about Backlog</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Backlog" class="switch-input">
                        <label for="Backlog" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
            <div class="pr-feature-card blue mt-4">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="BoardIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Board") }}</h3>
                        <p>
                            View, Ticket and Manage work as it makes its way through your team’s process.
                            <router-link to="/"
                                > Learn more about Board</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Board" class="switch-input">
                        <label for="Board" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
            <div class="pr-feature-card red mt-4">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="ReportsIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Reports") }}</h3>
                        <p>
                            Analyze and track your team’s work by reporting on the project’s activity. 
                            <router-link to="/"
                                >Learn more about Reports</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Reports" class="switch-input">
                        <label for="Reports" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
            <div class="pr-feature-card gray mt-4">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="CodeIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Code") }}</h3>
                        <p>
                            Connect Bitbucket, GitHub, and other development tools to connect your team's repositories to DocsHero.
                            <router-link to="/"
                                > Learn more about Code</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Code" class="switch-input">
                        <label for="Code" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
            <div class="pr-feature-card blue mt-4">
                <div class="pr-left">
                    <div class="icon">
                        <CustomIcon name="ReleasesIcon" />
                    </div>
                    <div>
                        <h3 class="card-title">{{ $t("Releases") }}</h3>
                        <p>
                            Versions help with packaging and planning project deliveries. 
                            <router-link to="/"
                                >Learn more about Releases</router-link
                            >
                        </p>
                    </div>
                </div>
                <div class="pr-right">
                    <div class="switch-group">
                        <input type="checkbox" id="Releases" class="switch-input">
                        <label for="Releases" class="switch-label"></label>
                    </div>
                    <div class="settings">
                        <CustomIcon name="settingIcon" />
                    </div>
                </div>
            </div>
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
import { debounce } from "@/utils/debounce";
import Dropdown from "@/Components/Dropdown.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
export default {
    computed: {
        ...mapGetters("projects", ["projects"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput,
        Dropdown,
        MultiSelectInput,
    },

    data() {
        return {
            showSearch: false,
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
        };
    },
    methods: {
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("project-management-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-page");
        next();
    },
};
</script>
