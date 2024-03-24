export default {
    namespaced: true,
    state: {
        slaServiceTimes: [],
    },
    getters: {
        slaServiceTimes: (state) => state.slaServiceTimes,
    },
    mutations: {
        slaServiceTimes: (state, payload) => (state.slaServiceTimes = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/sla-service-time", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "slaServiceTimes",
                        res?.data?.data
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/sla-service-time", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/sla-service-time/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/sla-service-time/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/sla-service-time/${id}`);
        },
    },
};
