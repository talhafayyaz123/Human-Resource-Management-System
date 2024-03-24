export default {
    namespaced: true,
    state: {
        daySpecifications: [],
    },
    getters: {
        daySpecifications: (state) => state.daySpecifications,
    },
    mutations: {
        daySpecifications: (state, payload) =>
            (state.daySpecifications = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expense/day-report", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "daySpecifications",
                        res?.data ?? []
                    );
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/travel-expense/day-report", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/travel-expense/day-report/${id}`);
        },
        update({}, data) {
            return this.$apiService.put(`/travel-expense/day-report`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/travel-expense/day-report/${id}`);
        },
    },
};
