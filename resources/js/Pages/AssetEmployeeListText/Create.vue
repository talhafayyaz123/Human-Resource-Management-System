<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="lg:w-100">
            <form @submit.prevent="store">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Asset Employee List Text Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-1 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <QuillTextEditor
                                        class=""
                                        :content="form.assetEmployeeText"
                                        :required="true"
                                        :error="errors.assetEmployeeText"
                                        @delta="form.assetEmployeeText = $event"
                                        :label="$t('Asset Employee List Text')"
                                        :height="'500px'"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        to="/asset-employee-list-text"
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
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        PageHeader,
        TextAreaInput,
        QuillTextEditor,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Assest Employee List Text",
                    to: "/asset-employee-list-text",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                assetEmployeeText: "",
            },
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assetEmployeeText/create", {
                    ...this.form,
                });
                await this.$store.dispatch("assetEmployeeText/list");
                this.$router.push("/asset-employee-list-text");
            } catch (e) {}
        },
    },
};
</script>
