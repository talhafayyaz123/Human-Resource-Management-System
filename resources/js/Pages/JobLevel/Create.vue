<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Job level Details") }}
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
                                    v-model="form.experienceStart"
                                    :error="errors.experienceStart"
                                    :label="$t('Experience start')"
                                    :required="true"
                                    :type="`number`"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.experienceEnd"
                                    :error="errors.experienceEnd"
                                    :label="$t('Experience end')"
                                    :required="true"
                                    :type="`number`"
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
                <router-link to="/job-level" class="primary-btn mr-3">
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
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
                    to: "/job-level",
                },
                {
                    text: this.$t("Create"),
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
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("jobLevel/create", {
                    ...this.form,
                });
                //await this.$store.dispatch("jobLevel/list");
                this.$router.push("/job-level");
            } catch (e) {}
        },
    },
};
</script>
