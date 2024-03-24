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
                            <span class="cursor-pointer">{{ $t("News") }}</span>
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
                        <span class="mr-2">
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
                        </span>
                        <span>{{ $t("Add News") }}</span>
                    </router-link>
                </div>
                <!---========--->
            </div>
        </div>

        <div class="project-news-page">
            <div class="card p-4">
                <ul class="news-list">
                    <li class="green">
                        <div class="news-meta">
                            <div class="date flex items-center mr-2">
                                <div class="icon mr-1">
                                    <CustomIcon name="dateIcon" />
                                </div>
                                <p>2023-08-20</p>
                            </div>
                            <div class="date flex items-center">
                                <div class="icon mr-1">
                                    <CustomIcon name="clockIcon" />
                                </div>
                                <p>12:35 PM</p>
                            </div>
                        </div>
                        <div class="news-card">
                            <div class="news-card-header">
                                <div class="flex items-center user-profile">
                                    <img
                                        src="@/assets/images/tobais.png"
                                        alt=""
                                    />
                                    <h3>Tobias Schmidt-Tudl</h3>
                                </div>
                                <div class="">
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
                                                            to="/"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="editIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t("Edit")
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
                            </div>
                            <div class="news-card-body">
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop
                                    publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum. Lorem
                                    Ipsum is simply dummy text of the printing
                                    and typesetting industry. Lorem Ipsum has
                                    been the industry's standard dummy text ever
                                    since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to
                                    make a type specimen book. It has survived
                                    not only five centuries, but also the leap
                                    into electronic typesetting, remaining
                                    essentially unchanged. It was popularised in
                                    the 1960s with the release of Letraset
                                    sheets containing Lorem Ipsum passages, and
                                    more recently with desktop publishing
                                    software like Aldus PageMaker including
                                    versions of Lorem Ipsum.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="orange">
                        <div class="news-meta">
                            <div class="date flex items-center mr-2">
                                <div class="icon mr-1">
                                    <CustomIcon name="dateIcon" />
                                </div>
                                <p>2023-08-20</p>
                            </div>
                            <div class="date flex items-center">
                                <div class="icon mr-1">
                                    <CustomIcon name="clockIcon" />
                                </div>
                                <p>12:35 PM</p>
                            </div>
                        </div>
                        <div class="news-card">
                            <div class="news-card-header">
                                <div class="flex items-center user-profile">
                                    <img
                                        src="@/assets/images/tobais.png"
                                        alt=""
                                    />
                                    <h3>Tobias Schmidt-Tudl</h3>
                                </div>
                                <div class="">
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
                                                            to="/"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="editIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t("Edit")
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
                            </div>
                            <div class="news-card-body">
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop
                                    publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum. Lorem
                                    Ipsum is simply dummy text of the printing
                                    and typesetting industry. Lorem Ipsum has
                                    been the industry's standard dummy text ever
                                    since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to
                                    make a type specimen book. It has survived
                                    not only five centuries, but also the leap
                                    into electronic typesetting, remaining
                                    essentially unchanged. It was popularised in
                                    the 1960s with the release of Letraset
                                    sheets containing Lorem Ipsum passages, and
                                    more recently with desktop publishing
                                    software like Aldus PageMaker including
                                    versions of Lorem Ipsum.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="sky">
                        <div class="news-meta">
                            <div class="date flex items-center mr-2">
                                <div class="icon mr-1">
                                    <CustomIcon name="dateIcon" />
                                </div>
                                <p>2023-08-20</p>
                            </div>
                            <div class="date flex items-center">
                                <div class="icon mr-1">
                                    <CustomIcon name="clockIcon" />
                                </div>
                                <p>12:35 PM</p>
                            </div>
                        </div>
                        <div class="news-card ">
                            <div class="news-card-header">
                                <div class="flex items-center user-profile">
                                    <img
                                        src="@/assets/images/tobais.png"
                                        alt=""
                                    />
                                    <h3>Tobias Schmidt-Tudl</h3>
                                </div>
                                <div class="">
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
                                                            to="/"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="editIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t("Edit")
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
                            </div>
                            <div class="news-card-body">
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop
                                    publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum. Lorem
                                    Ipsum is simply dummy text of the printing
                                    and typesetting industry. Lorem Ipsum has
                                    been the industry's standard dummy text ever
                                    since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to
                                    make a type specimen book. It has survived
                                    not only five centuries, but also the leap
                                    into electronic typesetting, remaining
                                    essentially unchanged. It was popularised in
                                    the 1960s with the release of Letraset
                                    sheets containing Lorem Ipsum passages, and
                                    more recently with desktop publishing
                                    software like Aldus PageMaker including
                                    versions of Lorem Ipsum.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="red">
                        <div class="news-meta">
                            <div class="date flex items-center mr-2">
                                <div class="icon mr-1">
                                    <CustomIcon name="dateIcon" />
                                </div>
                                <p>2023-08-20</p>
                            </div>
                            <div class="date flex items-center">
                                <div class="icon mr-1">
                                    <CustomIcon name="clockIcon" />
                                </div>
                                <p>12:35 PM</p>
                            </div>
                        </div>
                        <div class="news-card">
                            <div class="news-card-header">
                                <div class="flex items-center user-profile">
                                    <img
                                        src="@/assets/images/tobais.png"
                                        alt=""
                                    />
                                    <h3>Tobias Schmidt-Tudl</h3>
                                </div>
                                <div class="">
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
                                                            to="/"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="editIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t("Edit")
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
                            </div>
                            <div class="news-card-body">
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop
                                    publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum. Lorem
                                    Ipsum is simply dummy text of the printing
                                    and typesetting industry. Lorem Ipsum has
                                    been the industry's standard dummy text ever
                                    since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to
                                    make a type specimen book. It has survived
                                    not only five centuries, but also the leap
                                    into electronic typesetting, remaining
                                    essentially unchanged. It was popularised in
                                    the 1960s with the release of Letraset
                                    sheets containing Lorem Ipsum passages, and
                                    more recently with desktop publishing
                                    software like Aldus PageMaker including
                                    versions of Lorem Ipsum.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="blue">
                        <div class="news-meta">
                            <div class="date flex items-center mr-2">
                                <div class="icon mr-1">
                                    <CustomIcon name="dateIcon" />
                                </div>
                                <p>2023-08-20</p>
                            </div>
                            <div class="date flex items-center">
                                <div class="icon mr-1">
                                    <CustomIcon name="clockIcon" />
                                </div>
                                <p>12:35 PM</p>
                            </div>
                        </div>
                        <div class="news-card">
                            <div class="news-card-header">
                                <div class="flex items-center user-profile">
                                    <img
                                        src="@/assets/images/tobais.png"
                                        alt=""
                                    />
                                    <h3>Tobias Schmidt-Tudl</h3>
                                </div>
                                <div class="">
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
                                                            to="/"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="editIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t("Edit")
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
                            </div>
                            <div class="news-card-body">
                                <p>
                                    Lorem Ipsum is simply dummy text of the
                                    printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy
                                    text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled
                                    it to make a type specimen book. It has
                                    survived not only five centuries, but also
                                    the leap into electronic typesetting,
                                    remaining essentially unchanged. It was
                                    popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum
                                    passages, and more recently with desktop
                                    publishing software like Aldus PageMaker
                                    including versions of Lorem Ipsum. Lorem
                                    Ipsum is simply dummy text of the printing
                                    and typesetting industry. Lorem Ipsum has
                                    been the industry's standard dummy text ever
                                    since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to
                                    make a type specimen book. It has survived
                                    not only five centuries, but also the leap
                                    into electronic typesetting, remaining
                                    essentially unchanged. It was popularised in
                                    the 1960s with the release of Letraset
                                    sheets containing Lorem Ipsum passages, and
                                    more recently with desktop publishing
                                    software like Aldus PageMaker including
                                    versions of Lorem Ipsum.
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
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
        document.body.classList.add("project-management-show-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-show-page");
        next();
    },
};
</script>
