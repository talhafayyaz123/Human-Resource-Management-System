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
                    <button class="filterBtn">
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
                        <span>{{ $t("Create Status Message") }}</span>
                    </router-link>
                </div>
                <!---========--->
            </div>
        </div>

        <div class="grid items-start grid-cols-2 gap-6">
            <div class="w-full">
                <div class="card">
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full col-span-2">
                                <div class="flex items-center justify-center">
                                    <div class="profile-upload-image">
                                        <img
                                            class="profile-img"
                                            src="@/assets/images/tobais.png"
                                            alt=""
                                        />
                                        <button class="upload-btn">
                                            <CustomIcon name="camimageIcon" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text cursor-pointer"
                                            >
                                                <CustomIcon name="buildIcon" />
                                            </span>
                                        </div>
                                        <text-input
                                            v-model="form.prname"
                                            :label="$t('Project Name')"
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.prname"
                                        :label="$t('Project Number')"
                                        :required="true"
                                    />
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <text-area
                                        v-model="form.prname"
                                        :label="$t('Project Description')"
                                        :required="true"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        :key="form.participants"
                                        v-model="form.participants"
                                        :options="users ?? []"
                                        :required="true"
                                        :multiple="true"
                                        :text-label="$t('Project Type')"
                                        :customLabel="customLabel"
                                        trackBy="id"
                                        moduleName="auth"
                                        :search-param-name="'search_string'"
                                        :query="{
                                            limit_start: 0,
                                            limit_count: 10,
                                            type: 'employee',
                                        }"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        :key="form.participants"
                                        v-model="form.participants"
                                        :options="users ?? []"
                                        :required="true"
                                        :multiple="true"
                                        :text-label="$t('Project Category')"
                                        :customLabel="customLabel"
                                        trackBy="id"
                                        moduleName="auth"
                                        :search-param-name="'search_string'"
                                        :query="{
                                            limit_start: 0,
                                            limit_count: 10,
                                            type: 'employee',
                                        }"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <label class="input-label"
                                        >{{ $t("Start Date") }}&nbsp;<span
                                            style="color: red"
                                            >*</span
                                        ></label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :style="dropdownStyles"
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="form.startDate"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <label class="input-label"
                                        >{{ $t("End Date") }}&nbsp;<span
                                            style="color: red"
                                            >*</span
                                        ></label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :style="dropdownStyles"
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="form.startDate"
                                    />
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        :key="form.participants"
                                        v-model="form.participants"
                                        :options="users ?? []"
                                        :required="true"
                                        :multiple="true"
                                        :text-label="$t('Customer')"
                                        :customLabel="customLabel"
                                        trackBy="id"
                                        moduleName="auth"
                                        :search-param-name="'search_string'"
                                        :query="{
                                            limit_start: 0,
                                            limit_count: 10,
                                            type: 'employee',
                                        }"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group flex items-center">
                                    <label for="" class="mr-4"
                                        >Special Invoicing</label
                                    >
                                    <div class="checkbox-group mr-2">
                                        <input
                                            type="radio"
                                            id="yes"
                                            class="checkbox-input"
                                            name="Invoicing"
                                        />
                                        <label for="yes" class="checkbox-label"
                                            >Yes</label
                                        >
                                    </div>
                                    <div class="checkbox-group">
                                        <input
                                            type="radio"
                                            id="no"
                                            class="checkbox-input"
                                            name="Invoicing"
                                        />
                                        <label for="no" class="checkbox-label"
                                            >No</label
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group flex items-center">
                                    <label for="" class="mr-4"
                                        >Internal Project</label
                                    >
                                    <div class="checkbox-group mr-2">
                                        <input
                                            type="radio"
                                            id="yess"
                                            class="checkbox-input"
                                            name="Project"
                                        />
                                        <label for="yess" class="checkbox-label"
                                            >Yes</label
                                        >
                                    </div>
                                    <div class="checkbox-group">
                                        <input
                                            type="radio"
                                            id="noo"
                                            class="checkbox-input"
                                            name="Project"
                                        />
                                        <label for="noo" class="checkbox-label"
                                            >No</label
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.prname"
                                        :label="$t('Order Confirmation')"
                                        :required="true"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text cursor-pointer"
                                            >
                                                <CustomIcon name="clockIcon" />
                                            </span>
                                        </div>
                                        <text-input
                                            v-model="form.prname"
                                            :label="$t('Budget')"
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text cursor-pointer"
                                            >
                                                <CustomIcon name="clockIcon" />
                                            </span>
                                        </div>
                                        <text-input
                                            v-model="form.prname"
                                            :label="$t('Used Hours')"
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        :key="form.participants"
                                        v-model="form.participants"
                                        :options="users ?? []"
                                        :required="true"
                                        :multiple="true"
                                        :text-label="$t('Project Status')"
                                        :customLabel="customLabel"
                                        trackBy="id"
                                        moduleName="auth"
                                        :search-param-name="'search_string'"
                                        :query="{
                                            limit_start: 0,
                                            limit_count: 10,
                                            type: 'employee',
                                        }"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group flex items-center">
                                    <label for="" class="mr-4">{{
                                        $t("Escalation Level")
                                    }}</label>
                                    <CustomIcon name="flagIcon" />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group flex items-center">
                                    <label for="" class="mr-4"
                                        >Project Manager</label
                                    >
                                    <ul class="team-list">
                                        <li>
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group flex items-center">
                                    <label for="" class="mr-4"
                                        >Project Team Member</label
                                    >
                                    <ul class="team-list">
                                        <li>
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </li>
                                        <li>
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </li>
                                        <li>
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </li>
                                        <li>
                                            <img
                                                src="@/assets/images/avatar-4.png"
                                                alt=""
                                            />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Messages</h3>
                    </div>
                    <div class="card-body">
                        <div class="pr-message-card">
                            <div class="message-card-header">
                                <div class="flex items-center">
                                    <div class="user-det mr-2">
                                        <img
                                            src="http://[::1]:5173/resources/js/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                        <h4>Muhammad Hassan</h4>
                                    </div>
                                    <div class="card-meta">
                                        <div class="flex items-start mr-1">
                                            <div class="mr-1">
                                                <CustomIcon name="datePrIcon" />
                                            </div>
                                            <p>Monday - 13th Nov</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-1">
                                                <CustomIcon
                                                    name="clockPrIcon"
                                                />
                                            </div>
                                            <p>12:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-mode-pr">
                                    <CustomIcon name="editPrIcon" />
                                </div>
                            </div>
                            <div class="message-card-content">
                                <h2>Total cost plan value is adhered to</h2>
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged.
                                </p>
                            </div>
                        </div>
                        <div class="pr-message-card mt-3 orange">
                            <div class="message-card-header">
                                <div class="flex items-center">
                                    <div class="user-det mr-2">
                                        <img
                                            src="http://[::1]:5173/resources/js/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                        <h4>Muhammad Hassan</h4>
                                    </div>
                                    <div class="card-meta">
                                        <div class="flex items-start mr-1">
                                            <div class="mr-1">
                                                <CustomIcon name="datePrIcon" />
                                            </div>
                                            <p>Monday - 13th Nov</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-1">
                                                <CustomIcon
                                                    name="clockPrIcon"
                                                />
                                            </div>
                                            <p>12:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-mode-pr">
                                    <CustomIcon name="editPrIcon" />
                                </div>
                            </div>
                            <div class="message-card-content">
                                <h2>Total cost plan value is adhered to</h2>
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged.
                                </p>
                            </div>
                        </div>
                        <div class="pr-message-card mt-3 sky">
                            <div class="message-card-header">
                                <div class="flex items-center">
                                    <div class="user-det mr-2">
                                        <img
                                            src="http://[::1]:5173/resources/js/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                        <h4>Muhammad Hassan</h4>
                                    </div>
                                    <div class="card-meta">
                                        <div class="flex items-start mr-1">
                                            <div class="mr-1">
                                                <CustomIcon name="datePrIcon" />
                                            </div>
                                            <p>Monday - 13th Nov</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-1">
                                                <CustomIcon
                                                    name="clockPrIcon"
                                                />
                                            </div>
                                            <p>12:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-mode-pr">
                                    <CustomIcon name="editPrIcon" />
                                </div>
                            </div>
                            <div class="message-card-content">
                                <h2>Total cost plan value is adhered to</h2>
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged.
                                </p>
                            </div>
                        </div>
                        <div class="pr-message-card mt-3">
                            <div class="message-card-header">
                                <div class="flex items-center">
                                    <div class="user-det mr-2">
                                        <img
                                            src="http://[::1]:5173/resources/js/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                        <h4>Muhammad Hassan</h4>
                                    </div>
                                    <div class="card-meta">
                                        <div class="flex items-start mr-1">
                                            <div class="mr-1">
                                                <CustomIcon name="datePrIcon" />
                                            </div>
                                            <p>Monday - 13th Nov</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-1">
                                                <CustomIcon
                                                    name="clockPrIcon"
                                                />
                                            </div>
                                            <p>12:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-mode-pr">
                                    <CustomIcon name="editPrIcon" />
                                </div>
                            </div>
                            <div class="message-card-content">
                                <h2>Total cost plan value is adhered to</h2>
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged.
                                </p>
                            </div>
                        </div>
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
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextareaInput.vue";
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
        TextInput,
        TextArea,
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
