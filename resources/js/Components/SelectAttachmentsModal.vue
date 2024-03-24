<template>
    <div>
        <div
            class="custom-modal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-overlay"></div>

            <div class="modal-content">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div class="modal-bg modal-md">
                        <div class="modal-header">
                            <div>
                                <h2 class="">
                                    {{ $t("Add Attachment") }}
                                </h2>
                                <slot name="warning"></slot>
                            </div>
                            <div
                                class="cursor-pointer"
                                @click="onCancel"
                            >
                                <CustomIcon name="xCircleIcon" />
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="doc-table">
                                    <thead>
                                        <tr>
                                            <th class="">
                                                {{ $t("Name") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Software") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Contract Type") }}
                                            </th>

                                            <th class="">
                                                {{ $t("Prefix") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Version") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Created At") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                attachment, index
                                            ) in attachments?.data ?? []"
                                            :key="attachment.id"
                                            class=""
                                        >
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    <input
                                                        @click="
                                                            onSelected(
                                                                $event,
                                                                attachment,
                                                                index
                                                            )
                                                        "
                                                        type="checkbox"
                                                        class="border-gray-300 rounded h-4 w-4 self-center"
                                                    />
                                                    &nbsp;<span
                                                        class="self-center"
                                                        >{{
                                                            attachment.name
                                                        }}</span
                                                    >
                                                </a>
                                            </td>
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    {{
                                                        attachment.software
                                                            ?.name ?? ""
                                                    }}
                                                </a>
                                            </td>
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    {{
                                                        attachment.contractType
                                                            ?.name ?? ""
                                                    }}
                                                </a>
                                            </td>
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    {{ attachment.prefix }}
                                                </a>
                                            </td>
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    {{ attachment.version }}
                                                </a>
                                            </td>
                                            <td class="">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center"
                                                >
                                                    {{
                                                        $dateFormatter(
                                                            attachment.createdAt,
                                                            globalLanguage
                                                        )
                                                    }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                (attachments?.data.length ??
                                                    0) === 0
                                            "
                                        >
                                            <td class="" colspan="4">
                                                {{
                                                    $t("No attachments found")
                                                }}.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4">
                                <pagination
                                    :limit="10"
                                    align="center"
                                    :data="attachments"
                                    @pagination-change-page="searchAttachments"
                                ></pagination>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                @click="onCancel"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                type="button"
                                class="secondary-btn"
                                @click="onAdded"
                            >
                                {{ $t("Add") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";

export default {
    name: "SelectAttachmentsModal",
    emits: ["selected", "cancelled"],
    computed: {
        ...mapGetters("attachments", ["attachments"]),
    },
    props: {
        contractTypeId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            page: 1,
            selectedAttachments: [],
        };
    },
    components: {
        pagination,
    },
    unmounted() {
        this.$store.commit("attachments/attachments", {
            data: [],
            links: [],
        });
    },
    methods: {
        onSelected(event, item, index) {
            if (event.target.checked) {
                this.selectedAttachments.push({ ...item });
            } else {
                this.selectedAttachments.splice(index, 1);
            }
        },
        onAdded() {
            this.$emit("selected", this.selectedAttachments);
        },
        onCancel() {
            this.$emit("cancelled");
        },
        /**
         * sets the products in dataProducts by hitting the products list API with filters
         */
        async filterAttachments() {
            try {
                await this.$store.dispatch("attachments/list", {
                    page: this.page,
                    contractTypeId: this.contractTypeId,
                });
            } catch (e) {
                console.log(e);
            }
        },
        searchAttachments(page = 1) {
            this.page = page;
            setTimeout(async () => {
                this.filterAttachments();
            }, 100);
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
.margin-top-3rem {
    margin-top: 3rem;
}
</style>
