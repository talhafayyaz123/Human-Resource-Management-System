<template>
    <PageHeader :items="breadcrumbItems" />

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Start Date") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.startDate"
                            :class="errors.startDate ? 'error' : ''"
                            @update:model-value="getStatistics"
                        />
                        <div v-if="errors?.endDate" class="form-error">
                            {{ errors.endDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("End Date") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.endDate"
                            :class="errors.endDate ? 'error' : ''"
                            @update:model-value="getStatistics"
                        />
                        <div v-if="errors?.endDate" class="form-error">
                            {{ errors.endDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            v-model="form.employees"
                            :key="form.employees"
                            :error="errors['employees']"
                            :options="userProfiles?.data ?? []"
                            :multiple="true"
                            :text-label="$t('Employees')"
                            label="employee"
                            trackBy="userId"
                            moduleName="userProfile"
                            @update:model-value="getStatistics('employees')"
                            :query="{
                                isConsulting: !fromProjectManagement ? 1 : 0,
                                isPMConsulting: fromProjectManagement ? 1 : 0,
                            }"
                        />
                    </div>
                </div>
                <div class="w-full" v-if="$hasRole('admin')">
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            v-model="form.team"
                            :error="errors['team']"
                            :options="teams?.data ?? []"
                            :multiple="false"
                            :text-label="$t('Team')"
                            label="name"
                            trackBy="id"
                            moduleName="userTeams"
                            @update:model-value="getStatistics('team')"
                        />
                    </div>
                </div>
                <div class="w-full" v-else>
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            v-model="form.team"
                            :error="errors['team']"
                            :options="consultingTeams"
                            :multiple="false"
                            :text-label="$t('Team')"
                            label="name"
                            trackBy="id"
                            @update:model-value="getStatistics('team')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="consultingDashboardStatistics?.team">
        <!-- the statistics card for team -->
        <Statistics
            :is-team="typeof consultingDashboardStatistics?.team === 'object'"
            :statistics="consultingDashboardStatistics?.team"
        />
        <h3 class="card-title">
            {{ $t("Employees") }}:
        </h3>
    </div>
    <!-- the statistics card for employees -->
    <Statistics
        v-for="(
            statistics, index
        ) in consultingDashboardStatistics?.employees ?? []"
        :key="'consulting-statistics-' + index"
        :statistics="statistics"
    />
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import Statistics from "./Components/Statistics.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [userProfilePicturesMixin],
    props: {
        fromProjectManagement: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        MultiSelectInput,
        Statistics,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Consulting"),
                    to: "/consulting-dashboard",
                },
                {
                    text: this.$t("Dashboard"),
                    active:true
                },
            ],
            consultingTeams: [],
            form: {
                // set the startDate to the start of the month
                startDate: new Date(
                    `${new Date().getFullYear()}-${
                        new Date().getMonth() + 1 < 10 ? "0" : ""
                    }${new Date().getMonth() + 1}-01`
                )
                    .toISOString()
                    .slice(0, 10),
                endDate: new Date().toISOString().slice(0, 10),
                employees: [],
                team: null,
            },
        };
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("consultingDashboard", ["consultingDashboardStatistics"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("auth", ["userProfilePictures"]),
    },
    methods: {
        /**
         * gets the statistics and sets 'consultingDashboardStatistics' state
         */
        async getStatistics(filterName) {
            try {
                // if the team is set in the filter, then we must only send the team and remove all the employees that were set
                // in the filter
                if (filterName === "team") {
                    this.form.employees.splice(0, this.form.employees.length);
                }
                // if employees are selected then team must be set to null
                else if (filterName === "employees") {
                    this.form.team = null;
                }

                await this.$store.dispatch(
                    "consultingDashboard/consultingDashboardStatistics",
                    {
                        ...this.form,
                        employees: this.form.employees.map((employee) => {
                            return {
                                userId: employee.userId,
                            };
                        }),
                        team: this.form.team?.id ?? "",
                    }
                );
                await this.getUserProfilePictures(
                    this.consultingDashboardStatistics?.employees?.map(
                        (employee) => {
                            return {
                                userId: employee?.employee?.userId,
                            };
                        }
                    ),
                    "userId"
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
    unmounted() {
        // reset the consultingDashboardStatistics back to default on unmount
        this.$store.commit(
            "consultingDashboard/consultingDashboardStatistics",
            {
                team: null,
                employees: [],
            }
        );
        this.$store.commit("userProfile/userProfiles", {
            data: [],
            links: [],
        });
    },
    async mounted() {
        try {
            // fetch the userProfile listing from users from consulting teams, which is why the 'isConsulting' query param is set
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("userProfile/list", {
                isConsulting: !this.fromProjectManagement ? 1 : 0,
                isPMConsulting: this.fromProjectManagement ? 1 : 0,
            });
            if (this.$hasRole("admin")) {
                await this.$store.dispatch("userTeams/list");
                return;
            }
            // fetch consulting team listing
            const response = await this.$store.dispatch(
                `userTeams/${
                    this.fromProjectManagement
                        ? "pmDashboardTeamsList"
                        : "consultingTeamsList"
                }`
            );
            this.consultingTeams = response?.data?.data ?? [];
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped></style>
