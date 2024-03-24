<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a class="nav-link" :class="activeClasses">
                        {{ $t("Tenants") }}
                    </a>
                </li>
            </ul>
        </div>
        <form @submit.prevent="update">
            <div>
                <tenants-tab
                    v-if="isApiCalled"
                    @tenantValueChanged="tenantValueChanged"
                    :customers="companies.data"
                    :errors="errors"
                    :distributedFileSystemOptions="distributedFileSystemOptions"
                    :databaseCloudOptions="databaseCloudOptions"
                    :cloudSystems="systemCloud.data"
                    :propTenant="form.tenant"
                ></tenants-tab>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/systems/cloud" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button :loading="isLoading" class="secondary-btn">
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
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import PriceInput from "@/Components/PriceInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import ServerTabs from "../Components/ServerTabs.vue";
import TenantsTab from "../Components/TenantsTab.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const filesystemResponse = await this.$store.dispatch(
                "distributedFilesystem/list"
            );
            this.distributedFileSystemOptions =
                filesystemResponse?.data?.data ?? [];

            const cloudResponse = await this.$store.dispatch(
                "databaseCloud/list"
            );
            this.databaseCloudOptions = cloudResponse?.data?.data ?? [];

            const response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                isSystem: true,
            });
            await this.$store.dispatch("systemCloud/list");
        } catch (e) {}
        this.refresh();
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        PriceInput,
        SelectLinkInput,
        TrashedMessage,
        MultiSelectInput,
        ServerTabs,
        TenantsTab,
        PageHeader
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("systemCloud", ["systemCloud"]),
        ...mapGetters("companies", ["companies"]),
    },
    props: {
        products: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/systems/cloud",
                },
                {
                    text: this.$t("Systems"),
                    to: `/systems/cloud?page=${this.returnPage}`,
                },
                {
                    text: "Cloud",
                    to: `/systems/cloud?page=${this.returnPage}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            distributedFileSystemOptions: [],
            returnPage:'',
            databaseCloudOptions: [],
            systems: {},
            isApiCalled: false,
            form: {
                tenant: {},
            },
            activeClasses: "active",
            inactiveClasses: "inactive",
        };
    },
    methods: {
        async refresh() {
            if(this.$route.query.page){
             this.returnPage=this.$route.query.page; 
            }

            try {
                const response = await this.$store.dispatch(
                    "systemCloud/showTenant",
                    this.$route.params.id
                );
                this.systems = response?.data?.systems ?? {};
                document.title = "Edit Cloud Systems " + this.systems?.tenant?.tenantName;
                this.form = {
                    type: "cloud",
                    tenant: this.systems?.tenant,
                };
                this.isApiCalled = true;
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        tenantValueChanged(val) {
            this.form.tenant = val;
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systems/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                    },
                });
                await this.$store.dispatch("systemCloud/list", {
                    type: "cloud",
                    withTenant: true,
                });
                this.$router.push("/systems/cloud?page="+this.returnPage);
            } catch (e) {}
        },
    },
};
</script>
