export default {
    namespaced: true,
    state: {
        units: {
            data: [],
            links: [],
        },
    },
    getters: {
        units: (state) => state.units,
    },
    mutations: {
        units: (state, payload) => (state.units = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/units", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "units",
                        res?.data?.units ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/units", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/units/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/units/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/units/${id}`);
        },
    },
};
