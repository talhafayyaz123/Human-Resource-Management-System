export default {
    namespaced: true,
    state: {
        storages: {
            data: [],
            links: [],
        },
    },
    getters: {
        storages: (state) => state.storages,
    },
    mutations: {
        storages: (state, payload) => (state.storages = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/storage-locations", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "storages",
                        res?.data?.storages ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/storage-locations", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/storage-locations/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/storage-locations/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/storage-locations/${id}`);
        },
    },
};
