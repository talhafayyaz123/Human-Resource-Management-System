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
                                $t("Sprint Board")
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
                            <CustomIcon name="saveIcon" />
                        </div>
                        <span>{{ $t("Complete Sprint") }}</span>
                    </router-link>
                </div>
                <!---========--->
            </div>
        </div>

        <div class="project-board-page sprint-board-page">
            <div class="flex items-start justify-between mb-5">
                <div class="">
                    <div class="flex items-center">
                        <h3 class="card-title">{{ $t("Sprint") }} 2</h3>
                        <span class="ml-2">
                            <CustomIcon name="iIcon" />
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <button class="primary-btn">
                            {{ $t("High Priority Issues") }}
                        </button>
                        <button class="primary-btn">
                            {{ $t("Sprint Planning") }}
                        </button>
                        <button class="primary-btn">
                            {{ $t("Only My Issues") }}
                        </button>
                        <button class="primary-btn">
                            {{ $t("Recently Update") }}
                        </button>
                    </div>
                </div>
                <div class="sprint-meta">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="dateIcon" />
                            </div>
                            <h3>Start Date:</h3>
                        </div>
                        <p>2023-08-20</p>
                    </div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="dateIcon" />
                            </div>
                            <h3>End Date:</h3>
                        </div>
                        <p>2023-08-20</p>
                    </div>
                    <div class="flex items-center mb-1">
                        <div class="icon"><CustomIcon name="clockIcon" /></div>
                        <p>4 Days Remaining</p>
                    </div>
                </div>
            </div>

            <div class="swimboard">
                <div class="boards" ref="box1" @scroll="handleScrollBox1">
                    <div class="boards-col">
                        <div class="board-card sky">
                            <h3>To Do <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card blue">
                            <h3>In Progress <span>(02)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card orange">
                            <h3>QA TESTING <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card red">
                            <h3>DEPLOY <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card green">
                            <h3>Done <span>(03)</span></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="boards overflow-board" ref="box2" @scroll="handleScrollBox2">
                <div class="boards-col">

                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body">
                            <img src="@/assets/images/supplier.png" alt="" />
                        </div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boards-col">

                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boards-col">

                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body">
                            <img src="@/assets/images/supplier.png" alt="" />
                        </div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body">
                            <img src="@/assets/images/supplier.png" alt="" />
                        </div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boards-col">

                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body">
                            <img src="@/assets/images/supplier.png" alt="" />
                        </div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="boards-col">

                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body">
                            <img src="@/assets/images/supplier.png" alt="" />
                        </div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
                            </div>
                        </div>
                    </div>
                    <div class="task-card">
                        <div class="task-card-header">
                            <div class="icon">
                                <CustomIcon name="loudIcon" />
                            </div>
                            <h3>As a Patient I want to view Doctors Degree</h3>
                        </div>
                        <div class="task-card-body"></div>
                        <div class="task-divider"></div>
                        <div class="task-card-footer">
                            <div class="task">
                                <div class="icon">
                                    <CustomIcon name="themeIcon" />
                                </div>
                                <span class="ml-1 mr-1"
                                    ><CustomIcon name="UpblueIcon"
                                /></span>
                                <div class="tag">8</div>
                            </div>
                            <div class="">
                                <h4>DHE-6</h4>
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
            syncingScroll: false,
        };
    },
    methods: {
        handleScrollBox1() {
            if (!this.syncingScroll) {
                this.syncingScroll = true;
                this.$refs.box2.scrollLeft = this.$refs.box1.scrollLeft;
                this.syncingScroll = false;
            }
        },
        handleScrollBox2() {
            if (!this.syncingScroll) {
                this.syncingScroll = true;
                this.$refs.box1.scrollLeft = this.$refs.box2.scrollLeft;
                this.syncingScroll = false;
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
        document.body.classList.add("projectboard-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-show-page");
        document.body.classList.remove("projectboard-page");
        next();
    },
};
</script>
