export default {
    namespaced: true,
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService.get("/personal-request-approvers", {
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/personal-request-approvers",
                payload
            );
        },
    },
};
