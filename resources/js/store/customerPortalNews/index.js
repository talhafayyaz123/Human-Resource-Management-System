export default {
    namespaced: true,
    state: {
        news: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        news: (state) => state.news,
        count: (state) => state.count,
    },
    mutations: {
        news: (state, payload) => (state.news = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/customer-portal-news", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("news", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/customer-portal-news",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(`/customer-portal-news/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/customer-portal-news/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/customer-portal-news/${id}`
            );
        },
    },
};
