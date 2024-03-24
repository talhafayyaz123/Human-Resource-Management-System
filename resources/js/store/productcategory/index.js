export default {
    namespaced: true,
    state: {
        data: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        data: (state) => state.data,
        count: (state) => state.count,
    },
    mutations: {
        productCategory: (state, payload) => (state.data = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/product-category", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("productCategory", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/product-category", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/product-category/${id}`);
        },
        getByProductType({}, queryParams) {
            return this.$apiService.get(
                `/product-category/get-by-product-type`,
                {
                    params: queryParams,
                }
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/product-category/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/product-category/${id}`);
        },
    },
};
