export default {
    methods: {
        /**
         * sends elo request with the data retrieved using the id
         * @param {id} id of the invoice
         * @param {actionName} elo request module action
         */
        sendEloRequest(id, actionName) {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        "invoices/downloadGeneratedInvoice",
                        id
                    );
                    const invoice = response?.data?.invoice;
                    // if there is invoice details, then generate the document
                    if (invoice) {
                        // send the elo request
                        await this.$store.dispatch(
                            "globalSettings/sendEloApiRequest",
                            {
                                content: {
                                    moduleAction: actionName,
                                    data: {
                                        ...invoice,
                                        id: invoice.id,
                                    },
                                },
                            }
                        );
                    }
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
    },
};
