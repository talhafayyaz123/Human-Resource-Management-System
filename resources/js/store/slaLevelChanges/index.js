export default {
    namespaced: true,
    state: {
        slaLevelChanges: [],
    },
    getters: {
        slaLevelChanges: (state) => state.slaLevelChanges,
    },
    mutations: {
        slaLevelChanges: (state, payload) => (state.slaLevelChanges = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/sla-level-change", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "slaLevelChanges",
                        res?.data?.data
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/sla-level-change", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/sla-level-change/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/sla-level-change/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/sla-level-change/${id}`);
        },
    },
};
