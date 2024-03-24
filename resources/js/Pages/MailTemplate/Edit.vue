<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Mail Template Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.subject"
                                    :error="errors.subject"
                                    :required="true"
                                    :label="$t('Subject')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.active"
                                    :error="errors.active"
                                    :required="true"
                                    :label="$t('Status')"
                                >
                                    <option :value="0">{{ $t("Inactive") }}</option>
                                    <option :value="1">{{ $t("Active") }}</option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                    <div class="w-full my-3">
                        <div class="form-group">
                            <QuillTextEditor
                                v-if="show"
                                :content-type="'html'"
                                :content="form.body"
                                :required="true"
                                :error="errors.body"
                                @delta="updateDescription($event)"
                                :label="$t('Body')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/mail-templates" class="primary-btn mr-3">
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
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
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
        TextAreaInput,
        QuillTextEditor,
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
                    text: "Mail Templates",
                    to: "/mail-templates",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            show: false,
            parsedHtml: "",
            form: {
                subject: "",
                active: 1,
                body: "",
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "mailTemplates/show",
                this.$route.params.id
            );
            this.form = response?.data ?? { subject: "", active: 1, body: "" };
        } catch (e) {
            console.log(e);
        } finally {
            this.show = true;
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        updateDescription(event){
            this.form.body=event;
            this.parsedHtml=event;
        },

        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("mailTemplates/update", {
                    ...this.form,
                    body: this.parsedHtml, // set the body to parsed html
                });
                await this.$store.dispatch("mailTemplates/list");
                this.$router.push("/mail-templates");
            } catch (e) {}
        },
    },
};
</script>
