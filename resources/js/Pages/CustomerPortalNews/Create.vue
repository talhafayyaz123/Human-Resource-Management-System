<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill News Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.language"
                                    :required="true"
                                    :key="form.language"
                                    :error="errors.language"
                                    class="pb-4 pr-6 w-full lg:w-full"
                                    :label="$t('Language')"
                                >
                                    <option value="english">
                                        {{ $t("English") }}
                                    </option>
                                    <option value="german">
                                        {{ $t("German") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group"></div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    class="pb-4 pr-6 w-full lg:w-full"
                                    :required="true"
                                    v-model="form.subject"
                                    :error="errors?.subject"
                                    :label="$t('Subject')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.audience"
                                    :required="true"
                                    :error="errors.audience"
                                    class="pb-4 pr-6 w-full lg:w-full"
                                    :label="$t('Audience')"
                                >
                                    <option value="internal">
                                        {{ $t("Internal") }}
                                    </option>
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="partner">
                                        {{ $t("Partner") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.admin"
                                    :showLabels="false"
                                    :options="users"
                                    :multiple="false"
                                    class="pb-4 pr-6 w-full lg:full"
                                    :textLabel="$t('Admin')"
                                    label="first_name"
                                    :isDisabled="true"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors.driverId"
                                    v-if="isApiCalled"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
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
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
                                        >
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.first_name }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.last_name }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <QuillTextEditor
                                :key="form.language"
                                :content-type="'html'"
                                :content="form.description"
                                :required="true"
                                :error="errors.description"
                                @delta="form.description = $event"
                                :label="$t('Description')"
                                height="20rem"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/customer-portal-news"
                    class="primary-btn mr-3"
                >
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
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    components: {
        QuillTextEditor,
        LoadingButton,
        TextInput,
        TextAreaInput,
        MultiSelectInput,
        SelectInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("News"),
                    to: "/customer-portal-news",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                subject: "",
                description: "",
                audience: "internal",
                admin: "",
            },
            isApiCalled: false,
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("auth", ["user"]),
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        this.form.admin = this.users.find((user) => user.id == this.user.id);
        this.isApiCalled = true;
    },
    methods: {
        filterOptions(options, selectedValues) {
            return options.filter(
                (option) => !selectedValues.includes(option.value)
            );
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("customerPortalNews/create", {
                    ...this.form,
                    userId: this.form.admin?.id,
                });
                await this.$store.dispatch("customerPortalNews/list");
                this.$router.push("/customer-portal-news");
            } catch (e) {}
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
};
</script>
