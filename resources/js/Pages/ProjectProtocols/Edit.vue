<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Project Protocol Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <ProjectProtocolForm
                        ref="projectProtocolForm"
                        :action-form="form"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/project-protocols?page=${this.$route.query.page}`" class="primary-btn mr-3">
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
    <div class="card my-3">
        <div class="card-header">
            <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <DescriptionEntries />
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
                    to: "/project-protocols?page="+this.$route.query.page,
                },
                {
                    text: this.$t("Project Protocols"),
                    to: "/project-protocols?page="+this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                }
            ],
            form: {
                customerId: "",
                date: new Date(),
                protocolTypeId: "",
                projectId: "",
                projectStatusId: "",
                distributors: [],
                participants: [],
                recorderId: "",
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            let response = await this.$store.dispatch(
                "projectProtocols/show",
                this.$route.params.id
            );
            const form = response?.data?.data ?? this.form;
            document.title = "Edit Protocols " + form?.project?.projectNumber;
            // fetch the distributors based on ids
            let distributors = [];
            for (let i = 0; i < form?.distributors?.length ?? 0; i++) {
                response = await this.$store.dispatch("auth/show", {
                    id: form?.distributors?.[i],
                });
                if (response?.data?.id) distributors.push(response.data);
            }
            // fetch the participants based on ids
            let participants = [];
            for (let i = 0; i < form?.participants?.length ?? 0; i++) {
                response = await this.$store.dispatch("auth/show", {
                    id: form?.participants?.[i],
                });
                if (response?.data?.id) participants.push(response.data);
            }
            // fecth the recorder deom form.recorderId
            response = await this.$store.dispatch("auth/show", {
                id: form.recorderId,
            });
            this.form = {
                customerId: form.customer,
                date: new Date(form.date),
                protocolTypeId: form.protocolType,
                projectId: form.project,
                projectStatusId: form.projectStatus,
                distributors: distributors,
                participants: participants,
                recorderId: response?.data ?? "",
            };
            // the projects are fetched based on customerId
            await this.$store.dispatch("projects/list", {
                companyId: this.form.customerId?.id,
            });
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        /**
         * run the create API and refresh the project protocols list
         */
        async update() {
            try {
                this.$store.commit("isLoading", true);
                // grab the form from the ProjectProtocolForm component
                const form = this.$refs.projectProtocolForm?.form ?? {};
                await this.$store.dispatch("projectProtocols/update", {
                    id: this.$route.params.id,
                    data: {
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
                    },
                });
                await this.$store.dispatch("projectProtocols/list");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
