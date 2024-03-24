export default {
    namespaced: true,
    state: {
        mileageMonitoring: {
            data: [],
            links: [],
        },
    },
    getters: {
        mileageMonitoring: (state) => state.mileageMonitoring,
    },
    mutations: {
        mileageMonitoring: (state, payload) =>
            (state.mileageMonitoring = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/mileage-monitoring", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "mileageMonitoring",
                        res.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
    },
};
