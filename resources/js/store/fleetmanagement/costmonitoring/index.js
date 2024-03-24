export default {
    namespaced: true,
    state: {
        costMonitoring: {
            data: [],
            links: [],
        },
    },
    getters: {
        costMonitoring: (state) => state.costMonitoring,
    },
    mutations: {
        costMonitoring: (state, payload) => (state.costMonitoring = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/cost-monitoring", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "costMonitoring",
                        res.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
    },
};
