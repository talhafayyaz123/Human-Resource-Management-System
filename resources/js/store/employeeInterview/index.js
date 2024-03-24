export default {
    namespaced: true,
    state: {
        employeeInterview: {
            data: [],
            links: [],
        },
        saveBotton: false,
    },
    getters: {
        employeeInterview: (state) => state.employeeInterview,
        saveBotton: (state) => state.saveBotton,
    },
    mutations: {
        employeeInterview: (state, payload) =>
            (state.employeeInterview = payload),
        saveBotton: (state, payload) => (state.saveBotton = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/employee-interview", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "employeeInterview",
                        res?.data?.interviews ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/employee-interview", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/employee-interview/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/employee-interview/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/employee-interview/${id}`);
        },
        toggleSaveBotton({ commit }, value) {
            commit("saveBotton", value);
        },
    },
};
