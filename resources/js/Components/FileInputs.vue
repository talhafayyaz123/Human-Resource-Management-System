<template>
    <!-- scroll area -->
    <div class="custom-dropdone">
        <div
            class="dropzone-area"
            v-if="!isReadonly"
            @drop="dropHandler"
            @dragover="dragOverHandler"
        >
            <div class="icon">
                <CustomIcon name="uploadIcon" />
            </div>
            <div class="text">
                <h3>{{$t('Select a file or drag and drop here')}}</h3>
            </div>
            <input
                id="hidden-input"
                type="file"
                ref="file"
                @change="setFiles"
                name="files[]"
                multiple
                class="hidden"
                :accept="accept"
            />
            <button class="primary-btn gap-2" type="button" @click="$refs.file.click()">
                <CustomIcon name="uploadsmalIcon" />
                {{ $t("Select File") }}
            </button>
        </div>
        <div class="mt-3 flex items-center gap-2" v-if="!isReadonly">
            <h3 class="card-title">{{ $t("To Upload") }}</h3>
            <CustomIcon name="iIcon" />
        </div>
        <div class="dropzone-list">
            <ul class="grid items-center grid-cols-2 gap-6 mt-3">
                <li v-if="form.files.length === 0">
                    <h3>{{$t("No Files Selected")}}</h3>
                </li>

                <li v-else
                    v-for="(file, index) in form.files"
                    :key="index"
                    tabindex="0"
                    class=""
                >
                    <div class="dropzone-box">
                        <div class="flex items-center gap-4">
                            <div class="icon">
                                <CustomIcon name="uploaddefaultIcon" />
                            </div>
                            <div>
                                <h3>{{ file.name }}</h3>
                                <p v-if="file.size" class="">
                                    {{
                                        file.size > 1024
                                            ? file.size > 1048576
                                                ? Math.round(
                                                      file.size / 1048576
                                                  ) + "MB"
                                                : Math.round(file.size / 1024) +
                                                  "KB"
                                            : file.size + "B"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex pb-2">
                            <img
                                v-if="showPreview && !!file?.base64"
                                :src="'data:image\/png;base64,' + file?.base64"
                                style="
                                    width: 55px !important;
                                    height: 40px !important;
                                    margin-right: 10px;
                                "
                            />
                            <!-- <span v-else class="p-1 text-blue-800">
                                <i>
                                    <svg
                                        class="fill-current w-4 h-4 ml-auto pt-0"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z"
                                        />
                                    </svg>
                                </i>
                            </span> -->

                            <button
                                v-if="!isReadonly"
                                @click="removeFile(index)"
                                class="delete"
                                type="button"
                            >
                                <CustomIcon name="DeleteIcon" />
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["fileChanged", "removeFile"],
    props: {
        productFiles: Object,
        removeInParent: {
            type: Boolean,
            default: false,
        },
        accept: {
            type: String,
            default: "",
        },
        isReadonly: {
            type: Boolean,
            default: false,
        },
        showPreview: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            form: this.productFiles,
        };
    },
    watch: {
        productFiles: {
            handler: function (val) {
                this.form = { ...val };
            },
            deep: true,
        },
    },
    methods: {
        dropHandler(event) {
            event.preventDefault();
            if (event.dataTransfer.items) {
                // Use DataTransferItemList interface to access the file(s)
                for (var i = 0; i < event.dataTransfer.items.length; i++) {
                    const item = event.dataTransfer.items[i];
                    // If dropped items aren't files, reject them
                    if (item.kind === "file") {
                        const file = item.getAsFile();
                        //check if array already has the file so no duplication occurs
                        if (
                            this.form.files.filter((e) => e.name === file.name)
                                .length > 0
                        ) {
                            continue;
                        }
                        let getFile = file;
                        this.form.files.push(getFile);
                    }
                }
            }
            this.$emit("fileChanged", this.form.files);
        },
        dragOverHandler(event) {
            event.preventDefault();
        },
        /**
         * removes the file from the files array and emits 'removeFile' event
         * if 'removeInParent' is set then the file will be removed in the parent component
         * @param {index} index of the file to be removed
         */
        removeFile(index) {
            if (!this.removeInParent) {
                this.form.files.splice(index, 1);
            }
            this.$emit("fileChanged", this.form.files);
            this.$emit("removeFile", index);
        },
        setFiles(event) {
            const file = event.target.files;
            if (file.length === 0) {
                return false;
            }
            for (var i = 0; i < file.length; i++) {
                //check if array already has the file so no duplication occurs
                if (
                    this.form.files.filter((e) => e.name === file[i].name)
                        .length > 0
                ) {
                    continue;
                }
                let getFile = file[i];
                this.form.files.push(getFile);
            }
            this.$emit("fileChanged", this.form.files);
        },
    },
};
</script>

<style></style>
