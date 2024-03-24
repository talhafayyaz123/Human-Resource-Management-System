export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({}, { id, queryParams }) {
            return this.$apiService.get(`/ticket-comments/ticket/${id}`, {
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post("/ticket-comments", payload, {
                params: {
                    fromAdmin: true,
                },
            });
        },
        createPartnerTicketComment({}, payload) {
            return this.$apiService.post("/partner/ticket-comments", payload, {
                params: {
                    fromAdmin: true,
                },
            });
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/ticket-comments/${id}`, data);
        },
        updatePartnerTicketComment({}, { id, data }) {
            return this.$apiService.put(`/partner/ticket-comments/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/ticket-comments/${id}`);
        },
        partnerCommentDestroy({}, id) {
            return this.$apiService.delete(`/partner/ticket-comments/${id}`);
        },
        sendMail({}, { id, data }) {
            return this.$apiService.post(
                `/ticket-comments/send-mail/${id}`,
                data
            );
        },
    },
};
