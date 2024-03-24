export default {
    namespaced: true,
    state: {
        assetEmployeeText: {
            data: [],
            links: [],
        },
    },
    getters: {
        assetEmployeeText: (state) => state.assetEmployeeText,
    },
    mutations: {
        assetEmployeeText: (state, payload) => (state.assetEmployeeText = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/asset-employee-text", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "assetEmployeeText",
                        res?.data?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/asset-employee-text", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/asset-employee-text/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/asset-employee-text/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/asset-employee-text/${id}`);
        },
    },
};
