export default {
    namespaced: true,
    state: {
        groupings: [],
        count: 0,
    },
    getters: {
        groupings: (state) => state.groupings,
        count: (state) => state.count,
    },
    mutations: {
        groupings: (state, payload) => (state.groupings = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-groupings", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("groupings", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
    },
};
