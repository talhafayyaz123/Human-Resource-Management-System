<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="lg:w-1/2">
            <form @submit.prevent="update">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Asset Employee List Text Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
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
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        :to="`/asset-employee-list-text?page=${returnPage}`"
                        class="primary-btn mr-3"
                    >
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button
                        v-if="$can(`${$route.meta.permission}.edit`)"
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
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        PageHeader,
        TextAreaInput,
        QuillTextEditor,
    },
    data() {
        return {
            returnPage:'',
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Assest Employee List Text",
                    to: "/asset-employee-list-text?page="+this.$route.query.page,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                assetEmployeeText: "",
            },
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            if(this.$route.query.page){
                this.returnPage=this.$route.query.page; 
            }
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "assetEmployeeText/show",
                    this.$route.params.id
                );
                let employeeText = response?.data?.employeeText ?? {};
                this.form = {
                    assetEmployeeText: employeeText.assetEmployeeText,
                };
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("assetEmployeeText/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("assetEmployeeText/list");
            this.$router.push("/asset-employee-list-text?page="+this.returnPage);
        },
    },
};
</script>
