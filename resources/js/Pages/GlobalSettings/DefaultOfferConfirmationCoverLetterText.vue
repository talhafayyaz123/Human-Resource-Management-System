<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="lg:w-1/2">
            <form @submit.prevent="store">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Cover Letter Text") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <textarea-input
                                    :required="true"
                                    v-model="commentText"
                                    :error="errors.commentText"
                                    :label="$t('Comment')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
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
import { mapGetters } from "vuex";
import TextareaInput from "../../Components/TextareaInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },

    components: {
        TextareaInput,
        LoadingButton,
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
                    text: "Default Offer Confirmation Cover Letter Text",
                    to: "/default-offer-confirmation-cover-letter-text",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            commentText: "",
        };
    },
    mounted() {
        this.refresh();
    },

    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "globalSettings/getDefaultOfferConfirmationCoverLetterText"
                );
                this.commentText = response?.data?.commentText ?? "";
            } catch (e) {
                console.log(e);
            }
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "globalSettings/saveDefaultOfferConfirmationCoverLetterText",
                    {
                        commentText: this.commentText,
                    }
                );
            } catch (e) {}
        },
    },
};
</script>
