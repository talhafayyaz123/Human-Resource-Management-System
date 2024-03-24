export default {
    namespaced: true,
    state: {
        highestSchoolDegrees: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        highestSchoolDegrees: (state) => state.highestSchoolDegrees,
        count: (state) => state.count,
    },
    mutations: {
        highestSchoolDegrees: (state, payload) => (state.highestSchoolDegrees = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/highest-school-degrees", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "highestSchoolDegrees",
                        res?.data?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/highest-school-degrees", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/highest-school-degrees/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/highest-school-degrees/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/highest-school-degrees/${id}`);
        },
    },
};
