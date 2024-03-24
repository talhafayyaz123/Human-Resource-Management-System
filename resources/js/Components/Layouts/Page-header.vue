<template>
    <div class="page-header">
        <div class="page-title">
            <div class="page-title-right">
                <ol class="breadcrumb">
                    <li
                        class="breadcrumb-item"
                        v-for="(item, index) in items"
                        :key="index"
                    >
                        <div
                            class="flex items-center"
                            v-if="index < items.length - 1 && breadCrupmBtn"
                        >
                            <router-link
                                class="cursor-pointer"
                                @click="breadcrumFunct"
                                >{{ $t(item.text) }}</router-link
                            >
                        </div>
                        <div class="flex items-center" v-else>
                            <router-link
                                v-if="index < items.length - 1"
                                :to="item.to"
                                >{{ $t(item.text) }}</router-link
                            >
                            <span v-else>{{ $t(item.text) }}</span>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
        <div class="other-items d-flex justify-center">
            <!---========--->
            <div class="left-side flex items-center" v-if="optionalItems.leftSide">
                <div class="filter-btn" v-if="optionalItems.filterBtn">
                    <button class="filterBtn">
                        <CustomIcon name="filters" />
                        <span>{{ $t("Filters") }}</span>
                    </button>
                </div>
                <div class="flex items-center" v-if="optionalItems.SearchBtn">
                    <div
                        class="search-close"
                        @click="closeSearch"
                        v-if="showSearch"
                    >
                        <icon name="xIcon" />
                    </div>
                    <div class="search-icon" @click="searchToggle()" v-else>
                        <img src="@/assets/images/searchheader.svg" alt="" />
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
                <div class="divider"></div>
            </div>
            <!---========--->
            <div class="right-side">
                <button
                    v-if="optionalItems.csvBtn1Show"
                    class="primary-btn mr-2"
                    @click="csvBtn1()"
                    :disabled="isLoading"
                >
                    <div class="mr-2" v-if="!isLoading" >
                        <CustomIcon name="addIcon"/>
                    </div>
                    <div v-if="isLoading" class="btn-spinner mr-2" />
                    <span>{{ $t(optionalItems.csvBtn1Text) }}</span>
                </button>
                <button
                    v-if="optionalItems.csvBtn2Show"
                    class="primary-btn"
                    @click="csvBtn2()"
                >
                    <div class="mr-2">
                        <CustomIcon name="addIcon"/>
                    </div>
                    <span>{{ $t(optionalItems.csvBtn2Text) }}</span>
                </button>
                <router-link
                    :to="optionalItems.path"
                    v-if="optionalItems.isBtnShow"
                    class="primary-btn"
                >
                    <div class="mr-2">
                        <CustomIcon name="addIcon"/>
                    </div>
                    <span>{{ $t(optionalItems.btnText) }}</span>
                </router-link>

                <router-link
                    :to="`${optionalItems.btn2Path}?page=${page}`"
                    v-if="optionalItems.isBtn2Show && page"
                    class="primary-btn ml-2"
                >
                    <div class="mr-2">
                        <CustomIcon name="addIcon"/>
                    </div>
                    <span>{{ $t(optionalItems.btn2Text) }}</span>
                </router-link>

                <router-link
                    :to="optionalItems.btn2Path"
                    v-if="optionalItems.isBtn2Show && !page"
                    class="primary-btn ml-2"
                >
                    <div class="mr-2">
                        <CustomIcon name="addIcon"/>
                    </div>
                    <span>{{ $t(optionalItems.btn2Text) }}</span>
                </router-link>




                <slot name="filter"></slot>
            </div>
            <!---========--->
        </div>
    </div>
    <!-- end page title -->
</template>
<script>
import icon from "../Icon.vue";
export default {
    components: {
        icon,
    },
    props: {
        title: {
            type: String,
            default: "",
        },
        items: {
            type: Array,
            default: () => [],
        },
        currentView: String,
         page: Number,
        optionalItems: {
            type: Object,
            default: () => ({
                searchBar: false,
                isLoading: false,
                csvBtn1Show: false,
                csvBtn1Text: "",
                csvBtn2Show: false,
                csvBtn2Text: "",

                isBtnShow: false,
                btnText: "",
                path: "/",
                isBtn2Show: false,
                btn2Text: "",
                btn2Path: "/",
            }),
        },
        breadCrupmBtn: Boolean,
        isLoading: Boolean,
    },
    data() {
        return {
            showSearch: false,
        };
    },
    methods: {
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
        handleSearch() {
            this.$emit("search");
        },
        breadcrumFunct() {
            this.$emit("breadEvent");
        },
        csvBtn1() {
            this.$emit("csvBtn1");
        },
        csvBtn2() {
            this.$emit("csvBtn2");
        },
    },
    watch: {
        searchGlobal: {
            handler: function (current) {
                this.$emit("watchEvent", current);
            },
            deep: true, // Add this if necessary
        },
    },
};
</script>
