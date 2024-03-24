export default {
    namespaced: true,
    state: {
        slaLevelIncidents: [],
    },
    getters: {
        slaLevelIncidents: (state) => state.slaLevelIncidents,
    },
    mutations: {
        slaLevelIncidents: (state, payload) => (state.slaLevelIncidents = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/sla-level-incident", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "slaLevelIncidents",
                        res?.data?.data
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/sla-level-incident", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/sla-level-incident/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/sla-level-incident/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/sla-level-incident/${id}`);
        },
    },
};
