<template>
    <div>
        <div
            :class="['noSelect', item.child ? 'sub-item' : '']"
            v-for="item in items"
            :key="`item-${item.id}`"
        >
            <div
                @click.stop="toggleFolder(item)"
                :style="item.child ? 'margin-left: 3em' : ''"
                v-if="item.type === 'folder' && item.show && !item.rename"
                :class="`flex flex-col p-1 hover folder ${
                    item.open ? '' : 'folder-hover-color'
                } ${item.child ? 'ml-3' : ''} ${
                    item.id == (cutItem ? cutItem.id : false) ? 'item-cut' : ''
                }`"
            >
                <div
                    @contextmenu.prevent="onContextMenu(item, $event)"
                    :class="`flex folder-name ${
                        item.id == (selectedItem ? selectedItem.id : null)
                            ? 'selected-folder'
                            : ''
                    }`"
                >
                    <font-awesome-icon
                        :icon="[
                            item.open ? 'fa-chevron-down' : 'fa-chevron-down',
                            'fas',
                        ]"
                        :class="[
                            'self-center',
                            'ml-2',
                        ]"
                    ></font-awesome-icon>
                    <p class="ml-2 mb-0">{{ item.name }}</p>
                </div>
                <node
                    :selectedItem="selectedItem"
                    :cutItem="cutItem"
                    :items="item.children ? item.children : []"
                />
            </div>
            <div
                @dblclick="selectFile(item, $event)"
                @click.stop="selectFile(item, $event)"
                @contextmenu.prevent="onContextMenu(item, $event)"
                v-else-if="item.type === 'file' && item.show && !item.rename"
                :style="item.child ? 'margin-left: 3em' : ''"
                :class="[
                    'flex',
                    'p-1',
                    'hover',
                    'file',
                    item.child ? 'ml-3' : '',
                    item.id == (cutItem ? cutItem.id : false) ? 'item-cut' : '',
                    item.id == (selectedItem ? selectedItem.id : null)
                        ? 'selected-file'
                        : '',
                ]"
            >
                <i
                    v-if="item.extension == 'js'"
                    style="color: yellow; font-size: large"
                    class="bx bxl-javascript me-1"
                ></i>
                <i
                    v-else-if="item.extension == 'html'"
                    style="color: orange; font-size: large"
                    class="bx bxl-html5 me-1"
                ></i>
                <i
                    v-else-if="item.extension == 'css'"
                    style="color: lightblue; font-size: large"
                    class="bx bxl-css3 mr-1"
                ></i>
                <i
                    v-else
                    style="font-size: large"
                    class="bx bx-align-left mr-1"
                ></i>
                <p class="mb-0">
                    {{ item.displayName }}{{ item.extension ? "." : ""
                    }}{{ item.extension }}
                </p>
            </div>
            <input
                @click.stop=""
                v-if="item.type == 'folder' && item.showNewFileInput"
                id="newFileInput"
                @keypress.enter="saveNewFile"
                v-model="fileName"
                :class="[
                    sameNameError ? 'input-error' : 'new-input',
                    'mx-2',
                    'mr-4',
                ]"
                type="text"
            />
            <div
                v-if="item.showNewFileInput && sameNameError"
                class="error-message-box mx-2 mr-4"
            >
                <p class="m-0">
                    {{ $t("A file or folder") }} '{{ fileName }}'
                    {{
                        $t(
                            "already exists at this location. Please choose a different name."
                        )
                    }}
                </p>
            </div>
            <input
                @click.stop=""
                v-if="item.rename"
                id="renameInput"
                @keypress.enter="rename($event)"
                :value="
                    item.displayName +
                    (item.extension ? `.${item.extension}` : '')
                "
                class="new-input mx-2"
                type="text"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "node",
    data() {
        return {
            fileName: "",
            sameNameError: false,
        };
    },
    watch: {
        fileName(val) {
            const parentDirectory = this.selectedItem?.children;
            if (parentDirectory) {
                if (
                    parentDirectory?.some((item) => {
                        return (() => {
                            if (item.type == "folder") {
                                return item.name === this.fileName;
                            } else if (item.type == "file") {
                                return (
                                    item.name + "." + item.extension ===
                                    this.fileName
                                );
                            }
                        })();
                    })
                ) {
                    this.sameNameError = true;
                } else this.sameNameError = false;
            }
        },
    },
    mounted() {
        this.$emitter.on("renameToggled", () => {
            this.$nextTick(() => {
                const renameInput = document.querySelector("#renameInput");
                renameInput.focus();
                const value = renameInput.value;
                const dotIndex = value.indexOf(".");
                renameInput.selectionStart = 0;
                renameInput.selectionEnd = dotIndex;
            });
        });
        this.$emitter.on("addNewFileToggled", () => {
            this.$nextTick(() => {
                document.querySelector("#newFileInput").focus();
            });
        });
    },
    computed: {
        ...mapGetters("documentService", ["openedFiles", "selectedFile"]),
    },
    methods: {
        selectFile(item, event) {
            this.$store.commit("documentService/selectedFile", item);
            this.$emitter.emit("selected-item", { item: item });
            if (!this.openedFiles.some((file) => file.id == item.id)) {
                this.$store.commit("documentService/openedFiles", [
                    ...this.openedFiles,
                    { ...item },
                ]);
            }
            this.$nextTick(() => {
                document
                    .querySelector(
                        `#file-${this.selectedFile.id}-${this.selectedFile.name}___BV_tab_button__`
                    )
                    ?.scrollIntoView({
                        behavior: "smooth",
                        block: "nearest",
                        inline: "start",
                    });
            });
        },
        toggleFolder(item) {
            this.$emitter.emit("selected-item", { item: item });
        },
        saveNewFile() {
            if (this.sameNameError) return;
            this.$emitter.emit("fileAdded", this.fileName);
            this.fileName = "";
        },
        rename(event) {
            this.$emitter.emit("rename", event.target.value);
        },
        hideMenu() {
            document.getElementById("contextMenu").style.display = "none";
        },
        onContextMenu(item, event) {
            this.$emitter.emit("selected-item", item);
            if (
                document.getElementById("contextMenu").style.display == "block"
            ) {
                this.hideMenu();
            } else {
                var menu = document.getElementById("contextMenu");
                menu.style.display = "block";
                menu.style.left = +event.pageX - 90 + "px";
                menu.style.top = +event.pageY - 120 + "px";
            }
        },
    },
    props: {
        selectedItem: {
            type: Object,
            required: false,
            default: null,
        },
        items: {
            type: Array,
            required: false,
            default: () => [],
        },
        cutItem: {
            type: Object,
            required: false,
            default: null,
        },
    },
};
</script>

<style scoped>
.rotate90 {
    transform: rotate(90deg);
}

.folder {
    font-size: 1rem;
    padding: 0;
    margin: 0;
}

.folder-hover-color:hover {
    background-color: rgba(255, 255, 255, 0.15);
}

.file {
    font-size: 1rem;
    padding: 0;
    margin: 0;
}

.file:hover {
    background-color: rgba(255, 255, 255, 0.15);
}

.selected-file {
    background-color: rgba(255, 255, 255, 0.15);
}

.options {
    box-shadow: 0px 3px black;
}

.hover {
    cursor: pointer;
}

.sub-item {
    display: block;
}

.noSelect {
    -webkit-tap-highlight-color: transparent;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.noSelect:focus {
    outline: none !important;
}
.new-input {
    outline: none !important;
    background-color: #3d3c3d;
    border: 1px solid #54a3d7;
    color: #d9d9d9;
    width: -webkit-fill-available;
}
.item-cut {
    color: grey;
}
.input-error {
    outline: none !important;
    background-color: #3d3c3d;
    border: 1px solid #e74343;
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
.selected-folder {
    background: #c7e9ff;
}
</style>
