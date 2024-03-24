export default {
    namespaced: true,
    state: {
        serviceLevelAgreements: {
            data: [],
            links: [],
        },
    },
    getters: {
        serviceLevelAgreements: (state) => state.serviceLevelAgreements,
    },
    mutations: {
        serviceLevelAgreements: (state, payload) => (state.serviceLevelAgreements = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/service-level-agreements", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "serviceLevelAgreements",
                        res?.data?.serviceLevelAgreements ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/service-level-agreements", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/service-level-agreements/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/service-level-agreements/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/service-level-agreements/${id}`);
        },
    },
};
