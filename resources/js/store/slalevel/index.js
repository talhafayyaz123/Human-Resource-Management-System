export default {
    namespaced: true,
    state: {
        slaLevels: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        slaLevels: (state) => state.slaLevels,
        count: (state) => state.count,
    },
    mutations: {
        slaLevels: (state, payload) => (state.slaLevels = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/sla-levels", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "slaLevels",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/sla-levels", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/sla-levels/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/sla-levels/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/sla-levels/${id}`);
        },
    },
};
