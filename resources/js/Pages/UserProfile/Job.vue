<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Job Data") }}</h3>
            </div>
            <div class="card-body">
                <JobData :jobForm="data" :errors="errors" />
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Assigned Workspace") }}</h3>
            </div>
            <div class="card-body">
                <AssignedWorkspace :jobForm="data" :errors="errors" />
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Holidays") }}</h3>
            </div>
            <div class="card-body">
                <Holidays :jobForm="data" :errors="errors" />
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Working hours") }}</h3>
            </div>
            <div class="card-body">
                <WorkHours :jobForm="data" :errors="errors" />
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Performance Target") }}</h3>
            </div>
            <div class="card-body">
                <PerformanceTarget :jobForm="data" :errors="errors" />
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <!-- <div @click="backButton()" class="primary-btn cursor-pointer me-3">
                <span class="mr-1">
                    <CustomIcon name="backIcon" />
                </span>
                <span>{{ $t("Back") }}</span>
            </div> -->
            <loading-button
                :loading="isLoading"
                type="submit"
                @click="nextButton()"
                class="secondary-btn"
            >
                <span class="mr-1">
                    <CustomIcon name="nextIcon" />
                </span>
                {{ $t("Next") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import JobData from "./Job/JobData.vue";
import AssignedWorkspace from "./Job/AssignedWorkspace.vue";
import Holidays from "./Job/Holidays.vue";
import WorkHours from "./Job/WorkHours.vue";
import PerformanceTarget from "./Job/PerformanceTarget.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["isLoading"]),
    },
    props: ["data", "errors"],
    components: {
        JobData,
        AssignedWorkspace,
        Holidays,
        WorkHours,
        LoadingButton,
        PerformanceTarget,
    },
    methods: {
        nextButton() {
            let output = [];
            this.data.workHours.forEach((obj) => {
                const { days, numOfHours } = obj;
                days.forEach((day) => {
                    output.push({ day: day, numOfHours: numOfHours });
                });
            });

            this.data.workHoursArray = output;
        },
    },
};
</script>
