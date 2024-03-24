<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <!-- <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" to="/personal-requests">{{
                $t("Personal Requests")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Edit") }}</span>
        </h1> -->
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{ $t("Fill Personal Request Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.requester"
                                    :key="form.requester"
                                    :isDisabled="true"
                                    :textLabel="$t('Requester')"
                                    :multiple="false"
                                    trackBy="userId"
                                    :options="userProfiles?.data ?? []"
                                    moduleName="userProfile"
                                    label="employee"
                                    :error="errors['requester']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.department"
                                    :key="form.department"
                                    :textLabel="$t('Department')"
                                    :multiple="false"
                                    trackBy="id"
                                    label="name"
                                    :required="true"
                                    :options="departments?.data ?? []"
                                    moduleName="userDepartments"
                                    :error="errors['department']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.team"
                                    :key="form.team"
                                    :textLabel="$t('Team')"
                                    :multiple="false"
                                    trackBy="id"
                                    label="name"
                                    :required="true"
                                    :options="teams?.data ?? []"
                                    moduleName="userTeams"
                                    :error="errors['team']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.job"
                                    :key="form.job"
                                    :textLabel="$t('Job')"
                                    :multiple="false"
                                    trackBy="id"
                                    label="jobName"
                                    :required="true"
                                    :options="jobs?.data ?? []"
                                    moduleName="userJobs"
                                    :error="errors['job']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.location"
                                    :key="form.location"
                                    :textLabel="$t('Location')"
                                    :multiple="false"
                                    trackBy="id"
                                    label="name"
                                    :required="true"
                                    :options="locations?.data ?? []"
                                    moduleName="userLocations"
                                    :error="errors['location']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.contractType"
                                    :key="form.contractType"
                                    :textLabel="$t('Contract Type')"
                                    :multiple="false"
                                    trackBy="id"
                                    label="name"
                                    :required="true"
                                    :options="contractTypes?.data ?? []"
                                    moduleName="contractTypes"
                                    :error="errors['contractType']"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <label class="form-label"
                                    ><span class="text-red-500">*</span>&nbsp;{{
                                        $t("Start Date")
                                    }}:</label
                                >
                                <datepicker
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    class="lg:w-1/2"
                                    v-model="form.startDate"
                                    :class="errors.startDate ? 'error' : ''"
                                />
                                <div v-if="errors?.startDate" class="form-error">
                                    {{ errors.startDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-5">
                <div class="card-header flex justify-between">
                    <h3 class="card-title">{{ $t("Time Model") }}</h3>
                    <button
                        role="button"
                        type="button"
                        @click="
                            form.workHours.push({
                                days: '',
                                numOfHours: '',
                            })
                        "
                        class="px-3 py-1 text-white bg-blue-700 rounded hover:bg-blue-800 flex items-center space-x-1"
                    >
                        <svg
                            class="w-4 h-4 text-white stroke-current stroke-2"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12 4v16m-8-8h16" />
                        </svg>
                        <span>{{ $t("Add") }}</span>
                    </button>
                </div>
                <div class="card-body">
                    <div
                        v-for="(work, index) in form.workHours"
                        :key="index"
                        class="flex my-5"
                        :class="{
                            'pb-0':
                                index === form.workHours.length - 1 &&
                                errorsExist,
                        }"
                    >
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="work.days"
                                    :textLabel="$t('Working Days')"
                                    :multiple="true"
                                    :options="[
                                        'mon',
                                        'tue',
                                        'wed',
                                        'thu',
                                        'fri',
                                        'sat',
                                        'sun',
                                    ]"
                                >
                                    <template #option="{ props }">
                                        <p class="capitalize">{{ props.option }}</p>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    type="number"
                                    v-model="work.numOfHours"
                                    :error="errors.numOfHours"
                                    :label="$t('Daily Hours')"
                                    class="mx-3"
                                />
                            </div>
                        </div>
                        
                        <button
                            role="button"
                            type="button"
                            @click="form.workHours.splice(index, 1)"
                            style="color: red"
                            class="mx-3"
                        >
                            <font-awesome-icon
                                ref="icon"
                                icon="fa-regular fa-trash-can"
                            />
                        </button>
                    </div>
                    <div v-if="errorsExist" class="form-error mb-2">
                        {{
                            $t(
                                "Duplication exists in the days. Please correct it"
                            )
                        }}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/personal-requests" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userDepartments", ["departments"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("userLocations", ["locations"]),
        ...mapGetters("userJobs", ["jobs"]),
        ...mapGetters("contractTypes", ["contractTypes"]),
        ...mapGetters(["errors", "isLoading"]),
        errorsExist() {
            for (let key in this.errors) {
                if (key.startsWith("workHoursArray.")) {
                    return true;
                }
            }
            return false;
        },
    },
    components: {
        MultiSelectInput,
        LoadingButton,
        TextInput,
        PageHeader
    },
    data() {
        return {
            form: {
                requester: "",
                department: "",
                team: "",
                job: "",
                location: "",
                contractType: "",
                startDate: new Date(),
                workHours: [],
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Requests"),
                    to: "/personal-requests",
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                },
            ]
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "personalRequests/show",
                this.$route.params.id
            );
            this.form = {
                requester: response?.data?.data?.requester ?? "",
                department: response?.data?.data?.department ?? "",
                team: response?.data?.data?.team ?? "",
                job: response?.data?.data?.job ?? "",
                location: response?.data?.data?.location ?? "",
                contractType: response?.data?.data?.contractType ?? "",
                startDate: response?.data?.data?.startDate ?? new Date(),
                workHours: response?.data?.data?.timeModel ?? [],
            };
            if (!this.departments?.data?.length) {
                await this.$store.dispatch("userDepartments/list");
            }
            if (!this.teams?.data?.length) {
                await this.$store.dispatch("userTeams/list");
            }
            if (!this.jobs?.data?.length) {
                await this.$store.dispatch("userJobs/list");
            }
            if (!this.locations?.data?.length) {
                await this.$store.dispatch("userLocations/list");
            }
            if (!this.contractTypes?.data?.length) {
                await this.$store.dispatch("contractTypes/list");
            }
        } catch (e) {}
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async update() {
            try {
                this.$store.commit("isLoading", true);
                let output = [];
                this.form.workHours.forEach((obj) => {
                    const { days, numOfHours } = obj;
                    days.forEach((day) => {
                        output.push({ day: day, numOfHours: numOfHours });
                    });
                });

                await this.$store.dispatch("personalRequests/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        userId: this.form.requester?.id,
                        departmentId: this.form.department?.id,
                        teamId: this.form.team?.id,
                        jobId: this.form.job?.id,
                        locationId: this.form.location?.id,
                        contractTypeId: this.form.contractType?.id,
                        startDate: this.form.startDate,
                        timeModel: output,
                    },
                });
                await this.$store.dispatch("personalRequests/list");
                this.$router.push("/personal-requests");
            } catch (e) {}
        },
    },
};
</script>
