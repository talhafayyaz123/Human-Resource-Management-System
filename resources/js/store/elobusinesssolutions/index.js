export default {
    namespaced: true,
    state: {
        hosts: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        hosts: (state) => state.hosts,
        count: (state) => state.count,
    },
    mutations: {
        hosts: (state, payload) => (state.hosts = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/elo_business_solutions", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "hosts",
                        res?.data?.eloBusinessSolutions ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit(
                        "count",
                        res?.data?.eloBusinessSolutions?.total ?? 0
                    );
                    return res?.data?.eloBusinessSolutions;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/elo_business_solutions", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/elo_business_solutions/${id}`);
        },
        businessSolutionList({},$id) {
           
            return this.$apiService.get(`/elo_business_solution/list`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/elo_business_solutions/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/elo_business_solutions/${id}`);
        },
    },
};
