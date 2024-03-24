<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="lg:w-1/2">
            <form @submit.prevent="save">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Manager Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="managers"
                                    :key="managers"
                                    :error="errors['managers']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Managers')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
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
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
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
                    text: "Personal Modification Process Manager",
                    active: true,
                },
            ],
            managers: [],
        };
    },
    async mounted() {
        try {
            if (!this.userProfiles?.data?.length)
                await this.$store.dispatch("userProfile/list");
            const response = await this.$store.dispatch(
                "personalModificationProcessManagers/managers"
            );
            this.managers = response?.data ?? [];
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async save() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "personalModificationProcessManagers/save",
                    {
                        managers: this.managers.map((manager) => manager.id),
                    }
                );
            } catch (e) {}
        },
    },
};
</script>
