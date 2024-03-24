<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">

            <div class="card">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.process"
                                    :key="form.process"
                                    :error="errors.process"
                                    :required="true"
                                    :label="$t('Process')"
                                >
                                    <option value="name-change">
                                        {{ $t("Name Change") }}
                                    </option>
                                    <option value="bank-account-change">
                                        {{ $t("Bank Account Change") }}
                                    </option>
                                    <option value="change-of-health-insurance-company">
                                        {{ $t("Change Of Health Insurance Company") }}
                                    </option>
                                    <option value="change-of-address">
                                        {{ $t("Change Of Address") }}
                                    </option>
                                    <option value="change-of-tax-class">
                                        {{ $t("Change Of Tax Class") }}
                                    </option>
                                    <option value="change-of-child-allowance">
                                        {{ $t("Change Of Child Allowance") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                    <div class="my-5">
                        <component
                            @termsAgreed="termsAgreed = $event"
                            ref="processComponent"
                            :is="enums[form.process]"
                            :changeRequestData="form.changeRequestData ?? {}"
                            :responseReason="form.reason ?? ''"
                        ></component>
                    </div>
                    
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/personal-modification-processes" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                    :disabled="!termsAgreed"
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
import SelectInput from "@/Components/SelectInput.vue";
import NameChange from "./Components/NameChange.vue";
import AddressChange from "./Components/AddressChange.vue";
import BankDetailsChange from "./Components/BankDetailsChange.vue";
import HealthInsuranceChange from "./Components/HealthInsuranceChange.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";


export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
    },
    components: {
        LoadingButton,
        SelectInput,
        NameChange,
        AddressChange,
        BankDetailsChange,
        HealthInsuranceChange,
        PageHeader
    },
    data() {
        return {
            termsAgreed: false,
            form: {
                process: "",
            },
            enums: {
                "name-change": "name-change",
                "bank-account-change": "bank-details-change",
                "change-of-address": "address-change",
                "change-of-health-insurance-company": "health-insurance-change",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Modification Processes"),
                    to: "/personal-modification-processes",
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                },
            ]
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "personalModificationProcesses/show",
                this.$route.params.id
            );
            this.form = response?.data?.data ?? { process: "" };
        } catch (e) {}
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "personalModificationProcesses/update",
                    {
                        id: this.$route.params.id,
                        data: {
                            ...this.form,
                            userId: this.user.id,
                            reason: this.$refs.processComponent?.reason,
                            changeRequestData:
                                this.$refs.processComponent?.form,
                        },
                    }
                );
                await this.$store.dispatch(
                    "personalModificationProcesses/list"
                );
                this.$router.push("/personal-modification-processes");
            } catch (e) {}
        },
    },
};
</script>
