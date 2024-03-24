export default {
    namespaced: true,

    state: {
        ownStreamData: [],
        count: 0,
    },
    getters: {
        ownStreamData: (state) => state.ownStreamData,
    },
    mutations: {
        ownStreamData: (state, payload) => (state.ownStreamData = payload),
    },

    actions: {
        ownStreamData({ commit }, paramerter) {
            let page = paramerter?.page ?? 1;
            let search = paramerter?.search ?? "";

            if (!page && !search) {
                return Promise.resolve(); // No need to make a request if both page and search are falsy
            }

            let url = "/my-feed/own-stream?perPage=5";

            if (page) {
                url += "&page=" + page;
            }

            if (search) {
                url += "&search=" + search;
            }

            return this.$apiService
                .get(url)
                .then((res) => {
                    commit("ownStreamData", res.data);
                    return res;
                })
                .catch((error) => {
                    console.error("Error fetching own stream data:", error);
                    throw error;
                });
        },
        getFeedComments({}, { page, id }) {
            return this.$apiService.get(
                `/my-feed/get-feed-comments/${id}?page=${page}`
            );
        },
        create({}, payload) {
            return this.$apiService.post("/my-feed", payload);
        },
        createComment({}, payload) {
            return this.$apiService.post("my-feed/comment", payload);
        },
        updateComment({}, { id, data }) {
            return this.$apiService.put(`my-feed/${id}`, data);
        },

        updatePostComment({}, { id, data }) {
            return this.$apiService.put(`my-feed/comment/${id}`, data);
        },
        deleteComment({}, { id }) {
            return this.$apiService.delete(`my-feed/comment/${id}`);
        },
        subscribe({}, payload) {
            return this.$apiService.post("my-feed/subscribe", payload);
        },
        unSubscribe({}, payload) {
            return this.$apiService.post("my-feed/unsubscribe", payload);
        },
        // getOwnStream({}) {
        //     console.log('asdasdasdsa')
        //     return this.$apiService.get(`/my-feed/own-stream`);
        // },
        // tagStream({}, page = 1) {
        //     return this.$apiService.get(
        //         `/my-feed/tag-stream?perPage=5&page=` + page
        //     );
        // },
        tagStream({ commit }, paramerter) {
            let page = paramerter?.page ?? 1;
            let search = paramerter?.search ?? "";

            if (!page && !search) {
                return Promise.resolve(); // No need to make a request if both page and search are falsy
            }

            let url = "/my-feed/tag-stream?perPage=5";

            if (page) {
                url += "&page=" + page;
            }

            if (search) {
                url += "&search=" + search;
            }

            return this.$apiService.get(url);
        },
        ownStreamDestroy({}, id) {
            return this.$apiService.delete(`my-feed/${id}`);
        },

        // mentionedStream({}, page = 1) {
        //     return this.$apiService.get(
        //         `/my-feed/mention-stream?perPage=5&page=` + page
        //     );
        // },
        mentionedStream({ commit }, paramerter) {
            let page = paramerter?.page ?? 1;
            let search = paramerter?.search ?? "";

            if (!page && !search) {
                return Promise.resolve(); // No need to make a request if both page and search are falsy
            }

            let url = "/my-feed/mention-stream?perPage=5";

            if (page) {
                url += "&page=" + page;
            }

            if (search) {
                url += "&search=" + search;
            }

            return this.$apiService.get(url);
        },

        // subscribedStream({}, page = 1) {
        //     return this.$apiService.get(
        //         `/my-feed/subscribed-stream?perPage=5&page=` + page
        //     );
        // },
        subscribedStream({ commit }, paramerter) {
            let page = paramerter?.page ?? 1;
            let search = paramerter?.search ?? "";

            if (!page && !search) {
                return Promise.resolve(); // No need to make a request if both page and search are falsy
            }

            let url = "/my-feed/subscribed-stream?perPage=5";

            if (page) {
                url += "&page=" + page;
            }

            if (search) {
                url += "&search=" + search;
            }

            return this.$apiService.get(url);
        },
        addVote({}, data) {
            this.$apiService.post("/my-feed/add-vote", data);
        },
        show({}, id) {
            return this.$apiService.get(`/product-store-request/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/product-store-request/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/product-store-request/${id}`);
        },
    },
};
