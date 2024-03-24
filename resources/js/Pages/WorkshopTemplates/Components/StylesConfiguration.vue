<template>
    <div class="acccordian mt-3">
        <div class="acccordian-tab">
            <input
                class="tab-checkbox"
                type="checkbox"
                id="styles-configuration-accordion"
            />
            <label
                class="acccordian-tab-header"
                for="styles-configuration-accordion"
            >
                Styles
            </label>
            <div class="acccordian-content">
                <div v-if="showForAllWidgetsExceptRowColumnCard">
                    <div class="form-group mb-4">
                        <SelectInput
                            :label="$t('Italicize')"
                            v-model="
                                selectedWidget.configuration.styleClasses
                                    .italicize
                            "
                        >
                            <option value="italic">Yes</option>
                            <option value="not-italic">No</option>
                        </SelectInput>
                    </div>
                    <div class="form-group mb-4">
                        <SelectInput
                            :label="$t('Font Weight')"
                            v-model="
                                selectedWidget.configuration.styleClasses
                                    .fontWeight
                            "
                        >
                            <option value="font-thin">{{ $t("Thin") }}</option>
                            <option value="font-extralight">
                                {{ $t("Extralight") }}
                            </option>
                            <option value="font-light">
                                {{ $t("Light") }}
                            </option>
                            <option value="font-normal">
                                {{ $t("Normal") }}
                            </option>
                            <option value="font-medium">
                                {{ $t("Medium") }}
                            </option>
                            <option value="font-semibold">
                                {{ $t("Semibold") }}
                            </option>
                            <option value="font-bold">{{ $t("Bold") }}</option>
                            <option value="font-extrabold">
                                {{ $t("Extrabold") }}
                            </option>
                            <option value="font-black">
                                {{ $t("Black") }}
                            </option>
                        </SelectInput>
                    </div>
                    <div class="form-group mb-4">
                        <SelectInput
                            :label="$t('Text Decoration')"
                            v-model="
                                selectedWidget.configuration.styleClasses
                                    .textDecoration
                            "
                        >
                            <option value="underline">
                                {{ $t("Undeline") }}
                            </option>
                            <option value="overline">
                                {{ $t("Overline") }}
                            </option>
                            <option value="line-through">
                                {{ $t("Line Through") }}
                            </option>
                            <option value="no-underline">
                                {{ $t("No Underline") }}
                            </option>
                        </SelectInput>
                    </div>
                    <div class="form-group mb-4">
                        <SelectInput
                            :label="$t('Font Size')"
                            v-model="
                                selectedWidget.configuration.styleClasses
                                    .fontSize
                            "
                        >
                            <option value="text-xs">{{ $t("Xs") }}</option>
                            <option value="text-sm">
                                {{ $t("Sm") }}
                            </option>
                            <option value="text-base">{{ $t("Base") }}</option>
                            <option value="text-lg">{{ $t("Lg") }}</option>
                            <option value="text-xl">{{ $t("XL") }}</option>
                            <option value="text-2xl">{{ $t("2XL") }}</option>
                            <option value="text-3xl">{{ $t("3XL") }}</option>
                            <option value="text-4xl">
                                {{ $t("4XL") }}
                            </option>
                            <option value="text-5xl">{{ $t("5XL") }}</option>
                            <option value="text-6xl">{{ $t("6XL") }}</option>
                            <option value="text-7xl">{{ $t("7XL") }}</option>
                            <option value="text-8xl">{{ $t("8XL") }}</option>
                            <option value="text-9xl">{{ $t("9XL") }}</option>
                        </SelectInput>
                    </div>
                    <div class="form-group mb-4 flex justify-between">
                        <label class="text-white"
                            >{{ $t("Font Color") }}:</label
                        >
                        <input
                            v-model="
                                selectedWidget.configuration.inlineCSS.color
                            "
                            :label="$t('Font Color')"
                            type="color"
                        />
                    </div>
                </div>
                <div v-if="showForAllWidgetsExceptCard">
                    <div class="p-3 mb-3 card shadow rounded">
                        <div class="mb-2">
                            <label class="text-white">Padding:</label>
                        </div>
                        <div class="grid gap-4 grid-cols-4">
                            <div class="form-group">
                                <labal class="text-white">{{$t("Top")}}</labal>
                                <TextInput
                                    v-model="
                                        selectedWidget.configuration.inlineCSS
                                            .paddingTop
                                    "
                                    type="number"
                                    min="0"
                                />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Right")}}</labal>
                                <TextInput
                                    v-model="
                                        selectedWidget.configuration.inlineCSS
                                            .paddingRight
                                    "
                                    type="number"
                                    min="0"
                                    :label="$t('')"
                                />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Bottom")}}</labal>
                                <TextInput
                                    v-model="
                                        selectedWidget.configuration.inlineCSS
                                            .paddingBottom
                                    "
                                    type="number"
                                    min="0"
                                    :label="$t('')"
                                />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Left")}}</labal>
                                <TextInput
                                    v-model="
                                        selectedWidget.configuration.inlineCSS
                                            .paddingLeft
                                    "
                                    type="number"
                                    min="0"
                                    :label="$t('')"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="p-3 card shadow rounded">
                        <div class="mb-2">
                            <label class="text-white">Margin:</label>
                        </div>
                        <div class="grid gap-4 grid-cols-4">
                            <div class="form-group">
                                <labal class="text-white">{{$t("Top")}}</labal>
                                <TextInput
                                v-model="
                                    selectedWidget.configuration.inlineCSS
                                        .marginTop
                                "
                                type="number"
                                min="0"
                                :label="$t('')"
                            />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Right")}}</labal>
                                <TextInput
                                v-model="
                                    selectedWidget.configuration.inlineCSS
                                        .marginRight
                                "
                                type="number"
                                min="0"
                                :label="$t('')"
                            />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Bottom")}}</labal>
                                <TextInput
                                v-model="
                                    selectedWidget.configuration.inlineCSS
                                        .marginBottom
                                "
                                type="number"
                                min="0"
                                :label="$t('')"
                            />
                            </div>
                            <div class="form-group">
                                <labal class="text-white">{{$t("Left")}}</labal>
                                <TextInput
                                v-model="
                                    selectedWidget.configuration.inlineCSS
                                        .marginLeft
                                "
                                type="number"
                                min="0"
                                :label="$t('')"
                            />
                            </div>
                            
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    name: "StylesConfiguration",
    components: {
        TextInput,
        SelectInput,
    },
    computed: {
        ...mapGetters("workshopTemplates", ["selectedWidget"]),
        showForAllWidgetsExceptRowColumnCard() {
            return [
                "heading",
                "paragraph",
                "text-input",
                "number-input",
                "checkbox",
                "radio-button",
                "select",
                "table",
            ].includes(this.selectedWidget?.type);
        },
        showForAllWidgetsExceptCard() {
            return [
                "heading",
                "paragraph",
                "text-input",
                "number-input",
                "checkbox",
                "radio-button",
                "select",
                "table",
                "column",
                "row",
            ].includes(this.selectedWidget?.type);
        },
    },
};
</script>

<style scoped lang="scss">
/* Accordion styles */
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: #b6bfbf;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;
        /* Icon */
        &:hover {
        }
        &::after {
            content: "\276F";
            text-align: center;
            transition: all 0.35s;
            color: #b6bfbf;
            position: absolute;
            right: 10px;
            margin-top: 15px;
        }
    }
    &-content {
        display: none;
        max-height: 0;
        color: #b6bfbf;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}

.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    + .tab-label {
        &::after {
            transform: rotate(90deg);
            transform-origin: center;
        }
    }
    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}
</style>
