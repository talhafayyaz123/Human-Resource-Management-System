<template>
    <draggable
        :style="
            'grid-template-' +
            layoutType() +
            's: ' +
            sectionDivision() +
            (sectionDivision()
                ? ';' +
                  (layout.id === 'main-container'
                      ? 'min-height: 200px'
                      : 'height: 100%')
                : '') + '; padding: 0.3rem;'
        "
        class="grid gap-2"
        :list="layout.children"
        group="sections"
        item-key="id"
    >
        <template #item="{ element: section }">
            <div>
                <MiniSections :layout="section" />
                <div
                    v-if="section.contains"
                    style="
                        height: 100%;
                        min-height: 50px;
                        justify-content: center;
                        align-items: center;
                    "
                    class="cursor-pointer bg-gray-300 flex border border-blue-500"
                    :title="section.contains"
                >
                    <p>{{ section.contains }}</p>
                </div>
            </div>
        </template>
    </draggable>
</template>

<script>
import draggable from "vuedraggable";

export default {
    name: "MiniSections",
    props: ["layout"],
    components: {
        draggable,
    },
    methods: {
        layoutType() {
            let type = this.layout.type;
            this.layout.children.forEach((layout) => {
                type = layout.type;
            });
            return type;
        },
        sectionDivision() {
            let sectionDivision = "";
            for (let i = 0; i < this.layout.children.length; i++) {
                sectionDivision += `${
                    this.layout.children[i].id === "question"
                        ? "minmax(50px, max-content)"
                        : this.layout.children[i].value
                }${this.layout.children[i + 1] ? " " : ""}`;
            }
            return sectionDivision;
        },
    },
};
</script>
