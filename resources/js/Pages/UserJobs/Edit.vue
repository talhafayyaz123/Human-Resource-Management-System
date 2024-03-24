<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update fill job details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.jobTitle"
                                    :required="true"
                                    :error="errors.jobTitle"
                                    class=""
                                    :label="$t('Job Title')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    :key="form.contractType"
                                    v-model="form.contractType"
                                    :options="formOfContract.data"
                                    :multiple="false"
                                    :textLabel="$t('Contract type')"
                                    :required="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="contractType"
                                    :error="errors.contractType"
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
                                    :required="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="skillGroup"
                                    :error="errors.skillGroup"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    :key="form.jobLevel"
                                    v-model="form.jobLevel"
                                    :options="jobLevel.data"
                                    :multiple="false"
                                    :textLabel="$t('Job level')"
                                    :required="true"
                                    label="level_name"
                                    trackBy="id"
                                    moduleName="jobLevel"
                                    :error="errors.jobLevel"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.coreFunctions"
                                    class=""
                                    :required="true"
                                    :label="$t('Core functions')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.qualifications"
                                    class=""
                                    :required="true"
                                    :label="$t('Qualifications')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.jobDescription"
                                    :required="true"
                                    class=""
                                    :label="$t('Job Description')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/job?page=${this.$route.query.page}`"
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
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
        ...mapGetters("skillGroup", ["skillGroup"]),
        ...mapGetters("formOfContract", ["formOfContract"]),
        ...mapGetters("jobLevel", ["jobLevel"]),
        ...mapGetters("userjobs", ["jobs"]),
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
                    to: "/job",
                },
                {
                    text: this.$t("Job"),
                    to: `/job?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                jobTitle: "",
                jobDescription: "",
                contractType: "",
                coreFunctions: "",
                qualifications: "",
                skillGroup: [],
                jobLevel: "",
            },
            userjobs: {},
        };
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("skillGroup/list");
                await this.$store.dispatch("jobLevel/list");
                this.$store.dispatch("formOfContract/list");

                const response = await this.$store.dispatch(
                    "userJobs/show",
                    this.$route.params.id
                );
                document.title = "Job Edit " + response?.data?.jobs?.jobNumber;
                this.form = response?.data?.jobs ?? {};
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
                await this.$store.dispatch("userJobs/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        jobLevel: this.form.jobLevel.id,
                        contractType: this.form.contractType.id,
                        skillGroup: this.form.skillGroup.map((skillGroup) => {
                            return skillGroup.id;
                        }),
                    },
                });
                this.$router.push(`/job?page=${this.$route.query.page}`);
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
