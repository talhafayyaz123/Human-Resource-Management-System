<template>
    <div
        class="detail-card-header"
        :style="
            'color: ' +
            stylesConfiguration.productDetails?.cardHeaderTextColor +
            '; background-color: ' +
            stylesConfiguration.productDetails?.cardHeaderBgColor
        "
    >
        <h3
            class=""
            :style="
                'color: ' +
                stylesConfiguration.productDetails?.cardHeaderTextColor
            "
        >
            {{ $t("Product Details") }}
        </h3>
    </div>
    <div class="detail-card-body">
        <div
            v-if="$route.name === 'SurveysShow'"
            class="p-2"
            v-html="htmlFromText(selectedQuestion?.productDetails)"
        ></div>
        <QuillTextEditor
            v-if="
                typeof selectedQuestion?.productDetails === 'string' &&
                $route.name !== 'SurveysShow'
            "
            class="w-full"
            :selectedQuestion="selectedQuestion"
            :content="selectedQuestion.productDetails"
            @delta="updateSelectedQuestion($event)"
        />
    </div>
</template>

<script>
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import Quill from "quill";

export default {
    components: {
        QuillTextEditor,
    },
    props: ["selectedQuestionParent", "stylesConfigurationParent"],
    emits: ["selectedQuestionParent"],
    watch: {
        selectedQuestionParent(val) {
            this.selectedQuestion = val;
        },
        stylesConfigurationParent(val) {
            this.stylesConfiguration = val;
        },
    },
    methods: {
        htmlFromText(text) {
            try {
                let article = document.createElement("article");
                let quill = new Quill(article, {
                    readOny: true,
                });
                quill.setContents(JSON.parse(text));
                return quill.root.innerHTML;
            } catch (err) {
                return "";
            }
        },
        updateSelectedQuestion(event) {
            this.selectedQuestion.productDetails = event;
            this.$emit("selectedQuestionParent", this.selectedQuestion);
        },
    },
    mounted() {
        this.selectedQuestion = this.selectedQuestionParent;
        this.stylesConfiguration = this.stylesConfigurationParent;
    },
    data() {
        return {
            selectedQuestion: null,
            stylesConfiguration: {},
        };
    },
};
</script>

<style scoped>
:deep(.ql-container) {
    word-break: break-all;
}
</style>
