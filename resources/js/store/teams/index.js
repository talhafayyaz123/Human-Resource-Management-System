export default {
    namespaced: true,
    state: {
        teams: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        teams: (state) => state.teams,
        count: (state) => state.count,
    },
    mutations: {
        teams: (state, payload) => (state.teams = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/user/teams", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("teams", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/user/teams", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/user/teams/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/user/teams/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/user/teams/${id}`);
        },
        getTeamsByDepartment({}, id) {
            return this.$apiService.get(`/get-teams-by-department/${id}`);
        },
        consultingTeamsList() {
            return this.$apiService.get("/consulting-teams");
        },
        consultingTeamsSave({}, payload) {
            return this.$apiService.post("/consulting-teams", payload);
        },
        pmDashboardTeamsList() {
            return this.$apiService.get("/pm-dashboard-teams");
        },
        pmDashboardTeamsSave({}, payload) {
            return this.$apiService.post("/pm-dashboard-teams", payload);
        },
    },
};
