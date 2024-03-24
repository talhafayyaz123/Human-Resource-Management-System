<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Update News Details") }}</h3>
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
                                    :required="true"
                                    v-model="form.subject"
                                    :error="errors?.subject"
                                    class="pb-4 pr-6 w-full lg:w-full"
                                    :label="$t('Subject')"
                                />
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.audience"
                                    :required="true"
                                    :key="form.audience"
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
                            <MultiSelectInput
                                v-model="form.admin"
                                :showLabels="false"
                                :options="users"
                                class="pb-8 pr-6 w-full lg:w-full"
                                :multiple="false"
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
                                        style="grid-template-columns: 50% 50%"
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
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <QuillTextEditor
                                :key="form.language"
                                :content="form.description"
                                :content-type="'html'"
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
                    :to="`/customer-portal-news?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
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
import TextAreaInput from "@/Components/TextareaInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    async mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("auth", ["user"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TextAreaInput,
        TrashedMessage,
        QuillTextEditor,
        MultiSelectInput,
        SelectInput,
        PageHeader,
    },
    watch: {
        "form.language": {
            handler: function (val) {
                if (this.form.language === "english") {
                    this.form.subject = this.englishSubject;
                    this.form.description = this.englishDescription;
                } else {
                    this.form.subject = this.germanSubject;
                    this.form.description = this.germanDescription;
                }
            },
            deep: true,
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("News"),
                    to: `/customer-portal-news?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            isLoaded: false,
            modelData: {},
            form: {
                subject: "",
                description: "",
                admin: "",
                audience: "",
                language: "english",
            },
            germanDescription: "",
            englishDescription: "",
            germanSubject: "",
            englishSubject: "",
            isApiCalled: false,
        };
    },
    methods: {
        filterOptions(options, selectedValues) {
            return options.filter(
                (option) => !selectedValues.includes(option.value)
            );
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
                const response = await this.$store.dispatch(
                    "customerPortalNews/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    language: this.modelData.language,
                    audience: this.modelData.audience,
                };
                this.englishDescription = this.modelData.description;
                this.germanDescription = this.modelData.germanDescription;
                this.germanSubject = this.modelData.germanSubject;
                this.englishSubject = this.modelData.subject;
                if (this.modelData.language === "english") {
                    this.form.subject = this.englishSubject;
                    this.form.description = this.englishDescription;
                } else {
                    this.form.subject = this.germanSubject;
                    this.form.description = this.germanDescription;
                }

                this.form.admin = this.users.find(
                    (user) => user.id == this.modelData?.userId
                );
                this.isApiCalled = true;
            } catch (e) {
            } finally {
                this.isLoaded = true;
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("customerPortalNews/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        userId: this.form.admin?.id,
                    },
                });
                await this.$store.dispatch("customerPortalNews/list");
                this.$router.push(
                    `/customer-portal-news?page=${this.$route.query.page}`
                );
            } catch (e) {}
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "customerPortalNews/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("customerPortalNews/list");
                        this.$router.push(
                            `/customer-portal-news?page=${this.$route.query.page}`
                        );
                    } catch (e) {}
                }
            });
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
};
</script>
