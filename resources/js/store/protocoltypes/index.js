export default {
    namespaced: true,
    state: {
        protocolTypes: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        protocolTypes: (state) => state.protocolTypes,
        count: (state) => state.count,
    },
    mutations: {
        protocolTypes: (state, payload) => (state.protocolTypes = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/protocol-types", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "protocolTypes",
                        res?.data?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/protocol-types", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/protocol-types/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/protocol-types/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/protocol-types/${id}`);
        },
    },
};
