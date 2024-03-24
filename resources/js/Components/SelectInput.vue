<template>
    <div :class="$attrs.class">
        <label v-if="label && !floatingLabel" class="form-label input-label" :for="id"
            >{{
                label
            }}&nbsp;<span v-if="required" style="color: red">*</span></label
        >
        <div v-if="floatingLabel" class="relative z-0">
            <select
                :id="id"
                ref="input"
                :disabled="isReadOnly"
                v-model="selected"
                v-bind="{ ...$attrs, class: null }"
                class="form-control"
                :class="{ error: error }"
            >
                <slot />
            </select>
            <!-- <label
                :for="'floating_standard_' + id"
                :class="`absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform ${
                    selected
                        ? '-translate-y-6 scale-75'
                        : '-translate-y-1 scale-100'
                } top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6`"
                ><span v-if="required" style="color: red">*</span>&nbsp;{{
                    label
                }}</label
            > -->
            <label class="input-label" :for="'floating_standard_' + id"
                >{{ label }}&nbsp;<span v-if="required" style="color: red"
                    >*</span
                ></label
            >
        </div>
        <select
            v-else
            :disabled="isReadOnly"
            :id="id"
            ref="input"
            v-model="selected"
            v-bind="{ ...$attrs, class: null }"
            class="form-select form-control"
            :class="{ error: error }"
        >
            <slot />
        </select>
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";
export default {
    inheritAttrs: true,
    props: {
        required: { type: Boolean, required: false },
        isReadOnly: Boolean,
        floatingLabel: Boolean,
        id: {
            type: String,
            default() {
                return `select-input-${uuid()}`;
            },
        },
        error: String,
        label: String,
        modelValue: [String, Number, Boolean, Object],
    },
    emits: ["update:modelValue"],
    data() {
        return {
            selected: this.modelValue,
        };
    },
    watch: {
        selected(selected, oldValue) {
            this.$emit("update:modelValue", selected);
            // emits the old and new value of the select-input on change event
            this.$emit("oldAndNewValueOnChange", {
                oldValue: oldValue,
                newValue: selected,
            });
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
    },
};
</script>
