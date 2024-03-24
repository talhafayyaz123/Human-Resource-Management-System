export default {
    namespaced: true,
    state: {
        users: [],
        partnerUsers: [],
        count: 0,
        partnerUsersCount: 0,

    },
    getters: {
        users: (state) => state.users,
        partnerUsers: (state) => state.partnerUsers,
        count: (state) => state.count,
        partnerUsersCount: (state) => state.partnerUsersCount,
    },
    mutations: {
        users: (state, payload) => (state.users = payload),
        partnerUsers: (state, payload) => (state.partnerUsers = payload),
        count: (state, payload) => (state.count = payload),
        partnerUsersCount: (state, payload) => (state.partnerUsersCount = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-users", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("users", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? []);
                    return res;
                });
        },
        partnerUserList({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-users", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("partnerUsers", res?.data?.data ?? []);
                    commit("partnerUsersCount", res?.data?.count ?? []);
                    return res;
                });
        },

    },
};
