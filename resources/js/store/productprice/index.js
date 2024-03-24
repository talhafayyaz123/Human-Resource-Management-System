export default {
    namespaced: true,
    state: {
        productPrices: {
            data: [],
            links: [],
        },
        priceListLabel:[],
    },
    getters: {
        productPrices: (state) => state.productprices,
        priceListLabel: (state) => state.priceListLabel,
    },
    mutations: {
        productPrices: (state, payload) => (state.productprices = payload),
        priceListLabel: (state, payload) => (state.priceListLabel = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/product-price", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "productPrices",
                        res?.data?.prices ?? {
                            data: [],
                            links: [],
                        }
                    );
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/product-price", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/product-price/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/product-price/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/product-price/${id}`);
        },
        priceListName({ commit }) {
            return this.$apiService
                .get("/partner-management/price-list")
                .then((res) => {
                    console.log(res)
                    commit("priceListLabel", res?.data?.price ?? []);
                    return res;
                });
        },
        getAllProductsRelatedToSoftware({}, id) {
            return this.$apiService.get(`/get-software-price/${id}`);
        },
    },
};
