export default {
    namespaced: true,
    state: {
        myTasks: [],
        tasks: [],
        milestones: [],
        employees: [],
    },
    getters: {
        myTasks: (state) => state.myTasks,
        tasks: (state) => state.tasks,
        milestones: (state) => state.milestones,
        employees: (state) => state.employees,
    },
    mutations: {
        myTasks: (state, payload) => (state.myTasks = payload),
        tasks: (state, payload) => (state.tasks = payload),
        milestones: (state, payload) => (state.milestones = payload),
        employees: (state, payload) => (state.employees = payload),
    },
    actions: {
        myTasks({ commit }, queryParams) {
            return this.$apiService
                .get("/project-management/my-tasks", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("myTasks", res?.data?.tasks ?? []);
                });
        },
        projectData({ commit }, id) {
            return this.$apiService
                .get(`/project-management/${id}`)
                .then((response) => {
                    commit("tasks", response?.data?.tasks ?? []);
                    commit("milestones", response?.data?.milestones ?? []);
                    commit("employees", response?.data?.employees ?? []);
                });
        },
    },
};
