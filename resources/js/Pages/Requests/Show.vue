<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link
                class="text-indigo-400 hover:text-indigo-600"
                :to="`/requests`"
                >{{ $t("Requests") }}</router-link
            >
            <span class="text-indigo-400 font-medium">/</span>
            {{ $t("Show") }}
        </h1>
        <form @submit.prevent="">
            <div class="bg-white rounded-md shadow margin-bottom-3rem">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                        <label class="form-label font-bold"
                            >{{ $t("Name") }}:</label
                        >
                        <p>{{ form.name }}</p>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                        <label class="form-label font-bold"
                            >{{ $t("Company") }}:</label
                        >
                        <p>{{ form.company_id?.companyName ?? "-" }}</p>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                        <label class="form-label font-bold"
                            >{{ $t("Tenant") }}:</label
                        >
                        <p>{{ form.tenant_id?.companyName ?? "-" }}</p>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/4">
                        <label class="form-label font-bold"
                            >{{ $t("Usage Statistics") }}:</label
                        >
                        <p>{{ form.tenant_id?.companyName ?? "-" }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex flex-row-reverse justify-between">
                <loading-button
                    @click="generateNewLicense"
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="btn-green"
                    >{{ $t("Generate New License") }}</loading-button
                >
                <button
                    @click="toggleModal = true"
                    v-if="licenseKey"
                    class="px-3 py-2 mx-1 docsHeroColorBlue rounded"
                >
                    {{ $t("Show License Key") }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
    },
    props: {},
    data() {
        return {
            form: {
                name: "",
                company_id: "",
                tenant_id: "",
                rules: [],
            },
            toggleModal: false,
            selectedProducts: [],
            licenseKey: "",
            usageStatistics: [],
        };
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
            const response = await this.$store.dispatch(
                "licensingLicenses/show",
                this.$route.params.id
            );
            this.form = {
                id: this.$route.params.id,
                name: response?.data?.name ?? "",
                company_id: response?.data?.company ?? "",
                tenant_id: response?.data?.tenant ?? "",
                rules: response?.data?.rules ?? [],
            };
            this.selectedProducts = response?.data?.products ?? [];
            this.usageStatistics = response?.data?.events ?? [];
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * hashes the inputString and returns the hashed string
         * @param {inputString} input string to be hashed
         */

        /**
         * hits the create api-key API and creates a licenseKey from the client_id and secret retrieved from the response
         * shows the created license key in the modal
         * and download the license key as a file
         */
        async generateNewLicense() {
            try {
                this.$store.commit("isLoading", true);
                const payload = {
                    name:
                        (this.form.company_id?.companyName ?? "") +
                        "-" +
                        this.form.name,
                    company_id: this.form.company_id?.id ?? "",
                    tenant_id: this.form.tenant_id?.id ?? "",
                    roles: this.form.rules.map((rule) => rule.role_id),
                    status: 1,
                };
                const response = await this.$store.dispatch(
                    "apiKeys/create",
                    payload
                );
                this.$store.commit("isLoading", true);
                this.licenseKey += response?.data?.jwt ?? "";
                this.licenseKey += ":";
                this.licenseKey += window.btoa(this.form.id);
                this.licenseKey += ":";
                this.licenseKey += window.btoa(this.form.name);
                this.licenseKey += ":";
                this.licenseKey += window.btoa(this.form.company_id?.id ?? "");
                this.licenseKey += ":";
                this.licenseKey += window.btoa(
                    this.form.company_id?.companyName ?? ""
                );
                let hashed = this.hashCode(this.licenseKey);
                this.licenseKey += ":";
                this.licenseKey += hashed;

                this.toggleModal = true;
                let fileName = `${this.form.company_id?.companyName ?? ""}-${
                    this.form.name
                }.lic`;
                let blob = new Blob([this.licenseKey], {
                    type: "text/plain",
                });
                let url = URL.createObjectURL(blob);
                let link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("isLoading", false);
            }
        },
    },
};
</script>
