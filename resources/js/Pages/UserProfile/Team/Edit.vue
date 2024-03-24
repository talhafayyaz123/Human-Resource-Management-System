<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Update Teams Details") }}</h3>
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
                                    v-if="isLoaded"
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
                                    v-if="isLoaded"
                                    @update:model-value="filterTeamMembers"
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
                                    v-if="isLoaded"
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
                                    :key="form.skillGroup"
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
                <router-link
                    :to="`/user/teams?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <button
                    v-if="!modelData.deleted_at"
                    class="delete-btn mr-3"
                    tabindex="-1"
                    type="button"
                    @click="destroy"
                >
                    <span class="mr-1">
                        <CustomIcon name="DeleteIcon" />
                    </span>
                    {{ $t("Delete Teams") }}
                </button>
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
import TrashedMessage from "@/Components/TrashedMessage.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("userProfile/list", {
            perPage: 25,
            page: 1,
        });
        await this.$store.dispatch("skillGroup/list");
        await this.$store.dispatch("userDepartments/list", {
            perPage: 10,
            page: 1,
        });
        this.refresh();
    },
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
        TrashedMessage,
        MultiSelectInput,
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
                    text: this.$t("Personal Management"),
                    to: "/user/teams",
                },
                {
                    text: this.$t("Teams"),
                    to: `/user/teams?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            isLoaded: false,
            modelData: {},
            form: {
                name: "",
                departmentId: "",
                teamLeadId: "",
                teamMembers: [],
                skillGroup: [],
            },
        };
    },
    methods: {
        async filterTeamMembers(event) {
            this.isLoaded = false;
            if (event?.id) {
                this.form.teamMembers = this.form.teamMembers.filter(
                    (member) => member.id != event.id
                );
            } else {
                this.form.teamMembers = this.modelData?.teamMembers?.map(
                    (teamMember) => {
                        return {
                            ...(this.userProfiles?.data?.find(
                                (user) => user.id == teamMember?.id
                            ) ?? {}),
                        };
                    }
                );
            }
            await this.$nextTick();
            this.isLoaded = true;
        },
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "userTeams/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    name: this.modelData.name,
                    teamLeadId: this.userProfiles?.data?.find(
                        (user) => user.id == this.modelData?.teamLead
                    ),
                    departmentId: this.departments?.data?.find(
                        (department) =>
                            department.id == this.modelData?.departmentId
                    ),
                    teamMembers: this.modelData?.teamMembers?.map(
                        (teamMember) => {
                            return {
                                ...(this.userProfiles?.data?.find(
                                    (user) => user.id == teamMember?.id
                                ) ?? {}),
                            };
                        }
                    ),
                    skillGroup: this.modelData?.skillGroup,
                };
                document.title = "Edit Team " + this?.form?.name;
                const teamMembers = this.form.teamMembers.filter((member) => {
                    return member.id != this.modelData?.teamLead;
                });
                this.form.teamMembers = [...teamMembers];
            } catch (e) {
            } finally {
                this.isLoaded = true;
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                const teamMembers = [
                    ...this.form?.teamMembers.map((item) => item.userId),
                ];
                if (this.form?.teamLeadId?.id) {
                    teamMembers.push(this.form?.teamLeadId?.userId);
                }
                await this.$store.dispatch("userTeams/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        teamLeadId: this.form?.teamLeadId?.userId,
                        departmentId: this.form?.departmentId?.id,
                        teamMembers: teamMembers,
                        skillGroup: this.form?.skillGroup.map((skillGroup) => {
                            return skillGroup.id;
                        }),
                    },
                });
                await this.$store.dispatch("userTeams/list");
                this.$router.push(`/user/teams?page=${this.$route.query.page}`);
            } catch (e) {}
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "userTeams/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("userTeams/list");
                        this.$router.push(
                            `/user/teams?page=${this.$route.query.page}`
                        );
                    } catch (e) {}
                }
            });
        },
    },
};
</script>
