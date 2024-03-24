export default {
    namespaced: true,
    state: {
        contactReports: {
            data: [],
            links: [],
        },
    },
    getters: {
        contactReports: (state) => state.contactReports,
    },
    mutations: {
        contactReports: (state, payload) => (state.contactReports = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/contact-reports", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("contactReports", res.data);
                    return res;
                });
        },
        getByResubmit({}, queryParams) {
            return this.$apiService.get("/contact-reports/get-by-resubmit", {
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post("/contact-reports", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/contact-reports/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/contact-reports/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/contact-reports/${id}`);
        },
    },
};
