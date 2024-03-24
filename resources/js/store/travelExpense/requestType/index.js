export default {
    namespaced: true,
    state: {
        requestTypes: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        requestTypes: (state) => state.requestTypes,
        count: (state) => state.count,
    },
    mutations: {
        requestTypes: (state, payload) => (state.requestTypes = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expenses/request-types", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("requestTypes", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/travel-expenses/request-types",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(`/travel-expenses/request-types/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/travel-expenses/request-types/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/travel-expenses/request-types/${id}`
            );
        },
    },
};
