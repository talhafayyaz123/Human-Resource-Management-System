<template>
    <div>
        <!-- <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" to="/change-processes">{{
                $t("Change Processes")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Edit") }}</span>
        </h1> -->

        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="save">
            <div class="card">
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :isReadOnly="true"
                                    v-model="form.process"
                                    :key="form.process"
                                    :error="errors.process"
                                    class=""
                                    :label="$t('Process')"
                                >
                                    <option value="name-change">
                                        {{ $t("Name Change") }}
                                    </option>
                                    <option value="bank-account-change">
                                        {{ $t("Bank Account Change") }}
                                    </option>
                                    <option
                                        value="change-of-health-insurance-company"
                                    >
                                        {{
                                            $t(
                                                "Change Of Health Insurance Company"
                                            )
                                        }}
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
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :isDisabled="true"
                                    class=""
                                    v-model="form.requester"
                                    :key="form.requester"
                                    :error="errors['requester']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('Requester')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <label class="form-label input-label"
                                    >{{ $t("Request Date") }}:</label
                                >
                                <datepicker
                                    :disabled="true"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    class="form-control"
                                    v-model="form.createdAt"
                                    :class="errors.createdAt ? 'error' : ''"
                                />
                                <div
                                    v-if="errors?.createdAt"
                                    class="form-error"
                                >
                                    {{ errors.createdAt }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :isDisabled="true"
                                    class=""
                                    v-model="form.editor"
                                    :key="form.editor"
                                    :error="errors['editor']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :text-label="$t('Editor')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.status"
                                    :key="form.status"
                                    :error="errors.status"
                                    class=""
                                    :label="$t('Status')"
                                >
                                    <option value="open">
                                        {{ $t("Open") }}
                                    </option>
                                    <option value="in-progress">
                                        {{ $t("In Progress") }}
                                    </option>
                                    <option value="released">
                                        {{ $t("Released") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <component
                        @termsAgreed="termsAgreed = $event"
                        ref="processComponent"
                        :is="enums[form.process]"
                        :changeRequestData="form.changeRequestData ?? {}"
                        :responseReason="form.reason ?? ''"
                        :fromChangeRequest="true"
                    ></component>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Checklist") }}</h3>
                </div>
                <div class="card-body">
                    <div
                        v-for="item in form.checkList"
                        :key="'checklist-' + item.id"
                        class="checkbox-group"
                    >
                        <input
                            class="checkbox-input"
                            type="checkbox"
                            :id="'checklist-item-' + item.id"
                        />
                        <label class="checkbox-label" :for="'checklist-item-' + item.id">{{
                            item.text
                        }}</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/change-processes" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.update`)"
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
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import NameChange from "../PersonalModificationProcesses/Components/NameChange.vue";
import AddressChange from "../PersonalModificationProcesses/Components/AddressChange.vue";
import BankDetailsChange from "../PersonalModificationProcesses/Components/BankDetailsChange.vue";
import HealthInsuranceChange from "../PersonalModificationProcesses/Components/HealthInsuranceChange.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    components: {
        MultiSelectInput,
        LoadingButton,
        SelectInput,
        NameChange,
        AddressChange,
        BankDetailsChange,
        HealthInsuranceChange,
        PageHeader,
    },
    data() {
        return {
            termsAgreed: false,
            form: {
                process: "",
                requester: "",
                createdAt: "",
                editor: "",
                status: "open",
                checkList: [],
            },
            enums: {
                "name-change": "name-change",
                "bank-account-change": "bank-details-change",
                "change-of-address": "address-change",
                "change-of-health-insurance-company": "health-insurance-change",
            },
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/change-processes",
                },
                {
                    text: this.$t("Change Processes"),
                    to: "/change-processes",
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                }
            ],
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "changeProcesses/show",
                this.$route.params.id
            );
            this.form = response?.data ?? {
                process: "",
                requester: "",
                createdAt: "",
                editor: "",
                status: "open",
                checkList: [],
            };
        } catch (e) {}
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async save() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("changeProcesses/updateStatus", {
                    id: this.$route.params.id,
                    status: this.form.status,
                });
                await this.$store.dispatch("changeProcesses/list");
                this.$router.push("/change-processes");
            } catch (e) {}
        },
    },
};
</script>
