export default {
    namespaced: true,
    state: {
        fuelMonitoring: {
            data: [],
            links: [],
        },
    },
    getters: {
        fuelMonitoring: (state) => state.fuelMonitoring,
    },
    mutations: {
        fuelMonitoring: (state, payload) => (state.fuelMonitoring = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/fuel-monitoring", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "fuelMonitoring",
                        res.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
    },
};
