export default {
    namespaced: true,
    state: {
        assetArticles: {
            data: [],
            links: [],
        },
    },
    getters: {
        assetArticles: (state) => state.assetArticles,
    },
    mutations: {
        assetArticles: (state, payload) => (state.assetArticles = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/asset-articles", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "assetArticles",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/asset-articles", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/asset-articles/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/asset-articles/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/asset-articles/${id}`);
        },
    },
};
