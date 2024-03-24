import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["termsAgreed"],
    data() {
        return {
            termsAgreed: false,
        };
    },
    watch: {
        termsAgreed(val) {
            this.$emit("termsAgreed", val);
        },
    },
    props: {
        changeRequestData: {
            type: Object,
            default: () => ({}),
        },
        responseReason: {
            type: String,
            default: "",
        },
        fromChangeRequest: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters("userProfile", ["authUserProfile"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters(["errors"]),
    },
    components: {
        TextInput,
    },
    mounted() {
        this.form = this.changeRequestData ?? this.form;
        this.reason = this.responseReason ?? "";
        this.termsAgreed = Object.keys(this.changeRequestData).length;
    },
};
