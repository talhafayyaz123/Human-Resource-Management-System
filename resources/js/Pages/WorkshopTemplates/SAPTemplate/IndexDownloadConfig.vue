<template></template>

<script>
export default {
    data() {
        return {
            configJson: {
                alwaysReloadConfig: true,
                newBasePath:
                    "¶SAP¶{{{sord.objKeys.SAP_OBJ_TXT}}} ({{{sord.objKeys.SAP_OBJECT}}})¶{{#if sord.objKeys.FISCALYEAR}}{{{sord.objKeys.FISCALYEAR}}}¶{{else if sord.objKeys.VENDOR_VENDORNO}}{{{sord.objKeys.VENDOR_VENDORNO}}} - {{{sord.objKeys.VENDOR_NAME}}}¶{{else}}{{/if}}{{#if sord.objKeys.SAP_INST_NAME}}{{{sord.objKeys.OBJECT_ID}}} - {{{sord.objKeys.SAP_INST_NAME}}}{{else}}{{{sord.objKeys.OBJECT_ID}}}{{/if}}",
                switchMask: [
                    {
                        fieldName: "SAP_OBJECT",
                        fieldValue: "BUS2012",
                        newMask: "SAP-PurchaseOrder",
                    },
                    {
                        fieldName: "SAP_OBJECT",
                        fieldValue: "LFA1",
                        newMask: "SAP-Vendor",
                    },
                    {
                        fieldName: "SAP_OBJECT",
                        fieldValue: "VBRK",
                        newMask: "SAP-BillingDocument",
                    },
                ],
                shorttextFields: ["SAP_DESCR", "SAP_ARO_TXT"],
                requieredFieldsForMove: [
                    "SAP_OBJ_TXT",
                    "SAP_OBJECT",
                    "OBJECT_ID",
                ],
                rightsList: [
                    {
                        matches: [
                            {
                                "matches.onDocument": "",
                                "matches.onStructure": "",
                                "matches.replaceRights": "",
                                "matches.fieldName": "",
                                "matches.fieldValue": "",
                                "matches.isRegex": "",
                                onDocument: false,
                                onStructure: false,
                                replaceRights: false,
                                fieldName: "SAP_OBJECT",
                                fieldValue: "BUS0815",
                            },
                            {
                                onDocument: true,
                                onStructure: false,
                                replaceRights: false,
                                fieldName: "SAP_OBJECT",
                                fieldValue: "BUS2012",
                                isRegex: false,
                            },
                        ],
                        rightsEntries: [
                            {
                                user: "9999",
                                rights: {
                                    r: true,
                                    w: true,
                                    d: true,
                                    e: true,
                                    l: true,
                                    p: false,
                                },
                            },
                            {
                                user: "Prechtl",
                                rights: {
                                    r: true,
                                    w: true,
                                    d: true,
                                    e: true,
                                    l: false,
                                    p: false,
                                },
                            },
                            {
                                user: "Schmidt-Tudl",
                                rights: {
                                    r: true,
                                    w: true,
                                    d: true,
                                    e: true,
                                    l: true,
                                    p: false,
                                },
                            },
                            {
                                user: "Kiel",
                                rights: {
                                    r: true,
                                    w: true,
                                    d: true,
                                    e: false,
                                    l: false,
                                    p: false,
                                },
                            },
                            {
                                user: "Biehl",
                                rights: {
                                    r: true,
                                    w: true,
                                    d: false,
                                    e: false,
                                    l: false,
                                    p: false,
                                },
                            },
                        ],
                    },
                ],
                structureList: [
                    {
                        fieldName: "SAP_OBJECT",
                        fieldValue: "BUS0815",
                        isRegex: "",
                        copyStructureFrom: "/ExampleStructure",
                        sortCriteria: [
                            {
                                fieldName: "AR_OBJECT",
                                fieldValue: "ZELOPDF",
                                isRegex: "",
                                subPath: "/SubFolderStatic",
                            },
                            {
                                fieldName: "AR_OBJECT",
                                fieldValue: "ZELOPRINT",
                                isRegex: "",
                                subPath: "/DynamicallyAdded",
                            },
                        ],
                    },
                    {
                        fieldName: "SAP_OBJECT",
                        fieldValue: "[B][U][S][0-9]{4}",
                        isRegex: true,
                        copyStructureFrom: "/Lieferant",
                        sortCriteria: [
                            {
                                fieldName: "AR_OBJECT",
                                fieldValue: "[T][S][T][A-Z]{5}",
                                isRegex: true,
                                subPath: "/02_Bestellungen",
                            },
                            {
                                fieldName: "AR_OBJECT",
                                fieldValue: "ZELOPDF",
                                isRegex: "",
                                subPath: "/90_Schriftverkehr",
                            },
                        ],
                    },
                ],
                enableMove: true,
                csUrl: "http://10.49.110.199:9060/sl-DEMO12/ContentServer",
                newPathMask: {
                    fieldsToSet: [
                        {
                            folderField: "SAP_OBJECT",
                            sordField: "SAP_OBJECT",
                            ignoreForSearch: false,
                        },
                        {
                            folderField: "OBJECT_ID",
                            sordField: "OBJECT_ID",
                            ignoreForSearch: false,
                        },
                        {
                            folderField: "VENDOR_NAME",
                            sordField: "VENDOR_NAME",
                            ignoreForSearch: false,
                        },
                        {
                            folderField: "VENDOR_NO",
                            sordField: "VENDOR_VENDORNO",
                            ignoreForSearch: false,
                        },
                    ],
                },
                refreshName: false,
                refreshRepository: true,
            },
        };
    },
};
</script>
