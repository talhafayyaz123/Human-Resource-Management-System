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
                                $t("Sprint Backlog")
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
            <div class="backlog-card">
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
                        <h3>RVK Sprint 8 18 Nov - 02 Dec. (3 Processes)</h3>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-3">
                            <div class="process-tag done">0Min.</div>
                            <div class="process-tag inwork">0Min.</div>
                            <div class="process-tag todo">0Min.</div>
                            <dropdown
                                :auto-close="false"
                                placement="bottom-end"
                            >
                                <template #default>
                                    <div class="cursor-pointer">
                                        <CustomIcon name="dotBarIcon" />
                                    </div>
                                </template>
                                <template #dropdown>
                                    <div class="dropdown-menu">
                                        <ul class="dropdown-list">
                                            <li class="dropdown-item">
                                                <router-link
                                                    class="flex items-center"
                                                    to="/project-settings-detail"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="editIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Edit")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li class="dropdown-item delete">
                                                <div class="icon mr-2">
                                                    <CustomIcon
                                                        name="DeleteIcon"
                                                    />
                                                </div>
                                                <span>{{ $t("Delete") }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                        <h3 class="mt-4">6 Processes</h3>
                    </div>
                </div>
                <div class="backlog-body" v-if="activeSection === 1">
                    <div class="mb-4">
                        <h3 class="mb-2">Go Live</h3>
                        <div class="flex items-center">
                            <div class="user mr-2">
                                <img
                                    src="@/assets/images/avatar-1.png"
                                    alt=""
                                />
                            </div>
                            <dropdown
                                :auto-close="false"
                                placement="bottom-end"
                            >
                                <template #default>
                                    <div class="cursor-pointer">
                                        <CustomIcon name="dotBarIcon" />
                                    </div>
                                </template>
                                <template #dropdown>
                                    <div class="dropdown-menu">
                                        <ul class="dropdown-list">
                                            <li class="dropdown-item">
                                                <router-link
                                                    class="flex items-center"
                                                    to="/project-settings-detail"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="editIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Edit")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li class="dropdown-item delete">
                                                <div class="icon mr-2">
                                                    <CustomIcon
                                                        name="DeleteIcon"
                                                    />
                                                </div>
                                                <span>{{ $t("Delete") }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                    </div>
                    <ul>
                        <li>
                            <div class="backlog-task" @click="productModalToggle">
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
                                    <div class="ello-tag yellow">
                                        ELO INVOICE PRODUCTIVSY
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
                            <div class="backlog-task" @click="productModalToggle">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="initiateIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <div class="ello-tag green">
                                        ELO BASED
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
                            <div class="backlog-task" @click="productModalToggle">
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
                                    <div class="ello-tag yellow">
                                        ELO INVOICE PRODUCTIVSY
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
            <div class="backlog-divider">
                <CustomIcon name="dividerIcon"/>
            </div>
            <div class="backlog-card">
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
                        <h3>RVK Sprint 8 18 Nov - 02 Dec. (3 Processes)</h3>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-3">
                            <div class="process-tag done">0Min.</div>
                            <div class="process-tag inwork">0Min.</div>
                            <div class="process-tag todo">0Min.</div>
                            <dropdown
                                :auto-close="false"
                                placement="bottom-end"
                            >
                                <template #default>
                                    <div class="cursor-pointer">
                                        <CustomIcon name="dotBarIcon" />
                                    </div>
                                </template>
                                <template #dropdown>
                                    <div class="dropdown-menu">
                                        <ul class="dropdown-list">
                                            <li class="dropdown-item">
                                                <router-link
                                                    class="flex items-center"
                                                    to="/project-settings-detail"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="editIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Edit")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li class="dropdown-item delete">
                                                <div class="icon mr-2">
                                                    <CustomIcon
                                                        name="DeleteIcon"
                                                    />
                                                </div>
                                                <span>{{ $t("Delete") }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                        <h3 class="mt-4">6 Processes</h3>
                    </div>
                </div>
                <div class="backlog-body" v-if="activeSection === 2">
                    <div class="mb-4">
                        <h3 class="mb-2">ELO Contract</h3>
                    </div>
                    <ul>
                        <li>
                            <div class="backlog-task">
                                <div class="flex items-center">
                                    <div class="task-icon">
                                        <CustomIcon name="requestIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <div class="ello-tag blue">
                                        ELOCONTRACT CONFIGRATION
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
            <div class="backlog-divider">
                <CustomIcon name="dividerIcon"/>
            </div>
            <div class="backlog-card">
                <div class="backlog-header" @click="toggleAccordion(3)">
                    <div class="flex items-center">
                        <div class="dropdown-icon">
                            <span v-if="activeSection !== 3"
                                ><CustomIcon name="nextIcon"
                            /></span>
                            <span v-if="activeSection === 3"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <h3>RVK Sprint 8 18 Nov - 02 Dec. (3 Processes)</h3>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-3">
                            <div class="process-tag done">0Min.</div>
                            <div class="process-tag inwork">0Min.</div>
                            <div class="process-tag todo">0Min.</div>
                            <dropdown
                                :auto-close="false"
                                placement="bottom-end"
                            >
                                <template #default>
                                    <div class="cursor-pointer">
                                        <CustomIcon name="dotBarIcon" />
                                    </div>
                                </template>
                                <template #dropdown>
                                    <div class="dropdown-menu">
                                        <ul class="dropdown-list">
                                            <li class="dropdown-item">
                                                <router-link
                                                    class="flex items-center"
                                                    to="/project-settings-detail"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="editIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Edit")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li class="dropdown-item delete">
                                                <div class="icon mr-2">
                                                    <CustomIcon
                                                        name="DeleteIcon"
                                                    />
                                                </div>
                                                <span>{{ $t("Delete") }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                        <h3 class="mt-4">3 Processes</h3>
                    </div>
                </div>
                <div class="backlog-body" v-if="activeSection === 3">
                    <div class="mb-4">
                        <h3 class="mb-2">ELO Contract</h3>
                    </div>
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
                                    <div class="ello-tag sky">
                                        ELO INVOICE CONFIGRATION
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
                                        <CustomIcon name="initiateIcon" />
                                    </div>
                                    <h3>
                                        PP3 Add Module - “Customer” (See old
                                        adminportal)
                                    </h3>
                                </div>
                                <div class="flex items-center">
                                    <div class="ello-tag sky">
                                        ELO INVOICE CONFIGRATION
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
                                    <div class="ello-tag sky">
                                        ELO INVOICE CONFIGRATION
                                    </div>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <button class="ens-btn">
                                                <span>THEM ENSPEICHER</span>
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
                                    <div class="yellow-tag">0Min</div>
                                    <span class="mr-2"
                                        ><CustomIcon name="equalIcon"
                                    /></span>
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
            <div class="backlog-divider">
                <CustomIcon name="dividerIcon"/>
            </div>
        </div>
        <Modal v-if="toggleModal" @toggleModal="CloseModal" />
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
import Modal from './components/ProjectTaskModal.vue'
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
        Modal
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
            toggleModal: false,
        };
    },
    methods: {
        productModalToggle(){
            this.toggleModal = true;
        },  
        CloseModal(){
            this.toggleModal = false;
        },  
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
