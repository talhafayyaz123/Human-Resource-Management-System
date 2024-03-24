export default {
    namespaced: true,
    state: {
        products: {
            data: [],
            links: [],
        },
        services: {
            data: [],
            links: [],
        },
        count: 0,
        filter: {
            product_category_id: "",
            status: "",
            search: "",
            product_software_id: "",
            type: "",
            sortBy: "",
            sortOrder: "asc",
        },
    },
    getters: {
        products: (state) => state.products,
        services: (state) => state.services,
        count: (state) => state.count,
        filter: (state) => state.filter,
    },
    mutations: {
        products: (state, payload) => (state.products = payload),
        services: (state, payload) => (state.services = payload),
        count: (state, payload) => (state.count = payload),
        filter: (state, payload) => (state.filter = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/products", { params: queryParams })
                .then((res) => {
                    commit(
                        "products",
                        res?.data?.products ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.products?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/products", payload);
        },
        fetchData({}) {
            return this.$apiService.get("/products/create");
        },
        show({}, id) {
            return this.$apiService.get(`/products/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/products/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/products/${id}`);
        },
        uploadFiles({}, payload) {
            return this.$apiService.post(`/products/files/upload`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        uploadFilesUpdate({}, payload) {
            return this.$apiService.post(`/products/files/update`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        productsWithQuantity({}, data) {
            return this.$apiService.get(
                `${data?.public ? "/public" : ""}/products-with-quantity`,
                {
                    params: { ...data },
                }
            );
        },
        importProducts({}, payload) {
            return this.$apiService.post("/import-products", payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        storeImportedProducts({}, payload) {
            return this.$apiService.post("/store-imported-products", payload);
        },
    },
};
