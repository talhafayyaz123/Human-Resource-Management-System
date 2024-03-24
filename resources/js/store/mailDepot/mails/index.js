export default {
    namespaced: true,
    state: {
        mails: [],
        count: 0,
    },
    getters: {
        mails: (state) => state.mails,
        count: (state) => state.count,
    },
    mutations: {
        mails: (state, payload) => (state.mails = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$mailDepotApiService
                .get("/list-mails", { params: queryParams })
                .then((res) => {
                    commit("mails", res?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$mailDepotApiService.post(`/create-mail`, payload);
        },
        show({}, id) {
            return this.$mailDepotApiService.get(`/list-mail-by-id?id=${id}`);
        },
        update({}, payload) {
            return this.$mailDepotApiService.post(`/update-mail`, payload);
        },
        destroy({}, payload) {
            return this.$mailDepotApiService.post(`/delete-mail`, payload);
        },
    },
};
