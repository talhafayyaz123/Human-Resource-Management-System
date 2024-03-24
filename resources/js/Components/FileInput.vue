<template>
    <div>
        <label class="form-label">
            <span v-if="required" style="color: red">*</span>&nbsp;{{ label }}
        </label>
        <input
            :id="'file_upload_' + id"
            ref="fileInput"
            v-bind="{ ...$attrs, class: null }"
            class="hidden"
            type="file"
            multiple
            @change="handleFileUpload"
        />
        <div
            @click="this.$refs.fileInput.click()"
            class="flex items-center justify-center py-2.5 px-4 w-full text-sm text-gray-900 bg-white border-2 border-gray-300 rounded-lg cursor-pointer"
        >
            <svg
                class="w-5 h-5 text-gray-500 mr-2"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M3 8c0-1.1.9-2 2-2h4V4c0-.55.45-1 1-1h2c.55 0 1 .45 1 1v2h4c1.1 0 2 .9 2 2v7c0 1.1-.9 2-2 2H5a2 2 0 0 1-2-2V8zm4 0c0 .55.45 1 1 1h4c.55 0 1-.45 1-1V4h-2v2H8V4H6v4zm7 2v5h-4v-5H8l4-4 4 4h-3z"
                    clip-rule="evenodd"
                />
            </svg>
            <label :for="'file_upload_' + id" class="cursor-pointer">
                {{ $t("Select Files") }}
            </label>
        </div>
        <div class="uploaded-files-container mt-2">
            <div
                v-for="(file, index) in selectedFiles"
                :key="index"
                class="uploaded-file flex items-center"
            >
                <span class="file-name text-gray-700">{{ file.name }}</span>
                <svg
                    class="delete-icon ml-2 cursor-pointer"
                    @click="deleteFile(index)"
                    role="button"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                >
                    <path
                        class="fill-current"
                        d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm4.586 13.414l-1.172 1.172L12 13.172l-3.414 3.414-1.172-1.172L10.828 12 7.414 8.586l1.172-1.172L12 10.828l3.414-3.414 1.172 1.172L13.172 12l3.414 3.414z"
                    />
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
    props: {
        modelValue: Array, // Updated prop to accept an array for multiple files
        label: String,
        id: {
            type: String,
            default() {
                return `file-input-${uuid()}`;
            },
        },
        required: {
            default: false,
            type: Boolean,
        },
    },
    data() {
        return {
            selectedFiles: [], // Array to store the selected files
        };
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files;
            if (file.length === 0) {
                return false;
            }
            for (var i = 0; i < file.length; i++) {
                //check if array already has the file so no duplication occurs
                if (
                    this.selectedFiles.filter((e) => e.name === file[i].name)
                        .length > 0
                ) {
                    continue;
                }
                let getFile = file[i];
                this.selectedFiles.push(getFile);
            }
            this.$emit("update:modelValue", this.selectedFiles);
        },
        deleteFile(index) {
            this.selectedFiles.splice(index, 1);
        },
    },
};
</script>
<style>
.uploaded-files-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.uploaded-file {
    background-color: #f0f0f0;
    padding: 8px;
    border-radius: 4px;
    display: flex;
    align-items: center;
}

.uploaded-file svg {
    cursor: pointer;
}

.delete-icon {
    fill: #ff0000;
    width: 16px;
    height: 16px;
}

.delete-icon:hover {
    fill: #aa0000;
}
</style>
