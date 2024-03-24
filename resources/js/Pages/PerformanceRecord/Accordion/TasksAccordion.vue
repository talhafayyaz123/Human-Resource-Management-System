<template>
    <button
        @click="toggleAccordion('tasks')"
        class="accordion-button rounded-md shadow"
    >
        <span
            class="accordion-header-icon"
            :class="{
                'accordion-header-icon--open': isAccordionOpen('tasks'),
            }"
        ></span>
        <span class="accordion-header-text">{{ $t("Tasks") }}</span>
    </button>
    <div
        :class="{ 'accordion-content': true }"
        v-show="isAccordionOpen('tasks')"
    >
        <table class="w-full whitespace-nowrap">
            <tbody>
                <tr v-for="project in tasks" :key="project.projectId">
                    <td>
                        <button
                            @click="
                                toggleAccordion('project_' + project.projectId)
                            "
                            class="accordion-button rounded-md shadow"
                        >
                            <span
                                class="accordion-header-icon"
                                :class="{
                                    'accordion-header-icon--open':
                                        isAccordionOpen(
                                            'project_' + project.projectId
                                        ),
                                }"
                            ></span>
                            <span class="accordion-header-text">{{
                                $t(project.name)
                            }}</span>
                        </button>
                        <div
                            :class="{
                                'accordion-content': true,
                                hidden: !isAccordionOpen(
                                    'project_' + project.projectId
                                ),
                            }"
                        >
                            <!-- Milestones Accordion -->
                            <table class="w-full">
                                <tbody
                                    v-for="milestone in project.milestones"
                                    :key="milestone.milestoneId"
                                >
                                    <tr>
                                        <td>
                                            <button
                                                @click="
                                                    toggleAccordion(
                                                        'milestone_' +
                                                            milestone.milestoneId
                                                    )
                                                "
                                                class="accordion-button rounded-md shadow"
                                            >
                                                <span
                                                    class="accordion-header-icon"
                                                    :class="{
                                                        'accordion-header-icon--open':
                                                            isAccordionOpen(
                                                                'milestone_' +
                                                                    milestone.milestoneId
                                                            ),
                                                    }"
                                                ></span>
                                                <span
                                                    class="accordion-header-text"
                                                    >{{
                                                        $t(milestone.name)
                                                    }}</span
                                                >
                                            </button>
                                            <div
                                                :class="{
                                                    'accordion-content': true,
                                                    hidden: !isAccordionOpen(
                                                        'milestone_' +
                                                            milestone.milestoneId
                                                    ),
                                                }"
                                            >
                                                <!-- Tasks Records -->
                                                <table
                                                    class="w-full whitespace-nowrap"
                                                >
                                                    <tr
                                                        class="text-left font-bold"
                                                    >
                                                        <th
                                                            class="pb-4 pt-6 px-6 compact-cell"
                                                        >
                                                            {{ $t("Date") }}
                                                        </th>
                                                        <th
                                                            class="pb-4 pt-6 px-6"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Person/Reference"
                                                                )
                                                            }}
                                                        </th>
                                                        <th
                                                            class="pb-4 pt-6 px-6"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Activity Description"
                                                                )
                                                            }}
                                                        </th>
                                                        <th
                                                            class="pb-4 pt-6 px-6 compact-cell"
                                                        >
                                                            <p>
                                                                {{
                                                                    $t(
                                                                        "Quantity"
                                                                    )
                                                                }}
                                                                /
                                                                {{ $t("Unit") }}
                                                            </p>
                                                        </th>
                                                        <th
                                                            class="pb-4 pt-6 px-6 compact-cell"
                                                        >
                                                            {{ $t("Goodwill") }}
                                                        </th>
                                                        <th
                                                            class="pb-4 pt-6 px-6 compact-cell"
                                                        >
                                                            {{ $t("Action") }}
                                                        </th>
                                                    </tr>
                                                    <draggable
                                                        :list="milestone.tasks"
                                                        :group="
                                                            taskAccordionGroup(
                                                                milestone.milestoneId
                                                            )
                                                        "
                                                        @change="
                                                            recordOrderChanged(
                                                                $event,
                                                                milestone.tasks ??
                                                                    []
                                                            )
                                                        "
                                                        item-key="id"
                                                        tag="tbody"
                                                    >
                                                        <template
                                                            v-for="task in milestone.tasks"
                                                            v-slot:item="{
                                                                element: task,
                                                            }"
                                                        >
                                                            <tr
                                                                :class="
                                                                    task.timeCheckingStatus !==
                                                                    null
                                                                        ? task.timeCheckingStatus ==
                                                                          1
                                                                            ? 'text-green-700'
                                                                            : 'text-red-700'
                                                                        : ''
                                                                "
                                                                :key="
                                                                    task.taskId
                                                                "
                                                            >
                                                                <!-- Task Record Content -->
                                                                <td>
                                                                    {{
                                                                        $dateFormatter(
                                                                            task.date,
                                                                            globalLanguage
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        task.person
                                                                    }}
                                                                </td>
                                                                <td
                                                                    class="description-cell"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            task.description
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        replace(
                                                                            task.quantity
                                                                        )
                                                                    }}
                                                                    {{
                                                                        $t(
                                                                            "Hours"
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        task.isGoodwill ==
                                                                        0
                                                                            ? $t(
                                                                                  "No"
                                                                              )
                                                                            : $t(
                                                                                  "Yes"
                                                                              )
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    <button
                                                                        class="pl-5 pt-5"
                                                                        @click="
                                                                            $emit(
                                                                                'edit-entry',
                                                                                task
                                                                            )
                                                                        "
                                                                    >
                                                                        <font-awesome-icon
                                                                            icon="fa-regular fa-pen-to-square"
                                                                        ></font-awesome-icon>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </draggable>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
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
        tasks: {
            type: Array,
            default: () => [],
        },
        openAccordions: {
            type: Array,
            default: () => [],
        },
    },
    computed: {
        taskAccordionGroup() {
            return (milestoneId) => "taskAccordion" + milestoneId;
        },
    },
    methods: {
        /**
         * triggered when the order for the table record entry is changed using drag
         * we run the 'change-order' API for performance record when the order changes
         * @param {event} order change event
         */
        async recordOrderChanged(event, tasks) {
            if (event.moved) {
                try {
                    const movedIndex = event.moved.newIndex;
                    const movedEntry = tasks[movedIndex];
                    const previousEntry = tasks[movedIndex - 1];
                    const nextEntry = tasks[movedIndex + 1];

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
    text-align: left;
}

/* Remove border-bottom for td elements in the first nested table */
.accordion-content > table:not(:first-child) td {
    border-bottom: none;
}

/* Remove border-bottom for td elements in the second nested table */
.accordion-content table table td {
    border-bottom: none;
}

/* Add border-bottom to the td elements in the third nested table */
.accordion-content table table table td {
    border-bottom: 1px solid #e2e8f0;
}

/* Remove border-bottom for the last td element in the third nested table */
.accordion-content table table table tr:last-child td {
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
