export default {
    namespaced: true,
    state: {
        softwares: {
            data: [],
            links: [],
        },
    },
    getters: {
        softwares: (state) => state.softwares,
    },
    mutations: {
        softwares: (state, payload) => (state.softwares = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/softwares", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "softwares",
                        res?.data?.softwares ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/softwares", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/softwares/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/softwares/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/softwares/${id}`);
        },
    },
};
