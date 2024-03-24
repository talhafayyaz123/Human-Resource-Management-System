<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Document Service Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    v-model="name"
                                    :key="name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="companyId"
                                    :key="companyId"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Company"
                                    label="companyName"
                                    :error="errors.companyId"
                                    trackBy="id"
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
                                    @change="handleFileChange"
                                    ref="fileInput"
                                    type="file"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-2 grid-cols-[40%_60%] mt-2">
                        <div class="mt-5">
                            <div
                                class="flex items-center"
                                v-for="(value, key) in config"
                                :key="key"
                            >
                                <div class="w-full">
                                    <div class="form-group">
                                        <TextInput
                                            @change="setKey"
                                            @click="setCodeString(value, key)"
                                            :model-value="key"
                                            :shouldHighlight="selectedConfig === key"
                                            class="cursor-pointer my-3"
                                        />
                                    </div>
                                </div>
                                
                                <font-awesome-icon
                                    @click="removeConfig(key)"
                                    class="cursor-pointer mx-3"
                                    icon="fas fa-trash"
                                />
                            </div>
                            <button
                                @click="config['Key'] = {}"
                                class="secondary-btn py-2 px-3"
                            >
                                {{ $t("Add new config entry") }}
                            </button>
                        </div>
                        <div>
                            <label class="font-bold" for="">JSON:</label>
                            <MonacoEditor
                                @contentChange="setConfig($event)"
                                :readOnly="false"
                                :codeString="codeString"
                                :height="'500px'"
                                :language="'json'"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/employees" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    @click="downloadOriginalFile"
                    :loading="isLoading"
                    class="btn-green mr-2"
                    >{{ $t("Download Original") }}</loading-button
                >
                <loading-button
                    v-if="$can(`${$route.meta.permission}.list`)"
                    @click="
                        $router.push(`/document-service/${$route.params.id}`)
                    "
                    :loading="isLoading"
                    class="secondary-btn mr-2"
                    >{{ $t("View Document") }}</loading-button
                >
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    @click="update"
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
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import MonacoEditor from "@/Components/MonacoEditor.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("documentService", ["documentServices", "count"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        PageHeader,
        MonacoEditor,
    },
    remember: "form",
    data() {
        return {
            selectedConfig: null,
            config: {},
            codeString: "",
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
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            name: "",
            companyId: "",
            tenant_id: "",
            documentType: "",
            form: new FormData(),
        };
    },
    async mounted() {
        this.refresh();
    },
    methods: {
        /**
         * triggered when config key name is changed
         * @param {event} change event
         */
        setKey(event) {
            try {
                const previousConfig = this.config[this.selectedConfig];
                delete this.config[this.selectedConfig];
                this.config[event.target.value] = previousConfig;
                this.selectedConfig = event.target.value;
            } catch (e) {}
        },
        /**
         * removes 'key' from config variable
         * @param {key} key to be removed
         */
        removeConfig(key) {
            delete this.config[key];
            if (this.selectedConfig === key) {
                this.codeString = "";
                this.json = "";
                this.selectedConfig = null;
            }
        },
        /**
         * sets the changed config json value to the config variable
         * @param {*} event
         */
        setConfig(event) {
            if (this.selectedConfig && this.config[this.selectedConfig])
                this.config[this.selectedConfig] = event;
        },
        /**
         * sets codeString, selectedConfig when we click on a config key
         * @param {value} value to be set to codeString
         * @param {key} key to be selected
         */
        setCodeString(value, key) {
            if (!!this.selectedConfig) {
                try {
                    this.config[this.selectedConfig] = JSON.parse(
                        this.config[this.selectedConfig]
                    );
                } catch (e) {}
            }
            this.codeString = JSON.stringify(value);
            this.json = "";
            this.selectedConfig = key;
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const documentResponse = await this.$store.dispatch(
                    "documentService/show",
                    this.$route.params.id
                );
                this.name = documentResponse?.data?.name;
                this.companyId = documentResponse?.data?.company_id;
                this.tenant_id = documentResponse?.data?.tenant_id;
                this.documentType = documentResponse?.data?.type;
                this.config = documentResponse?.data?.config ?? {};
                if (
                    typeof this.config !== "object" ||
                    this.config instanceof Array
                ) {
                    this.config = {};
                }
                this.selectedConfig = Object.keys(this.config)?.[0] ?? null;
                if (this.selectedConfig) {
                    this.setCodeString(
                        this.config[this.selectedConfig],
                        this.selectedConfig
                    );
                }
                if (!this.companies?.data?.length)
                    await this.$store.dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                    });
                if (!this.partners?.data?.length)
                    await this.$store.dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                        customerType: "partner",
                    });
                let company =
                    this.companies?.data?.find(
                        (company) => company.id === this.companyId
                    ) ?? "";

                let response = null;
                if (!company && this.companyId) {
                    response = await this.$store.dispatch(
                        "companies/show",
                        this.$route.query.companyId
                    );
                    company = response?.data?.modelData ?? "";
                }
                this.companyId = company;

                let tenant =
                    this.companies?.data?.find(
                        (company) => company.id === this.tenant_id
                    ) ?? "";
                if (!tenant && this.tenant_id) {
                    response = await this.$store.dispatch(
                        "companies/show",
                        this.tenant_id
                    );
                    tenant = response?.data?.modelData ?? "";
                }
                this.tenant_id = tenant;
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        // calls the download orignal file API from document service and downloads the file
        async downloadOriginalFile() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "documentService/downloadOriginalFile",
                    {
                        id: this.$route.params.id,
                        documentType: this.documentType,
                        filename: this.name,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
        async handleFileChange(event) {
            const file = event?.target?.files?.[0];
            this.form.append("upload_files", file);
        },
        async update() {
            try {
                if (this.companyId?.id) {
                    for (let key in this.config) {
                        try {
                            this.config[key] = JSON.parse(this.config[key]);
                        } catch (e) {}
                    }
                    this.form.append(
                        "data",
                        JSON.stringify({
                            id: this.$route.params.id,
                            company_id: this.companyId?.id,
                            tenant_id: this.tenant_id?.id,
                            config: this.config,
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
                this.refresh();
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
