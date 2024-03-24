<template>
    <div
        class="card rounded shadow-lg"
        @contextmenu.self="onContextMenu(null, $event)"
    >
        <ContextMenu
            @openFile="openFile"
            @cutItem="cutItemForPasting"
            @copyItem="copyItem"
            @pasteItem="pasteItem"
            @toggleRename="toggleRename"
            @deleteFile="toggleDeleteModal = true"
            @onAddFile="onAddFile"
            :hideMenu="hideMenu"
            :items="items"
            :id="'contextMenu'"
            :shouldPaste="shouldPaste"
            :path="path"
        />
        <ContextMenu
            @openFile="openFile"
            @cutItem="cutItemForPasting"
            @copyItem="copyItem"
            @pasteItem="pasteItem"
            @toggleRename="toggleRename"
            @deleteFile="toggleDeleteModal = true"
            @onAddFile="onAddFile"
            :items="items"
            :id="'contextMenuRoot'"
            :shouldPaste="shouldPaste"
            :path="path"
        />
        <p class="mx-2 my-2">File Explorer</p>
        <div class="options flex justify-between p-2">
            <div class="ps-2 flex items-center">
                <font-awesome-icon
                    @click="addNewItem('folder')"
                    :title="$t('Add Folder')"
                    style="font-size: large"
                    icon="fas fa-folder-plus"
                    class="ml-1 hover"
                ></font-awesome-icon>
                <font-awesome-icon
                    @click="addNewItem('file')"
                    :title="$t('Add File')"
                    style="font-size: large"
                    icon="fas fa-file-circle-plus"
                    class="ml-1 hover"
                ></font-awesome-icon>
            </div>
            <div>
                <button
                    @click="saveAll"
                    class="self-center secondary-btn"
                >
                    {{ $t("Save All") }}
                </button>
                <button
                    @click="save(true)"
                    class="self-center secondary-btn"
                >
                    {{ $t("Save") }}
                </button>
            </div>
        </div>
        <FileStructureTreeNode
            class="file-structure-node"
            :selectedItem="selectedItem"
            @fileAdded="fileAdded"
            :items="items"
            :cutItem="cutItem"
        />
        <input
            ref="newFileInput"
            v-if="showNewFileInput"
            @keypress.enter="saveNewFile"
            v-model="newFileNameInput"
            :class="[sameNameError ? 'input-error' : 'new-input', 'mx-2']"
            type="text"
        />
        <div v-if="showNewFileInput && sameNameError" class="error-message-box">
            <p class="m-0">
                {{ $t("A file or folder") }} '{{ newFileNameInput }}'
                {{
                    $t(
                        "already exists at this location. Please choose a different name."
                    )
                }}
            </p>
        </div>
        <Modal
            :title="$t('Delete File/Folder')"
            width="50%"
            v-if="toggleDeleteModal"
            @toggleModal="toggleDeleteModal = $event"
        >
            <template #body>
                <p class="my-4">
                    Are you sure you want to delete '{{
                        selectedItem.name +
                        `${
                            selectedItem.extension
                                ? "." + selectedItem.extension
                                : ""
                        }`
                    }}'?
                </p>
            </template>
            <template #footer>
                <button
                    type="button"
                    class="secondary-btn"
                    @click="toggleDeleteModal = false"
                >
                    <span class="flex">{{ $t("Close") }}</span>
                </button>
                <button
                    type="button"
                    class="secondary-btn"
                    @click="deleteFile()"
                >
                    <span class="flex">{{ $t("Delete") }}</span>
                </button>
            </template>
        </Modal>
    </div>
</template>

<script>
import Modal from "../../Components/EditModal.vue";
import FileStructureTreeNode from "./FileStructureNode.vue";
import ContextMenu from "./ContextMenu.vue";
import { mapGetters } from "vuex";

