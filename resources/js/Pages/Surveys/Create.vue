<template>
    <div class="page-header">
        <div class="page-title">
            <div class="page-title-right">
                <ol class="breadcrumb m-0 align-items-center">
                    <li class="breadcrumb-item">
                        <router-link to="/dashboard">{{
                            $t("Admin Portal")
                        }}</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link to="/surveys">{{
                            $t("Survey")
                        }}</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="cursor-pointer">{{ $t("Create") }}</span>
                    </li>
                </ol>
            </div>
        </div>
        <div class="other-items d-flex justify-center">
            <!---========--->
            <div class="right-side">
                <button
                    class="primary-btn gap-2 mr-2"
                    :disabled="!surveyId"
                    @click="exportJson"
                >
                    <CustomIcon name="ExportIcon" />
                    <span>{{ $t("Export") }}</span>
                </button>
                <button
                    class="primary-btn gap-2"
                    :disabled="!surveyId"
                    @click="save"
                >
                    <CustomIcon name="saveDarkIcon" />
                    <span>{{ $t("Save") }}</span>
                </button>
            </div>
            <!---========--->
        </div>
    </div>
    <div style="margin-right: 25px">
        <ul
            class="config-btns"
            :style="`right: ${
                configurationToggle || styleBarToggle ? '25.1' : '0'
            }%;`"
        >
            <li :class="{ active: configurationToggle }">
                <div
                    class="cursor-pointer"
                    @click="configurationToggle = !configurationToggle"
                >
                    <CustomIcon name="configurationIcon" />
                </div>
            </li>
            <li :class="{ active: styleBarToggle }">
                <div
                    class="cursor-pointer"
                    @click="styleBarToggle = !styleBarToggle"
                >
                    <CustomIcon name="styleconfigurationIcon" />
                </div>
            </li>
        </ul>

        <div
            v-if="cartToggle && minimizeCart"
            class="absolute bg-white rounded shadow-lg border border-blue-100 toggled-card"
            style="
                width: 30%;
                right: 0%;
                z-index: 1;
                max-height: 100vh;
                min-height: 100vh;
                overflow: auto;
            "
        >
            <Cart
                @minimizeCart="
                    minimizeCart = $event;
                    cartToggle = false;
                "
                :minimizeCart="minimizeCart"
                :cartParent="cart"
                :stylesConfigurationParent="stylesConfiguration"
                :selectedQuestionParent="selectedQuestion"
                @cartParent="
                    cart = $event;
                    $emit('cartParent', cart);
                "
                @manualProducts="$emit('manualProducts', $event)"
                :manualProductsParent="manualProducts"
                :selectedInputs="selectedInputs"
                :questionsParent="questions"
            />
        </div>
        <div v-if="styleBarToggle" class="rightbar-card">
            <div class="divider"></div>
            <div class="rightbar-card-title">
                <h2>{{ $t("Styles Configuration") }}</h2>
            </div>
            <div class="divider"></div>
            <div class="rightbar-body">
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="steps-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="steps-accordion"
                        >
                            {{ $t("Steps") }}
                        </label>
                        <div class="acccordian-content">
                            <div class="flex items-center justify-between mb-2">
                                <p class="">{{ $t("Steps BG Color") }}:</p>
                                <input
                                    v-model="
                                        stylesConfiguration.steps.cardBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">{{ $t("Steps Text Color") }}:</p>
                                <input
                                    v-model="
                                        stylesConfiguration.steps.cardTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Steps Header Text Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.steps
                                            .cardHeaderTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Steps Header BG Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.steps
                                            .cardHeaderBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="question-details-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="question-details-accordion"
                        >
                            {{ $t("Question Details") }}
                        </label>
                        <div class="acccordian-content">
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Question Details BG Color") }} :
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.questionDetails
                                            .cardBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Question Details Text Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.questionDetails
                                            .cardTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="cart-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="cart-accordion"
                        >
                            {{ $t("Cart") }}
                        </label>
                        <div class="acccordian-content">
                            <div class="flex items-center justify-between mb-2">
                                <p class="">{{ $t("Cart BG Color") }}:</p>
                                <input
                                    v-model="
                                        stylesConfiguration.cart.cardBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">{{ $t("Cart Text Color") }}:</p>
                                <input
                                    v-model="
                                        stylesConfiguration.cart.cardTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Cart Header Text Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.cart
                                            .cardHeaderTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Cart Header BG Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.cart
                                            .cardHeaderBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="product-details-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="product-details-accordion"
                        >
                            {{ $t("Product Details") }}
                        </label>
                        <div class="acccordian-content">
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Product Details BG Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.productDetails
                                            .cardBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Product Details Text Color") }}:
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.productDetails
                                            .cardTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{
                                        $t("Product Details Header Text Color")
                                    }}
                                    :
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.productDetails
                                            .cardHeaderTextColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="">
                                    {{ $t("Product Details Header BG Color") }}
                                    :
                                </p>
                                <input
                                    v-model="
                                        stylesConfiguration.productDetails
                                            .cardHeaderBgColor
                                    "
                                    class="justify-self-center"
                                    type="color"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="layout-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="layout-accordion"
                        >
                            {{ $t("Layout") }}
                        </label>
                        <div class="acccordian-content">
                            <MiniSections
                                :layout="stylesConfiguration.layout"
                            />
                        </div>
                    </div>
                </div>
                <div class="acccordian">
                    <div class="acccordian-tab">
                        <input
                            class="tab-checkbox"
                            type="checkbox"
                            id="global-configuration-accordion"
                        />
                        <label
                            class="acccordian-tab-header"
                            for="global-configuration-accordion"
                        >
                            {{ $t("Global Configuration") }}
                        </label>
                        <div class="acccordian-content">
                            <div class="flex justify-between">
                                <label for="manual-products">{{
                                    $t("Add Products Manually")
                                }}</label>
                                <input
                                    @input="
                                        cartConfigToggled(
                                            $event,
                                            'addProductsManually'
                                        )
                                    "
                                    v-model="cart.addProductsManually"
                                    type="checkbox"
                                    name=""
                                    id="manual-products"
                                />
                            </div>
                            <div class="flex justify-between mt-2">
                                <label for="minimize-cart">{{
                                    $t("Minimize Cart")
                                }}</label>
                                <input
                                    @input="
                                        cartConfigToggled(
                                            $event,
                                            'minimizeCart'
                                        )
                                    "
                                    v-model="cart.minimizeCart"
                                    type="checkbox"
                                    name=""
                                    id="minimize-cart"
                                />
                            </div>
                            <div class="flex justify-between mt-2">
                                <label for="minimize-steps">{{
                                    $t("Minimize Steps")
                                }}</label>
                                <input
                                    @input="
                                        cartConfigToggled(
                                            $event,
                                            'minimizeSteps'
                                        )
                                    "
                                    v-model="cart.minimizeSteps"
                                    type="checkbox"
                                    name=""
                                    id="minimize-steps"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="configurationToggle" class="rightbar-card">
            <div class="divider"></div>
            <div class="rightbar-card-title">
                <h2>{{ $t("Configuration") }}</h2>
            </div>
            <div class="divider"></div>
            <div class="rightbar-body">
                <div
                    class="px-3 py-2 toggled-card"
                    style="max-height: 100vh; min-height: 100vh; overflow: auto"
                    v-if="selectedQuestion"
                >
                    <div
                        class="flex justify-between"
                        v-if="
                            selectedQuestion?.configuration?.type ===
                            'image-input'
                        "
                    >
                        <p>
                            <span class="font-bold">Type:</span
                            >&nbsp;&nbsp;Image Input
                        </p>
                        <font-awesome-icon
                            @click="removeSelectedQuestionConfigurationType"
                            class="cursor-pointer cross"
                            icon="fa-solid fa-xmark"
                        />
                    </div>
                    <div v-else class="tabs">
                        <div
                            v-for="(group, groupIndex) in selectedQuestion
                                .configuration.groups"
                            :key="'group-' + groupIndex"
                            class="config-card"
                        >
                            <div class="form-group">
                                <select-input
                                @update:model-value="setSelectedQuestionType"
                                v-model="group.type"
                                :floatingLabel="false"
                                label="Type"
                                class="my-2"
                            >
                                <option value="single-select">
                                    {{ $t("Single Select") }}
                                </option>
                                <option value="multiple-select">
                                    Multi Select
                                </option>
                                <option value="image-input">
                                    {{ $t("Image Input") }}
                                </option>
                                <option value="number-input">
                                    {{ $t("Number Input") }}
                                </option>
                                <option value="number-slider">
                                    {{ $t("Number Slider") }}
                                </option>
                                <option value="text-input">
                                    {{ $t("Text Input") }}
                                </option>
                            </select-input>
                            </div>
                            <div class="form-group mt-4">
                                <text-input
                                class="w-full"
                                v-model="group.title"
                                :label="$t('Title')"
                                placeholder=""
                                type="text"
                            />
                            </div>
                            
                            <div
                                v-for="(option, index) in group.options"
                                :key="'option-' + index"
                                class="config-card"
                            >
                                <div
                                    v-if="
                                        allOptions[option].type ===
                                        'single-select'
                                    "
                                >
                                    <input
                                        class="tab-checkbox"
                                        type="checkbox"
                                        :id="allOptions[option].id"
                                    />
                                    <label
                                        class="tab-label"
                                        :for="allOptions[option].id"
                                    >
                                        <font-awesome-icon
                                            @click="
                                                removeOption(index, groupIndex)
                                            "
                                            class="cursor-pointer cross"
                                            icon="fa-solid fa-xmark"
                                        />
                                        <text-input
                                            @input="
                                                errorList[
                                                    allOptions[option].id
                                                ] = null
                                            "
                                            class="w-full mt-4"
                                            v-model="allOptions[option].title"
                                            :label="$t('Title')"
                                            placeholder=""
                                            type="text"
                                        />
                                    </label>
                                    <p
                                        v-if="errorList[allOptions[option].id]"
                                        class="text-red-700"
                                    >
                                        {{ errorList[allOptions[option].id] }}
                                    </p>
                                    <div class="tab-content">
                                        <div class="form-group">
                                            <select-input
                                            @input="nextQuestionChanged($event)"
                                            v-model="allOptions[option].next"
                                            :floatingLabel="false"
                                            label="Next Step"
                                            class="mt-2"
                                        >
                                            <option value="">
                                                {{ $t("Next Question") }}
                                            </option>
                                            <option
                                                v-for="question in filteredQuestion"
                                                :key="
                                                    question?.value?.id ??
                                                    question.id
                                                "
                                                :value="
                                                    question?.value?.id ??
                                                    question.id
                                                "
                                            >
                                                {{
                                                    question?.value?.title ??
                                                    question.title
                                                }}
                                            </option>
                                        </select-input>
                                        </div>
                                        <button
                                            @click="
                                                openProductsModal(
                                                    index,
                                                    groupIndex
                                                )
                                            "
                                            type="button"
                                            class="secondary-btn"
                                        >
                                            {{ $t("Add Products") }}
                                        </button>
                                    </div>
                                </div>
                                <div
                                    v-else-if="
                                        allOptions[option].type ===
                                        'multiple-select'
                                    "
                                >
                                    <input
                                        class="tab-checkbox"
                                        type="checkbox"
                                        :id="allOptions[option].id"
                                    />
                                    <label
                                        class="tab-label"
                                        :for="allOptions[option].id"
                                    >
                                        <font-awesome-icon
                                            @click="
                                                removeOption(index, groupIndex)
                                            "
                                            class="cursor-pointer cross"
                                            icon="fa-solid fa-xmark"
                                        />
                                        <div class="form-group">
                                            <text-input
                                            @input="
                                                errorList[
                                                    allOptions[option].id
                                                ] = null
                                            "
                                            class="w-full mt-4"
                                            v-model="allOptions[option].title"
                                            label="Title"
                                            placeholder=""
                                            type="text"
                                        />
                                        </div>
                                    </label>
                                    <p
                                        v-if="errorList[allOptions[option].id]"
                                        class="text-red-700"
                                    >
                                        {{ errorList[allOptions[option].id] }}
                                    </p>
                                    <div class="tab-content">
                                        <button
                                            @click="
                                                openProductsModal(
                                                    index,
                                                    groupIndex
                                                )
                                            "
                                            type="button"
                                            class="secondary-btn"
                                        >
                                            {{ $t("Add Products") }}
                                        </button>
                                    </div>
                                </div>
                                <div
                                    v-else-if="
                                        allOptions[option].type ===
                                        'number-input'
                                    "
                                >
                                    <input
                                        class="tab-checkbox"
                                        type="checkbox"
                                        :id="allOptions[option].id"
                                    />
                                    <label
                                        class="tab-label"
                                        :for="allOptions[option].id"
                                    >
                                        <font-awesome-icon
                                            @click="
                                                removeOption(index, groupIndex)
                                            "
                                            class="cursor-pointer cross"
                                            icon="fa-solid fa-xmark"
                                        />
                                        <div class="form-group">
                                            <text-input
                                            @input="
                                                errorList[
                                                    allOptions[option].id
                                                ] = null
                                            "
                                            class="w-full mt-4"
                                            v-model="allOptions[option].title"
                                            :label="$t('Title')"
                                            placeholder=""
                                            type="text"
                                        />
                                        </div>
                                    </label>
                                    <p
                                        v-if="errorList[allOptions[option].id]"
                                        class="text-red-700"
                                    >
                                        {{ errorList[allOptions[option].id] }}
                                    </p>
                                    <div class="tab-content">
                                        <div class="grid grid-cols-3 gap-2">
                                            <text-input
                                                v-model="allOptions[option].min"
                                                :label="$t('Min')"
                                                placeholder=""
                                                type="number"
                                            />
                                            <text-input
                                                v-model="allOptions[option].max"
                                                :label="$t('Max')"
                                                placeholder=""
                                                type="number"
                                            />
                                            <text-input
                                                v-model="
                                                    allOptions[option].step
                                                "
                                                :label="$t('Step')"
                                                placeholder=""
                                                type="number"
                                            />
                                        </div>
                                        <button
                                            @click="
                                                openNumberInputProductsModal(
                                                    index,
                                                    groupIndex
                                                )
                                            "
                                            type="button"
                                            class="secondary-btn"
                                        >
                                            Add Products
                                        </button>
                                    </div>
                                </div>
                                <div
                                    v-else-if="
                                        allOptions[option].type ===
                                        'number-slider'
                                    "
                                >
                                    <input
                                        class="tab-checkbox"
                                        type="checkbox"
                                        :id="option.id"
                                    />
                                    <label
                                        class="tab-label"
                                        :for="allOptions[option].id"
                                    >
                                        <font-awesome-icon
                                            @click="
                                                removeOption(index, groupIndex)
                                            "
                                            class="cursor-pointer cross"
                                            icon="fa-solid fa-xmark"
                                        />
                                        <div class="form-group">
                                            <text-input
                                            @input="
                                                errorList[
                                                    allOptions[option].id
                                                ] = null
                                            "
                                            class="w-full mt-4"
                                            v-model="allOptions[option].title"
                                            label="Title"
                                            placeholder=""
                                            type="text"
                                        />
                                        </div>
                                    </label>
                                    <p
                                        v-if="errorList[allOptions[option].id]"
                                        class="text-red-700"
                                    >
                                        {{ errorList[allOptions[option].id] }}
                                    </p>
                                    <div class="tab-content">
                                        <div class="grid grid-cols-3 gap-2 form-group">
                                            <text-input
                                                v-model="allOptions[option].min"
                                                label="Min"
                                                placeholder=""
                                                type="number"
                                            />
                                            <text-input
                                                v-model="allOptions[option].max"
                                                label="Max"
                                                placeholder=""
                                                type="number"
                                            />
                                            <text-input
                                                v-model="
                                                    allOptions[option].step
                                                "
                                                label="Step"
                                                placeholder=""
                                                type="number"
                                            />
                                        </div>
                                    </div>
                                    <button
                                        @click="
                                            openNumberInputProductsModal(
                                                index,
                                                groupIndex
                                            )
                                        "
                                        type="button"
                                        class="secondary-btn"
                                    >
                                        {{ $t("Add Products") }}
                                    </button>
                                </div>
                                <div
                                    v-else-if="
                                        allOptions[option].type === 'text-input'
                                    "
                                >
                                    <font-awesome-icon
                                        @click="removeOption(index, groupIndex)"
                                        class="cursor-pointer cross"
                                        icon="fa-solid fa-xmark"
                                    />
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="form-group">
                                            <text-input
                                            @input="
                                                errorList[
                                                    allOptions[option].id
                                                ] = null
                                            "
                                            v-model="allOptions[option].title"
                                            :label="$t('Title')"
                                            placeholder=""
                                            type="text"
                                        />
                                        </div>
                                        <div class="form-group">
                                            <text-input
                                            v-model="
                                                allOptions[option].placeholder
                                            "
                                            :label="$t('Placeholder')"
                                            placeholder=""
                                            type="text"
                                        />
                                        </div>
                                    </div>
                                    <p
                                        v-if="errorList[allOptions[option].id]"
                                        class="text-red-700"
                                    >
                                        {{ errorList[allOptions[option].id] }}
                                    </p>
                                    <button
                                        @click="
                                            openProductsModal(index, groupIndex)
                                        "
                                        type="button"
                                        class="secondary-btn"
                                    >
                                        {{ $t("Add Products") }}
                                    </button>
                                </div>
                            </div>
                            <button
                                @click="addOption(groupIndex)"
                                type="button"
                                class="secondary-btn w-full btn-border mt-3"
                            >
                                {{ $t("Add Option ") }} +
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="
                            selectedQuestion.configuration.type !==
                            'image-input'
                        "
                    >
                        <button
                            @click="addGroup"
                            type="button"
                            class="secondary-btn w-full btn-border mt-2"
                        >
                            {{ $t("Add Group ") }} +
                        </button>
                        <div
                            v-if="
                                selectedQuestion?.configuration?.groups?.length
                            "
                        >
                            <div
                                v-for="(condition, index) in selectedQuestion
                                    .configuration.conditions"
                                :key="'condition-' + index"
                                class="config-card mt-4"
                            >
                                <div class="flex items-center justify-end">
                                    <span  class="cursor-pointer" @click="removeCondition(index)"
                                    ><CustomIcon name="xyellowIcon"
                                /></span>
                                </div>

                                <div
                                    v-for="(
                                        conditionOption, conditionOptionIndex
                                    ) in condition.options"
                                    :key="
                                        'condition-option-' +
                                        conditionOptionIndex
                                    "
                                    class="grid items-center grid-cols-3 gap-2 mt-3"
                                >
                                    <div class="col-span-1 w-full">
                                        <div class="w-full" v-if="conditionOption.operator == 'if'">
                                            <p> If </p>
                                        </div>
                                        <div class="w-full" v-else>
                                            <div class="form-group" >
                                                <select-input
                                                    v-model="conditionOption.operator"
                                                    :floatingLabel="false"
                                                    label=""
                                                >
                                                    <option value="&&">
                                                        {{ $t("And") }}
                                                    </option>
                                                    <option value="||">
                                                        {{ $t("Or") }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-2 w-full">
                                        <div class="form-group">
                                            <select-input
                                                v-model="conditionOption.option"
                                                @oldAndNewValueOnChange="
                                                    addConditionToSelectedOption(
                                                        $event,
                                                        condition,
                                                        conditionOptionIndex
                                                    )
                                                "
                                                :floatingLabel="false"
                                                :label="$t('Option')"
                                            >
                                                <option
                                                    v-for="(
                                                        option, index
                                                    ) in Object.values(
                                                        typeof allOptions ===
                                                            'object'
                                                            ? allOptions
                                                            : {}
                                                    )"
                                                    :key="'option-' + index"
                                                    :value="option"
                                                >
                                                    {{ option.title }}
                                                </option>
                                            </select-input>
                                        </div>
                                        <div class="form-group">
                                            <select-input
                                                v-if="
                                                    conditionOption.option
                                                        ?.type ===
                                                    'multiple-select'
                                                "
                                                v-model="
                                                    conditionOption.condition
                                                "
                                                :floatingLabel="false"
                                                :label="$t('Condition')"
                                            >
                                                <option value="checked">
                                                    is checked
                                                </option>
                                                <option value="unchecked">
                                                    is not checked
                                                </option>
                                            </select-input>
                                        </div>
                                        <div class="form-group">
                                            <select-input
                                                v-if="
                                                    conditionOption.option
                                                        ?.type ===
                                                        'number-input' ||
                                                    conditionOption.option
                                                        ?.type ===
                                                        'number-slider'
                                                "
                                                v-model="
                                                    conditionOption.condition
                                                "
                                                :floatingLabel="false"
                                                :label="$t('Condition')"
                                            >
                                                <option value="==">
                                                    {{ $t("equals to") }}
                                                </option>
                                                <option value="!=">
                                                    {{ $t("not equals to") }}
                                                </option>
                                                <option value=">">
                                                    {{ $t("greater than") }}
                                                </option>
                                                <option value=">=">
                                                    {{
                                                        $t(
                                                            "greater than equals to"
                                                        )
                                                    }}
                                                </option>
                                                <option value="<">
                                                    {{ $t("less than") }}
                                                </option>
                                                <option value="<=">
                                                    {{
                                                        $t(
                                                            "less than equals to"
                                                        )
                                                    }}
                                                </option>
                                            </select-input>
                                        </div>
                                        <div class="form-group">
                                            <text-input
                                                v-if="
                                                    conditionOption.option
                                                        ?.type ===
                                                        'number-input' ||
                                                    conditionOption.option
                                                        ?.type ===
                                                        'number-slider'
                                                "
                                                v-model="conditionOption.value"
                                                label="Value"
                                                placeholder=""
                                                type="number"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <select-input
                                                v-if="
                                                    conditionOption.option
                                                        ?.type === 'text-input'
                                                "
                                                v-model="
                                                    conditionOption.condition
                                                "
                                                :floatingLabel="false"
                                                :label="$t('Condition')"
                                            >
                                                <option value="contains">
                                                    {{ $t("contains") }}
                                                </option>
                                                <option value="doesNotContain">
                                                    {{ $t("does not contain") }}
                                                </option>
                                            </select-input>
                                        </div>
                                        <div class="form-group">
                                            <text-input
                                                v-if="
                                                    conditionOption.option
                                                        ?.type === 'text-input'
                                                "
                                                v-model="conditionOption.value"
                                                :label="$t('Value')"
                                                placeholder=""
                                                type="text"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center align-center mt-2"
                                >
                                    <div class="cursor-pointer" @click="addOptionsCondition(index)">
                                        <CustomIcon name="addCircleFillIcon"/>
                                    </div>
                                    
                                </div>
                                <div class="conditions flex items-center gap-2 mt-3">
                                    <p class="">{{ $t("Then") }}</p>
                                    <button
                                        @click="
                                            openConditionProductsModal(index)
                                        "
                                        type="button"
                                        class="secondary-btn"
                                    >
                                        Products
                                    </button>
                                    <select-input
                                        v-model="condition.next"
                                        :floatingLabel="false"
                                        label=""
                                    >
                                        <option value="">
                                            {{ $t("Next Question") }}
                                        </option>
                                        <option
                                            v-for="question in filteredQuestion"
                                            :key="
                                                question?.value?.id ??
                                                question.id
                                            "
                                            :value="
                                                question?.value?.id ??
                                                question.id
                                            "
                                        >
                                            {{
                                                question?.value?.title ??
                                                question.title
                                            }}
                                        </option>
                                    </select-input>
                                </div>
                                <div
                                    class="mt-4"
                                >
                                
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <CustomIcon name="percentIcon" />
                                                </span>
                                            </div>
                                            <text-input
                                        v-model="condition.discount"
                                        label="Set Discount"
                                        placeholder=""
                                        type="number"
                                        min="0"
                                    />
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <button
                                @click="addCondition()"
                                type="button"
                                class="secondary-btn btn-border w-full mt-3"
                            >
                                {{ $t("Add Condition ") }} +
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <li v-if="!configurationToggle && !styleBarToggle && minimizeCart">
            <div
                :style="`right: ${
                    cartToggle ? '30' : '0'
                }%; top: 70%; background: #ed7d31; height: auto;`"
                class="absolute card px-3 py-2 bg-white-500 rounded shadow-md cursor-pointer"
                @click="cartToggle = !cartToggle"
            >
                <font-awesome-icon
                    color="#2957a4"
                    icon="fa-solid fa-cart-shopping"
                />
            </div>
        </li>
        <div class="form-group w-1/4 mb-5">
            <TextInput
                :required="true"
                :label="$t('Name')"
                v-model="name"
                :error="errors.name"
                @update:model-value="$store.commit('errors', {})"
            ></TextInput>
        </div>

        <Sections
            :name="name"
            :layout="stylesConfiguration.layout"
            :stepsParent="steps"
            :selectedQuestionParent="selectedQuestion"
            :selectedChapterParent="selectedChapter"
            :cartParent="cart"
            :validatorParent="validator"
            :stylesConfigurationParent="stylesConfiguration"
            :questionsParent="questions"
            :manualProducts="manualProducts"
            @manualProducts="manualProducts = $event"
            :minimizeCart="minimizeCart"
            :minimizeSteps="minimizeSteps"
            @minimizeCart="minimizeCart = $event"
            :selectedInputs="selectedInputs"
            @selectedInputs="selectedInputs = $event"
            @stepsParent="steps = $event"
            @selectedQuestionParent="selectedQuestion = $event"
            @selectedChapterParent="selectedChapter = $event"
            @cartParent="cart = $event"
            @validatorParent="validator = $event"
            @stylesConfigurationParent="stylesConfiguration = $event"
            @questionsParent="questions = $event"
        />
    </div>
    <select-product-modal
        v-if="productToggle"
        @selected="addProducts"
        @cancelled="productToggle = false"
        :selectedItems="option.products"
        :questions="questions"
        :products="products"
        :formulaInput="false"
    ></select-product-modal>
    <select-product-modal
        :key="selectedQuestion?.id"
        v-if="numberInputProductsToggle"
        @selected="addNumberInputProducts"
        :option="option"
        :formulaInput="true"
        @cancelled="numberInputProductsToggle = false"
        :selectedItems="option.products"
        :questions="questions"
        :options="selectedQuestion?.configuration?.options ?? []"
        :products="products"
    ></select-product-modal>
    <select-product-modal
        v-if="conditionsProductToggle"
        @selected="addConditionsProduct"
        :formulaInput="
            selectedQuestion?.configuration?.type !== 'multiple-select'
        "
        @cancelled="conditionsProductToggle = false"
        :selectedItems="selectedCondition.products"
        :questions="questions"
        :option="selectedCondition"
        :options="selectedQuestion?.configuration?.options ?? []"
        :products="products"
    ></select-product-modal>
