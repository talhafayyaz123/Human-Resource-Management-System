<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Asset List Create") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.employeeId"
                                    :errors="errors.employeeId"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :textLabel="$t('Employees')"
                                    :customLabel="customLabel"
                                    :trackBy="'id'"
                                    :moduleName="'userProfile'"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
                                    :required="true"
                                    v-model="form.personalNumber"
                                    :error="errors.personalNumber"
                                    :label="$t('Personal Number')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
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
                                    :isReadonly="true"
                                    :required="true"
                                    v-model="form.location"
                                    :error="errors.location"
                                    :label="$t('Location')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.teamId"
                                    :key="randomNumber"
                                    v-if="form.teamId"
                                    :errors="errors.teamId"
                                    :options="teams?.data ?? []"
                                    :multiple="true"
                                    :textLabel="$t('Team')"
                                    label="name"
                                    :trackBy="'id'"
                                    :moduleName="'teams'"
                                    :taggable="true"
                                    :required="true"
                                    :isReadonly="true"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <!-- <MultiSelectInput
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            v-model="form.employeeText"
                            :key="randomNumber"
                            v-if="form.teamId"
                            :errors="errors.employeeText"
                            :options="assetEmployeeText?.data ?? []"
                            :multiple="true"
                            :textLabel="$t('Employee Text')"
                            label="assetEmployeeText"
                            :trackBy="'id'"
                            :moduleName="'employeeText'"
                            :taggable="true"
                            :required="true"
                            :isReadonly="true"
                        /> -->
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/asset-lists" class="primary-btn mr-3">
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
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("assetEmployeeText", ["assetEmployeeText"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        NumberInput,
        PageHeader,
    },
    async mounted() {
        await this.$store.dispatch("userProfile/list");
        await this.$store.dispatch("userTeams/list");
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Asset Management"),
                    to: "/asset-lists",
                },
                {
                    text: this.$t("Asset Lists"),
                    to: "/asset-lists",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                personalNumber: "",
                name: "",
                assetType: "",
                employeeId: "",
                location: "",
                teamId: "",
                employeeText: "",
            },
            randomNumber: "",
        };
    },
    watch: {
        "form.employeeId": async function (newEmployeeId, oldEmployeeId) {
            let response = await this.$store.dispatch(
                "assetList/getEmployee",
                newEmployeeId["id"]
            );
            response = response?.data?.employee;

            this.form.name = response.name;
            this.form.personalNumber = response?.personalNumber;
            if (response.location?.name)
                this.form.location = response.location?.name;
            else {
                this.form.location = "";
            }
            this.form.teamId = response.teams;
            this.randomNumber = Math.random() * 100;
            await this.$nextTick();
        },
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assetList/create", {
                    ...this.form,
                    employeeId: this.form.employeeId["id"] ?? "",
                    teamId: this.form.teamId["id"] ?? "",
                });
                await this.$store.dispatch("assetList/list");
                this.$router.push("/asset-lists");
            } catch (e) {}
        },
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
    },
    async mounted() {
        await this.$store.dispatch("assetEmployeeText/list");
    },
};
</script>
