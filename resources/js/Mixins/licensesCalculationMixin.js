export default {
    methods: {
        /**
         *  recomputes the salePriceTotal, maintenancePrice, maintenancePriceTotal of the product
         * @param {index} the index of which the salePrice was changed
         * @param {modelName} the name of the variable of which the properties need to be modified, can be nullable
         * @param {context} the vue component instance('this') of the component that this method is being called from, can be nullable
         */
        async maintenancePriceTotalChanged(index, modelName, context) {
            await this.$nextTick();
            const product =
                context?.[modelName] ?? this.dataProducts?.data?.[index];
            if (product) {
                if (!product.maintenanceRate) {
                    product.maintenancePriceTotal = 0;
                    return;
                }
                product.maintenancePrice =
                    +product.maintenancePriceTotal / +product.quantity;
                // context?.[modelName] is only set when we are edit mode for licenses, in create this calculation should take place
                if (!context?.[modelName]) {
                    product.salePrice =
                        (100 * (+product.maintenancePrice ?? 0)) /
                        (+product.maintenanceRate ?? 0);
                    product.salePriceTotal =
                        +product.quantity * product.salePrice;
                }
                if (context?.[modelName]) {
                    context[modelName] = { ...product };
                } else this.dataProducts.data[index] = { ...product };
            }
        },
        /**
         *  recomputes the salePriceTotal, maintenancePrice, maintenancePriceTotal of the product
         * @param {index} the index of which the salePrice was changed
         * @param {modelName} the name of the variable of which the properties need to be modified, can be nullable
         * @param {context} the vue component instance('this') of the component that this method is being called from, can be nullable
         */
        async maintenancePriceChanged(index, modelName, context) {
            await this.$nextTick();
            const product =
                context?.[modelName] ?? this.dataProducts?.data?.[index];
            if (product) {
                if (!product.maintenanceRate) {
                    product.maintenancePrice = 0;
                    return;
                }
                product.maintenancePriceTotal =
                    +product.quantity * product.maintenancePrice;
                // context?.[modelName] is only set when we are edit mode for licenses, in create this calculation should take place
                if (!context?.[modelName]) {
                    product.salePrice =
                        (100 * (+product.maintenancePrice ?? 0)) /
                        (+product.maintenanceRate ?? 0);
                    product.salePriceTotal =
                        +product.quantity * product.salePrice;
                }
                if (context?.[modelName]) {
                    context[modelName] = { ...product };
                } else this.dataProducts.data[index] = { ...product };
            }
        },
        /**
         *  recomputes the salePriceTotal, maintenancePrice, maintenancePriceTotal of the product
         * @param {index} the index of which the salePrice was changed
         * @param {modelName} the name of the variable of which the properties need to be modified, can be nullable
         * @param {context} the vue component instance('this') of the component that this method is being called from, can be nullable
         */
        async salePriceTotalChanged(index, modelName, context) {
            await this.$nextTick();
            const product =
                context?.[modelName] ?? this.dataProducts?.data?.[index];
            if (product) {
                product.salePrice = +product.salePriceTotal / +product.quantity;
                // context?.[modelName] is only set when we are edit mode for licenses, in create this calculation should take place
                if (!context?.[modelName]) {
                    product.maintenancePrice =
                        (+product.salePrice * +(product.maintenanceRate ?? 0)) /
                        100;
                    product.maintenancePriceTotal =
                        +product.quantity * product.maintenancePrice;
                }
                if (context?.[modelName]) {
                    context[modelName] = { ...product };
                } else this.dataProducts.data[index] = { ...product };
            }
        },
        /**
         *  recomputes the salePriceTotal, maintenancePrice, maintenancePriceTotal of the product
         * @param {index} the index of which the salePrice was changed
         * @param {modelName} the name of the variable of which the properties need to be modified, can be nullable
         * @param {context} the vue component instance('this') of the component that this method is being called from, can be nullable
         */
        async salePriceChanged(index, modelName, context) {
            await this.$nextTick();
            const product =
                context?.[modelName] ?? this.dataProducts?.data?.[index];
            if (product) {
                product.salePriceTotal = +product.quantity * product.salePrice;
                // context?.[modelName] is only set when we are edit mode for licenses, in create this calculation should take place
                if (!context?.[modelName]) {
                    product.maintenancePrice =
                        (+product.salePrice * +(product.maintenanceRate ?? 0)) /
                        100;
                    product.maintenancePriceTotal =
                        +product.quantity * product.maintenancePrice;
                }
                if (context?.[modelName]) {
                    context[modelName] = { ...product };
                } else this.dataProducts.data[index] = { ...product };
            }
        },
        /**
         *  recomputes the salePrice and all the dependent properties of the product
         * @param {index} the index of which the quantity was changed
         * @param {modelName} the name of the variable of which the properties need to be modified, can be nullable
         * @param {context} the vue component instance('this') of the component that this method is being called from, can be nullable
         */
        async quantityChanged(index, modelName, context) {
            await this.$nextTick();
            const product =
                context?.[modelName] ?? this.dataProducts?.data?.[index];
            if (product) {
                product.salePriceTotal = +product.quantity * +product.salePrice;
                product.maintenancePriceTotal =
                    +product.quantity * +product.maintenancePrice;
                if (context?.[modelName]) {
                    context[modelName] = { ...product };
                } else this.dataProducts.data[index] = { ...product };
            }
        },
    },
};
