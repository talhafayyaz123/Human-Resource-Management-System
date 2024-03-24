export default {
    namespaced: true,
    state: {
        attachments: {
            data: [],
            links: [],
        },
    },
    getters: {
        attachments: (state) => state.attachments,
    },
    mutations: {
        attachments: (state, payload) => (state.attachments = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/attachments", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "attachments",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/attachments", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/attachments/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/attachments/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/attachments/${id}`);
        },
    },
};
