<template>
    <div id="designer"></div>
</template>

<script>
import { Designer } from "@/Components/designer/src/index.ts";
import "@/Components/designer/css/designer.css";
import "@/Components/designer/css/designer-light.css";
import "@/Components/designer/css/designer-dark.css";

export default {
    data() {
        return {
            designer: null,
            localStorageKey: "sqdFullscreen",
        };
    },
    methods: {
        createTaskStep(id, type, name, properties) {
            return {
                id,
                componentType: "task",
                type,
                name,
                properties: properties || {},
            };
        },
        createIfStep(id, _true, _false) {
            return {
                id,
                componentType: "switch",
                type: "if",
                name: "If",
                branches: {
                    true: _true,
                    false: _false,
                },
                properties: {},
            };
        },

        createContainerStep(id, steps) {
            return {
                id,
                componentType: "container",
                type: "loop",
                name: "Loop",
                properties: {},
                sequence: steps,
            };
        },

        appendCheckbox(root, label, isReadonly, isChecked, onClick) {
            const item = document.createElement("div");
            item.innerHTML = '<div><h3></h3> <input type="checkbox" /></div>';
            const h3 = item.getElementsByTagName("h3")[0];
            h3.innerText = label;
            const input = item.getElementsByTagName("input")[0];
            input.checked = isChecked;
            if (isReadonly) {
                input.setAttribute("disabled", "disabled");
            }
            input.addEventListener("click", () => {
                onClick(input.checked);
            });
            root.appendChild(item);
        },

        appendPath(root, step) {
            const parents = this.designer.getStepParents(step);
            const path = document.createElement("div");
            path.className = "step-path";
            path.innerText =
                "Step path: " +
                parents
                    .map((parent) => {
                        return typeof parent === "string"
                            ? parent
                            : parent.name;
                    })
                    .join("/");
            root.appendChild(path);
        },
        loadState() {
            const state = localStorage[this.localStorageKey];
            if (state) {
                return JSON.parse(state);
            }
            return {
                definition: this.getStartDefinition(),
            };
        },

        saveState() {
            localStorage[this.localStorageKey] = JSON.stringify({
                definition: this.designer.getDefinition(),
                undoStack: this.designer.dumpUndoStack(),
            });
        },
        getStartDefinition() {
            return {
                properties: {},
                sequence: [
                    this.createIfStep(
                        "00000000000000000000000000000001",
                        [
                            this.createTaskStep(
                                "00000000000000000000000000000002",
                                "save",
                                "Save file",
                                { isInvalid: true }
                            ),
                        ],
                        [
                            this.createTaskStep(
                                "00000000000000000000000000000003",
                                "text",
                                "Send email"
                            ),
                        ]
                    ),
                    this.createContainerStep(
                        "00000000000000000000000000000004",
                        [
                            this.createTaskStep(
                                "00000000000000000000000000000005",
                                "task",
                                "Create task"
                            ),
                        ]
                    ),
                ],
            };
        },
    },
    mounted() {
        const initialState = this.loadState();

        const configuration = {
            undoStackSize: 20,
            undoStack: initialState.undoStack,

            toolbox: {
                groups: [
                    {
                        name: "Main",
                        steps: [
                            this.createTaskStep(null, "task", "Create task"),
                            this.createTaskStep(null, "exit", "Exit"),
                            this.createIfStep(null, [], []),
                            this.createContainerStep(null, []),
                        ],
                    },
                    {
                        name: "File system",
                        steps: [this.createTaskStep(null, "save", "Save file")],
                    },
                    {
                        name: "E-mail",
                        steps: [
                            this.createTaskStep(null, "text", "Send email"),
                        ],
                    },
                ],
                isCollapsed: false,
            },

            controlBar: true,

            steps: {
                isDuplicable: () => true,
                iconUrlProvider: (_, type) => {
                    return `../images/icon-${type}.svg`;
                },
            },

            validator: {
                step: (step) => {
                    return !step.properties["isInvalid"];
                },
            },

            editors: {
                rootEditorProvider: (definition, _context, isReadonly) => {
                    const root = document.createElement("div");
                    root.className = "definition-json";
                    root.innerHTML =
                        '<textarea style="width: 100%; border: 0;" rows="50"></textarea>';
                    const textarea = root.getElementsByTagName("textarea")[0];
                    if (isReadonly) {
                        textarea.setAttribute("readonly", "readonly");
                    }
                    textarea.value = JSON.stringify(definition, null, 2);
                    return root;
                },

                stepEditorProvider: (
                    step,
                    editorContext,
                    _definition,
                    isReadonly
                ) => {
                    const root = document.createElement("div");

                    this.appendCheckbox(
                        root,
                        "Is invalid",
                        isReadonly,
                        !!step.properties["isInvalid"],
                        (checked) => {
                            step.properties["isInvalid"] = checked;
                            editorContext.notifyPropertiesChanged();
                        }
                    );

                    if (step.type === "if") {
                        this.appendCheckbox(
                            root,
                            "Catch branch",
                            isReadonly,
                            !!step.branches["catch"],
                            (checked) => {
                                if (checked) {
                                    step.branches["catch"] = [];
                                } else {
                                    delete step.branches["catch"];
                                }
                                editorContext.notifyChildrenChanged();
                            }
                        );
                    }

                    this.appendPath(root, step);
                    return root;
                },
            },
        };

        const placeholder = document.getElementById("designer");
        this.designer = Designer.create(
            placeholder,
            initialState.definition,
            configuration
        );

        this.designer.onDefinitionChanged.subscribe((newDefinition) => {
            this.saveState();
            console.log("the definition has changed!", newDefinition);
        });
    },
};
</script>

<style scoped>
:deep(.sqd-workspace) {
    height: calc(100vh - 75px) !important;
}
</style>
