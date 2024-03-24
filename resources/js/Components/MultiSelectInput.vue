<template>
    <div :class="$attrs.class">
        <label v-if="textLabel" class="form-label input-label" :for="id"
            >{{ textLabel }}&nbsp;<span v-if="required" style="color: #f00"
                >*</span
            ></label
        >
        <multiselect
            :disabled="isDisabled"
            :allowEmpty="allowEmpty"
            :show-labels="showLabels"
            :v-bind="{ ...$attrs }"
            ref="input"
            :key="key"
            :multiple="multiple"
            :placeholder="$t(placeholder)"
            v-model="selected"
            :options="options"
            :label="label"
            :trackBy="trackBy"
            :taggable="taggable"
            :customLabel="customLabel"
            @tag="addTag"
            @open="onOpen"
            @select="selectEvent"
            @remove="removeEvent"
            :tag-placeholder="tagPlaceholder"
            :internal-search="false"
            :loading="loading"
            :open-direction="openDirection"
            @search-change="searchInDropdown"
            class="form-control"
        >
            <template #singleLabel="props">
                <slot name="singleLabel" :props="props" />
            </template>
            <template #option="props">
                <slot name="option" :props="props" />
            </template>
            <template #beforeList="props">
                <slot name="beforeList" :props="props" />
            </template>
            <template #noOptions="props">
                <p v-if="options.length === 0">
                    {{ $t("List is empty.") }}
                </p>
            </template>
            <template #afterList>
                <div
                    v-if="options.length < count && showLoadMoreText"
                    ref="load"
                    class="flex align-center justify-center my-2"
                >
                    <div class="round-spinner mr-2 mt-1"></div>
                    <div>
                        <p>{{ $t("Loading more options...") }}</p>
                    </div>
                </div>
            </template>
        </multiselect>

        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import { v4 as uuid } from "uuid";
import { debounce } from "@/utils/debounce";

export default {
    inheritAttrs: true,
    components: {
        Multiselect,
    },
    props: {
        searchParamName: { type: String, default: "search" },
        countStore: { type: String, default: "count" },
        action: { type: String, default: "list" },
        key: { type: Object | Boolean | Number | String },
        query: { type: Object, default: {} },
        openDirection: { type: String, default: "bottom" },
        required: { type: Boolean, default: false },
        placeholder: { type: String, default: "Select Option" },
        allowEmpty: { type: Boolean, default: true },
        isDisabled: { type: Boolean, default: false },
        showLoadMoreText: { type: Boolean, default: true },
        showLabels: { type: Boolean },
        customLabel: { type: Function },
        taggable: { type: Boolean, default: false },
        customSearchInOptions: { type: Boolean, default: false },
        tagPlaceholder: { type: String, default: "" },
        id: {
            type: String,
            default() {
                return `multi-select-input-${uuid()}`;
            },
        },
        error: String,
        textLabel: String,
        label: String,
        trackBy: String,
        modelValue: [String, Number, Boolean, Array, Object],
        options: {
            type: Array,
            default: () => [],
        },
        multiple: { type: Boolean, default: true },
        moduleName: {
            type: String,
            default: "",
        },
    },

    emits: [
        "update:modelValue",
        "tagAdded",
        "search-change",
        "open",
        "select",
        "remove",
    ],
    data() {
        return {
            searchString: "",
            loading: false,
            count: 0,
            limit: 25,
            observer: new IntersectionObserver(this.infiniteScroll),
            search: "",
            selected: this.modelValue,
            timeoutId: "",
        };
    },
    watch: {
        selected(selected) {
            this.$emit("update:modelValue", selected);
        },
    },
    methods: {
        searchInDropdown(query) {
            // Clear the previous timeout
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }
            // Set up a new timeout
            this.timeoutId = setTimeout(() => {
                if (this.customSearchInOptions === true) {
                    this.$emit("asyncSearch", query);
                } else {
                    this.asyncSearch(query);
                }
            }, 700); // Adjust the delay time as needed
        },
        /**
         * emits search-change event that can be used to search based on the query
         * @param {query} the search text entered
         */
        async asyncSearch(query) {
            this.searchString = query;
            try {
                this.loading = true;
                const queryParams =
                    this.moduleName.includes("permission") ||
                    this.moduleName.includes("auth") ||
                    this.moduleName.includes("roles") ||
                    this.moduleName.includes("documentService") ||
                    this.moduleName.includes("serverPools")
                        ? {
                              limit_start: 0,
                              limit_count: this.limit,
                              type: "employee",
                          }
                        : {
                              page: 1,
                              perPage: this.limit,
                          };
                queryParams[this.searchParamName] = query;
                const data = await this.$store.dispatch(
                    `${this.moduleName}/${this.action}`,
                    {
                        ...queryParams,
                        ...this.query,
                    }
                );
                this.$emit("asyncSearch", data?.data);
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
                this.updateCountValue();
            }
        },
        /**
         * updates the count value from the getters of moduleName store
         */
        async updateCountValue() {
            if (this.moduleName) {
                this.count =
                    this.$store.getters[
                        `${this.moduleName}/${this.countStore}`
                    ];
                await this.$nextTick();
                if (this.$refs.load) this.observer?.observe(this.$refs.load);
            }
        },
        onOpen(id) {
            this.$emit("open", id);
            this.updateCountValue();
        },
        async infiniteScroll([{ isIntersecting, target }]) {
            try {
                if (isIntersecting) {
                    const ul = target.offsetParent;
                    const scrollTop = target.offsetParent.scrollTop;
                    this.limit += 10;
                    const queryParams =
                        this.moduleName.includes("permissions") ||
                        this.moduleName.includes("auth") ||
                        this.moduleName.includes("roles") ||
                        this.moduleName.includes("documentService") ||
                        this.moduleName.includes("serverPools")
                            ? {
                                  limit_start: 0,
                                  limit_count: this.limit,
                              }
                            : {
                                  page: 1,
                                  perPage: this.limit,
                              };
                    queryParams[this.searchParamName] = this.searchString;
                    await this.$store.dispatch(
                        `${this.moduleName}/${this.action}`,
                        {
                            ...queryParams,
                            ...this.query,
                        }
                    );
                    await this.$nextTick();
                    ul.scrollTop = scrollTop;
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.updateCountValue();
            }
        },
        addTag(tag) {
            this.$emit("tagAdded", tag);
        },
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        selectEvent(selectedOption) {
            this.$emit("select", selectedOption);
        },
        removeEvent(removedOption) {
            this.$emit("remove", removedOption);
        },
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
:deep(.multiselect__content-wrapper) {
    /* position: relative; */
    min-width: fit-content;
}
:deep(.multiselect__single) {
    overflow-x: clip;
}
:deep(.multiselect--disabled) {
    color: black;
    opacity: 1;
}
:deep(.multiselect--disabled .multiselect__select) {
    display: none;
}
</style>
