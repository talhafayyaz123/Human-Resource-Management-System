import axios from "axios";

export default {
    namespaced: true,
    state: {
        expiryMonth: "",
        defaultCoverLetterText: "",
        partnerDiscount: "",
        offerConfirmationCommentDefaultText: "",
    },
    getters: {
        expiryMonth: (state) => state.expiryMonth,
        defaultCoverLetterText: (state) => state.defaultCoverLetterText,
        partnerDiscount: (state) => state.partnerDiscount,
        defaultOfferConfirmationCoverLetterText: (state) =>
            state.offerConfirmationCommentDefaultText,
    },
    mutations: {
        expiryMonth: (state, payload) => (state.expiryMonth = payload),
        defaultCoverLetterText: (state, payload) =>
            (state.defaultCoverLetterText = payload),
        partnerDiscount: (state, payload) =>
            (state.partnerDiscount = payload),
        defaultOfferConfirmationCoverLetterText: (state, payload) =>
            (state.offerConfirmationCommentDefaultText = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("expiryMonth", res.data?.expiryMonth ?? 0);
                    return res;
                });
        },
        save({}, payload) {
            return this.$apiService.post("/global-settings", payload);
        },
        documentAssignmentList({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings/document-assignment", {
                    params: queryParams,
                })
                .then((res) => {
                    return res;
                });
        },
        saveDocumentAssignment({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/document-assignment",
                payload
            );
        },
        mailTemplateAssignmentList({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings/mail-template-assignment", {
                    params: queryParams,
                })
                .then((res) => {
                    return res;
                });
        },
        saveMailTemplateAssignment({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/mail-template-assignment",
                payload
            );
        },
        cipConfigurationList({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings/cip-configuration", {
                    params: queryParams,
                })
                .then((res) => {
                    return res;
                });
        },
        saveCipConfiguration({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/cip-configuration",
                payload
            );
        },
        getTemplateByName({}, payload) {
            return this.$apiService
                .get("/global-settings/get-template-by-name/" + payload)
                .then((res) => {
                    return res?.data?.name ?? "";
                });
        },
        eloConfigurationList({ commit }) {
            return this.$apiService
                .get("/global-settings/elo-configuration")
                .then((response) => {
                    return response;
                });
        },
        saveEloConfiguration({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/elo-configuration",
                payload
            );
        },
        sendEloApiRequest({}, payload) {
            return this.$apiService.post(
                "/global-settings/elo-api-request",
                payload
            );
        },
        getDefaultCoverLetterText({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings/default-cover-letter-text")
                .then((res) => {
                    commit(
                        "defaultCoverLetterText",
                        res.data?.commentText ?? ""
                    );
                    return res;
                });
        },
        saveDefaultCoverLetterText({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/default-cover-letter-text-request",
                payload
            );
        },
        getDefaultOfferConfirmationCoverLetterText({ commit }, queryParams) {
            return this.$apiService
                .get(
                    "/global-settings/default-offer-confirmation-cover-letter-text"
                )
                .then((res) => {
                    commit(
                        "defaultOfferConfirmationCoverLetterText",
                        res.data?.commentText ?? ""
                    );
                    return res;
                });
        },
        saveDefaultOfferConfirmationCoverLetterText({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/default-offer-confirmation-cover-letter-text-request",
                payload
            );
        },
        getPartnerDiscount({ commit }, queryParams) {
            return this.$apiService
                .get("/global-settings/partner-management/discount")
                .then((res) => {
                    commit(
                        "partnerDiscount",
                        res.data?.commentText ?? ""
                    );
                    return res;
                });
        },
        savePartnerDiscount({ commit }, payload) {
            return this.$apiService.post(
                "/global-settings/partner-management/discount",
                payload
            );
        },
        moduleHistory({}, { id, queryParams }) {
            return this.$apiService.get(`/module-history/${id}`, {
                params: queryParams,
            });
        },
    },
};
