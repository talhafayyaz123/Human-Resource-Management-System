<template>
    <button
        @click="toggleAccordion('ticketComments')"
        class="accordion-button rounded-md shadow"
    >
        <span
            class="accordion-header-icon"
            :class="{
                'accordion-header-icon--open':
                    isAccordionOpen('ticketComments'),
            }"
        ></span>
        <span class="accordion-header-text">{{ $t("Tickets") }}</span>
    </button>
    <div
        :class="{ 'accordion-content': true }"
        v-show="isAccordionOpen('ticketComments')"
    >
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6 compact-cell">{{ $t("Date") }}</th>
                <th class="pb-4 pt-6 px-6">
                    {{ $t("Person/Reference") }}
                </th>
                <th class="pb-4 pt-6 px-6">
                    {{ $t("Activity Description") }}
                </th>
                <th class="pb-4 pt-6 px-6 compact-cell">
                    <p>{{ $t("Quantity") }} / {{ $t("Unit") }}</p>
                </th>
                <th class="pb-4 pt-6 px-6 compact-cell">
                    {{ $t("Goodwill") }}
                </th>
                <th class="pb-4 pt-6 px-6 compact-cell">{{ $t("Action") }}</th>
            </tr>
            <draggable
                :list="ticketComments"
                group="ticketAccordion"
                @change="recordOrderChanged"
                item-key="id"
                tag="tbody"
            >
                <template
                    v-for="comment in ticketComments"
                    v-slot:item="{ element: comment }"
                >
                    <tr
                        :class="
                            comment.timeCheckingStatus !== null
                                ? comment.timeCheckingStatus == 1
                                    ? 'text-green-700'
                                    : 'text-red-700'
                                : ''
                        "
                        :key="comment.id"
                    >
                        <!-- Ticket content here -->
                        <td>
                            {{ $dateFormatter(comment.date, globalLanguage) }}
                        </td>
                        <td class="description-cell">
                            {{ comment.person }} / {{ $t("Ticket") }}
                            {{ comment?.ticketNumber }}
                        </td>
                        <td class="description-cell">
                            {{ $t(comment.description) }}
                        </td>
                        <td>
                            {{ replace(comment.quantity) }} {{ $t("Hours") }}
                        </td>
                        <td>
                            {{ comment.isGoodwill == 0 ? $t("No") : $t("Yes") }}
                        </td>
                        <td>
                            <button
                                class="pl-5 pt-5"
                                @click="$emit('edit-entry', comment)"
                            >
                                <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                            </button>
                        </td>
                    </tr>
                </template>
            </draggable>
        </table>
    </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
    components: {
        draggable,
    },
    emits: ["toggle-accordion", "edit-entry) "], // Declare the emitted event
    props: {
        ticketComments: {
            type: Array,
            default: () => [],
        },
        openAccordions: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        /**
         * triggered when the order for the table record entry is changed using drag
         * we run the 'change-order' API for performance record when the order changes
         * @param {event} order change event
         */
        async recordOrderChanged(event) {
            if (event.moved) {
                try {
                    const movedIndex = event.moved.newIndex;
                    const movedEntry = this.ticketComments[movedIndex];
                    const previousEntry = this.ticketComments[movedIndex - 1];
                    const nextEntry = this.ticketComments[movedIndex + 1];

                    const data = {
                        id: event.moved.element.id,
                        data: {
                            lowerOrder: previousEntry?.order,
                            upperOrder: nextEntry?.order,
                        },
                    };

                    if (movedIndex === 0) {
                        delete data.data.lowerOrder;
                    } else if (!nextEntry) {
                        delete data.data.upperOrder;
                    }

                    const response = await this.$store.dispatch(
                        "performanceRecords/changeOrder",
                        data
                    );

                    movedEntry.order = response?.data?.order;
                } catch (e) {
                    console.log(e);
                }
            }
        },
        toggleAccordion(key) {
            this.$emit("toggle-accordion", key);
        },
        replace(string) {
            try {
                return this.globalLanguage === "de"
                    ? parseFloat(string).toFixed(2).replaceAll(".", ",")
                    : parseFloat(string).toFixed(2);
            } catch (e) {
                return string;
            }
        },
        isAccordionOpen(key) {
            return this.openAccordions.includes(key);
        },
    },
};
</script>

<style scoped>
.accordion-button {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 10px;
    background-color: #ffffff;
    color: var(--secondary-color);
    font-weight: bold;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.accordion-button:hover {
    background-color: #f0f0f0;
}

.accordion-header-text {
    margin-left: 10px;
}

.accordion-header-icon {
    width: 10px;
    height: 10px;
    border-width: 1px;
    border-style: solid;
    border-color: #4a5568;
    border-top-color: transparent;
    border-right-color: transparent;
    transition: transform 0.2s ease-in-out;
}

.accordion-header-icon--open {
    transform: rotate(180deg);
}

.accordion-content {
    padding: 10px;
    border: 1px solid #e2e8f0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

tr:last-child td {
    border-bottom: none;
}

td.description-cell {
    word-wrap: break-word;
    white-space: pre-wrap;
}

th.compact-cell {
    width: 10%;
}
</style>
