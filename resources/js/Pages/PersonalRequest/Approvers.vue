<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Approver Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.manager"
                                    :key="form.manager"
                                    :error="errors['manager']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('Manager')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.management"
                                    :key="form.management"
                                    :error="errors['management']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Management')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.executiveManagement"
                                    :key="form.executiveManagement"
                                    :error="errors['executiveManagement']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Executive Management')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
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
import PageHeader from "@/Components/Layouts/Page-header.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    components: {
        LoadingButton,
        MultiSelectInput,
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
                    text: "Personal Request Approvers",
                    active: true,
                },
            ],
            form: {
                manager: "",
                management: [],
                executiveManagement: [],
            },
        };
    },
    async mounted() {
        try {
            const response = await this.$store.dispatch(
                "personalRequestApprovers/list"
            );
            this.form.manager = response?.data?.manager ?? "";
            this.form.management = response?.data?.management ?? [];
            this.form.executiveManagement =
                response?.data?.executiveManagement ?? [];
        } catch (e) {
            console.log(e);
        }
        if (!this.userProfiles?.data?.length)
            this.$store
                .dispatch("userProfile/list")
                .catch((e) => console.log(e));
    },
    methods: {
        async store() {
            this.$store.commit("isLoading", true);
            try {
                this.$store.dispatch("personalRequestApprovers/create", {
                    manager: this.form.manager?.id,
                    management: this.form.management.map(
                        (manager) => manager.id
                    ),
                    executiveManagement: this.form.executiveManagement.map(
                        (manager) => manager.id
                    ),
                });
            } catch (e) {
                console.log(e);
                this.$store.commit("isLoading", false);
            }
        },
    },
};
</script>
