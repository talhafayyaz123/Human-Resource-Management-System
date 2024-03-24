<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Upload File") }}</h3>
        </div>
        <div class="card-body">
            <input ref="fileInput" @change="fileAdded" type="file" />
            <button @click="uploadFile" class="secondary-btn mt-3">
                Upload
            </button>
        </div>
    </div>
    <div class="mt-3">
        <h3 class="card-title">
            {{ $t("Document and Contracts") }}
        </h3>
        <div class="table-responsive mt-2">
            <table class="doc-table">
                <tr class="text-left">
                    <th class="">{{ $t("File Name") }}</th>
                    <th class="">{{ $t("Size") }}</th>
                    <th class="">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="(item, index) in documentAndContact"
                    :key="'file-' + index"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        {{ item.fileName }}
                    </td>
                    <td class="">
                        {{ item.size }}
                    </td>
                    <td class="w-px">
                        <button @click="destroy(item.storedFileName)">
                            <font-awesome-icon
                                icon="fa-regular fa-trash-can py-4"
                            />
                        </button>
                    </td>
                </tr>
                <tr v-if="(documentAndContact?.length ?? 0) === 0">
                    <td class="" colspan="4">
                        {{ $t("No Documents found") }}.
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <!-- <div @click="backButton()" class="primary-btn cursor-pointer me-3">
                <span class="mr-1">
                    <CustomIcon name="backIcon" />
                </span>
                <span>{{ $t("Back") }}</span>
            </div> -->
        <loading-button @click="$emit('next', true)" class="secondary-btn">
            <span class="mr-1">
                <CustomIcon name="nextIcon" />
            </span>
            {{ $t("Next") }}
        </loading-button>
    </div>
</template>

<script>
export default {
    inject: ["userId"],
    data() {
        return {
            documentAndContact: [],
            form: new FormData(),
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "userProfile/getDocumentAndContract",
                    {
                        userProfileId: this.userId,
                    }
                );
                this.documentAndContact =
                    response?.data?.documentAndContact ?? [];
            } catch (e) {}
        },
        fileAdded(event) {
            const file = event?.target?.files?.[0];
            this.form.append("file", file);
            this.form.append("userProfileId", this.userId);
        },
        async uploadFile() {
            if (this.form.get("file")) {
                try {
                    await this.$store.dispatch(
                        "userProfile/uploadDocumentAndContract",
                        this.form
                    );
                    this.$refs.fileInput.value = null;
                    this.refresh();
                } catch (e) {
                    console.log(e);
                }
            }
        },
        async destroy(fileName) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "userProfile/deleteDocumentAndContract",
                            {
                                storedFileName: fileName,
                            }
                        );
                        this.refresh();
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

<style scoped></style>
