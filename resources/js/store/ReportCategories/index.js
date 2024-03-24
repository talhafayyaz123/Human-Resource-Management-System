export default {
    namespaced: true,
    state: {
        reportCategories: {
            data: [],
            links: [],
        },
    },
    getters: {
        reportCategories: (state) => state.reportCategories,
    },
    mutations: {
        reportCategories: (state, payload) =>
            (state.reportCategories = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/report-category", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("reportCategories", res.data);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/report-category", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/report-category/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/report-category/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/report-category/${id}`);
        },
    },
};
