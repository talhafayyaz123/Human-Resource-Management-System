export default {
    namespaced: true,
    state: {
        privateTransportations: [],
    },
    getters: {
        privateTransportations: (state) => state.privateTransportations,
    },
    mutations: {
        privateTransportations: (state, payload) =>
            (state.privateTransportations = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expense/transportation-report", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("privateTransportations", res?.data ?? []);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/travel-expense/transportation-report",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(
                `/travel-expense/transportation-report/${id}`
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/travel-expense/transportation-report/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/travel-expense/transportation-report/${id}`
            );
        },
    },
};
