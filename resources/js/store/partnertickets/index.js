export default {
    namespaced: true,
    state: {
        tickets: {
            data: [],
            links: [],
        },
        count: 0,
        openTickets: 0,
    },
    getters: {
        tickets: (state) => state.tickets,
        count: (state) => state.count,
        openTickets: (state) => state.openTickets,
    },
    mutations: {
        tickets: (state, payload) => (state.tickets = payload),
        count: (state, payload) => (state.count = payload),
        openTickets: (state, payload) => (state.openTickets = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            
            return this.$apiService
                .get("/partner/tickets", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("tickets", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/partner/tickets", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/partner/tickets/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/partner/tickets/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/partner/tickets/${id}`);
        },
        openTicketsCount({ commit }) {
            return this.$apiService.get(`/tickets/open-count`).then((res) => {
                commit("openTickets", res?.data?.count);
            });
        },
        articlesByDate({ commit }, params) {
            return this.$apiService
                .get(`/tickets/articles/by-date`, {
                    params: params,
                })
                .then((res) => {
                    commit(
                        "tickets",
                        res?.data?.tickets ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        getTicketByComment({}, id) {
            return this.$apiService.get(`/ticket/ticket-comments/${id}`);
        },
        getTicketComments({ commit }, params) {
            return this.$apiService
                .get(`/get-ticket-comments`, {
                    params: params,
                })
                .then((res) => {
                    return res;
                });
        },
        downloadTicketAttachment({}, { id, queryParams }) {
            this.$apiService
                .get(`/ticket-comment/download/${id}`, { responseType: "blob" })
                .then((response) => {
                    const contentType = response.headers["content-type"];
                    const url = window.URL.createObjectURL(
                        new Blob([response.data], { type: contentType })
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", queryParams.name);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                });
        },
        downloadTicketAttachmentBase64({}, { id, queryParams }) {
            return this.$apiService.get(`/ticket-comment/download/${id}`, {
                responseType: "blob",
            });
        },
    },
};
