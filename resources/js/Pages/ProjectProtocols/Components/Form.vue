<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-model="form.customerId"
                    :error="errors.customerId"
                    :key="form.customerId"
                    class=""
                    :textLabel="$t('Customer')"
                    label="companyName"
                    :options="companies?.data ?? []"
                    @update:model-value="fetchProjectsByCustomer"
                    trackBy="id"
                    :required="true"
                    :multiple="false"
                    moduleName="companies"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                    <label class="input-label"
                        >{{ $t("Date") }}<span class="text-red-600">&nbsp;*</span></label
                    >
                    <datepicker
                        :clearable="false"
                        :enable-time-picker="false"
                        auto-apply
                        :close-on-auto-apply="false"
                        v-model="form.date"
                        :class="errors.date ? 'error' : ''"
                        class="form-control"
                    />
                    <div v-if="errors?.date" class="form-error">
                        {{ errors.date }}
                    </div>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-model="form.protocolTypeId"
                    :error="errors.protocolTypeId"
                    :key="form.protocolTypeId"
                    class=""
                    :textLabel="$t('Protocol Type')"
                    label="name"
                    :options="protocolTypes?.data ?? []"
                    trackBy="id"
                    :required="true"
                    :multiple="false"
                    moduleName="protocolTypes"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-model="form.projectId"
                    :error="errors.projectId"
                    :key="form.projectId"
                    class=""
                    :textLabel="$t('Project')"
                    label="projectNumber"
                    :options="projects?.data ?? []"
                    trackBy="id"
                    :required="true"
                    :multiple="false"
                    :isDisabled="!form.customerId"
                    moduleName="projects"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-model="form.projectStatusId"
                    :error="errors.projectStatusId"
                    :key="form.projectStatusId"
                    class=""
                    :textLabel="$t('Project Status')"
                    label="name"
                    :options="projectStatuses?.data ?? []"
                    trackBy="id"
                    :required="true"
                    :multiple="false"
                    moduleName="projectStatuses"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    class=""
                    :error="errors.distributors"
                    :key="form.distributors"
                    v-model="form.distributors"
                    :options="users ?? []"
                    :required="true"
                    :multiple="true"
                    :text-label="$t('Distributors')"
                    :customLabel="customLabel"
                    trackBy="id"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :query="{
                        limit_start: 0,
                        limit_count: 10,
                        type: 'employee',
                    }"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    class=""
                    :error="errors.participants"
                    :key="form.participants"
                    v-model="form.participants"
                    :options="users ?? []"
                    :required="true"
                    :multiple="true"
                    :text-label="$t('Participants')"
                    :customLabel="customLabel"
                    trackBy="id"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :query="{
                        limit_start: 0,
                        limit_count: 10,
                        type: 'employee',
                    }"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    class=""
                    :error="errors.recorderId"
                    :key="form.recorderId"
                    v-model="form.recorderId"
                    :options="users ?? []"
                    :required="true"
                    :multiple="false"
                    :text-label="$t('Recorder')"
                    :customLabel="customLabel"
                    trackBy="id"
                    moduleName="auth"
                    :isDisabled="true"
                />
            </div>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import mainMixin from "../Mixins/mainMixin";
import { toRaw } from "vue";
import { mapGetters } from "vuex";

const defaultForm = {
    customerId: "",
    date: new Date(),
    protocolTypeId: "",
    projectId: "",
    projectStatusId: "",
    distributors: [],
    participants: [],
    recorderId: "",
};

export default {
    name: "ProjectProtocolForm",
    mixins: [mainMixin],
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters(["errors"]),
    },
    props: {
        actionForm: {
            type: Object,
            default: { ...defaultForm },
        },
    },
    components: {
        MultiSelectInput,
    },
    data() {
        return {
            form: { ...defaultForm },
        };
    },
    watch: {
        actionForm: {
            handler: function () {
                this.syncForm();
            },
            deep: true,
        },
        user: {
            handler: function () {
                this.form.recorderId = this.user;
            },
            deep: true,
        },
    },
    unmounted() {
        this.form = { ...defaultForm };
    },
    mounted() {
        this.syncForm();
        if (this.user?.id) {
            this.form.recorderId = this.user;
        }
    },
    methods: {
        /**
         * fetch projects based on customerId
         */
        async fetchProjectsByCustomer() {
            try {
                this.form.projectId = "";
                await this.$store.dispatch("projects/list", {
                    companyId: this.form.customerId?.id,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * syncs the prop actionForm with form
         */
        syncForm() {
            this.form = toRaw({ ...this.actionForm }); // remove the reactivity
        },
        /**
         * returns a custom label value for the selected option in multiselect component
         * @param {props} the properties of the options
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
};
</script>

<style scoped></style>
