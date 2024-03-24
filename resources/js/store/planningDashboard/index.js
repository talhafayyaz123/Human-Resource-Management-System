export default {
    namespaced: true,
    state: {
        data: {
            data: [],
            links: [],
        },
    },
    getters: {
        data: (state) => state.data,
    },
    mutations: {
        projects: (state, payload) => (state.data = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/planing-board", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("projects", res.data);
                    return res;
                });
        },
        // create({}, payload) {
        //     return this.$apiService.post("/product-category", payload);
        // },
        // show({}, id) {
        //     return this.$apiService.get(`/product-category/${id}`);
        // },
        // getByProductType({}, queryParams) {
        //     return this.$apiService.get(
        //         `/product-category/get-by-product-type`,
        //         {
        //             params: queryParams,
        //         }
        //     );
        // },
        update({}, payload) {
            return this.$apiService.post("/planing-board", payload);
        },
        // destroy({}, id) {
        //     return this.$apiService.delete(`/product-category/${id}`);
        // },
    },
};
