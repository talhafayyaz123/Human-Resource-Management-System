export default {
    namespaced: true,
    state: {
        versions: {
            data: [],
            links: [],
        },
    },
    getters: {
        versions: (state) => state.versions,
    },
    mutations: {
        versions: (state, payload) => (state.versions = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/versions", {
                    params: queryParams
                })
                .then((res) => {
                    commit(
                        "versions",
                        res?.data?.versions ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/versions", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/versions/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/versions/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/versions/${id}`);
        },
        getVersions({}, id) {
            return this.$apiService.get(`/versions-by-software/${id}`);
        },
    },
};
