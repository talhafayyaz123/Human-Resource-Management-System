export default {
    methods: {
        /**
         * sets the form fields using the modelData
         * sets the offer components
         * @param {offerSelected} determines if the coverLetterText should be synced with modelData or not
         */
        setData(offerSelected = false) {
            return new Promise(async (resolve, reject) => {
                try {
                    this.form = {
                        term:
                            this.termsOfPayment?.data?.find(
                                (term) => term.id == this.modelData?.term
                            ) ?? {},
                        company: {},
                        removeFromStatistics:
                            this.modelData?.removeFromStatistics == 1,
                        plannedStartDate: new Date(
                            this.modelData?.plannedStartDate ?? ""
                        ),
                        project: this.modelData?.project,
                        serviceLevelAgreement:
                            this.serviceLevelAgreements?.data?.find(
                                (serviceLevelAgreement) =>
                                    serviceLevelAgreement.id ==
                                    this.modelData?.sla_id
                            ) ?? {},
                        type: this.modelData?.type,
                        receiverType: this.modelData?.receiverType,
                        template: this.modelData?.template,
                        productsGroupedBy: this.modelData?.groupedBy,
                        coverLetterText: offerSelected
                            ? this.form.coverLetterText
                            : this.modelData?.coverLetterText,
                        offerDescriptionText:
                            this.modelData?.offerDescriptionText,
                        expiryDate: new Date(this.modelData?.expiryDate),
                        paymentTerms: this.modelData?.paymentTerms,
                        createdBy: this.modelData?.createdBy,
                        createdAt: this.modelData?.createdAt,
                        offerNumber: this.modelData?.offerNumber,
                        contactPerson: this.modelData?.contactPerson ?? "",
                        orderStatus: this.modelData?.orderStatus ?? "draft",
                        offerStatus: this.modelData?.offerStatus ?? "versendet",
                        updatedAt: new Date(this.modelData?.updatedAt ?? ""),
                        externalOrderNumber: "",
                        identifier: this.modelData?.identifier ?? "",
                        moduleHistory: this.modelData?.moduleHistory ?? [],
                    };
                    await this.fetchCompanyLeadEmployees(false);
                    if (this.modelData?.createdBy)
                        this.$store
                            .dispatch("auth/show", {
                                id: this.modelData?.createdBy,
                            })
                            .then((res) => {
                                this.user = res.data;
                            });
                    this.shouldShow = false;
                    this.$nextTick(async () => {
                        this.form.company =
                            this.form.receiverType === "lead"
                                ? this.leads?.data?.find(
                                      (company) =>
                                          company.id == this.modelData?.company
                                  ) ?? {}
                                : this.form.receiverType === "partner"
                                ? this.partners?.data?.find(
                                      (company) =>
                                          company.id == this.modelData?.company
                                  ) ?? {}
                                : this.companies?.data?.find(
                                      (company) =>
                                          company.id == this.modelData?.company
                                  ) ?? {};
                        if (!this.form.company && !!this.modelData?.company) {
                            const companyResponse = await this.$store.dispatch(
                                "companies/show",
                                this.modelData?.company
                            );
                            this.form.company =
                                companyResponse?.data?.modelData ?? {};
                        }
                        this.form.externalOrderNumber = !!this.modelData
                            ?.externalOrderNumber
                            ? this.modelData?.externalOrderNumber
                            : this.form.company?.externalOrderNumber;
                        this.shouldShow = true;
                        await this.fetchCompanyLeadEmployees(false); // filter the company employees list based on the company
                        await this.fetchProjectsByCustomer(); // filter the projects list based on the company
                        // fetch the contactPerson from the show API
                        if (this.modelData?.contactPerson)
                            this.$store
                                .dispatch("auth/show", {
                                    id: this.modelData?.contactPerson,
                                })
                                .then((res) => {
                                    this.form.contactPerson = res?.data ?? "";
                                });
                        // set the project by finding from the filtered projects list
                        this.form.project =
                            this.projects?.data?.find(
                                (project) =>
                                    project.id == this.modelData?.project
                            ) ?? {};
                    });
                    const components = this.modelData?.components;
                    const optionalComponents =
                        this.modelData?.optionalComponents;
                    components.forEach((component) => {
                        if (component.type === "license") {
                            this.additionalDescriptionToggled[
                                "softwareLicences"
                            ][component.id] =
                                component?.additionalDescription?.length;
                            this.componentIdsMappedToProductIds[
                                "softwareLicenses"
                            ][component?.product?.id] = component.id;
                            this.softwareLicences = [
                                ...this.softwareLicences,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                },
                            ];
                            this.softwareLicencesToggle = true;
                        } else if (component.type === "maintenance") {
                            this.additionalDescriptionToggled[
                                "softwareMaintenance"
                            ][component.id] =
                                component?.additionalDescription?.length;
                            this.componentIdsMappedToProductIds[
                                "softwareMaintenance"
                            ][component?.product?.id] = component.id;
                            this.softwareMaintenance = [
                                ...this.softwareMaintenance,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                    totalSalePriceAdjustedForQuantity:
                                        +component.salePrice *
                                        +component.quantity,
                                },
                            ];
                            this.softwareMaintenanceToggle = true;
                        } else if (component.type === "service") {
                            this.componentIdsMappedToProductIds["services"][
                                component?.product?.id
                            ] = component.id;
                            if (this.form.productsGroupedBy == "nothing") {
                                this.additionalDescriptionToggled["services"][
                                    component.id
                                ] = component?.additionalDescription?.length;
                                const totalWithoutDiscount =
                                    +component.quantity * +component.hourlyRate;
                                const discountAmount =
                                    (+totalWithoutDiscount *
                                        +component.discount) /
                                    100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const taxRate =
                                    (+nettoTotal * +component.tax) / 100;
                                this.services = [
                                    ...this.services,
                                    {
                                        ...component,
                                        componentId: component.id, //componentId is send as id while updating
                                        category: component.product.category,
                                        unit: component.product.unit,
                                        productId: component.product.id,
                                        articleNumber:
                                            component.product.articleNumber,
                                        name: component.product.name,
                                        salePrice: component.salePrice,
                                        tax: +component.tax,
                                        nettoTotal: nettoTotal,
                                        taxRate: taxRate,
                                        bruttoTotal: +nettoTotal + +taxRate,
                                        children: component.children.map(
                                            (child) => {
                                                return {
                                                    ...child,
                                                    category:
                                                        child.product.category,
                                                    unit: child.product.unit,
                                                    productId: child.product.id,
                                                    articleNumber:
                                                        child.product
                                                            .articleNumber,
                                                    name: child.product.name,
                                                    salePrice: child.salePrice,
                                                    tax: +child.tax,
                                                    totalRate:
                                                        +child.quantity *
                                                        +child.hourlyRate,
                                                };
                                            }
                                        ),
                                    },
                                ];
                                this.computeServicesByCategory();
                            } else {
                                const category = component.productCategory;
                                const products = component.products;
                                this.services = [
                                    ...this.services,
                                    ...products.map((product) => {
                                        const totalWithoutDiscount =
                                            +product.quantity *
                                            +product.hourlyRate;
                                        const discountAmount =
                                            (+totalWithoutDiscount *
                                                +product.discount) /
                                            100;
                                        const nettoTotal =
                                            +totalWithoutDiscount -
                                            +discountAmount;
                                        const taxRate =
                                            (+nettoTotal * +product.tax) / 100;
                                        return {
                                            ...product,
                                            tax: +product.tax,
                                            nettoTotal: nettoTotal,
                                            taxRate: taxRate,
                                            bruttoTotal: +nettoTotal + +taxRate,
                                            children: [],
                                        };
                                    }),
                                ];
                                const quantity = component.quantity;
                                const unit = this.unitForCategory({
                                    ...category,
                                    products,
                                });
                                const hourlyRate = component.hourlyRate;
                                const totalWithoutDiscount =
                                    +hourlyRate * +quantity;
                                const discount = component.discount;
                                const discountAmount =
                                    (+totalWithoutDiscount * +discount) / 100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const tax = component.tax;
                                const taxRate = (+nettoTotal * +tax) / 100;
                                this.additionalDescriptionToggled["categories"][
                                    category.id
                                ] = component?.additionalDescription?.length;
                                this.categories.push({
                                    ...category,
                                    additionalDescription:
                                        component?.additionalDescription ?? "",
                                    products: products,
                                    unit: unit,
                                    description: component.description,
                                    quantity: quantity,
                                    hourlyRate: hourlyRate,
                                    discount: discount,
                                    nettoTotal: nettoTotal,
                                    tax: tax,
                                    taxRate: taxRate,
                                    bruttoTotal: +nettoTotal + +taxRate,
                                });
                            }
                            this.servicesToggle = true;
                            this.groupToggler["service"][component.id] =
                                component?.children?.length > 0;
                        } else if (component.type === "application") {
                            this.additionalDescriptionToggled["ams"][
                                component.id
                            ] = component?.additionalDescription?.length;
                            this.componentIdsMappedToProductIds["ams"][
                                component?.product?.id
                            ] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity * +component.hourlyRate;
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.ams = [
                                ...this.ams,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    salePrice: component.salePrice,
                                    hourlyRate: component.hourlyRate,
                                    paymentPeriod: component.paymentPeriod,
                                    serviceHours: component.quantity,
                                    tax: +component.tax,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                },
                            ];
                            if (
                                component.slaInfrastructureId ||
                                component.slaServiceTimeId ||
                                component.slaLevelId
                            ) {
                                this.slaToggled[component.id] = true;
                            }
                            this.show = false;
                            this.globalPeriodAMS = this.ams?.[0]?.paymentPeriod;
                            this.$nextTick(() => (this.show = true));
                            this.applicationManagementServiceToggle = true;
                            this.fetchSLA();
                        } else if (component.type === "hosting") {
                            this.additionalDescriptionToggled["hosting"][
                                component.id
                            ] = component?.additionalDescription?.length;
                            this.componentIdsMappedToProductIds["hosting"][
                                component?.product?.id
                            ] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity * +component.pricePerPeriod;
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.hosting = [
                                ...this.hosting,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    pricePerPeriod: component.pricePerPeriod,
                                    quantity: component.quantity,
                                    paymentPeriod: component.paymentPeriod,
                                    tax: +component.tax,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    children: component.children.map(
                                        (child) => {
                                            return {
                                                ...child,
                                                productId: child.product.id,
                                                articleNumber:
                                                    child.product.articleNumber,
                                                productSoftware:
                                                    child.product
                                                        .productSoftware,
                                                name: child.product.name,
                                                unit: child.product.unit,
                                                pricePerPeriod:
                                                    child.pricePerPeriod,
                                                quantity: child.quantity,
                                                paymentPeriod:
                                                    child.paymentPeriod,
                                                tax: +child.tax,
                                                totalPricePeriod:
                                                    +child.quantity *
                                                    +child.pricePerPeriod,
                                            };
                                        }
                                    ),
                                },
                            ];
                            this.globalPeriodHosting =
                                this.hosting?.[0]?.paymentPeriod;
                            this.hostingToggle = true;
                            this.groupToggler["hosting"][component.id] =
                                component?.children?.length > 0;
                        } else if (component.type === "cloud") {
                            this.additionalDescriptionToggled["cloud"][
                                component.id
                            ] = component?.additionalDescription?.length;
                            this.componentIdsMappedToProductIds["cloud"][
                                component?.product?.id
                            ] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity *
                                (+component.salePrice *
                                    (+component.duration ?? 1));
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.cloud = [
                                ...this.cloud,
                                {
                                    ...component,
                                    componentId: component.id, //componentId is send as id while updating
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    salePrice: component.salePrice,
                                    quantity: component.quantity,
                                    paymentPeriod: component.paymentPeriod,
                                    tax: +component.tax,
                                    duration: +component.duration,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    children: component.children.map(
                                        (child) => {
                                            return {
                                                ...child,
                                                productId: child.product.id,
                                                articleNumber:
                                                    child.product.articleNumber,
                                                productSoftware:
                                                    child.product
                                                        .productSoftware,
                                                name: child.product.name,
                                                unit: child.product.unit,
                                                pricePerPeriod:
                                                    child.pricePerPeriod,
                                                quantity: child.quantity,
                                                paymentPeriod:
                                                    child.paymentPeriod,
                                                tax: +child.tax,
                                                totalPricePeriod:
                                                    +child.quantity *
                                                    (+child.duration ?? 1) *
                                                    +child.salePrice,
                                            };
                                        }
                                    ),
                                },
                            ];
                            this.globalPeriodCloud =
                                this.cloud?.[0]?.paymentPeriod;
                            this.cloudToggle = true;
                            this.groupToggler["cloud"][component.id] =
                                component?.children?.length > 0;
                        }
                    });
                    optionalComponents.forEach((component) => {
                        if (component.type === "application") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "ams"
                                ][component?.product?.id] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity * +component.hourlyRate;
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.optionalAms = [
                                ...this.optionalAms,
                                {
                                    ...component,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    salePrice: component.salePrice,
                                    hourlyRate: component.hourlyRate,
                                    paymentPeriod: component.paymentPeriod,
                                    serviceHours: component.quantity,
                                    tax: +component.tax,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                },
                            ];
                            if (
                                component.slaInfrastructureId ||
                                component.slaServiceTimeId ||
                                component.slaLevelId
                            ) {
                                this.slaToggled[component.id] = true;
                            }
                            this.optionalAMSProductsToggle = true;
                            this.applicationManagementServiceToggle = true;
                            this.fetchSLA();
                        } else if (component.type === "license") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "softwareLicenses"
                                ][component?.product?.id] = component.id;
                            this.optionalSoftwareLicenses = [
                                ...this.optionalSoftwareLicenses,
                                {
                                    ...component,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                },
                            ];
                            this.softwareLicencesToggle = true;
                            this.optionalSoftwareProductsToggle = true;
                        } else if (component.type === "maintenance") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "softwareMaintenance"
                                ][component?.product?.id] = component.id;
                            this.optionalSoftwareMaintenance = [
                                ...this.optionalSoftwareMaintenance,
                                {
                                    ...component,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    maintenanceRate: component.maintenanceRate,
                                    totalSalePriceAdjustedForQuantity:
                                        +component.salePrice *
                                        +component.quantity,
                                },
                            ];
                            this.softwareMaintenanceToggle = true;
                            this.optionalSoftwareProductsToggle = true;
                        } else if (component.type === "hosting") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "hosting"
                                ][component?.product?.id] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity * +component.pricePerPeriod;
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.optionalHosting = [
                                ...this.optionalHosting,
                                {
                                    ...component,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    pricePerPeriod: component.pricePerPeriod,
                                    quantity: component.quantity,
                                    paymentPeriod: component.paymentPeriod,
                                    tax: +component.tax,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                },
                            ];
                            this.hostingToggle = true;
                            this.optionalHostingProductsToggle = true;
                        } else if (component.type === "cloud") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "cloud"
                                ][component?.product?.id] = component.id;
                            const totalWithoutDiscount =
                                +component.quantity *
                                (+component.salePrice *
                                    (+component.duration ?? 1));
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.optionalCloud = [
                                ...this.optionalCloud,
                                {
                                    ...component,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    productSoftware:
                                        component.product.productSoftware,
                                    name: component.product.name,
                                    unit: component.product.unit,
                                    salePrice: component.salePrice,
                                    quantity: component.quantity,
                                    paymentPeriod: component.paymentPeriod,
                                    tax: +component.tax,
                                    duration: +component.duration,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                },
                            ];
                            this.cloudToggle = true;
                            this.optionalCloudProductsToggle = true;
                        } else if (component.type === "service") {
                            // in offer confirmation create (offerSelected == true), we do not need to made 'optionalComponentIdsMappedToProductIds'
                            if (!offerSelected)
                                this.optionalComponentIdsMappedToProductIds[
                                    "services"
                                ][component?.product?.id] = component.id;
                            if (this.form.productsGroupedBy == "nothing") {
                                const totalWithoutDiscount =
                                    +component.quantity * +component.hourlyRate;
                                const discountAmount =
                                    (+totalWithoutDiscount *
                                        +component.discount) /
                                    100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const taxRate =
                                    (+nettoTotal * +component.tax) / 100;
                                this.optionalServices = [
                                    ...this.optionalServices,
                                    {
                                        ...component,
                                        category: component.product.category,
                                        unit: component.product.unit,
                                        productId: component.product.id,
                                        articleNumber:
                                            component.product.articleNumber,
                                        name: component.product.name,
                                        salePrice: component.salePrice,
                                        tax: +component.tax,
                                        nettoTotal: nettoTotal,
                                        taxRate: taxRate,
                                        bruttoTotal: +nettoTotal + +taxRate,
                                    },
                                ];
                                this.computeServicesByCategory();
                            } else {
                                const category = component.productCategory;
                                const products = component.products;
                                this.optionalServices = [
                                    ...this.optionalServices,
                                    ...products.map((product) => {
                                        const totalWithoutDiscount =
                                            +product.quantity *
                                            +product.hourlyRate;
                                        const discountAmount =
                                            (+totalWithoutDiscount *
                                                +product.discount) /
                                            100;
                                        const nettoTotal =
                                            +totalWithoutDiscount -
                                            +discountAmount;
                                        const taxRate =
                                            (+nettoTotal * +product.tax) / 100;
                                        return {
                                            ...product,
                                            tax: +product.tax,
                                            nettoTotal: nettoTotal,
                                            taxRate: taxRate,
                                            bruttoTotal: +nettoTotal + +taxRate,
                                        };
                                    }),
                                ];
                                const quantity = component.quantity;
                                const unit = this.unitForCategory({
                                    ...category,
                                    products,
                                });
                                const hourlyRate = component.hourlyRate;
                                const totalWithoutDiscount =
                                    +hourlyRate * +quantity;
                                const discount = component.discount;
                                const discountAmount =
                                    (+totalWithoutDiscount * +discount) / 100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const tax = component.tax;
                                const taxRate = (+nettoTotal * +tax) / 100;
                                this.optionalCategories.push({
                                    ...category,
                                    products: products,
                                    unit: unit,
                                    description: component.description,
                                    quantity: quantity,
                                    hourlyRate: hourlyRate,
                                    discount: discount,
                                    nettoTotal: nettoTotal,
                                    tax: tax,
                                    taxRate: taxRate,
                                    bruttoTotal: +nettoTotal + +taxRate,
                                });
                            }
                            console.log(this.optionalCategories);
                            this.servicesToggle = true;
                            this.optionalServicesProductsToggle = true;
                        }
                    });
                    this.softwareLicences.forEach((item, index) => {
                        this.calculateProductValue(index, "softwareLicences");
                    });
                    this.optionalSoftwareLicenses.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "optionalSoftwareLicenses"
                        );
                    });
                    this.softwareLicencesTax = this.calculateTax(
                        "softwareLicences"
                    )?.map((product) => {
                        return {
                            ...product,
                            totalSalePriceAdjustedForQuantity:
                                +product.salePrice * +product.quantity,
                        };
                    });
                    this.softwareMaintenance.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "softwareMaintenance"
                        );
                    });
                    this.optionalSoftwareMaintenance.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "optionalSoftwareMaintenance"
                        );
                    });
                    this.softwareMaintenanceTax = this.calculateTax(
                        "softwareMaintenance"
                    );
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
    },
};
