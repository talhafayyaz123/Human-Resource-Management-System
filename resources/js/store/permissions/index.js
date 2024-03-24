export default {
    namespaced: true,
    state: {
        permissions: [],
        permissionsGlobal: [],
        count: 0,
    },
    getters: {
        permissions: (state) => state.permissions,
        permissionsGlobal: (state) => state.permissionsGlobal,
        count: (state) => state.count,
    },
    mutations: {
        permissions: (state, payload) => (state.permissions = payload),
        permissionsGlobal: (state, payload) =>
            (state.permissionsGlobal = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        permissionsGlobal({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-permissions", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("permissionsGlobal", [...res?.data?.data] ?? []);
                });
        },
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/permissions", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("permissions", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$authApiService.post("/create-permission", payload);
        },
        show({}, id) {
            return this.$authApiService.get(`/list-permission-by-id?id=${id}`);
        },
        update({}, data) {
            return this.$authApiService.post(`/update-permission`, data);
        },
        destroy({}, payload) {
            return this.$authApiService.post(`/delete-permission`, payload);
        },
    },
};
