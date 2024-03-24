export default {
    namespaced: true,
    state: {
        offerRequests: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        offerRequests: (state) => state.offerRequests,
        count: (state) => state.count,
    },
    mutations: {
        offerRequests: (state, payload) => (state.offerRequests = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/offer-requests", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "offerRequests",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/offer-requests", payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        show({}, id) {
            return this.$apiService.get(`/offer-requests/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.post(`/offer-requests/${id}`, data, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        destroy({}, id) {
            return this.$apiService.delete(`/offer-requests/${id}`);
        },
        createOffer({}, id) {
            return this.$apiService.get(`/offer-requests/create-offer/${id}`);
        },
    },
};
