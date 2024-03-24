export default {
    namespaced: true,
    state: {
        requirements: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        requirements: (state) => state.requirements,
    },
    mutations: {
        requirements: (state, payload) => (state.requirements = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/personal-requirements", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "requirements",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        setStatus({}, payload) {
            return this.$apiService.post("/personal-requirements", payload);
        },
    },
};
