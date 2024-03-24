<template>
    <div class="card my-5">
        <div class="card-header flex items-center gap-2">
          <h3 class="card-title">{{ $t("Config File") }}</h3>
          <CustomIcon name="iIcon" />
        </div>
        <div class="card-body">
            <file-inputs
                @removeFile="removeFile"
                @file-changed="addFiles"
                :productFiles="form"
                :removeInParent="true"
                :accept="'application/json'"
            />
        </div>
    </div>
    
</template>

<script>
import FileInputs from "@/Components/FileInputs.vue";

export default {
    props: {
        id: {
            type: Number,
            required: true,
        },
        files: {
            type: Array,
            default: () => [],
        },
    },
    components: {
        FileInputs,
    },
    mounted() {
        this.syncFormFilesWithFiles();
    },
    watch: {
        files() {
            this.syncFormFilesWithFiles();
        },
    },
    data() {
        return {
            form: {
                files: [],
            },
        };
    },
    methods: {
        async removeFile(index) {
            try {
                await this.$store.dispatch(
                    "workshopTemplates/deleteFile",
                    this.form.files?.[index]?.id
                );
                // remove the file after API is successful
                this.form.files.splice(index, 1);
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * syncs the values of form files to files prop
         */
        syncFormFilesWithFiles() {
            this.form.files = this.files;
        },
        /**
         * calls the upload file API for the workshop template
         * triggered when the user uploads a file
         * @param {files} the files being uploaded
         */
        async addFiles(files) {
            try {
                const formData = new FormData();
                formData.append("id", this.id);
                files.forEach((file, index) => {
                    // skip the files that are already uploaded
                    if (!file.id) {
                        formData.append(`files[${index}]`, file);
                    }
                });
                const response = await this.$store.dispatch(
                    "workshopTemplates/uploadFiles",
                    formData
                );
                // sync the files with the latest files received in response
                // we need to do this because the if we delete a file, we need the id for that file
                // so we need to get the latest created files
                this.form.files = response?.data?.files ?? files;
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
