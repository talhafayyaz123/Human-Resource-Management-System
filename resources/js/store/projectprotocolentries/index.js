export default {
    namespaced: true,
    actions: {
        list({ commit }, { id, queryParams }) {
            return this.$apiService
                .get(`/project-protocols/get-entries-by-protocol/${id}`, {
                    params: queryParams,
                })
                .then((res) => {
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/project-protocols/description-entries",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(
                `/project-protocols/description-entries/${id}`
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/project-protocols/description-entries/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/project-protocols/description-entries/${id}`
            );
        },
    },
};
