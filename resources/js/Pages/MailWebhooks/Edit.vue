<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Mail Webhook Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.reg_ex_content"
                                    :error="errors.reg_ex_content"
                                    :label="$t('Regex Content')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.reg_ex_subject"
                                    :error="errors.reg_ex_subject"
                                    :label="$t('Regex Subject')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.reg_ex_from_mail"
                                    :error="errors.reg_ex_from_mail"
                                    :label="$t('Regex From Mail')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.reg_ex_to_mail"
                                    :error="errors.reg_ex_to_mail"
                                    :label="$t('Regex To Mail')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="">
                                    <p class="form-label">
                                        <span class="text-red-500">*&nbsp;</span
                                        >{{ $t("Data Eval") }}:
                                    </p>
                                    <MonacoEditor
                                        @contentChange="form.data_eval = $event"
                                        :readOnly="false"
                                        :codeString="code"
                                        :language="'php'"
                                        height="300px"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.active"
                                    :key="form.active"
                                    :error="errors.active"
                                    :label="$t('Status')"
                                    :required="true"
                                >
                                    <option :value="1">{{ $t("Active") }}</option>
                                    <option :value="0">{{ $t("Inactive") }}</option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.url"
                                    :error="errors.url"
                                    :label="$t('URL')"
                                    :required="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/mail-webhooks" class="primary-btn mr-3">
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MonacoEditor from "../../Components/MonacoEditor.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        MonacoEditor,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Mail Webhooks",
                    to: "/mail-webhooks",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            code: "",
            form: {
                name: "",
                reg_ex_content: "",
                reg_ex_subject: "",
                reg_ex_from_mail: "",
                reg_ex_to_mail: "",
                data_eval: "",
                active: 1,
                url: "",
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "mailWebhooks/show",
                this.$route.params.id
            );
            this.form = {
                id: response?.data?.id ?? "",
                name: response?.data?.name ?? "",
                reg_ex_content: response?.data?.reg_ex_content ?? "",
                reg_ex_subject: response?.data?.reg_ex_subject ?? "",
                reg_ex_from_mail: response?.data?.reg_ex_from_mail ?? "",
                reg_ex_to_mail: response?.data?.reg_ex_to_mail ?? "",
                data_eval: response?.data?.data_eval ?? "",
                active: response?.data?.active ?? "",
                url: response?.data?.url ?? "",
            };
            this.code = this.form.data_eval;
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async store() {
            try {
                // check if the code contains return
                if (!/return\s+[^;]+;/.test(this.form.data_eval)) {
                    this.$store.commit("flashMessage", {
                        show: true,
                        message: this.$t("Data Eval does not contain return"),
                        type: "error",
                        link: "",
                    });
                    return;
                }
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("mailWebhooks/update", {
                    ...this.form,
                    active: parseInt(this.form.active),
                });
                await this.$store.dispatch("mailWebhooks/list");
                this.$router.push("/mail-webhooks");
            } catch (e) {}
        },
    },
};
</script>
