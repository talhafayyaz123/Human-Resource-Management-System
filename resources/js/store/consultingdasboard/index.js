export default {
    namespaced: true,
    state: {
        consultingDashboardStatistics: {
            team: null,
            employees: [],
        },
    },
    getters: {
        consultingDashboardStatistics: (state) =>
            state.consultingDashboardStatistics,
    },
    mutations: {
        consultingDashboardStatistics: (state, payload) =>
            (state.consultingDashboardStatistics = payload),
    },
    actions: {
        consultingDashboardStatistics({ commit }, payload) {
            return this.$apiService
                .post("/consulting-dashboard", payload)
                .then((res) => {
                    commit(
                        "consultingDashboardStatistics",
                        typeof res?.data?.data === "object"
                            ? res?.data?.data
                            : {
                                  team: null,
                                  employees: [],
                              }
                    );
                    return res;
                });
        },
    },
};
