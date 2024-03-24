export default {
    namespaced: true,
    state: {
        tasks: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        tasks: (state) => state.tasks,
        count: (state) => state.count,
    },
    mutations: {
        tasks: (state, payload) => (state.tasks = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, params) {
            return this.$apiService
                .get(`/project-management/tasks`, {
                    params: params,
                })
                .then((res) => {
                    commit(
                        "tasks",
                        res?.data?.tasks ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/project-management/tasks", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/project-management/tasks/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/project-management/tasks/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/project-management/tasks/${id}`);
        },
        backlog({}) {
            return this.$apiService.get("/project-management/tasks/backlog");
        },
        byDate({ commit }, params) {
            return this.$apiService
                .get(`/project-management/tasks/by-date`, {
                    params: params,
                })
                .then((res) => {
                    commit(
                        "tasks",
                        res?.data?.tasks ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
    },
};
