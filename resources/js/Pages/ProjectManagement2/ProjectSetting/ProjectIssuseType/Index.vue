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
                                $t("Issue Type")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
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

        <div class="issuse-type-page">
            <div class="issuse-type-left">
                <div class="issuse-type-box">
                    <div class="issuse-header">
                        <h3 class="mr-2">{{ $t("Issue Type") }}</h3>
                        <CustomIcon name="iIcon" />
                    </div>
                    <div class="issuse-body">
                        <IssuseRightBar />
                    </div>
                </div>
            </div>
            <div class="issuse-type-center">
                <div class="card">
                    <div class="">
                        <div class="flex items-center mb-2">
                            <span class="mr-2"
                                ><CustomIcon name="taskFIcon"
                            /></span>
                            <h3 class="card-title">{{ $t("Task") }}</h3>
                        </div>
                        <p>Tasks track small, distinct pieces of work.</p>
                    </div>
                    <div class="desc-fields mt-4">
                        <h3 class="card-title">
                            {{ $t("Description Fields") }}
                        </h3>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="shortTextIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Summary"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none; right: 14px"
                                >
                                    <span class="input-group-text">
                                        <button
                                            class="secondary-btn"
                                            style="padding: 6px; height: auto"
                                        >
                                            Required
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="paragraphIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Description"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none"
                                >
                                    <span class="input-group-text">
                                        <div class="">
                                            <CustomIcon name="nextBoldIcon" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="desc-fields mt-4">
                        <h3 class="card-title">
                            {{ $t("Context Fields") }}
                        </h3>
                        <button class="primary-btn mt-3">
                            <span>{{ $t("Status") }}</span>
                            <span class="ml-2"
                                ><CustomIcon name="nextBoldIcon"
                            /></span>
                        </button>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="peopleIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Assignee"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none"
                                >
                                    <span class="input-group-text">
                                        <div class="">
                                            <CustomIcon name="nextBoldIcon" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="dateFieldIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Due Date"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none"
                                >
                                    <span class="input-group-text">
                                        <div class="">
                                            <CustomIcon name="nextBoldIcon" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="labelFieldIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Labels"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none"
                                >
                                    <span class="input-group-text">
                                        <div class="">
                                            <CustomIcon name="nextBoldIcon" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="divider-group flex items-center">
                            <div class="divider"></div>
                            <p class="divider-text">Hide When Empty</p>
                            <div class="divider"></div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <CustomIcon name="peopleIcon" />
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Reporter"
                                />
                                <div
                                    class="input-group-append"
                                    style="border: none"
                                >
                                    <span class="input-group-text">
                                        <div class="">
                                            <CustomIcon name="nextBoldIcon" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button class="primary-btn mr-3">
                        <span class="mr-1">
                            <CustomIcon name="DeleteIcon" />
                        </span>
                        <span>{{ $t("Discard") }}</span>
                    </button>
                    <button class="secondary-btn">
                        <span class="mr-1">
                            <CustomIcon name="saveIcon" />
                        </span>
                        <span>{{ $t("Save and Proceed") }}</span>
                    </button>
                </div>
            </div>
            <div class="issuse-type-right">
                <div class="issuse-type-box">
                    <div class="issuse-header">
                        <h3 class="mr-2">{{ $t("Fields") }}</h3>
                        <CustomIcon name="iIcon" />
                    </div>
                    <div class="issuse-body">
                        <div class="create-field">
                            <div class="flex items-center justify-between">
                                <h3>{{ $t("Create a Field") }}</h3>
                                <div class="down-ico">
                                    <CustomIcon name="downIcon" />
                                </div>
                            </div>
                            <p>
                                Drag a field to one of the sections on the left
                                to create a custom field for this issue type.
                            </p>
                            <div class="fields">
                                <div
                                    class="grid items-center justify-between grid-cols-3 gap-4"
                                >
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="shortTextIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Short Text") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="paragraphIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Paragraph") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="dateFieldIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Date") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon name="numberIcon" />
                                            </div>
                                            <h4>{{ $t("Number") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="clockFieldIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Time Stamp") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="labelFieldIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Labels") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="dropdownIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Dropdown") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="checkboxIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("Checkbox") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon name="peopleIcon" />
                                            </div>
                                            <h4>{{ $t("People") }}</h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="dropdownIcon"
                                                />
                                            </div>
                                            <h4>
                                                {{ $t("Dependent Dropdown") }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="field">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="linkFieldIcon"
                                                />
                                            </div>
                                            <h4>{{ $t("URL") }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <label for="">{{ $t("Search all fields") }}</label>
                            <div class="form-group mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <CustomIcon
                                                name="searchFieldIcon"
                                            />
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Type to search all fields"
                                    />
                                </div>
                            </div>
                            <p>
                                Reuse 31 fields from other issue types and
                                projects
                            </p>
                            <div class="mt-3">
                                <label for="">{{
                                    $t("Suggested Fields")
                                }}</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <CustomIcon name="dateIcon" />
                                            </span>
                                        </div>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Start Date"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
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

import IssuseRightBar from "./components/IssuseRightBar.vue";

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
        TextInput,
        TextArea,
        Modal,
        LoadingButton,
        IssuseRightBar,
    },

    data() {
        return {
            showSearch: false,
            toggleNotificationModal: false,
            toggleNotificationEmailModal: false,
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
        addNotiModal() {
            this.toggleNotificationModal = true;
        },
        hideModal() {
            this.toggleNotificationModal = false;
        },
        editNotiEmailModal() {
            this.toggleNotificationEmailModal = true;
        },
        hideModal2() {
            this.toggleNotificationEmailModal = false;
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
