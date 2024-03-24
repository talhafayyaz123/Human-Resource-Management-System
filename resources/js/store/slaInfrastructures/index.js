export default {
    namespaced: true,
    state: {
        slaInfrastructures: [],
    },
    getters: {
        slaInfrastructures: (state) => state.slaInfrastructures,
    },
    mutations: {
        slaInfrastructures: (state, payload) => (state.slaInfrastructures = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/sla-infrastructure", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "slaInfrastructures",
                        res?.data?.data
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/sla-infrastructure", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/sla-infrastructure/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/sla-infrastructure/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/sla-infrastructure/${id}`);
        },
    },
};