</template>

<script>
import SurveyLibrary from "./survey";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import TextInput from "../../Components/TextInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import SelectProductModal from "../../Components/SelectProductModal.vue";
import Modal from "../../Components/EditModal.vue";
import MonacoEditor from "../../Components/MonacoEditor.vue";
import { v4 as uuidv4 } from "uuid";

import ImageInput from "./ImageInput.vue";
import NumberInput from "./NumberInput.vue";
import Sections from "./Sections/Index.vue";
import MiniSections from "./MiniSections/Index.vue";
import { mapGetters } from "vuex";
import Cart from "./Sections/Cart.vue";
import allOptionsMixin from "@/Mixins/allOptionsMixin";
import surveyFormulaMixin from "@/Mixins/surveyFormulaMixin";

export default {
    mixins: [allOptionsMixin, surveyFormulaMixin],
    beforeRouteLeave(to, from, next) {
        if (this.unsavedChanges) {
            this.$swal({
                title: this.$t(
                    "Do you really want to leave? you have unsaved changes!"
                ),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    next();
                } else {
                    next(false);
                }
            });
        } else {
            next();
        }
    },
    components: {
        Cart,
        MiniSections,
        Sections,
        SelectProductModal,
        QuillTextEditor,
        TextInput,
        TextAreaInput,
        SelectInput,
        ImageInput,
        MonacoEditor,
        Modal,
        NumberInput,
    },
    async mounted() {
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        const response = await this.$store.dispatch(
            "products/productsWithQuantity",
            {
                type: "software",
            }
        );
        this.products = response?.data?.products ?? this.products;
    },
    data() {
        return {
            returnPage: "",
            cartToggle: false,
            minimizeCart: false,
            minimizeSteps: false,
            unsavedChanges: false,
            manualProducts: [],
            selectedInputs: {},
            products: {
                data: [],
                links: [],
            },
            configurationToggle: false,
            styleBarToggle: false,
            numberInputProductsToggle: false,
            toggleError: false,
            validator: {},
            conditionsProductToggle: false,
            selectedCondition: null,
            selectedChapter: null,
            steps: [],
            cart: {
                products: [],
                config: [],
                addProductsManually: false,
                minimizeCart: false,
                minimizeSteps: false,
            },
            stylesConfiguration: {
                steps: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                questionDetails: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                },
                cart: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                productDetails: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                layout: {
                    id: "main-container",
                    type: "row",
                    customCss: "min-height: 100vh;",
                    children: [
                        {
                            id: "steps",
                            type: "column",
                            children: [],
                            value: "25%",
                            contains: "steps",
                        },
                        {
                            id: "right-column",
                            type: "column",
                            children: [
                                {
                                    id: "question",
                                    type: "row",
                                    children: [],
                                    value: "minmax(300px, max-content)",
                                    contains: "question",
                                },
                                {
                                    id: "bottom-row",
                                    type: "row",
                                    children: [
                                        {
                                            id: "details",
                                            type: "column",
                                            children: [],
                                            value: "60%",
                                            contains: "details",
                                        },
                                        {
                                            id: "cart",
                                            type: "column",
                                            children: [],
                                            value: "40%",
                                            contains: "cart",
                                        },
                                    ],
                                    value: "auto",
                                    contains: null,
                                },
                            ],
                            value: "75%",
                            contains: null,
                        },
                    ],
                    value: "100%",
                    contains: null,
                },
            },
            chapters: [],
            option: {},
            productToggle: false,
            details: "",
            questions: [],
            selectedQuestion: null,

            name: "",
        };
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("surveys", {
            surveyId: "id",
            errorList: "errorList",
        }),
        filteredQuestion() {
            return this.questions.filter((question) =>
                this.selectedQuestion
                    ? this.selectedQuestion.id != question.id
                    : true
            );
        },
    },
    methods: {
        /**
         * set the configuration type of the selected question to an empty string
         * this method is only called when we remove the image type from configuration when configuration type was set to
         * image-input
         */
        removeSelectedQuestionConfigurationType() {
            this.selectedQuestion.configuration.type = "";
        },
        /**
         * checks if the type that was selected for an option for the selected question is an 'image-input'
         * if it is then set the condfiguration type to 'image-input'. For all the other types we do not need to set the
         * configuration type because we have a type set for each option added by the user. Also we have to make sure that
         * if an image type was selected we remove all the other options for the selected question since image-input cannot
         * contain an option
         * @param {type} the option type being selected
         */
        setSelectedQuestionType(type) {
            if (type === "image-input") {
                this.selectedQuestion.configuration.type = type;
                this.selectedQuestion.configuration.groups.splice(
                    0,
                    this.selectedQuestion.configuration.groups.length
                ); // remove all the options added for the selected question
                this.selectedQuestion.configuration.conditions.splice(
                    0,
                    this.selectedQuestion.configuration.conditions.length
                ); // remove all the conditions added for the selected question
            }
        },
        /**
         * removes the condition from the conditions object of all the options that are part of the deleted condition
         * @param {condition} the condition to be deleted
         */
        removeConditionFromOptions(condition) {
            /**
             * loop over every subCondition of the to be deleted condition
             * here 'options' represents the subConditions
             */
            condition.options.forEach((subCondition) => {
                try {
                    /**
                     * Each subCondition has the option that was selected for that subCondition
                     * we can make use of that option to delete the condition from the conditions object of the option
                     * wrap in try/catch because delete can result in an error
                     */
                    delete subCondition.option.conditions[condition.id];
                } catch (e) {
                    console.log(e);
                }
            });
        },
        /**
         * adds the condition to the selected option's condition object
         * trigerred when the option is selected for any condition
         * @param {oldValue} the previous value before the on change event is triggred for the select input
         * @param {newValue} the selected value on change
         * @param {condition} the condition to be added to the option's condition object
         * @param {conditionOptionIndex} index of the condition option (a condition can have several sub conditions, a condition option is that sub condition)
         */
        addConditionToSelectedOption(
            { oldValue, newValue },
            condition,
            conditionOptionIndex
        ) {
            let option = null;
            let subConditions = [];
            // since the the value has been changed we need to remove the condition from the previously selected option
            if (typeof oldValue === "object") {
                // check if oldValue exists and is object
                option = { ...oldValue };
                // grab the subConditions array of the selected option
                subConditions =
                    option.conditions[condition.id]?.subConditions ?? [];
                // find the index of conditionOptionIndex in the subConditions array
                const subConditionIndex = subConditions.findIndex(
                    (subCondition) => subCondition == conditionOptionIndex
                );
                // remove the subCondition from the subConditions array based on the computed subConditionIndex
                subConditions.splice(subConditionIndex, 1);
                // update the option since the subConditions array has changed
                option.conditions[condition.id].subConditions = subConditions;
                /**
                 * if the subConditions array becomes empty this means that the current option is not part of any condition
                 * hence we check the length and if 0 we delete the condition from the conditions object of the selected option
                 */
                if (subConditions.length == 0) {
                    // wrap in try/catch since the delete could result in an error if the condition does not exist
                    try {
                        delete option.conditions[condition.id];
                    } catch (e) {
                        console.log(e);
                    }
                }
                this.addOptionMixin(option); // update the option in the allOptions state in survey store
            }
            //check if newValue is spreadable and exists
            if (typeof newValue === "object") {
                option = { ...newValue };
                // grab the subConditions array of the selected option
                subConditions =
                    option.conditions[condition.id]?.subConditions ?? [];
                /**
                 * push the 'conditionOptionIndex' in the 'subConditions' and assign it to the option
                 */
                subConditions.push(conditionOptionIndex);
                option.conditions[condition.id] = {
                    subConditions: Array.from(new Set(subConditions)), // get only the unique values from the subConditions array since there can be duplicates
                };
                this.addOptionMixin(option); // update the option in the allOptions state in survey store
            }
        },

        async cartConfigToggled(event, type) {
            if (type === "minimizeCart")
                this.minimizeCart = event.target.checked;
            else if (type === "minimizeSteps")
                this.minimizeSteps = event.target.checked;
            else this.cart.addProductsManually = event.target.checked;
            if (!this.minimizeSteps)
                try {
                    this.stylesConfiguration.layout = JSON.parse(`{
                    "id": "main-container",
                    "type": "row",
                    "customCss": "min-height: 100vh;",
                    "children": [
                        {
                            "id": "steps",
                            "type": "column",
                            "children": [],
                            "value": "25%",
                            "contains": "steps"
                        },
                        {
                            "id": "right-column",
                            "type": "column",
                            "children": [
                                {
                                    "id": "question",
                                    "type": "row",
                                    "children": [],
                                    "value": "minmax(300px, max-content)",
                                    "contains": "question"
                                },
                                {
                                    "id": "bottom-row",
                                    "type": "row",
                                    "children": [
                                        {
                                            "id": "details",
                                            "type": "column",
                                            "children": [],
                                            "value": "${
                                                this.minimizeCart
                                                    ? "100%"
                                                    : "60%"
                                            }",
                                            "contains": "details"
                                        }
                                        ${
                                            this.minimizeCart
                                                ? ""
                                                : `,{
                                            "id": "cart",
                                            "type": "column",
                                            "children": [],
                                            "value": "40%",
                                            "contains": "cart"
                                        }`
                                        }
                                    ],
                                    "value": "auto",
                                    "contains": null
                                }
                            ],
                            "value": "75%",
                            "contains": null
                        }
                    ],
                    "value": "100%",
                    "contains": null
                }`);
                } catch (e) {
                    console.log(e);
                }
            else
                try {
                    this.stylesConfiguration.layout = JSON.parse(`{
                    "id": "main-container",
                    "type": "row",
                    "customCss": "min-height: 100vh;",
                    "children": [
                        {
                        "id": "top",
                        "type": "column",
                        "children": [
                            {
                            "id": "steps",
                            "type": "column",
                            "children": [],
                            "value": "25%",
                            "contains": "steps"
                            },
                            {
                            "id": "questions",
                            "type": "column",
                            "children": [],
                            "value": "75%",
                            "contains": "question"
                            }
                        ],
                        "value": "minmax(300px, max-content)",
                        "contains": null
                        },
                        {
                        "id": "bottom",
                        "type": "row",
                        "children": [
                            {
                                "id": "details",
                                "type": "column",
                                "children": [],
                                "value": "${
                                    this.minimizeCart ? "100%" : "60%"
                                }",
                                    "contains": "details"
                                }
                                ${
                                    this.minimizeCart
                                        ? ""
                                        : `,{
                                "id": "cart",
                                "type": "column",
                                "children": [],
                                "value": "40%",
                                "contains": "cart"
                            }`
                                }
                        ],
                        "value": "auto",
                        "contains": null
                        }
                    ],
                    "value": "100%",
                    "contains": null
                    }
                `);
                } catch (e) {
                    console.log(e);
                }
            const response = await this.$store.dispatch(
                `surveys/${this.surveyId ? "update" : "create"}`,
                this.surveyId
                    ? {
                          id: this.surveyId,
                          data: {
                              addProductsManually:
                                  type === "addProductsManually"
                                      ? event.target.checked
                                      : this.cart.addProductsManually,
                              minimizeCart:
                                  type === "minimizeCart"
                                      ? event.target.checked
                                      : this.cart.minimizeCart,
                              minimizeSteps:
                                  type === "minimizeSteps"
                                      ? event.target.checked
                                      : this.cart.minimizeSteps,
                          },
                      }
                    : {
                          addProductsManually:
                              type === "addProductsManually"
                                  ? event.target.checked
                                  : this.cart.addProductsManually,
                          minimizeCart:
                              type === "minimizeCart"
                                  ? event.target.checked
                                  : this.cart.minimizeCart,
                          minimizeSteps:
                              type === "minimizeSteps"
                                  ? event.target.checked
                                  : this.cart.minimizeSteps,
                      }
            );
            await this.$store.dispatch("surveys/saveStylesConfigurations", {
                ...this.stylesConfiguration,
                surveyId: this.surveyId,
            });
        },
        exportJson() {
            try {
                const surveyJson = {
                    id: this.surveyId,
                    name: "Survey 1",
                    steps: this.steps,
                    cart: this.cart,
                    stylesConfiguration: {
                        ...this.stylesConfiguration,
                    },
                };
                let fileContents = `const surveyJson = ${JSON.stringify(
                    surveyJson
                )}`;

                let fileName = "surveyJson.js";
                let blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                let url = URL.createObjectURL(blob);
                let link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
                fileContents = SurveyLibrary.toString();

                fileName = "survey.js";
                blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                url = URL.createObjectURL(blob);
                link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
                let surveyCss = `:deep(.custom-action-grid) {
    display: grid;
    grid-template-columns: 30% 70%;
    gap: 10px;
}

.flash-message {
    position: absolute;
    right: 2%;
    top: 3%;
    z-index: 9999 !important;
}

.justify-evenly{
    justify-content: space-evenly;
}

.question:hover {
    background: #eee;
    color: #000;
    border: 1px solid #aaa;
}

.question:hover>.cross {
    display: block;
}

.chapter:hover>.cross {
    display: block;
}

ul {
    margin-left: 20px;
}

.chapter li {
    list-style-type: none;
    margin: 10px 0 10px 10px;
    position: relative;
}

.chapter .question:before {
    content: "";
    position: absolute;
    top: -13px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 27px;
}

.chapter .question-title-input:before {
    content: "";
    position: absolute;
    top: -20px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 40px;
}

.chapter li:after {
    position: absolute;
    content: "";
    top: 5px;
    left: -20px;
    border-left: 1px solid #ddd;
    width: 20px;
    height: 100%;
}
.chapter li:last-child:after {
    display: none;
}
.chapter li span {
    display: block;
    color: #000;
    text-decoration: none;
}
.chapter li span:hover,
.chapter li span:focus {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li span,
.chapter li span:focus + ul li span {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li:after,
.chapter li span:focus + ul li:after,
.chapter li span:hover + ul li:before,
.chapter li span:focus + ul li:before {
    border-color: #aaa;
}

.main-div {
    padding: 2rem;
    display: grid;
    grid-template-columns: 20% 80%;
    gap: 1rem;
}

.mid-card {
    display: grid;
    grid-template-rows: 1fr 2fr;
    gap: 1rem;
}

.bottom-card {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 1rem;
}

.card {
    background-color: white;
    border: 1px solid #eeeeee;
    /* darker white */
}

.card-title {
    color: black;
    background-color: #fafafa;
}

.question-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.option-card {
    border: 1px solid #eeeeee;
}

.cross {
    position: absolute;
    right: 10px;
}

.top-4 {
    top: 4px;
}

.conditions {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr;
    gap: 0.2rem;
}

.conditions-number {
    grid-template-columns: 1fr 2fr 2fr 2fr !important;
}

:deep(.ql-container) {
    min-height: 300px;
}`;
                fileContents = surveyCss;

                fileName = "survey.css";
                blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                url = URL.createObjectURL(blob);
                link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
                let surveyHtml = `<!-- this is a sample html file to demostrate how the survey can be implemented  -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.tailwindcss.com"><\/script>
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"><\/script>
        <script src="https://unpkg.com/@vueup/vue-quill@latest"><\/script>
        <link
            rel="stylesheet"
            href="https://unpkg.com/@vueup/vue-quill@latest/dist/vue-quill.snow.prod.css"
        />
        <link rel="stylesheet" href="./survey.css" /> <!-- link the dowloaded survey.css file -->
        <title>Survey Viewer</title>
    </head>
    <body>
        <!-- container in which the survey will render -->
        <div class="survey-container"></div>
    </body>
    <script src="./survey.js"><\/script> <!-- link the dowloaded survey.js file -->
    <script src="./surveyJson.js"><\/script> <!-- get the survey JSON from the dowloaded survey.json file -->
    <script>
        // create a survey object by passing the JSON and the identifier of the container element to render the survey
        const baseUrl = "http://127.0.0.1:8000"; // the base url for the API routes of survey APIs
        const survey = new Survey(surveyJson, ".survey-container", baseUrl);
        // call this method on the survey object to render the survey
        survey.createSurvey();
    <\/script>
</html>`;
                fileContents = surveyHtml;

                fileName = "survey.html";
                blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                url = URL.createObjectURL(blob);
                link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * saves the surveyJson to local storage
         */
        saveSurveyJsonToLocalStorage() {
            const surveyJson = {
                id: this.$route.params.id ?? this.surveyId,
                name: this.name,
                steps: this.steps,
                cart: this.cart,
                stylesConfiguration: {
                    ...this.stylesConfiguration,
                },
            };
            localStorage.setItem("surveyJson", JSON.stringify(surveyJson));
        },
        async save() {
            try {
                this.unsavedChanges = true;
                this.saveSurveyJsonToLocalStorage();
                for (let i = 0; i < this.steps.length; i++) {
                    let payload = {};
                    payload = JSON.parse(
                        JSON.stringify(
                            this.steps[i].type === "chapter"
                                ? this.steps[i]?.value
                                : this.questions.find(
                                      (question) =>
                                          question.id ==
                                              this.steps[i].value?.id ?? {}
                                  )
                        )
                    );
                    payload.configuration.groups.forEach((group) => {
                        if (group?.options) {
                            // stores the complete option object from 'allOptions' state
                            let options = [];
                            group?.options.forEach((option) => {
                                this.allOptions[option].products =
                                    this.allOptions[option].products.map(
                                        (product) => {
                                            return {
                                                id: product.id,
                                                quantity: product.quantity,
                                            };
                                        }
                                    );
                                /**
                                 * since we are only storing the option ids into the options array in configuration of a question
                                 * we need to get the complete option object from the 'allOptions' state, the reason being, backend
                                 * accepts the complete option object with all it's values. Hence we store it in the 'options' variable created
                                 * above
                                 */
                                options = [...options, this.allOptions[option]];
                            });
                            // set the 'options' variable value to the configurations so that it can be sent with the payload
                            group.options = options;
                        }
                    });
                    if (payload?.configuration?.conditions)
                        payload.configuration.conditions.forEach(
                            (condition) => {
                                condition.products = condition.products.map(
                                    (product) => {
                                        return {
                                            id: product.id,
                                            quantity: product.quantity,
                                        };
                                    }
                                );
                            }
                        );
                    try {
                        await this.$store.dispatch(
                            `${
                                this.steps[i].type === "chapter"
                                    ? "chapters"
                                    : "questions"
                            }/update`,
                            {
                                id: payload?.value?.id ?? payload?.id,
                                data: payload?.value ?? payload,
                            }
                        );
                    } catch (e) {
                        let errorKey = "";
                        if (
                            Object.keys(
                                e?.cause?.response?.data?.errors ?? {}
                            ).some((key) => {
                                if (
                                    key.includes("configuration.options") &&
                                    key.includes("title") &&
                                    e?.cause?.response?.data?.errors?.[
                                        key
                                    ]?.[0]?.includes("duplicate")
                                ) {
                                    errorKey = key;
                                    return true;
                                } else return false;
                            })
                        ) {
                            const selectedQuestion =
                                this.questions?.find(
                                    (question) => question.id == payload.id
                                ) ?? {};
                            const option =
                                selectedQuestion?.configuration?.options?.[
                                    errorKey.match(/\d+/)?.[0]
                                ];
                            let errorList = { ...this.errorList };
                            errorList[this.allOptions[option].id] =
                                "Title must be distinct";
                            this.$store.commit("surveys/errorList", errorList);
                            this.selectedQuestion = { ...selectedQuestion };
                            this.configurationToggle = true;
                        } else if (
                            Object.keys(
                                e?.cause?.response?.data?.errors ?? {}
                            ).some((key) => {
                                if (
                                    key.includes("configuration.options") &&
                                    key.includes("title")
                                ) {
                                    errorKey = key;
                                    return true;
                                } else return false;
                            })
                        ) {
                            const selectedQuestion =
                                this.questions?.find(
                                    (question) => question.id == payload.id
                                ) ?? {};
                            const option =
                                selectedQuestion?.configuration?.options?.[
                                    errorKey.match(/\d+/)?.[0]
                                ];
                            let errorList = { ...this.errorList };
                            errorList[this.allOptions[option].id] =
                                "Title is a required field";
                            this.$store.commit("surveys/errorList", errorList);
                            this.selectedQuestion = { ...selectedQuestion };
                            this.configurationToggle = true;
                        }
                    }
                    for (
                        let j = 0;
                        j <
                        (this.steps[i].type === "chapter"
                            ? this.steps[i].value.questions.length
                            : 0);
                        j++
                    ) {
                        payload = this.steps[i].value.questions[j];
                        await this.$store.dispatch("questions/update", {
                            id: payload?.value?.id ?? payload?.id,
                            data: payload?.value ?? payload,
                        });
                    }
                    if (this.surveyId || this.$route.params.id) {
                        await this.$store.dispatch("surveys/update", {
                            id: this.$route.params.id ?? this.surveyId,
                            data: {
                                addProductsManually: this.cart
                                    .addProductsManually
                                    ? 1
                                    : 0,
                                minimizeCart: this.cart.minimizeCart ? 1 : 0,
                                minimizeSteps: this.cart.minimizeSteps ? 1 : 0,
                                name: this.name,
                            },
                        });
                    }
                }
                await this.$store.dispatch("surveys/saveStylesConfigurations", {
                    ...this.stylesConfiguration,
                    surveyId: this.$route.params.id ?? this.surveyId,
                });
                await this.$store.dispatch("surveyConfiguration/create", {
                    surveyConfigurations: this.cart.config.map((config) => {
                        return {
                            ...config,
                            surveyId: this.$route.params.id,
                        };
                    }),
                    surveyId: this.$route.params.id,
                });
                this.unsavedChanges = false;
            } catch (e) {
                this.unsavedChanges = false;
                console.log(e);
            }
        },
        cloneQuestion(params) {
            return params.value;
        },
        nextQuestionChanged(event) {
            this.selectedQuestion.next = event.target.value;
        },
        openConditionProductsModal(index) {
            this.selectedCondition =
                this.selectedQuestion.configuration.conditions[index];
            this.conditionsProductToggle = true;
        },
        addConditionsProduct(products) {
            this.selectedCondition.products = products.map((product) => ({
                ...product,
                parentId: this.selectedCondition.id,
            }));
            this.conditionsProductToggle = false;
            // update the selected condition in the allConditions state
            this.addConditionMixin(this.selectedCondition);
            this.selectedCondition = null;
        },
        removeCondition(index) {
            this.removeConditionFromOptions(
                this.selectedQuestion.configuration.conditions[index]
            );
            // get the removed condition
            const removedCondition =
                this.selectedQuestion.configuration.conditions.splice(index, 1);
            this.removeConditionMixin(removedCondition?.[0]?.id); // remove the deleted condition from the allConditions state
        },
        addOptionsCondition(index) {
            this.selectedQuestion.configuration.conditions[index].options.push({
                option: "",
                condition: "",
                operator: "&&",
                value:
                    this.selectedQuestion.configuration.type === "number-input"
                        ? 0
                        : "",
            });
            // update the selected condition in the allConditions state
            this.addConditionMixin(
                this.selectedQuestion.configuration.conditions[index]
            );
        },
        addCondition() {
            const condition = {
                id: uuidv4().replaceAll("-", ""),
                discount: "",
                products: [],
                options: [
                    {
                        option: "",
                        condition: "",
                        operator: "if",
                    },
                ],
                isSatisfied: false,
                next: "",
            };
            // add condition to the allConditions state
            this.addConditionMixin(condition);
            this.selectedQuestion.configuration.conditions.push(condition);
        },
        openProductsModal(index, groupIndex) {
            this.option =
                this.allOptions[
                    this.selectedQuestion?.configuration?.groups?.[
                        groupIndex
                    ]?.options?.[index]
                ];
            this.productToggle = true;
        },
        openNumberInputProductsModal(index, groupIndex) {
            this.option =
                this.allOptions[
                    this.selectedQuestion?.configuration?.groups?.[
                        groupIndex
                    ]?.options?.[index]
                ];
            this.numberInputProductsToggle = true;
        },
        addProducts(products) {
            const modifiedProducts = products.map((product) => {
                return {
                    ...product,
                    parentId: this.option.id,
                };
            });
            this.option.products = modifiedProducts;
            this.productToggle = false;
        },
        addNumberInputProducts(products) {
            const modifiedProducts = products.map((product) => {
                return {
                    ...product,
                    parentId: this.option.id,
                };
            });
            this.option.products = modifiedProducts;
            this.numberInputProductsToggle = false;
        },
        /**
         * removes the option from the group as well as allOptions state
         * @param {index} index of the option to be removed
         * @param {groupIndex} index of the group that the removed option is a part of
         */
        removeOption(index, groupIndex) {
            const removedOption =
                this.selectedQuestion?.configuration?.groups?.[
                    groupIndex
                ]?.options?.splice(index, 1);
            // call the removeOptionMixin method to remove the option from the allOptions state
            this.removeOptionMixin(removedOption?.[0]); // removedOption = [id]
        },
        /**
         * Adds a group to the configuration of selected question
         */
        addGroup() {
            const uuid = uuidv4().replaceAll("-", "");
            const group = {
                id: uuid,
                type: "single-select",
                title: "",
                options: [],
            };
            this.selectedQuestion.configuration.groups.push(group);
        },
        addOption(index) {
            const uuid = uuidv4().replaceAll("-", "");
            const option = {
                id: uuid,
                uuid: uuid,
                title: "",
                value:
                    this.selectedQuestion.configuration.type ===
                        "number-input" ||
                    this.selectedQuestion.configuration.type === "number-slider"
                        ? 0
                        : "",
                type:
                    this.selectedQuestion?.configuration?.groups?.[index]
                        ?.type ?? "single-select",
                conditions: {},
                products: [],
                next: "",
                min: 0,
                max: null,
                step: 1,
                placeholder: "",
            };
            this.addOptionMixin(option); // add the option to the allOptions state in survey store
            this.selectedQuestion?.configuration?.groups?.[
                index
            ]?.options?.push(option.id);
        },
    },
};
</script>

<style lang="scss" scoped>
.justify-evenly {
    justify-content: space-evenly;
}

:deep(.custom-action-grid) {
    display: grid;
    grid-template-columns: 30% 70%;
    gap: 10px;
}

.question:hover {
    background: #eee;
    color: #000;
    border: 1px solid #aaa;
}

.question:hover > .cross {
    display: block;
}

.chapter:hover > .cross {
    display: block;
}

ul {
    margin-left: 20px;
}

.chapter li {
    list-style-type: none;
    margin: 10px 0 10px 10px;
    position: relative;
}

.chapter .question:before {
    content: "";
    position: absolute;
    top: -13px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 27px;
}

.chapter .question-title-input:before {
    content: "";
    position: absolute;
    top: -20px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 40px;
}

.chapter li:after {
    position: absolute;
    content: "";
    top: 5px;
    left: -20px;
    border-left: 1px solid #ddd;
    width: 20px;
    height: 100%;
}
.chapter li:last-child:after {
    display: none;
}
.chapter li span {
    display: block;
    color: #000;
    text-decoration: none;
}
.chapter li span:hover,
.chapter li span:focus {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li span,
.chapter li span:focus + ul li span {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li:after,
.chapter li span:focus + ul li:after,
.chapter li span:hover + ul li:before,
.chapter li span:focus + ul li:before {
    border-color: #aaa;
}

.main-div {
    display: grid;
    grid-template-columns: 1fr 3fr;
    gap: 1rem;
}

.mid-card {
    display: grid;
    grid-template-rows: 1fr 2fr;
    gap: 1rem;
}

.bottom-card {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 1rem;
}

.card {
    background-color: white;
    border: 1px solid #eeeeee; /* darker white */
}

.card-title {
    color: black;
    background-color: #fafafa;
}

.question-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.option-card {
    border: 1px solid #eeeeee;
}

.cross {
    color: black !important;
    position: absolute;
    right: 10px;
}

.tabs .cross {
    color: black !important;
    position: absolute;
}

.top-4 {
    top: 4px;
}

.conditions {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr;
    gap: 0.2rem;
}

.conditions-number {
    grid-template-columns: 1fr 2fr 2fr 2fr !important;
}

:deep(.ql-container) {
    min-height: 300px;
}

/* Accordion styles */
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: white;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        background: white;
        cursor: pointer;
        /* Icon */
        &:hover {
        }
        &::after {
            content: "\276F";
            text-align: center;
            transition: all 0.35s;
            color: black;
            position: absolute;
            right: 10px;
            margin-top: 15px;
        }
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        background: white;
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
.toggled-card::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

.toggled-card::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.toggled-card::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}
</style>
