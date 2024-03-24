export default {
    namespaced: true,
    state: {
        handouts: {
            data: [],
            links: [],
        },
    },
    getters: {
        handouts: (state) => state.handouts,
    },
    mutations: {
        handouts: (state, payload) => (state.handouts = payload),
    },
    actions: {
        list({ commit }, { page, search } = {}) {
            return this.$apiService
                .get("/handouts", {
                    params: {
                        page,
                        search,
                    },
                })
                .then((res) => {
                    commit(
                        "handouts",
                        res?.data?.handouts ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/handouts", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/handouts/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/handouts/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/handouts/${id}`);
        },
    },
};
