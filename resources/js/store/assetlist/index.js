export default {
    namespaced: true,
    state: {
        assetLists: {
            data: [],
            links: [],
        },
    },
    getters: {
        assetLists: (state) => state.assetLists,
    },
    mutations: {
        assetLists: (state, payload) => (state.assetLists = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/asset-lists", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "assetLists",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/asset-lists", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/asset-lists/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/asset-lists/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/asset-lists/${id}`);
        },
        getEmployee({}, id) {
            return this.$apiService.get(`/get-asset-employee/${id}`);
        },
        getEmployeeAsset({}, queryParams) {
            return this.$apiService.get(`/employee-assets`, {
                params: queryParams,
            });
        },
    },
};
