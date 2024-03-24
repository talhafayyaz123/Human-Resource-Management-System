<template>
    <div :id="id" class="context-menu-custom" style="display: none">
        <ul class="menu">
            <li
                @click="addNewFile('file')"
                v-if="selectedItemType == 'folder' || id == 'contextMenuRoot'"
                class="rename"
            >
                <a href="#">
                    <div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("New File") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+N</p>
                    </div>
                </a>
            </li>
            <li
                @click="addNewFile('folder')"
                v-if="selectedItemType == 'folder' || id == 'contextMenuRoot'"
                class="rename"
            >
                <a href="#">
                    <div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("New Folder") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+Shift+N</p>
                    </div>
                </a>
            </li>
            <li
                @click="$emit('openFile')"
                v-if="selectedItemType == 'file' && id == 'contextMenu'"
                class="rename"
            >
                <a href="#">
                    <div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("Open") }}</p>
                        <p class="mr-4 p-0 mb-0"> {{ $t("Click") }} </p>
                    </div>
                </a>
            </li>
            <hr class="m-0" />
            <li
                v-if="id === 'contextMenu'"
                @click="$emit('copyItem')"
                class="rename"
            >
                <a href="#"
                    ><div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("Copy") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+C</p>
                    </div></a
                >
            </li>
            <li
                v-if="id === 'contextMenu'"
                @click="$emit('cutItem')"
                class="rename"
            >
                <a href="#"
                    ><div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("Cut") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+X</p>
                    </div></a
                >
            </li>
            <li
                v-if="selectedItemType == 'folder' || path == 'this.items'"
                @click="$emit('pasteItem')"
                :class="['rename', shouldPaste ? '' : 'disabled']"
            >
                <a :class="[shouldPaste ? '' : 'disabled']" href="#"
                    ><div class="flex justify-content-between w-full">
                        <p class="p-0 mb-0">{{ $t("Paste") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+V</p>
                    </div></a
                >
            </li>
            <hr v-if="id === 'contextMenu'" class="m-0" />
            <li
                v-if="id === 'contextMenu'"
                @click="$emit('toggleRename')"
                class="rename"
            >
                <a href="#"
                    ><div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("Rename") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+R</p>
                    </div></a
                >
            </li>
            <li
                v-if="id === 'contextMenu'"
                @click="deleteFileOrFolder"
                class="trash"
            >
                <a href="#"
                    ><div class="flex justify-between w-full">
                        <p class="p-0 mb-0">{{ $t("Delete") }}</p>
                        <p class="mr-4 p-0 mb-0">Ctrl+Delete</p>
                    </div></a
                >
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ["id", "hideMenu", "shouldPaste", "path"],
    data() {
        return {
            selectedItemType: "folder",
            type: "",
            showNewFileInput: false,
            newFileNameInput: "",
            items: [],
        };
    },
    mounted() {
        this.$emitter.on("selected-item", (data) => {
            this.selectedItemType = data.type;
        });
        document.addEventListener("click", () => {
            document.getElementById(this.id).style.display = "none";
        });
    },
    methods: {
        deleteFileOrFolder() {
            this.$emit("deleteFile");
        },
        addNewFile(type) {
            this.type = type;
            this.newFileNameInput = "";
            this.showNewFileInput = true;
            this.$emit("onAddFile", this.$data);
        },
    },
};
</script>

<style scoped>
ul {
    list-style: none;
}
.context-menu-custom {
    position: absolute;
    z-index: 999;
}
.menu {
    display: flex;
    flex-direction: column;
    background-color: #3d3c3d;
    border-radius: 0px;
    box-shadow: 0 1px 2px black;
    padding: 10px 0;
    width: 270px;
}
.menu > li > a {
    font: inherit;
    border: 0;
    padding: 4px 0px 4px 25px;
    width: 100%;
    display: flex;
    align-items: center;
    position: relative;
    text-decoration: unset;
    color: rgb(237, 237, 237);
}
.menu > li > a:hover {
    background: #084770;
}
.menu > li > a > i {
    padding-right: 10px;
}
.new-input {
    outline: none !important;
    background-color: #3d3c3d;
    border: 1px solid #54a3d7;
    color: #d9d9d9;
    width: -webkit-fill-available;
}
.disabled {
    pointer-events: none;
    color: rgb(160, 160, 160) !important;
}
</style>
