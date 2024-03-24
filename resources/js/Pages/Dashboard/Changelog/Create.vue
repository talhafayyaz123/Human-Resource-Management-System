<template>
    <div>
        <!-- <h1 class="header mb-8 text-3xl font-bold header">
            <router-link class="secondary-color" to="/dashboard">{{
                $t("Changelog")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Create") }}</span>
        </h1> -->

        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Changelog details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.subject"
                                :error="errors.subject"
                                :label="$t('Subject')"
                            />
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="true"
                                v-if="users && isApiCalled"
                                :showLabels="false"
                                v-model="form.adminId"
                                :options="users"
                                :multiple="false"
                                :textLabel="$t('Admin')"
                                label="first_name"
                                trackBy="id"
                                moduleName="auth"
                                :search-param-name="'search_string'"
                                :customLabel="customLabel"
                                :error="errors.assignee"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="grid-template-columns: 25% 25% 50%"
                                    >
                                        <p class="font-bold">
                                            {{ $t("First Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Last Name") }}
                                        </p>
                                        <p class="font-bold">{{ $t("Email") }}</p>
                                    </div>
                                    <hr />
                                </template>
                                <template #singleLabel="{ props }">
                                    <p>
                                        {{ props.option.first_name }}
                                        {{ props.option.last_name }}
                                    </p>
                                </template>
                                <template #option="{ props }">
                                    <div
                                        class="grid"
                                        style="grid-template-columns: 25% 25% 50%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.email }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <QuillTextEditor
                                :content="form.description"
                                :required="true"
                                :error="errors.description"
                                @delta="form.description = $event"
                                :label="$t('Description')"
                                :height="'500px'"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/dashboard" class="primary-btn mr-3">
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
import NumberInput from "@/Components/NumberInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        this.form.adminId = this.users.find((user) => user.id == this.user.id);
        this.isApiCalled = true;
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        MultiSelectInput,
        NumberInput,
        QuillTextEditor,
        PageHeader,
    },
    data() {
        return {
            form: {
                subject: "",
                adminId: "",
                description: "",
            },
            isApiCalled: false,
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Changelog"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("changelog/create", {
                    ...this.form,
                    adminId: this.form.adminId?.id,
                });
                await this.$store.dispatch("changelog/list");
                this.$router.push("/dashboard");
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>
