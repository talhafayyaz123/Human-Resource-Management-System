<template>
    <div class="">
        <div class="flex item-center justify-end">
            <button
                type="button"
                @click="toggleProductsModal()"
                class="secondary-btn"
            >
                <span>{{ $t("Add Mail") }}</span>
            </button>
        </div>
        <div class="table-responsive mt-3">
            <div>
                <table class="doc-table">
                    <thead>
                        <tr class="text-left">
                            <th>
                                {{ $t("Mail") }}
                            </th>
                            <th>
                                {{ $t("Action") }}
                            </th>
                        </tr>
                    </thead>

                    <tbody v-for="(mail, index) in mails" :key="index">
                        <tr class="text-left">
                            <td>
                                <div class="">
                                    {{ mail.mail }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-x-2">
                                    <button
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </button>
                                    <button
                                        @click.stop="destroy(mail.id)"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-trash-can"
                                        />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("mailMails", ["mails"]),
    },
    components: {
        SelectInput,
        TextAreaInput,
        NumberInput,
    },
    data() {
        return {
            start: 0,
            perPage: 50,
            productToggle: false,
            products: [],
            selectedProducts: [],
            additionalDescriptionToggled: {},
        };
    },
    async mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            await this.$store.dispatch("mailMails/list"), {
                limit_count: this.perPage,
                limit_start: this.start
            };
        },
        async destroy(id) {
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
                    await this.$store.dispatch("mailMails/destroy", id);
                    this.refresh(true);
                }
            });
        },},
};
</script>

<style scoped lang="scss">
td > :only-child {
    max-width: none !important;
    overflow: none !important;
    text-overflow: none;
    white-space: nowrap;
    display: block;
    padding: 0px;
}

/* break text on hover */
td > :only-child:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
.table-align {
    width: 250px !important;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    &:hover {
        text-overflow: inherit;
        white-space: wrap;
    }
}
.input-width {
    width: 100px !important;
}
</style>
