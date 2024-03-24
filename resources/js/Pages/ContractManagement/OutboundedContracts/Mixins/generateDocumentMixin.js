export default {
    methods: {
        /**
         * generates pdf generation for the attachment
         * @param {attachment} attachment data
         * @param {contractData} contract data
         * @param {documentType} pdf/docx
         */
        async generateProcessTemplate(attachment, contractData, documentType) {
            try {
                const payload = {
                    ...attachment,
                    id: attachment.template,
                    output: documentType,
                    updatedTime: Date.now(),
                    ...contractData,
                };
                this.$store.commit("isLoading", true);
                const filename = attachment.attachmentNumber
                    ? "attachment-" + attachment.attachmentNumber
                    : "attachment-" +
                      attachment.prefix +
                      new Date().getFullYear() +
                      "-XXXXXX";
                await this.$store.dispatch("documentService/processTemplate", {
                    data: payload,
                    filename: filename + `.${documentType}`,
                    documentType: documentType,
                });
            } catch (e) {
                console.log(e);
            }
        },
    },
};
