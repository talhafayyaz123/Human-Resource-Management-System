export default {
    methods: {
        /**
         * sets the form fields using the modelData
         * sets the offer components
         */
        setData() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.form = {
                        company: {},
                        requestStatus: this.modelData?.requestStatus ?? "",
                        receiverType: this.modelData?.receiverType,
                        productsGroupedBy: this.modelData?.groupedBy,
                        createdBy: this.modelData?.createdBy,
                        createdAt: this.modelData?.createdAt,
                        offerRequestNumber: this.modelData?.offerRequestNumber,
                        text: this.modelData?.text,
                        createdBy: this.modelData?.createdBy,
                        contactPerson: this.modelData?.contactPerson ?? "",
                        status: this.modelData?.status,
                        projectId: this.modelData?.projectId,
                    };
                    this.shouldShow = false;
                    this.$nextTick(async () => {
                        this.form.company =
                            this.form.receiverType === "lead"
                                ? this.leads?.data?.find(
                                      (company) =>
                                          company.id ==
                                          this.modelData?.receiver?.id
                                  ) ?? {}
                                : this.companies?.data?.find(
                                      (company) =>
                                          company.id ==
                                          this.modelData?.receiver?.id
                                  ) ?? {};
                        if (
                            !this.form.company &&
                            !!this.modelData?.receiverId
                        ) {
                            const companyResponse = await this.$store.dispatch(
                                "companies/show",
                                this.modelData?.receiver?.id
                            );
                            this.form.company =
                                companyResponse?.data?.modelData ?? {};
                        }
                        this.shouldShow = true;
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
                    });
                    this.modelData.components.forEach((component) => {
                        if (component.type === "license") {
                            this.softwareLicences = {
                                ...component,
                                licenseReport: {
                                    files: component.licenseReport
                                        ? [component.licenseReport]
                                        : [],
                                },
                            };
                            this.softwareLicencesToggle = true;
                        }
                        if (component.type === "maintenance") {
                            this.softwareMaintenance = component;
                            this.softwareMaintenanceToggle = true;
                        }
                        if (component.type === "service") {
                            this.services = component;
                            this.servicesToggle = true;
                        }
                        if (component.type === "application") {
                            this.ams = component;
                            this.applicationManagementServiceToggle = true;
                        }
                        if (component.type === "hosting") {
                            this.hosting = component;
                            this.hostingToggle = true;
                        }
                        if (component.type === "cloud") {
                            this.cloud = component;
                            this.cloudToggle = true;
                        }
                    });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
    },
};
