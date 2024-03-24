export default {
    namespaced: true,
    state: {
        boards: [],
        selectedBoard: null,
        assignees: [],
        boardWithTasks: [],
    },
    getters: {
        boards: (state) => state.boards,
        selectedBoard: (state) => state.selectedBoard,
        boardWithTasks: (state) => state.boardWithTasks,
    },
    mutations: {
        boards: (state, payload) => (state.boards = payload),
        selectedBoard: (state, payload) => (state.selectedBoard = payload),
        boardWithTasks: (state, payload) => (state.boardWithTasks = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/task-boards", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("boards", res?.data?.data ?? []);
                });
        },
        listWithTasks({ commit }, queryParams) {
            return this.$apiService
                .get("/get-task-boards", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("boardWithTasks", res?.data?.tasks ?? []);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/task-boards", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/task-boards/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(`/task-boards/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/task-boards/${id}`);
        },
        initialize({}, queryParams) {
            return this.$apiService.get(`/board-initialize`, {
                params: queryParams,
            });
        },
        editBoardUsers({}, { id, data }) {
            return this.$apiService.post(`board-users/${id}/edit`, data);
        },
    },
};
