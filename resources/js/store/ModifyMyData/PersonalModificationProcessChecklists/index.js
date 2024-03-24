export default {
    namespaced: true,
    state: {
        checklists: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        checklists: (state) => state.checklists,
        count: (state) => state.count,
    },
    mutations: {
        checklists: (state, payload) => (state.checklists = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/personal-modification-process-checklists", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "checklists",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/personal-modification-process-checklists", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/personal-modification-process-checklists/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/personal-modification-process-checklists/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/personal-modification-process-checklists/${id}`);
        },
    },
};
