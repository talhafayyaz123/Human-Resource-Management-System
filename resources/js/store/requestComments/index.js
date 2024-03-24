export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({}, { id, queryParams }) {
            return this.$apiService.get(`/product-store-request-comments/product-store-request/${id}`,{
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post("/product-store-request-comments", payload, {
                params: {
                    fromAdmin: true,
                },
            });
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/product-store-request-comments/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/product-store-request-comments/${id}`);
        },
        sendMail({}, { id, data }) {
            return this.$apiService.post(
                `/product-store-request-comments/send-mail/${id}`,
                data
            );
        },
    },
};
