export default {
    namespaced: true,
    state: {
        storeEntries: {
            data: [],
            links: [],
        },
        reviews:[],
        reports:[],
        count: 0,
    },
    getters: {
        storeEntries: (state) => state.storeEntries,
        getReviews: (state) => state.reviews,
        count: (state) => state.count,
    },
    mutations: {
        storeEntries: (state, payload) => (state.storeEntries = payload),
        storeReviews: (state, payload) => (state.reviews = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/product-store", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "storeEntries",
                        res?.data?.productStores ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.productStores?.length ?? 0);
                });
        },
        getReviews({ commit }, queryParams) {
            return this.$apiService
                .get("/product-store-review", {
                    params: queryParams,
                })
                .then((res) => {
                     commit(
                        "storeReviews",
                        res?.data?.reviews
                    );
                  
                });
        },
        create({}, payload) {
            return this.$apiService.post("/product-store", payload);
        },
        saveHelpfulFeedback({}, id) {
            return this.$apiService.get(`/product-store-review/helpful/${id}`);
        },
        createReview({}, payload) {
            return this.$apiService.post("/product-store-review", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/product-store/${id}`);
        },
        reports({}) {
            return this.$apiService.get(`/review-report`);
        },
        
        update({}, { id, data }) {
            return this.$apiService.put(`/product-store/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/product-store/${id}`);
        },
        saveProductReviewReport({}, payload) {
            return this.$apiService.post("/product-store-review/report", payload);
        },
    },
};
