<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Fill  Skill Group Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :required="true"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    :key="form.skills"
                                    v-model="form.skills"
                                    :options="skills.data"
                                    :multiple="true"
                                    :textLabel="$t('Skills')"
                                    :required="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="skills"
                                    :error="errors.skills"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.jobs"
                                    :options="jobs.data"
                                    :multiple="true"
                                    :textLabel="$t('Jobs')"
                                    label="jobTitle"
                                    trackBy="id"
                                    moduleName="jobs"
                                    :error="errors.jobs"
                                    :key="form.jobs"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.teams"
                                    :options="teams.data"
                                    :multiple="true"
                                    :textLabel="$t('Teams')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="teams"
                                    :error="errors.teams"
                                    :key="form.teams"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/skill-group?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.update`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        QuillTextEditor,
        MultiSelectInput,
        TextInput,
        SelectInput,
        TextAreaInput,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("skills", ["skills"]),
        ...mapGetters("userJobs", ["jobs"]),
        ...mapGetters("userTeams", ["teams"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Skills Group",
                    to: `/skill-group?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                name: "",
                jobs: [],
                teams: [],
                skills: [],
            },
        };
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("skills/list");
                await this.$store.dispatch("userJobs/list", {
                    page: 1,
                });
                await this.$store.dispatch("userTeams/list");
                const response = await this.$store.dispatch(
                    "skillGroup/show",
                    this.$route.params.id
                );
                this.form = response?.data ?? {};
                document.title = "Edit Skill Group " + this.form?.name;
                /* this.form = {
                    name: this.skillGroup.name,
                    skills: this.skillGroup.skills
                };*/
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("skillGroup/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        jobs:
                            this.form?.jobs.map((job) => {
                                return job.id;
                            }) ?? [],
                        teams:
                            this.form?.teams.map((team) => {
                                return team.id;
                            }) ?? [],
                        skills: this.form?.skills.map((skill) => {
                            return skill.id;
                        }),
                    },
                });
                this.$router.push(
                    `/skill-group?page=${this.$route.query.page}`
                );
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
:deep(.ql-container) {
    height: auto;
}
</style>
