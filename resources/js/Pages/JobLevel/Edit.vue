<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update job level Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.levelName"
                                    :error="errors.levelName"
                                    :label="$t('Level name')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :type="`number`"
                                    v-model="form.experienceStart"
                                    :error="errors.experienceStart"
                                    :label="$t('Experience start')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.experienceEnd"
                                    :error="errors.experienceEnd"
                                    :type="`number`"
                                    :label="$t('Experience end')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.targetSalary"
                                    :error="errors.targetSalary"
                                    :label="$t('Target salary')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.limitSalary"
                                    :error="errors.limitSalary"
                                    :label="$t('Limit salary')"
                                    :required="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/job-level?page=${$route.query.page}`" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
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
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import NumberInput from "../../Components/NumberInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        NumberInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Job Level",
                    to: `/job-level?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                levelName: "",
                experienceStart: "",
                experienceEnd: "",
                targetSalary: "",
                limitSalary: "",
            },
            jobLevel: {},
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "jobLevel/show",
                    this.$route.params.id
                );
                this.form = response?.data?.jobLevel ?? {};
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("jobLevel/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("jobLevel/list");
            this.$router.push("/job-level");
        },
    },
};
</script>
