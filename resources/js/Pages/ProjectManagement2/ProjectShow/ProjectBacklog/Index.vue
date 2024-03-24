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
                                $t("Project")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("Scrum Backlog")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <!---========--->
                <div class="left-side flex items-center">
                    <div class="users-list mr-5">
                        <ul>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-1.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-2.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-3.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-4.png"
                                    alt=""
                                />
                            </li>
                        </ul>
                    </div>
                    <button class="filterBtn mr-4">
                        <icon name="usersIcon" class="mr-1" />
                        <span>{{ $t("Share") }}</span>
                    </button>
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
                <!---========--->
            </div>
        </div>

        <div class="project-backlog-page">
            <div class="backlog-item">
                <div class="backlog-header" @click="toggleAccordion(1)">
                    <div class="flex items-center">
                        <div class="dropdown-icon">
                            <span v-if="activeSection !== 1"
                                ><CustomIcon name="nextIcon"
                            /></span>
                            <span v-if="activeSection === 1"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <h3>Board (14 Operations)</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <h3>6 Processes</h3>
                        <div class="process-tag">6</div>
                        <div class="process-tag">7</div>
                        <div class="process-tag">2</div>
                    </div>
                </div>
                <div class="backlog-body" v-if="activeSection === 1">
                    <ul>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="themeIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn done">
                                                <span>Done</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="epicIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn inwork">
                                                <span>In Work</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="SupportIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn todo">
                                                <span>To Do</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="themeIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn done">
                                                <span>Done</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="epicIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn inwork">
                                                <span>In Work</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="SupportIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn todo">
                                                <span>To Do</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="themeIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn done">
                                                <span>Done</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="epicIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn inwork">
                                                <span>In Work</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="SupportIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="work-btn todo">
                                                <span>To Do</span>
                                                <div class="">
                                                    <CustomIcon
                                                        name="downIcon"
                                                    />
                                                </div>
                                            </button>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li
                                                        class="dropdown-item"
                                                    ></li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                    <div class="user">
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <button class="primary-btn mt-4">
                        <span class="mr-2">
                            <CustomIcon name="addIcon" />
                        </span>
                        <span>Create Operation</span>
                    </button>
                </div>
            </div>
            <div class="backlog-item">
                <div class="backlog-header" @click="toggleAccordion(2)">
                    <div class="flex items-center">
                        <div class="dropdown-icon">
                            <span v-if="activeSection !== 2"
                                ><CustomIcon name="nextIcon"
                            /></span>
                            <span v-if="activeSection === 2"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <h3>Backlog (0 Issues)</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <h3>6 Processes</h3>
                        <div class="process-tag">6</div>
                        <div class="process-tag">7</div>
                        <div class="process-tag">2</div>
                    </div>
                </div>
                <div class="backlog-body" v-if="activeSection === 2">
                    <div class="no-backlog">Your backlog is empty.</div>
                    <button class="primary-btn mt-4">
                        <span class="mr-2">
                            <CustomIcon name="addIcon" />
                        </span>
                        <span>Create Operation</span>
                    </button>
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
            activeSection: null,
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
        };
    },
    methods: {
        toggleAccordion(section) {
            if (this.activeSection === section) {
                this.activeSection = null;
            } else {
                this.activeSection = section;
            }
        },
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("project-management-show-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-show-page");
        next();
    },
};
</script>
