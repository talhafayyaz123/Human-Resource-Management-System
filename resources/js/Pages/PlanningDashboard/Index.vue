<template>
    <div class="planning-dashboard">
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
                            <router-link to="/planning-dashboard">{{
                                $t("Consulting")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span>{{ $t("Planning Dashboard") }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <div class="search">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <CustomIcon name="search" class="mr-1" />
                                </span>
                            </div>
                            <input
                                v-model="form.search"
                                type="search"
                                :placeholder="$t('Search here')"
                                class="form-control"
                                @blur="handleSearch"
                            />
                        </div>
                    </div>
                </div>
                <!---========--->
                <!-- <div class="mr-5">
                    <button
                        class="filterBtn"
                        style="
                            width: 40px;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        "
                    >
                        <CustomIcon name="settingIcon" class="w-full" />
                    </button>
                </div> -->
                <!---========--->
                <!-- <div class="right-side">
                    <div class="user-img">
                        <img src="@/assets/images/avatar-1.png" alt="" />
                    </div>
                </div> -->
                <!---========--->
            </div>
        </div>
        <div class="card plane-card">
            <div class="grid grid-cols-7 gap-5">
                <div
                    class="project-card"
                    v-for="project in data?.data"
                    :key="project.id"
                >
                    <div
                        class="flex items-start"
                        draggable="true"
                        @dragstart="onDragstart($event, project)"
                    >
                        <div class="icon">
                            <CustomIcon name="projIcon" />
                        </div>
                        <div class="content">
                            <h3>{{ project?.name }}</h3>
                            <p>{{ project?.companyName }}</p>
                            <div class="plane-hours">
                                <span>{{ $t("Planned Hours: ") }}</span>
                                <p>{{ project?.estimatedTime }} h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <Timeline
        :key="projects.length"
            :resources="projects"
            :events="tasks"
        /> -->
        <div @drop="onDrop($event)">
            <TimelineView
                :key="projects"
                :resources="projects"
                :events="tasks"
                :column-width="35"
                :row-height="40"
                @event-change="handleEventChange"
                @date-change="handleDateChange"
                @visible-date-change="visibleDateChange"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { Timeline } from "@teej/vue-timeline";
import TimelineView from "../../Components/TimelineView/TimelineView.vue";
import "@teej/vue-timeline/dist/style.css";
import { debounce } from "@/utils/debounce";

export default {
    computed: {
        ...mapGetters("planningDashboard", ["data"]),
    },
    components: {
        Timeline,
        TimelineView,
    },
    data() {
        return {
            draggedData: {},
            projects: [],
            tasks: [],
            form: {
                search: "",
            },
        };
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "planningDashboard/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        this.refresh();
    },
    watch: {
        "form.search"(val) {
            this.debouncedFetch(...val);
        },
    },
    methods: {
        onDragstart(event, project) {
            this.draggedData = project;
        },
        findProjectById(id) {
            return this.projects.find((project) => project.id === id);
        },
        async onDrop(event) {
            try {
                const foundProject = this.findProjectById(this.draggedData?.id);
                if (
                    foundProject == undefined &&
                    this.draggedData.startDate != "" &&
                    this.draggedData.endDate != ""
                ) {
                    // const obj = {
                    //     id: this.draggedData?.id ?? "",
                    //     name: this.draggedData?.name ?? "",
                    // };
                    // const obj2 = {
                    //     id: this.draggedData?.id ?? "",
                    //     name: this.draggedData?.name ?? "",
                    //     startDate: this.draggedData?.actualStartDate ?? "",
                    //     endDate: this.draggedData?.actualFinishedDate ?? "",
                    //     resourceId: this.draggedData?.id ?? "",
                    // };
                    this.projects.push(this.draggedData);
                    this.tasks.push(this.draggedData);
                    await this.$store
                        .dispatch("planningDashboard/update", {
                            ...this.draggedData,
                        })
                        .then((res) => {
                            this.refresh();
                        });
                }
            } catch (e) {}
        },

        async refresh() {
            this.$store.commit("showLoader", true);
            await this.$store
                .dispatch("planningDashboard/list", {
                    search: this.searchGlobal,
                })
                .then((res) => {
                    this.projects = [];
                    this.tasks = [];
                    res?.data?.data?.forEach((resource) => {
                        if (
                            resource.isDragged == true &&
                            resource.startDate != "" &&
                            resource.endDate != ""
                        ) {
                            this.projects.push(resource);
                            this.tasks.push(resource);
                        }
                    });
                });
            this.$store.commit("showLoader", false);
        },
        async handleEventChange(event) {
            this.$swal({
                title: this.$t("Do you want to update the project?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes update it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        this.$store.dispatch("planningDashboard/update", {
                            id: event.id,
                            actualStartDate: event.startDate,
                            actualFinishedDate: event.endDate,
                        });
                    } catch (e) {}
                } else {
                    this.refresh();
                }
            });
        },
        handleDateChange(dates) {
            console.log("Dates Changed", dates);
        },
        visibleDateChange(data) {
            console.log("Scrolling", data);
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}

</style>
<style lang="scss">
.teej-timeline{
    margin-left: -200px;
}
.timeline-container {
    height: calc(100vh - 100px) !important;
    // .resources {
    //     display: none !important;
    // }
}
.info {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 15px;
    button {
        border: 1px solid #2957a4;
        border-radius: 6px;
        background: #2957a4;
        height: 40px;
        padding: 0 16px;
        color: white;
        font-size: 14px;
    }
}
</style>
