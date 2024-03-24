<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label input-label" :for="id">{{
            label
        }}&nbsp;<span v-if="required" style="color: red">*</span></label>
        <input :id="id" ref="input" :key="key" v-bind="{ ...$attrs, class: null }" :readonly="isReadonly" class="form-input form-control"
            :class="{ error: error }" :type="type" :value="modelValue" :max="max" :min="min"
            @input="$emit('update:modelValue', $event.target.value)" />
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
    mounted() {
        if (this.setCurrentDate) {
            const vm = this;
            Date.prototype.toDateInputValue = function () {
                var local = new Date(this);
                return vm.formatDateLite(local);
            };
            this.$refs.input.value = new Date().toDateInputValue();
            this.$emit("update:modelValue", this.$refs.input.value);
        }
    },
    inheritAttrs: true,
    props: {
        key: { type: Object | Boolean | Number | String },
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `date-input-${uuid()}`;
            },
        },
        type: {
            type: String,
            default: "date",
        },
        isReadonly: {
            type: Boolean,
            default: false,
        },
        floatingLabel: Boolean,
        error: String,
        label: String,
        modelValue: String,
        setCurrentDate: Boolean,
        max: { type: String, default: "" },
        min: { type: String, default: "" },
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
    },
};
</script>
