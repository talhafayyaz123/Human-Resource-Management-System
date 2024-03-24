export default {
    namespaced: true,
    state: {
        distributedFilesystems: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        distributedFilesystems: (state) => state.distributedFilesystems,
        count: (state) => state.count,
    },
    mutations: {
        distributedFilesystems: (state, payload) => (state.distributedFilesystems = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/distributed-filesystem", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("distributedFilesystems", res.data);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/distributed-filesystem", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/distributed-filesystem/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/distributed-filesystem/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/distributed-filesystem/${id}`);
        },
    },
};
