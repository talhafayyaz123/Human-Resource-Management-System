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
                                $t("Details")
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
                            placeholder="Role"
                            :query="{
                                limit_start: 0,
                                limit_count: 10,
                                type: 'employee',
                            }"
                        />
                    </div>
                    <button class="filterBtn">
                        <icon name="filters" class="mr-1" />
                        <span>{{ $t("Filters") }}</span>
                    </button>
                    <div class="divider ml-4"></div>
                </div>
                <!---========--->
                <div class="right-side">
                    <router-link to="/" class="primary-btn mr-3">
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
                        <span>{{ $t("Add People") }}</span>
                    </router-link>
                    <router-link to="/" class="primary-btn">
                        <span>{{ $t("Manage Role") }}</span>
                    </router-link>
                </div>
                <!---========--->
            </div>
        </div>

        <div class="access-page mb-4">
            <div class="flex items-center">
                <div class="access-status mr-4">
                    <CustomIcon name="lckIcon" />
                    <h3 class="ml-2">Private</h3>
                </div>
                <button class="primary-btn mr-4">{{ $t("Change Project Access") }}</button>
                <div class="acces-info">
                    <CustomIcon name="infIcon" />
                    <p class="ml-2">Only admins and people you add to the project can search for, view, create, or edit its issues.</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left">
                        <th>
                            <div class="">{{ $t("Name") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Email") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Project Group") }}</div>
                        </th>
                        <th>
                            <div class="text-center">{{ $t("Action") }}</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <img src="@/assets/images/avatar-1.png" />
                                <span class="ml-2">Tobias Schmidt-tudl</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <CustomIcon name="emailTableIcon" />
                                <span class="ml-2">loremipsum@gmail.com</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Customer") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center cursor-pointer">
                                <CustomIcon name="DeleteIcon" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <img src="@/assets/images/avatar-1.png" />
                                <span class="ml-2">Tobias Schmidt-tudl</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <CustomIcon name="emailTableIcon" />
                                <span class="ml-2">loremipsum@gmail.com</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Customer") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center cursor-pointer">
                                <CustomIcon name="DeleteIcon" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <img src="@/assets/images/avatar-1.png" />
                                <span class="ml-2">Tobias Schmidt-tudl</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <CustomIcon name="emailTableIcon" />
                                <span class="ml-2">loremipsum@gmail.com</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span class="">{{ $t("Customer") }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center cursor-pointer">
                                <CustomIcon name="DeleteIcon" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
