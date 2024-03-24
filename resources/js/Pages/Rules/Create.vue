<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Rules Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.rule_name"
                                    :error="errors.rule_name"
                                    :label="$t('Rule Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :textLabel="$t('Event Name')"
                                    :trackBy="'id'"
                                    :label="'name'"
                                    v-model="form.event_name"
                                    :multiple="false"
                                    :options="eventNames"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.product_id"
                                    :key="form.product_id"
                                    :options="products.data"
                                    :multiple="false"
                                    :textLabel="$t('Product')"
                                    label="name"
                                    :error="errors.product_id"
                                    trackBy="id"
                                    moduleName="products"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :textLabel="$t('Role')"
                                    :trackBy="'id'"
                                    :label="'title'"
                                    v-model="form.role_id"
                                    :multiple="false"
                                    moduleName="roles"
                                    :search-param-name="'search_string'"
                                    :options="roles"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.min_value"
                                    :error="errors.min_value"
                                    :label="$t('Min Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.max_value"
                                    :error="errors.max_value"
                                    :label="$t('Max Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.value"
                                    :error="errors.value"
                                    :label="$t('Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.deny_if_value"
                                    :error="errors.deny_if_value"
                                    :label="$t('Deny If Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.config"
                                    :error="errors.config"
                                    :label="$t('Config')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    :required="true"
                                    v-model="form.reset_type"
                                    :error="errors.reset_type"
                                    :label="$t('Reset Type')"
                                >
                                    <option value="add">{{ $t("add") }}</option>
                                    <option value="dec">{{ $t("dec") }}</option>
                                    <option value="max">{{ $t("max") }}</option>
                                    <option value="min">{{ $t("min") }}</option>
                                    <option value="set">{{ $t("set") }}</option>
                                </SelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.reset_value"
                                    :error="errors.reset_value"
                                    :label="$t('Reset Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    :type="'number'"
                                    v-model="form.reset_frequency_hours"
                                    :error="errors.reset_frequency_hours"
                                    :label="$t('Reset Frequency Hours')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    :required="true"
                                    v-model="form.deny_if_op"
                                    :error="errors.deny_if_op"
                                    :label="$t('Deny If Op')"
                                >
                                    <option value=">">{{ $t(">") }}</option>
                                    <option value="<">{{ $t("<") }}</option>
                                </SelectInput>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Reset Pattern") }}</h3>
                </div>
                <div class="card-body">
                    <CronExpressionMaker ref="cronExpressionMaker" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/rules" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    :disabled="$refs.cronExpressionMaker?.hasError ?? false"
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
import SelectInput from "../../Components/SelectInput.vue";
import CronExpressionMaker from "./Components/CronExpressionMaker.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("roles", ["roles"]),
        ...mapGetters("products", ["products"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        SelectInput,
        CronExpressionMaker,
        PageHeader,
    },
    props: {},
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Rules",
                    to: "/rules",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            eventNames: [],
            form: {
                name: "",
                event_name: "",
                company_id: "",
                tenant_id: "",
                product_id: "",
                rule_name: "",
                max_value: 0,
                min_value: 0,
                value: 0,
                deny_if_value: 0,
                role_id: "",
                config: "",
                reset_type: "",
                reset_value: 0,
                reset_frequency_hours: 0,
                reset_pattern: "",
                deny_if_op: "",
            },
        };
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
            if (!this.roles?.length) await this.$store.dispatch("roles/list");
            const response = await this.$store.dispatch("eventName/list");
            this.eventNames = response?.data;
            if (!this.products?.data?.length)
                await this.$store.dispatch("products/list");
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * hits the rules create API
         */
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("rules/create", {
                    ...this.form,
                    event_name: this.form.event_name?.id ?? "",
                    role_id: this.form.role_id?.id ?? "",
                    company_id: this.form.company_id?.id ?? "",
                    tenant_id: this.form.tenant_id?.id ?? "",
                    product_id: "" + (this.form.product_id?.id ?? ""),
                    max_value: parseInt(this.form.max_value),
                    min_value: parseInt(this.form.min_value),
                    value: parseInt(this.form.value),
                    deny_if_value: parseInt(this.form.deny_if_value),
                    reset_value: parseInt(this.form.reset_value),
                    reset_frequency_hours: parseInt(
                        this.form.reset_frequency_hours
                    ),
                    reset_pattern: this.$refs.cronExpressionMaker.resetPattern, // get the resetPattern value from CronExpressionMaker component
                });
                await this.$store.dispatch("rules/list");
                this.$router.push("/rules");
            } catch (e) {}
        },
    },
};
</script>
