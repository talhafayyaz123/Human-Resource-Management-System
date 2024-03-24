<template>
    <div>
        <div class="table-responsive mt-3">
            <table class="doc-table">
                <tr class="text-left">
                    <th
                        style="width: 22%"
                        @click="sort('offerNumber', 'offers')"
                        class=""
                    >
                        {{
                            $t(
                                `${
                                    type === "offer"
                                        ? "Offer"
                                        : "Offer Confirmation"
                                } Number`
                            )
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'offerNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        style="width: 25%"
                        @click="sort('company', 'offers')"
                        class=""
                    >
                        {{ $t("Customer") + "/" + $t("Lead")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'company'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        style="width: 20%"
                        @click="
                            sort(
                                type === 'offer'
                                    ? 'offerStatus'
                                    : 'orderStatus',
                                'offers'
                            )
                        "
                        class=""
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="
                                form.sortBy ===
                                (type === 'offer'
                                    ? 'offerStatus'
                                    : 'orderStatus')
                            "
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        style="width: 15%"
                        @click="sort('identifier', 'offers')"
                        class=""
                    >
                        {{ $t("Identifier")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'identifier'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        style="width: 25%"
                        @click="sort('totalNetto', 'offers')"
                        class=""
                    >
                        {{ $t("Netto Total")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'totalNetto'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                </tr>
                <tr
                    v-for="offer in listing?.data ?? []"
                    :key="offer.id"
                    :style="`background-color: ${
                        offer.offerStatus === 'beauftragt'
                            ? 'rgba(105, 255, 152, 0.3)'
                            : offer.offerStatus === 'abgelehnt'
                            ? 'rgba(255, 84, 41, 0.3)'
                            : ''
                    }`"
                    @click="$router.push(`/offers/${offer.id}/edit`)"
                >
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center "
                        >
                            {{ offer.offerNumber }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center "
                        >
                            {{ offer.company }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center "
                            tabindex="-1"
                        >
                            {{
                                type === "offer"
                                    ? offer.offerStatus
                                    : offer.orderStatus
                            }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center "
                            tabindex="-1"
                        >
                            {{ offer.identifier }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center "
                            tabindex="-1"
                        >
                            {{
                                $formatter(
                                    offer?.totalNetto.toFixed(2),
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                </tr>
                <tr v-if="(listing?.data?.length ?? 0) === 0">
                    <td class=" " colspan="4">
                        {{
                            $t(
                                `No ${
                                    type === "offer"
                                        ? "offers"
                                        : "offer comfirmations"
                                } found`
                            )
                        }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="listing"
                @pagination-change-page="pageChanged($event)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";

export default {
    emits: ["changePage"],
    props: {
        listing: {
            type: Object,
            required: true,
            default: () => ({
                data: [],
                links: [],
            }),
        },
        type: {
            type: String,
            default: "offer",
        },
    },
    watch: {
        form: {
            handler: function () {
                this.$emit("changePage", this.form);
            },
            deep: true,
        },
    },
    methods: {
        /**
         * triggered when page is changed in pagination
         * @param {page} pagination page number
         */
        pageChanged(page) {
            this.form.page = page;
        },
    },
    data() {
        return {
            form: {
                page: 1,
                sortBy: "offerNumber",
                sortOrder: "asc",
            },
        };
    },
    components: {
        Pagination,
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
table {
    font-size: 0.7rem;
}
td > :only-child {
    max-width: 30ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}
td > :only-child:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
</style>
