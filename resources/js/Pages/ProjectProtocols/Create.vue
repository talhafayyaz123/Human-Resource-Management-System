<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Project Protocol Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <ProjectProtocolForm ref="projectProtocolForm" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/project-protocols" class="primary-btn mr-3">
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
    <div class="card my-3" v-if="id">
        <div class="card-body">
            <DescriptionEntries  :id="id" />
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import ProjectProtocolForm from "./Components/Form.vue";
import DescriptionEntries from "./Components/DescriptionEntries.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        LoadingButton,
        ProjectProtocolForm,
        DescriptionEntries,
        PageHeader,
    },
    computed: {
        ...mapGetters(["isLoading"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Project Management"),
                    to: "/project-protocols",
                },
                {
                    text: this.$t("Project Protocols"),
                    to: "/project-protocols",
                },
                {
                    text: "Create",
                    active: true,
                }
            ],
            id: null,
        };
    },
    methods: {
        /**
         * run the create API and refresh the project protocols list
         */
        async store() {
            try {
                this.$store.commit("isLoading", true);
                // grab the form from the ProjectProtocolForm component
                const form = this.$refs.projectProtocolForm?.form ?? {};
                const response = await this.$store.dispatch(
                    "projectProtocols/create",
                    {
                        customerId: form.customerId?.id,
                        date: form.date,
                        protocolTypeId: form.protocolTypeId?.id,
                        projectId: form.projectId?.id,
                        projectStatusId: form.projectStatusId?.id,
                        distributors: form.distributors.map(
                            (distributor) => distributor.id
                        ),
                        participants: form.participants.map(
                            (participants) => participants.id
                        ),
                        recorderId: form.recorderId?.id,
                    }
                );
                this.id = response?.data?.id;
                await this.$store.dispatch("projectProtocols/list");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
