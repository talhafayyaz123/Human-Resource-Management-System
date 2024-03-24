<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Document Service Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="companyId"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Company"
                                    label="companyName"
                                    trackBy="id"
                                    :error="errors.companyId"
                                    moduleName="companies"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="tenant_id"
                                    :key="tenant_id"
                                    :options="partners?.data ?? []"
                                    :multiple="false"
                                    textLabel="Tenant"
                                    label="companyName"
                                    :error="errors.tenant_id"
                                    trackBy="id"
                                    moduleName="companies"
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    class="w-full my-5"
                                    @change="handleFileChange"
                                    ref="fileInput"
                                    type="file"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/document-service" class="primary-btn mr-3">
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
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Document Service",
                    to: "/document-service",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            name: "",
            companyId: "",
            tenant_id: "",
            form: new FormData(),
        };
    },
    mounted() {
        if (!this.companies?.data?.length)
            this.$store.dispatch("companies/list", {
                page: 1,
                perPage: 25,
            });
        if (!this.partners?.data?.length)
            this.$store.dispatch("companies/list", {
                page: 1,
                perPage: 25,
                customerType: "partner",
            });
    },
    methods: {
        async handleFileChange(event) {
            const file = event?.target?.files?.[0];
            this.form.append("upload_files", file);
        },
        async store() {
            try {
                if (this.companyId?.id) {
                    this.form.append(
                        "data",
                        JSON.stringify({
                            company_id: this.companyId?.id,
                            tenant_id: this.tenant_id?.id,
                        })
                    );
                } else {
                    this.$store.commit("errors", {
                        companyId: "Company is a required field",
                    });
                    return;
                }
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("documentService/create", this.form);
                this.$refs.fileInput.value = null;
                await this.$store.dispatch("documentService/list");
                this.$router.push("/document-service");
            } catch (e) {}
        },
    },
};
</script>
