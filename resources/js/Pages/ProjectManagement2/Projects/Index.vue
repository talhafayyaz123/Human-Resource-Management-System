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
                            <router-link to="/project-management2/project">{{
                                $t("Project Management 2")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("Projects")
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
                            <div class="form-group m-0" style="margin: 0;">
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
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :key="form.participants"
                            v-model="form.participants"
                            :options="users ?? []"
                            :required="true"
                            :multiple="true"
                            :customLabel="customLabel"
                            trackBy="id"
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            placeholder="All Products"
                            :query="{
                                limit_start: 0,
                                limit_count: 10,
                                type: 'employee',
                            }"
                        />
                    </div>
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :key="form.participants"
                            v-model="form.participants"
                            :options="users ?? []"
                            :required="true"
                            :multiple="true"
                            :customLabel="customLabel"
                            trackBy="id"
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            placeholder="All Categories"
                            :query="{
                                limit_start: 0,
                                limit_count: 10,
                                type: 'employee',
                            }"
                        />
                    </div>
                    <button class="filterBtn" @click="openFilterModal">
                        <icon name="filters" class="mr-1" />
                        <span>{{ $t("Filters") }}</span>
                    </button>
                    <div class="divider ml-4"></div>
                </div>
                <!---========--->
                <div class="right-side">
                    <router-link to="/" class="primary-btn">
                        <div class="mr-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="21"
                                viewBox="0 0 20 21"
                                fill="none"
                            >
                                <path
                                    d="M9.9974 4.66602V16.3327M4.16406 10.4993H15.8307"
                                    stroke="#38414A"
                                    stroke-width="1.67"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <span>{{ $t("Create Project") }}</span>
                    </router-link>
                </div>
                <!---========--->
            </div>
        </div>

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left">
                        <th>
                            <div class="">{{ $t("Project Name") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Number") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Manager") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Type") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Category") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Budget") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Status") }}</div>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <CustomIcon name="RewampIcon" />
                                <span class="ml-2">{{
                                    $t("Adminportal - Revamp")
                                }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span>000032</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <img src="@/assets/images/avatar-1.png" />
                                <span class="ml-2">Tobias Schmidt-tudl</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Kanban") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Customer") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <CustomIcon name="clockIcon" />
                                <span class="ml-2">{{ $t("20h") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Pending") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
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
                                                                name="settingIcon"
                                                            />
                                                        </div>
                                                        <span>{{
                                                            $t(
                                                                "Project Setting"
                                                            )
                                                        }}</span>
                                                    </router-link>
                                                </li>
                                                <li class="dropdown-item">
                                                    <router-link
                                                        class="flex items-center"
                                                        to="/project-news"
                                                    >
                                                        <div class="icon mr-2">
                                                            <CustomIcon
                                                                name="eyeIcon"
                                                            />
                                                        </div>
                                                        <span>{{
                                                            $t(
                                                                "Show"
                                                            )
                                                        }}</span>
                                                    </router-link>
                                                </li>
                                                <li
                                                    class="dropdown-item delete"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="DeleteIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Delete")
                                                    }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                </dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Modal
        title="Filters"
        v-if="toggleFilterModal"
        @toggleModal="hideModal"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="">
                <p>Choose the filters you desire from the main bar.</p>
           
                <div class="checkbox-group mt-3">
                    <input
                        type="checkbox"
                        id="Watchers"
                        class="checkbox-input"
                    />
                    <label for="Watchers" class="checkbox-label">{{
                        $t("All Products")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input
                        type="checkbox"
                        id="Assignee"
                        class="checkbox-input"
                    />
                    <label for="Assignee" class="checkbox-label">{{
                        $t("All Categories")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input type="checkbox" id="User" class="checkbox-input" />
                    <label for="User" class="checkbox-label">{{
                        $t("Status")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input
                        type="checkbox"
                        id="Reporter"
                        class="checkbox-input"
                    />
                    <label for="Reporter" class="checkbox-label">{{
                        $t("Type")
                    }}</label>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="hideModal" type="button" class="primary-btn mr-3">
                <CustomIcon name="cancelIcon" />
                <span>{{ $t("Cancel") }}</span>
            </button>
            <loading-button class="secondary-btn" :loading="isLoading">
                <CustomIcon name="saveIcon" />
                <span>{{ $t("Save") }}</span>
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
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
        Modal
    },

    data() {
        return {
            showSearch: false,
            toggleFilterModal: false,
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
        };
    },
    methods: {
        openFilterModal() {
            this.toggleFilterModal = true;
        },
        hideModal() {
            this.toggleFilterModal = false;
        },
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
    }
};
</script>
