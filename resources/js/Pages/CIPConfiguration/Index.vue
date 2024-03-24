<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill CIP Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.cipManager"
                                    :key="form.cipManager"
                                    :error="errors['cipManager']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('CIP Manager')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.qualityManagementOfficer"
                                    :key="form.qualityManagementOfficer"
                                    :error="errors['qualityManagementOfficer']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('Quality Management Officer')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.executiveBoard"
                                    :key="form.executiveBoard"
                                    :error="errors['executiveBoard']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Executive Board')"
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
                <router-link to="/dashboard" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.save`)"
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
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
                    text: this.$t("CIP Configuration"),
                    active: true,
                },
            ],
            form: {
                cipManager: "",
                qualityManagementOfficer: "",
                executiveBoard: [],
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            if (!this.userProfiles?.data?.length)
                await this.$store.dispatch("userProfile/list");
            const response = await this.$store.dispatch(
                "globalSettings/cipConfigurationList"
            );
            this.form = {
                cipManager: response?.data?.cipManager ?? "",
                qualityManagementOfficer:
                    response?.data?.qualityManagementOfficer ?? "",
                executiveBoard: response?.data?.executiveBoard ?? [],
            };
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "globalSettings/saveCipConfiguration",
                    {
                        cipManager: this.form.cipManager?.id,
                        qualityManagementOfficer:
                            this.form.qualityManagementOfficer?.id,
                        executiveBoard: this.form.executiveBoard?.map(
                            (member) => {
                                return (
                                    {
                                        id: member.id,
                                    } ?? []
                                );
                            }
                        ),
                    }
                );
            } catch (e) {}
        },
    },
};
</script>
