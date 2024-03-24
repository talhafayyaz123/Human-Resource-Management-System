<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <h6
            class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
        ></h6>
        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Team Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.departmentId"
                                    :options="departments?.data"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Department')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="departments"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.teamLeadId"
                                    :options="userProfiles?.data"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Team Lead')"
                                    label="employee"
                                    trackBy="id"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.teamMembers"
                                    :options="userProfileWithoutTheLead"
                                    class=""
                                    :multiple="true"
                                    :textLabel="$t('Team Members')"
                                    label="employee"
                                    trackBy="id"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.skillGroup"
                                    :options="skillGroup.data"
                                    :multiple="true"
                                    :textLabel="$t('Skill Group')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="skillGroup"
                                    :error="errors.skillGroup"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/user/teams" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button :loading="isLoading" class="secondary-btn">
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
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userDepartments", ["departments"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("skillGroup", ["skillGroup"]),
        userProfileWithoutTheLead() {
            return (
                this.userProfiles?.data?.filter(
                    (userProfile) => userProfile.id != this.form.teamLeadId?.id
                ) ?? this.userProfiles?.data
            );
        },
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/user/teams",
                },
                {
                    text: this.$t("Teams"),
                    to: "/user/teams",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                departmentId: "",
                teamLeadId: "",
                teamMembers: [],
                skillGroup: [],
            },
        };
    },
    mounted() {
        this.$store.dispatch("userProfile/list", {
            perPage: 25,
            page: 1,
        });
        this.$store.dispatch("skillGroup/list", {
            page: 1,
            search: "",
        });
        this.$store.dispatch("userDepartments/list", {
            perPage: 10,
            page: 1,
        });
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                const teamMembers = [
                    ...this.form?.teamMembers.map((item) => item.userId),
                ];
                if (this.form?.teamLeadId?.id) {
                    teamMembers.push(this.form?.teamLeadId?.userId);
                }
                await this.$store.dispatch("userTeams/create", {
                    ...this.form,
                    departmentId: this.form?.departmentId?.id,
                    teamLeadId: this.form?.teamLeadId?.userId,
                    teamMembers: teamMembers,
                    skillGroup: this.form?.skillGroup.map((skillGroup) => {
                        return skillGroup.id;
                    }),
                });
                await this.$store.dispatch("userTeams/list");
                this.$router.push("/user/teams");
            } catch (e) {}
        },
    },
};
</script>
