import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("protocolTypes", ["protocolTypes"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("projectStatuses", ["projectStatuses"]),
        ...mapGetters("auth", ["user", "users"]),
    },
    async mounted() {
        try {
            if (!this.companies?.data?.length) {
                await this.$store.dispatch("companies/list");
            }
            if (!this.protocolTypes?.data?.length) {
                await this.$store.dispatch("protocolTypes/list");
            }
            if (!this.projectStatuses?.data?.length) {
                await this.$store.dispatch("projectStatuses/list");
            }
            if (!this.users?.length) {
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 10,
                    type: "employee",
                });
            }
        } catch (e) {
            console.log(e);
        }
    },
};
