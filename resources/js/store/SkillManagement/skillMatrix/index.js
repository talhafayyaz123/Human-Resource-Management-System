export default {
    namespaced: true,
    state: {
        skillsMatrix: {
            data: [],
            links: [],
        },
        matrixData: {},
    },
    getters: {
        skillsMatrix: (state) => state.skillsMatrix,
        matrixData: (state) => state.matrixData,
    },
    mutations: {
        skillsMatrix: (state, payload) => (state.skillsMatrix = payload),
        matrixData: (state, payload) => (state.matrixData = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/skill-matrix", {
                    params: queryParams,
                })
                .then((res) => {
                    console.log(res)
                    commit(
                        "skillsMatrix",
                        res?.data?.skillsMatrix ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/skill-matrix", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/skill-matrix/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/skill-matrix/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/skill-matrix/${id}`);
        },
        matrix({commit}, id){
            return this.$apiService
                .get(`/matrix/${id}`)
                .then((res) => {
                    console.log(res)
                    commit(
                        "matrixData",
                        res?.data?.data
                    );
                });
        }
    },
};
