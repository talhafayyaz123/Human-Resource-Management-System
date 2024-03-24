<template></template>

<script>
export default {
    props: ["sapSystemID", "client", "host", "systemNr", "user", "password"],
    computed: {
        configJsonModified() {
            let configJson = { ...this.configJson };
            configJson["_ALL"]["sapSystems"]["PER"]["sysId"] = this.sapSystemID;
            configJson["_ALL"]["sapSystems"]["PER"]["client"] = this.client;
            configJson["_ALL"]["sapSystems"]["PER"]["host"] = this.host;
            configJson["_ALL"]["sapSystems"]["PER"]["sysNr"] = this.systemNr;
            configJson["_ALL"]["sapSystems"]["PER"]["user"] = this.user;
            configJson["_ALL"]["sapSystems"]["PER"]["password"] = this.password;
            return configJson;
        },
    },
    watch: {
        configJsonModified() {
            this.$emit("configJsonModified", this.configJsonModified);
        },
    },
    mounted() {
        this.$emit("configJsonModified", this.configJsonModified);
    },
    data() {
        return {
            configJson: {
                "ELO-jetapp243.int.cancom.de-2": {
                    scheduleTimer: 0,
                    deleteFinishedQAfterDays: 0,
                    sapSystems: {},
                    contReps: {},
                },
                _ALL: {
                    scheduleTimer: 45000,
                    deleteFinishedQAfterDays: 3,
                    sapSystems: {
                        PER: {
                            type: "RFC",
                            sysId: "PER",
                            client: "100",
                            user: "ELOCPIC",
                            password: "¶saved¶",
                            language: "DE",
                            host: "jetsapper10.int.cancom.de",
                            sysNr: "01",
                            viaMsgServ: false,
                            group: null,
                            r3name: null,
                            mshost: null,
                            msserv: null,
                            sapRouter: null,
                            instance: "_ALL",
                        },
                    },
                    contReps: {
                        TD: {
                            contRep: "TD",
                            ealEnabled: true,
                            acceptNextCert: false,
                            acceptAllCerts: false,
                            description: "PER Produktivrepository",
                            ealComponentDocumentName: "Document",
                            eloStorePath: null,
                            ealACL: null,
                            eloMaskName: "SAPDATA",
                            eloReplicationSet: [],
                            idxToComponent: "*",
                            idxOverwrite: false,
                            idxDeleteBeforUpdate: [],
                            idxIgnoreIfFilled: [],
                            ealMimeTypeRow: null,
                            bcDocClassFromExtension: false,
                            ealCompatibilityMode: false,
                            ealEncryptionKey: null,
                            ealEncryptionAlgorithm: null,
                            ealCompress: false,
                            initObjKeyForComponent: false,
                            initObjKeyForDocument: false,
                            eloKeywordName: null,
                            eloKeywordValue: null,
                            eloRegisteredFunctionForBC: null,
                            eloFindSordForDeleteDocumentRF: null,
                            signature: false,
                            docProtection: "rucd",
                            sapSystemId: "PER",
                            indexdownloadEnabled: true,
                            barcodeUploadEnabled: false,
                            webClientRelayUrlOnGet: null,
                            idxRemoteFunction: "/ELO/KEY_EXPORT",
                            allowedClients: [],
                            useIxContType: false,
                            instance: "_ALL",
                        },
                    },
                },
                "ELO-jetapp243-2": {
                    scheduleTimer: 0,
                    deleteFinishedQAfterDays: 0,
                    sapSystems: {},
                    contReps: {},
                },
            },
        };
    },
};
</script>
