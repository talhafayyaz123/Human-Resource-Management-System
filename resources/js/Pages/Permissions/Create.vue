<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Permission Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.title"
                                    :required="true"
                                    :error="errors.title"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.needs_permission"
                                    :options="permissions"
                                    moduleName="permissions"
                                    :search-param-name="'search_string'"
                                    :multiple="false"
                                    :textLabel="$t('Needs Permission')"
                                    label="title"
                                    trackBy="id"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.scope"
                                    :required="true"
                                    :error="errors.scope"
                                    :label="$t('Scope')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.value"
                                    maxlength="16"
                                    :required="true"
                                    :error="errors.value"
                                    :label="$t('Value')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="can_register">
                                        {{ $t("Can Be Assigned") }}
                                    </label>
                                    <input
                                        id="can_be_assigned"
                                        v-model="form.can_be_assigned"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="active"> {{ $t("Active") }} </label>
                                    <input
                                        id="active"
                                        v-model="form.active"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex flex-col">
                                    <MultiSelectInput
                                        v-model="form.grouping"
                                        :options="groupings"
                                        :multiple="false"
                                        :textLabel="$t('Groups')"
                                        label="grouping"
                                        trackBy="grouping"
                                        :moduleName="'permissionGroups'"
                                        open-direction="bottom"
                                        :search-param-name="'search_string'"
                                    />
                                    <button
                                        v-if="!group && !form.grouping"
                                        @click="addGroup"
                                        class="px-3 py-2 mt-2 docsHeroColorBlue rounded"
                                    >
                                        + Add Group
                                    </button>
                                    <Group
                                        v-if="group && !form.grouping"
                                        @deleteParentGroup="group = null"
                                        :isOriginal="true"
                                        :triggered="triggered"
                                        :group="group"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.description"
                                    :error="errors.description"
                                    :label="$t('Description')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.tenant_id"
                                    :key="form.tenant_id"
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
                                <MultiSelectInput
                                    v-model="form.company_id"
                                    :key="form.company_id"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Company"
                                    label="companyName"
                                    :error="errors.company_id"
                                    trackBy="id"
                                    moduleName="companies"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    v-model="form.type"
                                    :key="form.type"
                                    :label="$t('Type')"
                                    :required="false"
                                    :error="errors.type"
                                >
                                    <option :value="1">{{ $t("Owner") }}</option>
                                    <option :value="2">{{ $t("Partner") }}</option>
                                    <option :value="3">{{ $t("Customer") }}</option>
                                </SelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="is_all_tenants">
                                        {{ $t("Available For All Tenants") }}
                                    </label>
                                    <input
                                        id="is_all_tenants"
                                        v-model="form.is_all_tenants"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="active">
                                        {{ $t("Available For All Companies") }}
                                    </label>
                                    <input
                                        id="is_all_companies"
                                        v-model="form.is_all_companies"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/permissions" class="primary-btn mr-3">
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
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Group from "./Group.vue";
import { mapGetters } from "vuex";
import SelectInput from "@/Components/SelectInput.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("permissions", ["permissions"]),
        ...mapGetters("permissionGroups", ["groupings"]),
        ...mapGetters("companies", ["companies", "partners"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        TextAreaInput,
        Group,
        SelectInput,
        PageHeader,
    },
    props: {},
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Permissions",
                    to: "/permissions",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            triggered: false,
            group: null,
            form: {
                title: "",
                needs_permission: null,
                can_be_assigned: false,
                active: false,
                scope: "",
                value: "",
                grouping: "",
                description: "",
                tenant_id: "",
                company_id: "",
                is_all_tenants: false,
                is_all_companies: false,
                type: "",
            },
        };
    },
    async mounted() {
        try {
            if (!this.permissions?.length)
                await this.$store.dispatch("permissions/list", {
                    limit_start: 0,
                    limit_count: 100,
                });
            if (!this.groupings?.length)
                await this.$store.dispatch("permissionGroups/list", {
                    limit_start: 0,
                    limit_count: 100,
                });
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
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * adds a group
         */
        addGroup() {
            this.group = {
                name: "",
                child: null,
            };
        },
        /**
         * reccursively checks if a group has an empty display name or value if yes then return true
         * @param {group} the group that we want to check error for
         * @returns boolean
         */
        checkForGroupError(group) {
            if (!group) return false;
            let hasError = false;
            if (!group.name) {
                hasError = true;
            } else {
                if (group.child) {
                    hasError = this.checkForGroupError(group.child);
                }
            }
            return hasError;
        },
        /**
         * makes a group path string and returns it
         * this method is called recursively since we can have child and sub childs and so on
         * we traverse through all the sub groups to create a group path
         * @param {group} group object that has a 'name' and 'child' in it
         */
        getGroupPath(group) {
            if (!group) return "";
            let groupPath = "";
            groupPath += group.name;
            if (group.child) {
                groupPath += "/" + this.getGroupPath(group.child);
            }
            return groupPath;
        },
        async store() {
            this.triggered = true;
            if (!this.checkForGroupError(this.group)) {
                try {
                    this.$store.commit("isLoading", true);
                    const payload = {
                        ...this.form,
                        needs_permission:
                            this.form.needs_permission?.id ?? null,
                        can_be_assigned:
                            this.form.can_be_assigned === true ? 1 : 0,
                        active: this.form.active === true ? 1 : 0,
                        grouping:
                            this.form.grouping?.grouping ??
                            this.getGroupPath(this.group),
                        set_tenant_id: this.form.tenant_id?.id ?? "",
                        set_company_id: this.form.company_id?.id ?? "",
                        is_all_tenants:
                            this.form.is_all_tenants === true ? 1 : 0,
                        is_all_companies:
                            this.form.is_all_companies === true ? 1 : 0,
                    };
                    delete payload["tenant_id"];
                    delete payload["company_id"];
                    await this.$store.dispatch("permissions/create", payload);
                    await this.$store.dispatch("permissions/list", {
                        limit_start: 0,
                        limit_count: 100,
                    });
                    if (this.group)
                        await this.$store.dispatch("permissionGroups/list", {
                            limit_start: 0,
                            limit_count: 100,
                        });
                    this.$router.push("/permissions");
                } catch (e) {
                    console.log(e);
                }
            }
        },
    },
};
</script>
