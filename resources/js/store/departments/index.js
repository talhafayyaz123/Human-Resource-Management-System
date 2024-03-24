export default {
    namespaced: true,
    state: {
        departments: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        departments: (state) => state.departments,
        count: (state) => state.count,
    },
    mutations: {
        departments: (state, payload) => (state.departments = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/user/departments", { params: queryParams })
                .then((res) => {
                    commit("departments", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/user/departments", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/user/departments/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/user/departments/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/user/departments/${id}`);
        },
    },
};
