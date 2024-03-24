export default {
    namespaced: true,
    state: {
        processes: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        processes: (state) => state.processes,
        count: (state) => state.count,
    },
    mutations: {
        processes: (state, payload) => (state.processes = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/modify-my-data", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "processes",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/modify-my-data", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/modify-my-data/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/modify-my-data/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/modify-my-data/${id}`);
        },
    },
};