export default {
    created() {
        try {
            const itemsFromLocalStorage = JSON.parse(
                window.localStorage.getItem("fileSystem")
            );
            if (
                itemsFromLocalStorage &&
                typeof itemsFromLocalStorage === "object" &&
                Array.isArray(itemsFromLocalStorage)
            ) {
                this.$store.commit("documentService/items", [
                    ...itemsFromLocalStorage,
                ]);
            }
        } catch (err) {
            //
        }
        this.$emitter.on("selected-item", (data) => {
            this.selectedItem = data?.item ?? data;
            this.path = this.findPath(this.items);
            if (data.item) this.toggleFolder();
        });
        this.$emitter.on("rename", (data) => {
            this.rename(data);
        });
        this.$emitter.on("saveOrNot", (data) => {
            this.save(data);
        });
        this.$emitter.on("fileAdded", (data) => {
            this.fileAdded(data);
        });
        this.$emitter.on("keyboard-event", (type) => {
            if (type === "copy") {
                this.copyItem();
            } else if (type === "cut") {
                this.cutItemForPasting();
            } else if (type === "paste") {
                this.pasteItem();
            }
        });
    },
    mounted() {
        this.setItemsInterval();
        this.spliceAndCalculatePaths();
    },
    beforeDestroy() {
        clearInterval(this.saveItemsInterval);
    },
    components: {
        Modal,
        FileStructureTreeNode,
        ContextMenu,
    },
    computed: {
        ...mapGetters("documentService", [
            "items",
            "openedFiles",
            "selectedFile",
            "unsavedFiles",
            "previewFiles",
        ]),
        shouldPaste() {
            return this.cutItem || this.copiedItem;
        },
    },
    watch: {
        selectedFile(val) {
            if (val) {
                this.$emitter.emit(
                    "selectedFilePath",
                    this.findPath(this.items, val?.id)
                );
            }
        },
        newFileNameInput(val) {
            if (this.searchIfFileAlreadyExists(val)) {
                this.sameNameError = true;
            } else this.sameNameError = false;
        },
    },
    methods: {
        spliceAndCalculatePaths() {
            const previewFiles = this.previewFiles;
            previewFiles.splice(0, this.previewFiles.length);
            this.$store.commit("documentService/previewFiles", previewFiles);
            this.calculateAllFileExtensionPaths(this.items, "html");
        },
        saveAll() {
            try {
                window.localStorage.setItem(
                    "fileSystem",
                    JSON.stringify(this.items)
                );
                const unsavedFiles = this.unsavedFiles;
                unsavedFiles.splice(0, this.unsavedFiles.length);
                this.$store.commit(
                    "documentService/unsavedFiles",
                    unsavedFiles
                );
            } catch (err) {
                console.log(err);
            }
        },
        removeFileFromUnsavedFiles() {
            this.$store.commit("documentService/unsavedFiles", [
                ...(this.unsavedFiles.filter(
                    (file) => file.id != this.selectedFile.id
                ) ?? []),
            ]);
        },
        save(shouldSave) {
            if (shouldSave) {
                this.removeFileFromUnsavedFiles();
            }
            this.itemsTemp = [...this.items];
            this.unsavedFiles.forEach((file) => {
                const path = this.findPath(
                    this.itemsTemp,
                    file.id,
                    "this.itemsTemp"
                );
                const fileString = JSON.stringify(file);
                this.javascriptExecuter(`${path} = ${fileString}`);
            });
            if (!shouldSave) this.removeFileFromUnsavedFiles();
            window.localStorage.setItem(
                "fileSystem",
                JSON.stringify(this.itemsTemp)
            );
            this.$store.commit("documentService/items", [...this.itemsTemp]);
        },
        setItemsInterval() {
            this.saveItemsInterval = setInterval(() => {
                this.saveAll();
            }, 100000000);
        },
        openFile() {
            this.$store.commit("documentService/openedFiles", [
                ...this.openedFiles,
                this.selectedItem,
            ]);
            this.$store.commit(
                "documentService/selectedFile",
                this.selectedItem
            );
        },
        addNewItem(type) {
            this.type = type;
            const parentPath = this.selectedFile?.parentId
                ? this.findPath(this.items, this.selectedFile?.parentId)
                : null;
            if (parentPath) {
                this.javascriptExecuter(
                    `${parentPath}.showNewFileInput = true`
                );
                this.selectedItem = this.javascriptExecuter(`${parentPath}`);
                this.path = parentPath;
            } else this.showNewFileInput = true;
        },
        searchIfFileAlreadyExists(fileName) {
            const parentDirectory = this.javascriptExecuter(
                this.path === "this.items" ? this.path : `${this.path}.children`
            );
            if (parentDirectory) {
                return parentDirectory?.some((item) => {
                    return (() => {
                        if (item.type == "folder") {
                            return item.name === fileName;
                        } else if (item.type == "file") {
                            return (
                                item.name + "." + item.extension === fileName
                            );
                        }
                    })();
                });
            }
            return false;
        },
        javascriptExecuter(obj) {
            return Function('"use strict";return (' + obj + ")").call(this);
        },
        pasteItem() {
            if (this.copiedItem || this.cutItem) {
                if (this.cutItem) {
                    this.deleteFile(this.cutPath);
                    this.path =
                        this.path === "this.items"
                            ? "this.items"
                            : this.findPath(this.items);
                    this.cutPath = "";
                }
                if (this.path !== "this.items") {
                    this.toggleFolder(true);
                }
                if (this.selectedItem?.type == "file") {
                    const parentPath = this.selectedItem?.parentId
                        ? this.findPath(this.items, this.selectedItem?.parentId)
                        : null;
                    this.path = parentPath || this.path;
                }
                const tempObject = { ...(this.copiedItem || this.cutItem) };
                let nameCounter = 0;
                let terminate = false;
                let renameString = "";
                while (!terminate) {
                    renameString =
                        `${tempObject.name}${
                            nameCounter === 0 ? "" : `(${nameCounter})`
                        }` +
                        (tempObject.type == "file"
                            ? `.${tempObject.extension}`
                            : "");
                    if (this.searchIfFileAlreadyExists(renameString)) {
                        nameCounter += 1;
                    } else {
                        tempObject.name = tempObject.name + "_" + nameCounter;
                        tempObject.displayName = renameString
                            ? tempObject.type === "file"
                                ? renameString?.split(".")?.[0]
                                : renameString
                            : tempObject.name;
                        terminate = true;
                    }
                }
                tempObject.id = Date.now();
                tempObject.parentId =
                    this.javascriptExecuter(`${this.path}.id`) ?? null;
                tempObject.child = this.path !== "this.items";
                tempObject.show = true;
                const objectString = JSON.stringify(tempObject);
                this.javascriptExecuter(
                    `${this.path}${
                        this.path === "this.items" ? "" : ".children"
                    }.push(${objectString})`
                );
                this.cutItem = null;
                this.spliceAndCalculatePaths();
            }
        },
        cutItemForPasting() {
            this.copiedItem = null;
            this.cutPath = this.path;
            this.cutItem = this.javascriptExecuter(`${this.path}`);
        },
        copyItem() {
            this.cutItem = null;
            this.copiedItem = this.javascriptExecuter(`${this.path}`);
        },
        toggleFolder(show = false) {
            if (this.javascriptExecuter(`${this.path}`)) {
                const isOpen = this.javascriptExecuter(`${this.path}.open`);
                this.javascriptExecuter(
                    `${this.path}.open = ${show ? show : !isOpen}`
                );
                if (this.javascriptExecuter(`${this.path}.children`))
                    for (
                        let i = 0;
                        i <
                        this.javascriptExecuter(`${this.path}.children.length`);
                        i++
                    ) {
                        this.javascriptExecuter(
                            `${this.path}.children[${i}].show = ${
                                show ? show : !isOpen
                            }`
                        );
                    }
            }
        },
        fileAdded(fileName) {
            const name = fileName.split(".")[0];
            const extension = fileName.split(".")[1] ?? "";
            if (this.path) {
                const newFile = {
                    id: Date.now(),
                    parentId: this.path.id,
                    name: name,
                    displayName: name,
                    type: this.type,
                    extension: extension,
                    child: true,
                    children: [],
                    show: true,
                    open: false,
                    rename: false,
                    showNewFileInput: false,
                    content: "",
                };
                const newFileString = JSON.stringify(newFile);
                this.javascriptExecuter(
                    `${this.path}.children.push(${newFileString})`
                );
                this.javascriptExecuter(
                    `${this.path}.showNewFileInput = false`
                );
                if (newFile.type === "file")
                    this.$store.commit("documentService/openedFiles", [
                        ...this.openedFiles,
                        newFile,
                    ]);
                this.$store.commit("documentService/selectedFile", newFile);
                this.spliceAndCalculatePaths();
            }
        },
        toggleRename() {
            this.javascriptExecuter(`${this.path}.rename = true`);
            this.$emitter.emit("renameToggled");
        },
        rename(newName) {
            if (newName) {
                const name = newName.split(".")?.[0];
                const extension = newName.split(".")?.[1];
                if (name) {
                    this.javascriptExecuter(
                        `${this.path}.displayName = '${name}'`
                    );
                    this.javascriptExecuter(`${this.path}.name = '${name}'`);
                }
                this.javascriptExecuter(
                    `${this.path}.extension = '${extension ?? ""}'`
                );
                this.spliceAndCalculatePaths();
            }
            this.javascriptExecuter(`${this.path}.rename = false`);
        },
        calculateAllFileExtensionPaths(
            items,
            extension,
            displayName,
            parentPath
        ) {
            for (let i = 0; i < items.length; i++) {
                if (
                    items[i].type === "file" &&
                    items[i].extension === extension
                ) {
                    let directoryPath =
                        (displayName ? displayName + "/" : "") +
                        items[i].displayName +
                        "." +
                        items[i].extension;
                    let actualPath =
                        (parentPath ? parentPath : "") +
                        (items[i].child
                            ? `.children[${i}]`
                            : `${"this.items"}[${i}]`);
                    this.$store.commit("documentService/previewFiles", [
                        ...this.previewFiles,
                        {
                            directoryPath: directoryPath,
                            actualPath: actualPath,
                        },
                    ]);
                } else if (items[i].children) {
                    this.calculateAllFileExtensionPaths(
                        items[i].children,
                        extension,
                        items[i].displayName,
                        (parentPath ? parentPath : "") +
                            (items[i].child
                                ? `.children[${i}]`
                                : `${"this.items"}[${i}]`)
                    );
                }
            }
        },
        findPath(items, selectedFileId, itemString) {
            for (let i = 0; i < items.length; i++) {
                if (items[i].id == (selectedFileId || this.selectedItem.id)) {
                    return items[i].child
                        ? `.children[${i}]`
                        : `${itemString || "this.items"}[${i}]`;
                } else if (items[i].children) {
                    const child = this.findPath(
                        items[i].children,
                        selectedFileId,
                        itemString
                    );
                    if (child) {
                        return items[i].child
                            ? `.children[${i}]` + `${child}`
                            : `${itemString || "this.items"}[${i}]` +
                                  `${child}`;
                    }
                }
            }
        },
        deleteFile(customPath = null) {
            const path = customPath ? customPath : this.path;
            const item = this.javascriptExecuter(this.path);
            const parentArrayString = path.substr(0, path.length - 3);
            const deletionItemIndex = path.substr(-3)[1];
            this.javascriptExecuter(
                `${parentArrayString}.splice(${deletionItemIndex}, 1)`
            );
            this.toggleDeleteModal = false;
            if (item?.id == this.selectedItem?.id) {
                this.$store.commit("documentService/selectedFile", null);
                this.$store.commit("documentService/openedFiles", [
                    ...(this.openedFiles?.filter(
                        (file) => file.id != item.id
                    ) ?? []),
                ]);
                this.$store.commit(
                    "documentService/selectedFile",
                    this.openedFiles?.[0] ?? null
                );
            }
            this.spliceAndCalculatePaths();
        },
        saveNewFile() {
            if (this.sameNameError) return;
            const name = this.newFileNameInput.split(".")[0];
            const extension = this.newFileNameInput.split(".")[1];
            const newFile = {
                id: Date.now(),
                name: name,
                parentId: null,
                displayName: name,
                type: this.type,
                extension: extension,
                child: false,
                children: [],
                show: true,
                open: false,
                rename: false,
                showNewFileInput: false,
                content: "",
            };
            this.$store.commit("documentService/items", [
                ...this.items,
                newFile,
            ]);
            this.showNewFileInput = false;
            this.newFileNameInput = "";
            this.$emit("onAddFile", this.$data);
            if (newFile.type === "file")
                this.$store.commit("documentService/openedFiles", [
                    ...this.openedFiles,
                    newFile,
                ]);
            this.$store.commit("documentService/selectedFile", newFile);
            this.spliceAndCalculatePaths();
        },
        onAddFile(data) {
            this.type = data.type;
            if (
                document.getElementById("contextMenuRoot").style.display ==
                "block"
            ) {
                this.type = data.type;
                this.showNewFileInput = data.showNewFileInput;
                this.newFileNameInput = data.newFileNameInput;
                this.$nextTick(() => {
                    this.$refs.newFileInput?.focus();
                });
            }
            if (this.path && this.path !== "this.items") {
                this.toggleFolder(true);
                this.showNewFileInput = false;
                this.javascriptExecuter(`${this.path}.showNewFileInput = true`);
                this.$emitter.emit("addNewFileToggled");
            }
        },
        hideMenu() {
            document.getElementById("contextMenuRoot").style.display = "none";
        },
        onContextMenu(item, event) {
            event.preventDefault();
            this.path = "this.items";
            if (
                document.getElementById("contextMenuRoot").style.display ==
                "block"
            ) {
                this.hideMenu();
            } else {
                var menu = document.getElementById("contextMenuRoot");
                menu.style.display = "block";
                menu.style.left = +event.pageX - 90 + "px";
                menu.style.top = +event.pageY - 120 + "px";
            }
        },
    },
    data() {
        return {
            toggleDeleteModal: false,
            directoryPath: "",
            itemsTemp: [],
            saveItemsInterval: null,
            sameNameError: false,
            copiedItem: null,
            cutItem: null,
            path: "",
            cutPath: "",
            selectedItem: null,
            type: "",
            showNewFileInput: false,
            newFileNameInput: "",
        };
    },
};
</script>

<style scoped>
.options {
    border-bottom: 3px solid rgb(243, 243, 243);
}
.file-structure-node {
    max-height: 770px;
    overflow: auto;
}
.file-structure-node::-webkit-scrollbar {
    width: 3px;
    height: 3px;
}

.file-structure-node::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.file-structure-node::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}
.input-error {
    outline: none !important;
    background-color: #3d3c3d;
    border: 1px solid #e74343;
    color: #d9d9d9;
    width: -webkit-fill-available;
}
.new-input {
    outline: none !important;
    background-color: #3d3c3d;
    border: 1px solid #54a3d7;
    color: #d9d9d9;
    width: -webkit-fill-available;
}
.error-message-box {
    padding: 3px;
    margin-left: 8px;
    margin-right: 8px;
    border: 1px solid #e74343;
    border-top: none;
    background-color: #521313;
    height: auto;
    width: -webkit-fill-available;
}
:deep(.modal-header) {
    border-bottom: none;
}
</style>
