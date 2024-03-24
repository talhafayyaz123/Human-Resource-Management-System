class Survey {
    surveyJson = null;
    container = null;
    baseUrl = "";
    constructor(surveyJson, container, baseUrl) {
        this.surveyJson = surveyJson;
        this.container = container;
        this.baseUrl = baseUrl;
    }
    Pagination = {
        template: `<div>
            <div class="flex flex-wrap -mb-1">
                <template v-for="link in links" :key="link">
                    <div
                        @click="changePage(link)"
                        :class="[
                            link == page
                                ? 'text-white bg-[#2957a4]'
                                : 'text-grey-400',
                            'mb-1',
                            'mr-1',
                            'px-4',
                            'py-3',
                            'text-sm',
                            'leading-4',
                            'border rounded',
                            'cursor-pointer',
                        ]"
                        v-html="link"
                    />
                </template>
            </div>
        </div>`,
        props: {
            count: Number | String,
            perPage: Number | String,
            start: Number | String,
            length: Number | String,
            currentPage: Number | String,
        },
        data() {
            return {
                page: this.currentPage,
            };
        },
        watch: {
            currentPage: {
                handler: function (val) {
                    this.page = val;
                },
                deep: true,
            },
        },
        methods: {
            changePage(link) {
                this.page = link;
                this.$emit("paginationInfo", {
                    start: +this.perPage * link - +this.perPage,
                    end: +this.perPage,
                    page: this.page,
                });
            },
        },
        computed: {
            links() {
                return Math.ceil(+this.count / +this.perPage);
            },
        },
    };
    SelectProductsModal = {
        template: `  <div>
            <div
              class="relative z-10"
              aria-labelledby="modal-title"
              role="dialog"
              aria-modal="true"
            >
              <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
              ></div>
        
              <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                  class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                  <div
                    style="width: 70%"
                    class="relative transform rounded-lg bg-white text-left shadow-xl transition-all"
                  >
                    <div>
                      <div class="container mx-auto px-4 sm:px-8">
                        <div class="py-8">
                          <div class="flex justify-between">
                            <h2 class="text-2xl font-semibold leading-tight">
                              Add {{ typeOfItem }}
                            </h2>
                            <div class="mb-3">
                              <div
                                class="input-group relative flex flex-wrap items-stretch w-60 mb-4 rounded"
                              >
                                <input
                                  type="search"
                                  class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                  placeholder="Search by article or name"
                                  aria-label="Search"
                                  v-model="search"
                                  aria-describedby="button-addon2"
                                  @keyup.enter="searchProduct(1)"
                                />
                                <span
                                  class="input-group-text flex items-center px-3 py-1.5 text-base font-normal text-gray-700 text-center whitespace-nowrap rounded"
                                  id="basic-addon2"
                                >
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                            <div
                              class="inline-block min-w-full shadow-md rounded-lg overflow-hidden"
                            >
                              <table class="min-w-full leading-normal">
                                <thead>
                                  <tr>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Article number") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Name") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Status") }}
                                    </th>
        
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Sale price") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Discount") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Quantity") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Profit") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Listing price") }}
                                    </th>
                                    <th
                                      class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                      {{ translate("Tax") }}
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr
                                    v-for="(item, index) in dataProducts?.data"
                                    :key="index"
                                  >
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      <div class="flex">
                                        <input
                                          @click="onSelected($event, item, index)"
                                          :checked="isInputChecked(item)"
                                          type="checkbox"
                                          class="border-gray-300 rounded h-5 w-5"
                                        />
                                        &nbsp;
        
                                        {{ item.articleNumber }}
                                      </div>
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      {{ item.name }}
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      {{ item.status }}
                                    </td>
                                    <td
                                      v-if="typeof item?.salePrice === 'string'"
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      {{
                                        item?.salePrice?.replace(
                                          /\B(?=(\d{3})+(?!\d))/g,
                                          ","
                                        )
                                      }}
                                    </td>
                                    <td
                                      v-else
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      {{ item?.salePrice ?? "" }}
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                    >
                                      {{ item.discount }}
                                    </td>
                                    <td
                                      :class="
                                        isJson(item.quantity)
                                          ? 'h-5rem'
                                          : '' +
                                            ' px-5 py-5 border-b border-gray-200 bg-white text-sm flex'
                                      "
                                    >
                                      <div
                                        v-if="
                                          !formulaInput &&
                                          isInputChecked(item) &&
                                          isEdit
                                        "
                                      >
                                        {{
                                          selectedProducts.find(
                                            (selected) => selected.id === item.id
                                          ).quantity
                                        }}
                                      </div>
                                      <input
                                        @keypress.enter="executeFormula(item, index)"
                                        v-model="item.quantity"
                                        v-else-if="
                                          formulaInput && !isJson(item.quantity)
                                        "
                                        type="text"
                                        +
                                        :class="
                                          'appearance-none border-2 w-24 ' +
                                          !checkForFormula(item.quantity)
                                            ? 'border-gray-200'
                                            : (success
                                                ? 'border-green-500'
                                                : 'border-red-500') +
                                              ' rounded py-2 px-2 text-gray-700 leading-tight'
                                        "
                                      />
                                      <input
                                        v-else-if="
                                          !checkForFormula(item.quantity) ||
                                          !isJson(item.quantity)
                                        "
                                        class="appearance-none border-2 w-24 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        type="number"
                                        @change="addChangeQuantity(item)"
                                        v-model="item.quantity"
                                      />
                                      <p
                                        class="self-center ml-2"
                                        v-if="isJson(item.quantity)"
                                      >
                                        {{ result(item.quantity) }}
                                      </p>
                                      <p
                                        class="self-center ml-2"
                                        v-if="
                                          checkForFormula(item.quantity) &&
                                          !isJson(item.quantity)
                                        "
                                      >
                                        {{ executeFormulaAndReturnResult(item) }}
                                      </p>
                                      <button
                                        @click="executeFormula(item, index)"
                                        v-if="
                                          checkForFormula(item.quantity) &&
                                          !isJson(item.quantity)
                                        "
                                        class="rounded ml-2 text-white px-2 bg-gray-500"
                                      >
                                        Add
                                      </button>
                                      <button
                                        v-if="formulaInput"
                                        @click="openModal(item)"
                                        :class="
                                          'rounded ml-2 text-white px-2 ' +
                                          'bg-' +
                                          isJson(item.quantity)
                                            ? 'green'
                                            : 'gray' + '-500'
                                        "
                                      >
                                        f(x)
                                      </button>
                                      <button
                                        @click="item.quantity = 1"
                                        v-if="isJson(item.quantity)"
                                        :class="'rounded ml-2 text-white px-2 bg-red-500'"
                                      >
                                        Remove
                                      </button>
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                      v-if="typeof item?.profit === 'string'"
                                    >
                                      {{
                                        item.profit?.replace(
                                          /\B(?=(\d{3})+(?!\d))/g,
                                          ","
                                        )
                                      }}
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                      v-if="typeof item?.listingPrice === 'string'"
                                    >
                                      {{
                                        item.listingPrice?.replace(
                                          /\B(?=(\d{3})+(?!\d))/g,
                                          ","
                                        )
                                      }}
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                      v-if="typeof item?.tax === 'string'"
                                    >
                                      {{
                                        item.tax?.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                      }}
                                    </td>
                                    <td
                                      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                      v-else
                                    >
                                      {{ item?.tax ?? "" }}
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="flex justify-center">
                      <br />
                      <br />
                    <pagination :limit="10"
                        align="center"
                        :count="products?.total ?? 0"
                        :perPage="perPage"
                        :start="start"
                        :length="products?.data?.length ?? []"
                        :currentPage="currentPage"
                        @paginationInfo="refresh($event)"
                    ></pagination>
                    </div>
                    <div
                      class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                      <button
                        type="button"
                        class="secondary-btn"
                        @click="onAdded"
                      >
                        {{ translate("Add") }}
                      </button>
                      <button
                        @click="onCancel"
                        type="button"
                        class="primary-btn mr-3"
                      >
                        {{ translate("Cancel") }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        components: {
            Pagination: this.Pagination,
        },
        props: {
            questions: { type: Array, default: () => [] },
            products: { type: Object, default: () => ({}) },
            selectedItems: { type: Array, default: () => [] },
            typeOfItem: { default: "Products", type: String },
            isEdit: { default: false, type: Boolean },
            fromOffers: { default: false, type: Boolean },
            fromOffersEdit: { default: false, type: Boolean },
            groupedBy: { default: "nothing", type: String },
            formulaInput: { default: false, type: Boolean },
            options: { default: [], type: Array },
            option: { default: {}, type: Object },
        },
        data() {
            return {
                selectedProduct: null,
                toggleModal: false,
                selectedProducts: [],
                search: "",
                dataProducts: JSON.parse(JSON.stringify(this.products)), //to remove reactivity
                success: false,
                currentPage: this.products?.current_page ?? 1,
                start: 0,
                perPage: 10,
            };
        },
        mounted() {
            this.recomputeTableProducts();
            const clonedValue = JSON.parse(JSON.stringify(this.selectedItems)); //added lodash because dont want two way data binding
            this.selectedProducts = clonedValue;
        },
        watch: {
            products() {
                this.dataProducts = JSON.parse(JSON.stringify(this.products));
                this.recomputeTableProducts();
            },
        },
        emits: ["refresh"],
        methods: {
            recomputeTableProducts() {
                this.selectedItems.forEach((product) => {
                    const productIndex = this.dataProducts.data.findIndex(
                        (pr) =>
                            pr.id ==
                            (this.fromOffersEdit
                                ? product?.productId ?? product.id
                                : product.id)
                    );
                    if (productIndex > -1) {
                        const foundProduct = {
                            ...(this.fromOffers
                                ? product?.product
                                    ? {
                                          ...product.product,
                                          quantity: product.quantity,
                                          tax: "" + product.tax,
                                          discount: "" + product.discount,
                                          maintenanceRate:
                                              "" + product.maintenanceRate,
                                          salePrice: "" + product.salePrice,
                                      }
                                    : product
                                : product),
                        };
                        this.dataProducts.data[productIndex] = {
                            ...foundProduct,
                            status:
                                typeof foundProduct.status === "string"
                                    ? foundProduct.status
                                    : foundProduct.status == 1
                                    ? "active"
                                    : "inactive",
                            quantity: this.checkForFormula(
                                foundProduct.quantity
                            )
                                ? (() => {
                                      let matches = foundProduct.quantity.match(
                                          /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                                      );
                                      let quantity = foundProduct.quantity;
                                      matches?.forEach((match) => {
                                          const foundOption = this.options.find(
                                              (option) => option.uuid === match
                                          );
                                          if (!foundOption) {
                                              return 1;
                                          }
                                          quantity = quantity.replace(
                                              "$" + match,
                                              "$" +
                                                  foundOption?.title.toLowerCase()
                                          );
                                      });
                                      return quantity;
                                  })()
                                : this.isJson(foundProduct.quantity)
                                ? this.result(foundProduct.quantity)
                                : foundProduct.quantity,
                        };
                    }
                });
            },
            refresh(event) {
                this.$emit("refresh", event);
            },
            translate(text) {
                return text;
            },
            result(quantity) {
                let numberInputs = JSON.parse(quantity);
                let result = 0;
                numberInputs.forEach((input) => {
                    if (input.operator) {
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${+this.calculateResult(input)}`
                        );
                    } else {
                        result = +this.calculateResult(input);
                    }
                });
                return result;
            },
            calculateResult(numberInput) {
                let result = 0;
                if (!numberInput.parenthesis.length) {
                    const option = this.options.find(
                        (option) => option.uuid == numberInput.id
                    );
                    result = +(option?.value ?? numberInput.value);
                } else {
                    const option = this.options.find(
                        (option) => option.uuid == numberInput.id
                    );
                    result = +(option?.value ?? numberInput.value);
                    numberInput.parenthesis.forEach((input) => {
                        // result = this.calculateResult(input);
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${this.calculateResult(input)}`
                        );
                    });
                }
                return result;
            },
            addFormula() {
                const productIndex = this.dataProducts?.data.findIndex(
                    (product) => product.id == this.selectedProduct.id
                );
                if (productIndex > -1) {
                    const product = this.dataProducts.data[productIndex];
                    product.quantity = JSON.stringify(
                        this.selectedProduct.quantity
                    );
                    this.dataProducts.data[productIndex] = { ...product };
                }
                this.toggleModal = false;
            },
            openModal(item) {
                this.selectedProduct = { ...item };
                this.selectedProduct["quantity"] = this.isJson(
                    this.selectedProduct["quantity"]
                )
                    ? JSON.parse(this.selectedProduct["quantity"])
                    : [
                          {
                              type: "single",
                              operator: null,
                              value: "",
                              parenthesis: [],
                          },
                      ];
                this.toggleModal = true;
            },
            checkForFormula(formula) {
                if (!formula) return false;
                try {
                    return parseInt(formula) ? false : true;
                } catch (e) {
                    return false;
                }
            },
            isJson(value) {
                if (!value) return false;
                try {
                    return typeof JSON.parse(value) === "object";
                } catch (e) {
                    return false;
                }
            },
            executeFormula(item, index) {
                if (item.quantity.charCodeAt(0) == "61") {
                    let matches = item.quantity.match(
                        /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                    );
                    let quantity = item.quantity;
                    let originalFormulaString = item.quantity;
                    matches?.forEach((match) => {
                        const foundOption = this.options.find(
                            (option) =>
                                option.title.toLowerCase() ===
                                match.toLowerCase()
                        );
                        quantity = quantity.replace(
                            "$" + match,
                            +foundOption?.value
                        );
                    });
                    try {
                        if (!isNaN(this.javascriptExecuter(quantity.slice(1))))
                            item.quantity = originalFormulaString;
                        else item.quantity = 1;
                    } catch (e) {
                        item.quantity = 1;
                    }
                }
            },
            executeFormulaAndReturnResult(item) {
                if (this.checkForFormula(item.quantity)) {
                    let matches = item?.quantity?.match(
                        /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                    );
                    let quantity = item.quantity;
                    matches?.forEach((match) => {
                        const foundOption = this.options.find(
                            (option) =>
                                option.title.toLowerCase() ===
                                match.toLowerCase()
                        );
                        if (!foundOption) {
                            return 1;
                        }
                        quantity = quantity.replace(
                            "$" + match,
                            +foundOption?.value
                        );
                    });
                    try {
                        quantity = this.javascriptExecuter(quantity.slice(1));
                        this.success = quantity === undefined ? false : true;
                    } catch (e) {
                        quantity = 1;
                        this.success = false;
                    }
                    return quantity;
                } else {
                    return item.quantity;
                }
            },
            onSelected(event, item) {
                if (event.target.checked) {
                    this.selectedProducts.push(item);
                } else {
                    this.selectedProducts = this.selectedProducts.filter(
                        (product) => product.id !== item.id
                    );
                }
            },
            addChangeQuantity(item) {
                try {
                    this.selectedProducts.find(
                        (selected) => selected.id === item.id
                    ).quantity = item.quantity;
                } catch (e) {}
            },
            isInputChecked(product) {
                const found = this.selectedProducts.some((el) => {
                    if (this.fromOffers) {
                        return (
                            (el?.product ? el?.product?.id : el.id) ===
                            product.id
                        );
                    } else {
                        return el.id === product.id;
                    }
                });
                return found;
            },
            onAdded() {
                this.$emit(
                    "selected",
                    this.selectedProducts.map((product) => {
                        const foundProduct =
                            this.dataProducts.data.find((pr) => {
                                return (
                                    pr.id ==
                                    (this.fromOffersEdit
                                        ? product?.productId ?? product.id
                                        : product.id)
                                );
                            }) ?? product;
                        return {
                            ...foundProduct,
                            quantity: this.checkForFormula(
                                foundProduct.quantity
                            )
                                ? (() => {
                                      let matches =
                                          foundProduct?.quantity?.match(
                                              /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                                          );
                                      let quantity = foundProduct.quantity;
                                      matches?.forEach((match) => {
                                          const foundOption = this.options.find(
                                              (option) =>
                                                  option.title.toLowerCase() ===
                                                  match.toLowerCase()
                                          );
                                          if (!foundOption) {
                                              return 1;
                                          }
                                          quantity = quantity.replace(
                                              "$" + match,
                                              "$" + foundOption?.uuid
                                          );
                                      });
                                      return quantity;
                                  })()
                                : foundProduct.quantity,
                        };
                    })
                );
            },
            onCancel() {
                this.$emit("cancelled");
            },
            async searchProduct(page = 1) {
                // try {
                //     const productResponse = await this.$store.dispatch(
                //         this.fromOffers
                //             ? "products/productsWithQuantity"
                //             : "products/productsGetWithSearchPagination",
                //         { page: page, search: this.search }
                //     );
                //     this.dataProducts = productResponse?.data;
                // } catch (e) {}
            },
        },
    };
    Steps = {
        template: `
            <h5
            :style="
              'color: ' +
              stylesConfiguration.steps?.cardHeaderTextColor +
              '; background-color: ' +
              stylesConfiguration.steps?.cardHeaderBgColor
            "
            class="text-gray-900 text-md leading-tight card-title font-medium pb-2 pt-2 text-center"
          >
            {{ translate("Steps") }}
          </h5>
          <div
            v-for="(step, stepIndex) in steps"
            :key="'step-' + stepIndex"
            class="steps-container"
            :style="
              (minimizeStepsParent ? 'max-height: 20rem;' : '') + 'overflow: auto'
            "
          >
            <div :id="'step-' + step?.value?.id" class="px-3 py-2 cursor-pointer">
              <div
                :class="[
                  'relative',
                  selectedQuestion?.id == step?.value?.id ? 'font-bold' : '',
                ]"
                v-if="step.type === 'question'"
                @click="
                  selectedQuestion = step.value;
                  $emit('selectedQuestionParent', selectedQuestion);
                "
              >
                <div class="question">
                  <p>{{ step.value.title }}</p>
                </div>
              </div>
              <div class="relative" v-else-if="step.type === 'chapter'">
                <div class="chapter">
                  <h1 class="title">{{ step.value.title }}</h1>
                </div>
                <ul class="chapter" style="margin-left: 20px">
                  <div
                    v-for="(question, questionIndex) in step.value.questions ?? []"
                    :key="'question-' + questionIndex"
                      class="pt-2"
                  >
                    <li
                      @click="
                        selectedQuestion = question.value;
                        $emit('selectedQuestionParent', selectedQuestion);
                      "
                      :class="[
                        'relative',
                        selectedQuestion?.id == question?.value?.id ? 'font-bold' : '',
                      ]"
                    >
                      <div :id="'step-' + question?.value?.id" class="question">
                        <span>{{ question?.value?.title }} </span>
                      </div>
                    </li>
                  </div>
                </ul>
              </div>
            </div>
          </div>
          <p v-if="!steps.length" class="text-center mt-2 text-gray-400">
            {{ translate("No Questions/Chapters") }}
          </p>
    `,
        props: [
            "minimizeStepsParent",
            "stepsParent",
            "selectedQuestionParent",
            "selectedChapterParent",
            "questionsParent",
            "stylesConfigurationParent",
            "validatorParent",
        ],
        emits: [
            "stepsParent",
            "selectedQuestionParent",
            "selectedChapterParent",
            "questionsParent",
            "stylesConfigurationParent",
            "validatorParent",
        ],
        mounted() {
            this.steps = this.stepsParent;
            this.selectedQuestion = this.selectedQuestionParent;
            this.selectedChapter = this.selectedChapterParent;
            this.questions = this.questionsParent;
            this.stylesConfiguration = this.stylesConfigurationParent;
            this.validator = this.validatorParent;
        },
        data() {
            return {
                steps: [],
                selectedQuestion: null,
                selectedChapter: null,
                questions: [],
                stylesConfiguration: {},
                validator: {},
            };
        },
        watch: {
            stylesConfigurationParent(val) {
                this.stylesConfiguration = val;
            },
            selectedQuestionParent(val) {
                this.selectedQuestion = val;
            },
            questionsParent(val) {
                this.questions = val;
            },
            stepsParent(val) {
                this.steps = val;
            },
        },
        methods: {
            translate(text) {
                return text;
            },
        },
    };
    Question = {
        template: `  <div class="flex flex-col justify-between">
            <div>
              <p class="text-center mt-2 text-gray-400" v-if="!selectedQuestion">
                {{ translate("No Question Selected") }}
              </p>
              <h5
                class="text-gray-900 text-md leading-tight font-medium pb-2 pt-2 text-center"
              >
                {{ selectedQuestion?.title }}
              </h5>
              <div v-if="selectedQuestion" class="px-3 pb-3 question-details">
                <div>
                  <p class="font-bold">
                    {{ selectedQuestion.text }}
                  </p>
                  <p
                    v-if="validator?.[selectedQuestion?.id]?.text?.error"
                    class="text-red-600"
                  >
                    {{ validator?.[selectedQuestion?.id]?.text?.message }}
                  </p>
                  <p style="max-height: 10rem; overflow: auto" class="mt-5 mb-5">
                    {{ selectedQuestion.description }}
                  </p>
                  <p
                    v-if="validator?.[selectedQuestion?.id]?.description?.error"
                    class="text-red-600"
                  >
                    {{ validator?.[selectedQuestion?.id]?.description?.message }}
                  </p>
                </div>
                <div>
                  <div
                    v-if="selectedQuestion.configuration.type == 'single-select'"
                    class="mt-3"
                  >
                    <div
                      v-for="(option, index) in selectedQuestion.configuration.options"
                      :key="'question-options-' + index"
                    >
                      <div v-if="option.title" class="grid grid-cols-2 gap-2 py-2">
                        <input
                          :checked="option.value !== '' || option.value === true"
                          @input="optionSelected(index, $event)"
                          name="question-options"
                          :id="'question-options-' + index"
                          class="self-center justify-self-end"
                          type="radio"
                          v-model="option.value"
                        />
                        <label
                          class="ml-3 justify-self-start"
                          :for="'question-options-' + index"
                          >{{ option.title }}</label
                        >
                      </div>
                    </div>
                  </div>
                  <div
                    v-else-if="selectedQuestion.configuration.type == 'multiple-select'"
                    class="mt-3"
                  >
                    <div
                      v-for="(option, index) in selectedQuestion.configuration.options"
                      :key="'question-options-' + option.id"
                    >
                      <div v-if="option.title" class="grid grid-cols-2 gap-2 py-2">
                        <input
                          :ref="'question-options-' + option.id"
                          @input="optionSelected(index, $event)"
                          name="question-options"
                          :id="'question-options-' + index"
                          class="self-center justify-self-end"
                          type="checkbox"
                          v-model="option.value"
                        />
                        <label
                          class="ml-3 justify-self-start"
                          :for="'question-options-' + index"
                          >{{ option.title }}</label
                        >
                      </div>
                    </div>
                  </div>
                  <div
                    v-else-if="selectedQuestion.configuration.type == 'image-input'"
                    class="mt-3"
                  >
                    <!-- <image-input @images="addImages" /> -->
                  </div>
                  <div
                    v-else-if="selectedQuestion.configuration.type == 'number-input'"
                    class="mt-3"
                  >
                    <div
                      v-for="(option, index) in selectedQuestion.configuration.options"
                      :key="'question-options-' + option.id"
                    >
                      <div v-if="option.title" class="grid grid-cols-2 gap-2 py-2">
                        <label
                          class="ml-3 self-center justify-self-end"
                          :for="'question-options-' + index"
                          >{{ option.title }}:</label
                        >
                        <input
                          data-previous-value="0"
                          class="self-center ml-2 border justify-self-start"
                          :ref="'question-options-' + option.id"
                          @input="optionSelected(index, $event)"
                          name="question-options"
                          :id="'question-options-' + index"
                          label=""
                          placeholder=""
                          type="number"
                          :min="option.min"
                          :max="option.max"
                          :step="option.step"
                          :value="option.value ? option.value : 0"
                        />
                      </div>
                    </div>
                  </div>
                  <div
                    v-else-if="selectedQuestion.configuration.type == 'number-slider'"
                    class="mt-3"
                  >
                    <div
                      v-for="(option, index) in selectedQuestion.configuration.options"
                      :key="'question-options-' + option.id"
                    >
                      <div v-if="option.title" class="grid grid-cols-2 gap-2 py-2">
                        <div class="ml-3 self-center justify-self-end">
                          <label :for="'question-options-' + index">{{
                            option.title
                          }}</label>
                        </div>
                        <div class="flex justify-self-start">
                          <input
                            data-previous-value="0"
                            class="self-center ml-2"
                            :ref="'question-options-' + option.id"
                            @input="optionSelected(index, $event)"
                            name="question-options"
                            :id="'question-options-' + index"
                            label=""
                            placeholder=""
                            type="range"
                            :min="option.min"
                            :max="option.max"
                            :step="option.step"
                            :value="option.value ? option.value : 0"
                          />
                          <p class="ml-2">
                            {{ option.value }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    v-else-if="selectedQuestion.configuration.type == 'text-input'"
                    class="mt-3"
                  >
                    <div
                      v-for="(option, index) in selectedQuestion.configuration.options"
                      :key="'question-options-' + option.id"
                    >
                      <div></div>
                      <div v-if="option.title" class="grid grid-cols-2 gap-2 py-2">
                        <label
                          class="ml-3 self-center justify-self-end"
                          :for="'question-options-' + index"
                          >{{ option.title }}</label
                        >
                        <input
                          class="self-center ml-2 justify-self-start"
                          :ref="'question-options-' + option.id"
                          @input="optionSelected(index, $event)"
                          name="question-options"
                          :id="'question-options-' + index"
                          label=""
                          :placeholder="option.placeholder"
                          type="text"
                          v-model="option.value"
                        />
                      </div>
                      <div></div>
                    </div>
                  </div>
                  <p
                    v-if="validator?.[selectedQuestion?.id]?.value?.error"
                    class="text-red-600 text-center"
                  >
                    {{ validator?.[selectedQuestion?.id]?.value?.message }}
                  </p>
                </div>
              </div>
            </div>
            <div v-if="selectedQuestion" class="flex justify-center py-3">
              <button
                @click="back"
                type="button"
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-gray-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
              >
                {{ translate("Back") }}
              </button>
              <button
                @click="next"
                type="button"
                class="docsHeroColorBlue secondary-btn"
              >
                {{ translate("Next") }}
              </button>
            </div>
          </div>`,
        props: [
            "selectedQuestionParent",
            "questionsParent",
            "validatorParent",
            "cartParent",
            "manualProducts",
        ],
        emits: [
            "selectedQuestionParent",
            "questionsParent",
            "validatorParent",
            "cartParent",
        ],
        mounted() {
            this.selectedQuestion = this.selectedQuestionParent;
            this.questions = this.questionsParent;
            this.validator = this.validatorParent;
            this.cart = this.cartParent;
        },
        data() {
            return {
                selectedInputs: {},
                selectedQuestion: null,
                questions: [],
                validator: {},
                cart: [],
            };
        },
        watch: {
            questionsParent(val) {
                this.questions = val;
            },
            selectedQuestionParent(val) {
                this.selectedQuestion = val;
            },
            cartParent(val) {
                this.cart = val;
            },
            validatorParent(val) {
                this.validator = val;
            },
        },
        methods: {
            translate(text) {
                return text;
            },
            addImages(images) {
                this.$nextTick(() => {
                    if (this.selectedInputs[this.selectedQuestion.id]) {
                        this.selectedInputs[this.selectedQuestion.id][
                            "images"
                        ] = [];
                        images.forEach((image) => {
                            var reader = new FileReader();
                            reader.onload = () => {
                                const base64String = reader.result;
                                this.selectedInputs[this.selectedQuestion.id][
                                    "images"
                                ].push(base64String);
                            };
                            reader.readAsDataURL(image);
                        });
                    } else {
                        this.selectedInputs[this.selectedQuestion.id] = {
                            images: [],
                        };
                        images.forEach((image) => {
                            var reader = new FileReader();
                            reader.onload = () => {
                                const base64String = reader.result;
                                this.selectedInputs[this.selectedQuestion.id][
                                    "images"
                                ].push(base64String);
                            };
                            reader.readAsDataURL(image);
                        });
                    }
                    this.$emit("selectedInputs", { ...this.selectedInputs });
                });
            },
            async optionSelected(index, event) {
                await this.$nextTick();
                if (
                    this.selectedQuestion?.configuration?.type ===
                    "single-select"
                ) {
                    this.selectedQuestion.configuration.options.forEach(
                        (option) => {
                            let selectedProducts = [...option.products];
                            if (
                                option.id ==
                                this.selectedQuestion.configuration.options[
                                    index
                                ].id
                            ) {
                                this.selectedQuestion.next = option.next;
                                this.$nextTick(() => {
                                    if (
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ]
                                    ) {
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ]["options"][option.id] = { ...option };
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ]["options"][option.id].value =
                                            event.target.value;
                                    } else {
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ] = {
                                            options: {},
                                            conditions: {},
                                        };
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ]["options"][option.id] = { ...option };
                                        this.selectedInputs[
                                            this.selectedQuestion.id
                                        ]["options"][option.id].value =
                                            event.target.value;
                                    }
                                });
                                selectedProducts.forEach((product) => {
                                    let parsedQuantity = this.checkForFormula(
                                        product.quantity
                                    )
                                        ? this.executeFormula(product)
                                        : this.isJson(product.quantity)
                                        ? this.result(product.quantity)
                                        : product.quantity;
                                    if (
                                        this.cart.products.some(
                                            (cp) => cp.id == product.id
                                        )
                                    ) {
                                        const foundProductIndex =
                                            this.cart.products.findIndex(
                                                (selectedProduct) =>
                                                    selectedProduct.id ==
                                                    product.id
                                            );
                                        const foundProduct = {
                                            ...this.cart.products[
                                                foundProductIndex
                                            ],
                                        };
                                        foundProduct.quantity = +parsedQuantity;
                                        foundProduct?.belongsTo?.forEach(
                                            (optionId) => {
                                                if (optionId != option.uuid) {
                                                    let foundOption = {};
                                                    for (
                                                        let i = 0;
                                                        i <
                                                        this.questions.length;
                                                        i++
                                                    ) {
                                                        const question =
                                                            this.questions[i];
                                                        foundOption =
                                                            (
                                                                question?.value ??
                                                                question
                                                            ).configuration.options.find(
                                                                (op) =>
                                                                    op.id ==
                                                                    optionId
                                                            ) ??
                                                            (
                                                                question?.value ??
                                                                question
                                                            ).configuration.conditions.find(
                                                                (cond) =>
                                                                    cond.id ==
                                                                    optionId
                                                            ) ??
                                                            {};
                                                        if (
                                                            Object.keys(
                                                                foundOption
                                                            ).length > 0
                                                        ) {
                                                            break;
                                                        }
                                                    }
                                                    const foundProductOther =
                                                        optionId === "manual"
                                                            ? this.manualProducts.find(
                                                                  (pr) =>
                                                                      pr.id ==
                                                                      product.id
                                                              )
                                                            : {
                                                                  ...foundOption?.products?.find(
                                                                      (pr) =>
                                                                          pr.id ==
                                                                              product.id ??
                                                                          {}
                                                                  ),
                                                              };
                                                    const parsedQuantityFoundProduct =
                                                        this.checkForFormula(
                                                            foundProductOther.quantity
                                                        )
                                                            ? this.executeFormula(
                                                                  foundProductOther
                                                              )
                                                            : this.isJson(
                                                                  foundProductOther.quantity
                                                              )
                                                            ? this.result(
                                                                  foundProductOther.quantity
                                                              )
                                                            : foundProductOther.quantity;
                                                    foundProduct.quantity =
                                                        +foundProduct.quantity +
                                                        +parsedQuantityFoundProduct;
                                                }
                                            }
                                        );
                                        foundProduct.salePrice = (
                                            +foundProduct.quantity *
                                            +product.salePrice
                                        ).toString();
                                        foundProduct?.belongsTo?.push(
                                            option.uuid
                                        );
                                        const uniqueItems = Array.from(
                                            new Set(foundProduct?.belongsTo)
                                        );
                                        foundProduct.belongsTo = [
                                            ...uniqueItems,
                                        ];
                                        this.cart.products[foundProductIndex] =
                                            {
                                                ...foundProduct,
                                            };
                                    } else {
                                        const cartProduct = { ...product };
                                        cartProduct.belongsTo = [option.uuid];
                                        cartProduct.quantity = +parsedQuantity;
                                        cartProduct.salePrice =
                                            +parsedQuantity *
                                            +product.salePrice;
                                        this.cart.products.push({
                                            ...cartProduct,
                                        });
                                    }
                                });
                            } else {
                                delete this.selectedInputs?.[
                                    this.selectedQuestion.id
                                ]?.[option.id];
                                selectedProducts.forEach((product) => {
                                    let parsedQuantity = this.checkForFormula(
                                        product.quantity
                                    )
                                        ? this.executeFormula(product)
                                        : this.isJson(product.quantity)
                                        ? this.result(product.quantity)
                                        : product.quantity;
                                    const foundProductIndex =
                                        this.cart.products.findIndex(
                                            (selectedProduct) =>
                                                selectedProduct.id == product.id
                                        );
                                    if (foundProductIndex == -1) return;
                                    const foundProduct = {
                                        ...this.cart.products[
                                            foundProductIndex
                                        ],
                                    };
                                    if (
                                        foundProduct?.belongsTo?.includes(
                                            option.uuid
                                        )
                                    ) {
                                        foundProduct.quantity =
                                            +foundProduct.quantity -
                                            parsedQuantity;
                                        foundProduct.salePrice = (
                                            +foundProduct.quantity *
                                            +product.salePrice
                                        ).toString();
                                        foundProduct.belongsTo =
                                            foundProduct?.belongsTo?.filter(
                                                (id) => id != option.uuid
                                            );
                                        if (!foundProduct?.belongsTo?.length) {
                                            this.cart.products.splice(
                                                foundProductIndex,
                                                1
                                            );
                                        } else {
                                            this.cart.products[
                                                foundProductIndex
                                            ] = foundProduct;
                                        }
                                    }
                                });
                            }
                        }
                    );
                } else if (
                    this.selectedQuestion?.configuration?.type ===
                    "multiple-select"
                ) {
                    this.selectedQuestion.configuration.options[index].value =
                        event.target.checked;
                    const option =
                        this.selectedQuestion.configuration.options[index];
                    let selectedProducts = [...option.products];
                    if (event.target.checked) {
                        this.selectedQuestion.next = option.next;
                        this.$nextTick(() => {
                            if (this.selectedInputs[this.selectedQuestion.id]) {
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            } else {
                                this.selectedInputs[this.selectedQuestion.id] =
                                    {
                                        options: {},
                                        conditions: {},
                                    };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            }
                        });
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            if (
                                this.cart.products.some(
                                    (cp) => cp.id == product.id
                                )
                            ) {
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                foundProduct.quantity = +parsedQuantity;
                                foundProduct?.belongsTo?.forEach((optionId) => {
                                    if (optionId != option.uuid) {
                                        let foundOption = {};
                                        for (
                                            let i = 0;
                                            i < this.questions.length;
                                            i++
                                        ) {
                                            const question = this.questions[i];
                                            foundOption =
                                                (
                                                    question?.value ?? question
                                                ).configuration.options.find(
                                                    (op) => op.id == optionId
                                                ) ??
                                                (
                                                    question?.value ?? question
                                                ).configuration.conditions.find(
                                                    (cond) =>
                                                        cond.id == optionId
                                                ) ??
                                                {};
                                            if (
                                                Object.keys(foundOption)
                                                    .length > 0
                                            ) {
                                                break;
                                            }
                                        }
                                        const foundProductOther =
                                            optionId === "manual"
                                                ? this.manualProducts.find(
                                                      (pr) =>
                                                          pr.id == product.id
                                                  )
                                                : {
                                                      ...foundOption?.products?.find(
                                                          (pr) =>
                                                              pr.id ==
                                                                  product.id ??
                                                              {}
                                                      ),
                                                  };
                                        const parsedQuantityFoundProduct =
                                            this.checkForFormula(
                                                foundProductOther.quantity
                                            )
                                                ? this.executeFormula(
                                                      foundProductOther
                                                  )
                                                : this.isJson(
                                                      foundProductOther.quantity
                                                  )
                                                ? this.result(
                                                      foundProductOther.quantity
                                                  )
                                                : foundProductOther.quantity;
                                        foundProduct.quantity =
                                            +foundProduct.quantity +
                                            +parsedQuantityFoundProduct;
                                    }
                                });
                                if (
                                    !foundProduct.belongsTo.includes(
                                        option.uuid
                                    )
                                ) {
                                    const salePriceProduct =
                                        +product.salePrice * +parsedQuantity;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice +
                                        +salePriceProduct
                                    ).toString();
                                }
                                foundProduct?.belongsTo?.push(option.uuid);
                                const uniqueItems = Array.from(
                                    new Set(foundProduct?.belongsTo)
                                );
                                foundProduct.belongsTo = [...uniqueItems];
                                this.cart.products[foundProductIndex] = {
                                    ...foundProduct,
                                };
                            } else {
                                const cartProduct = { ...product };
                                cartProduct.belongsTo = [option.uuid];
                                cartProduct.quantity = +parsedQuantity;
                                cartProduct.salePrice =
                                    +parsedQuantity * +product.salePrice;
                                this.cart.products.push({ ...cartProduct });
                            }
                        });
                    } else {
                        delete this.selectedInputs?.[
                            this.selectedQuestion.id
                        ]?.[option.id];
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            if (foundProductIndex == -1) return;
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            if (
                                foundProduct?.belongsTo?.includes(option.uuid)
                            ) {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.quantity =
                                    +foundProduct.quantity - parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice - +salePriceProduct
                                ).toString();
                                foundProduct.belongsTo =
                                    foundProduct?.belongsTo?.filter(
                                        (id) => id != option.uuid
                                    );
                                if (!foundProduct?.belongsTo?.length) {
                                    this.cart.products.splice(
                                        foundProductIndex,
                                        1
                                    );
                                } else {
                                    this.cart.products[foundProductIndex] =
                                        foundProduct;
                                }
                            }
                        });
                    }
                    this.selectedQuestion.configuration.conditions.forEach(
                        (condition) => {
                            let conditionString = "";
                            for (let i = 0; i < condition.options.length; i++) {
                                const option =
                                    this.selectedQuestion.configuration.options.find(
                                        (option) =>
                                            option.id ==
                                            condition.options[i].option.id
                                    );
                                conditionString += `(${
                                    option.value ? true : false
                                } == ${
                                    condition.options[i].condition == "checked"
                                })${
                                    condition.options[i + 1]
                                        ? " " +
                                          condition.options[i + 1].operator +
                                          " "
                                        : ""
                                }`;
                            }
                            try {
                                selectedProducts = [...condition.products];
                                if (this.javascriptExecuter(conditionString)) {
                                    selectedProducts.forEach((product) => {
                                        let parsedQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(product)
                                                : this.isJson(product.quantity)
                                                ? this.result(product.quantity)
                                                : product.quantity;
                                        if (
                                            this.cart.products.some(
                                                (cp) => cp.id == product.id
                                            )
                                        ) {
                                            const foundProductIndex =
                                                this.cart.products.findIndex(
                                                    (selectedProduct) =>
                                                        selectedProduct.id ==
                                                        product.id
                                                );
                                            const foundProduct = {
                                                ...this.cart.products[
                                                    foundProductIndex
                                                ],
                                            };
                                            foundProduct.quantity =
                                                +parsedQuantity;
                                            foundProduct?.belongsTo?.forEach(
                                                (conditionId) => {
                                                    if (
                                                        conditionId !=
                                                        condition.id
                                                    ) {
                                                        let foundCondition = {};
                                                        for (
                                                            let i = 0;
                                                            i <
                                                            this.questions
                                                                .length;
                                                            i++
                                                        ) {
                                                            const question =
                                                                this.questions[
                                                                    i
                                                                ];
                                                            foundCondition =
                                                                (
                                                                    question?.value ??
                                                                    question
                                                                ).configuration.conditions.find(
                                                                    (cond) =>
                                                                        cond.id ==
                                                                        conditionId
                                                                ) ??
                                                                (
                                                                    question?.value ??
                                                                    question
                                                                ).configuration.options.find(
                                                                    (op) =>
                                                                        op.id ==
                                                                        conditionId
                                                                ) ??
                                                                {};
                                                            if (
                                                                Object.keys(
                                                                    foundCondition
                                                                ).length > 0
                                                            ) {
                                                                break;
                                                            }
                                                        }
                                                        const foundProductOther =
                                                            conditionId ===
                                                            "manual"
                                                                ? this.manualProducts.find(
                                                                      (pr) =>
                                                                          pr.id ==
                                                                          product.id
                                                                  )
                                                                : {
                                                                      ...foundCondition?.products?.find(
                                                                          (
                                                                              pr
                                                                          ) =>
                                                                              pr.id ==
                                                                                  product.id ??
                                                                              {}
                                                                      ),
                                                                  };
                                                        const parsedQuantityFoundProduct =
                                                            this.checkForFormula(
                                                                foundProductOther.quantity
                                                            )
                                                                ? this.executeFormula(
                                                                      foundProductOther
                                                                  )
                                                                : this.isJson(
                                                                      foundProductOther.quantity
                                                                  )
                                                                ? this.result(
                                                                      foundProductOther.quantity
                                                                  )
                                                                : foundProductOther.quantity;
                                                        foundProduct.quantity =
                                                            +foundProduct.quantity +
                                                            +parsedQuantityFoundProduct;
                                                    }
                                                }
                                            );
                                            if (
                                                !foundProduct?.belongsTo?.includes(
                                                    condition.id
                                                )
                                            ) {
                                                const salePriceCondition =
                                                    +product.salePrice *
                                                        +parsedQuantity -
                                                    (+product.salePrice *
                                                        +parsedQuantity *
                                                        +condition.discount) /
                                                        100;
                                                foundProduct.salePrice = (
                                                    +foundProduct.salePrice +
                                                    +salePriceCondition
                                                ).toString();
                                            }
                                            foundProduct?.belongsTo?.push(
                                                condition.id
                                            );
                                            const uniqueItems = Array.from(
                                                new Set(foundProduct?.belongsTo)
                                            );
                                            foundProduct.belongsTo = [
                                                ...uniqueItems,
                                            ];
                                            this.cart.products[
                                                foundProductIndex
                                            ] = {
                                                ...foundProduct,
                                            };
                                        } else {
                                            const cartProduct = { ...product };
                                            cartProduct.belongsTo = [
                                                condition.id,
                                            ];
                                            cartProduct.quantity =
                                                +parsedQuantity;
                                            cartProduct.salePrice =
                                                +parsedQuantity *
                                                    +product.salePrice -
                                                (+parsedQuantity *
                                                    +product.salePrice *
                                                    +condition.discount) /
                                                    100;
                                            this.cart.products.push({
                                                ...cartProduct,
                                            });
                                        }
                                    });
                                    this.$nextTick(() => {
                                        if (
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]
                                        ) {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        } else {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ] = {
                                                options: {},
                                                conditions: {},
                                            };
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        }
                                    });
                                    this.selectedQuestion.next = condition.next;
                                } else {
                                    selectedProducts.forEach((product) => {
                                        let parsedQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(product)
                                                : this.isJson(product.quantity)
                                                ? this.result(product.quantity)
                                                : product.quantity;
                                        const foundProductIndex =
                                            this.cart.products.findIndex(
                                                (selectedProduct) =>
                                                    selectedProduct.id ==
                                                    product.id
                                            );
                                        if (foundProductIndex == -1) return;
                                        const foundProduct = {
                                            ...this.cart.products[
                                                foundProductIndex
                                            ],
                                        };
                                        if (
                                            foundProduct?.belongsTo?.includes(
                                                condition.id
                                            )
                                        ) {
                                            foundProduct.quantity =
                                                +foundProduct.quantity -
                                                parsedQuantity;
                                            const salePriceCondition =
                                                +product.salePrice *
                                                    +parsedQuantity -
                                                (+product.salePrice *
                                                    +parsedQuantity *
                                                    +condition.discount) /
                                                    100;
                                            foundProduct.salePrice = (
                                                +foundProduct.salePrice -
                                                +salePriceCondition
                                            ).toString();
                                            foundProduct.belongsTo =
                                                foundProduct?.belongsTo?.filter(
                                                    (id) => id != condition.id
                                                );
                                            if (
                                                !foundProduct?.belongsTo?.length
                                            ) {
                                                this.cart.products.splice(
                                                    foundProductIndex,
                                                    1
                                                );
                                            } else {
                                                this.cart.products[
                                                    foundProductIndex
                                                ] = foundProduct;
                                            }
                                        }
                                    });
                                    delete this.selectedInputs?.[
                                        this.selectedQuestion.id
                                    ]?.[condition.id];
                                }
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    );
                } else if (
                    this.selectedQuestion?.configuration?.type ===
                        "number-input" ||
                    this.selectedQuestion?.configuration?.type ===
                        "number-slider"
                ) {
                    event.target.dataset.previousValue =
                        this.selectedQuestion.configuration.options[
                            index
                        ].value;
                    this.selectedQuestion.configuration.options[index].value =
                        event.target.value;
                    const option =
                        this.selectedQuestion.configuration.options[index];
                    let selectedProducts = [...option.products];
                    if (option.value > 0) {
                        this.selectedQuestion.next = option.next;
                        this.$nextTick(() => {
                            if (this.selectedInputs[this.selectedQuestion.id]) {
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            } else {
                                this.selectedInputs[this.selectedQuestion.id] =
                                    {
                                        options: {},
                                        conditions: {},
                                    };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            }
                        });
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            if (
                                this.cart.products.some(
                                    (cp) => cp.id == product.id
                                )
                            ) {
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                foundProduct.quantity = +parsedQuantity;
                                foundProduct?.belongsTo?.forEach((optionId) => {
                                    if (optionId != option.uuid) {
                                        let foundOption = {};
                                        for (
                                            let i = 0;
                                            i < this.questions.length;
                                            i++
                                        ) {
                                            const question = this.questions[i];
                                            foundOption =
                                                (
                                                    question?.value ?? question
                                                ).configuration.options.find(
                                                    (op) => op.id == optionId
                                                ) ??
                                                (
                                                    question?.value ?? question
                                                ).configuration.conditions.find(
                                                    (cond) =>
                                                        cond.id == optionId
                                                ) ??
                                                {};
                                            if (
                                                Object.keys(foundOption)
                                                    .length > 0
                                            ) {
                                                break;
                                            }
                                        }

                                        const foundProductOther =
                                            optionId === "manual"
                                                ? this.manualProducts.find(
                                                      (pr) =>
                                                          pr.id == product.id
                                                  )
                                                : {
                                                      ...foundOption?.products?.find(
                                                          (pr) =>
                                                              pr.id ==
                                                                  product.id ??
                                                              {}
                                                      ),
                                                  };
                                        const parsedQuantityFoundProduct =
                                            this.checkForFormula(
                                                foundProductOther.quantity
                                            )
                                                ? this.executeFormula(
                                                      foundProductOther
                                                  )
                                                : this.isJson(
                                                      foundProductOther.quantity
                                                  )
                                                ? this.result(
                                                      foundProductOther.quantity
                                                  )
                                                : foundProductOther.quantity;
                                        foundProduct.quantity =
                                            +foundProduct.quantity +
                                            +parsedQuantityFoundProduct;
                                    }
                                });
                                if (
                                    !foundProduct?.belongsTo?.includes(
                                        option.uuid
                                    )
                                ) {
                                    const salePriceProduct =
                                        +product.salePrice * +parsedQuantity;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice +
                                        +salePriceProduct
                                    ).toString();
                                } else {
                                    const salePriceProduct =
                                        +product.salePrice * +parsedQuantity;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice +
                                        +salePriceProduct
                                    ).toString();
                                    const previousQuantity =
                                        this.checkForFormula(product.quantity)
                                            ? this.executeFormula(
                                                  product,
                                                  event.target.dataset
                                                      .previousValue
                                              )
                                            : this.isJson(product.quantity)
                                            ? this.result(
                                                  product.quantity,
                                                  event.target.dataset
                                                      .previousValue
                                              )
                                            : product.quantity;
                                    const salePriceProductPrevious =
                                        +product.salePrice * +previousQuantity;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice -
                                        +salePriceProductPrevious
                                    ).toString();
                                }
                                foundProduct?.belongsTo?.push(option.uuid);
                                const uniqueItems = Array.from(
                                    new Set(foundProduct?.belongsTo)
                                );
                                foundProduct.belongsTo = [...uniqueItems];
                                this.cart.products[foundProductIndex] = {
                                    ...foundProduct,
                                };
                            } else {
                                const cartProduct = { ...product };
                                cartProduct.belongsTo = [option.uuid];
                                cartProduct.quantity = +parsedQuantity;
                                cartProduct.salePrice =
                                    +parsedQuantity * +product.salePrice;
                                this.cart.products.push({ ...cartProduct });
                            }
                        });
                    } else {
                        delete this.selectedInputs?.[
                            this.selectedQuestion.id
                        ]?.[option.id];
                        selectedProducts.forEach((product) => {
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            if (foundProductIndex == -1) return;
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            if (
                                foundProduct?.belongsTo?.includes(option.uuid)
                            ) {
                                const previousQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(
                                          product,
                                          event.target.dataset.previousValue
                                      )
                                    : this.isJson(product.quantity)
                                    ? this.result(
                                          product.quantity,
                                          event.target.dataset.previousValue
                                      )
                                    : product.quantity;
                                const salePriceProductPrevious =
                                    +product.salePrice * +previousQuantity;
                                foundProduct.quantity =
                                    +foundProduct.quantity - +previousQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice -
                                    +salePriceProductPrevious
                                ).toString();
                                foundProduct.belongsTo =
                                    foundProduct?.belongsTo?.filter(
                                        (id) => id != option.uuid
                                    );
                                if (!foundProduct?.belongsTo?.length) {
                                    this.cart.products.splice(
                                        foundProductIndex,
                                        1
                                    );
                                } else {
                                    this.cart.products[foundProductIndex] =
                                        foundProduct;
                                }
                            }
                        });
                    }
                    this.selectedQuestion.configuration.conditions.forEach(
                        (condition) => {
                            let conditionString = "";
                            for (let i = 0; i < condition.options.length; i++) {
                                const option = condition.options[i];
                                conditionString += `(${option.option.value} ${
                                    option.condition
                                } ${option.value})${
                                    condition.options[i + 1]
                                        ? " " +
                                          condition.options[i + 1].operator +
                                          " "
                                        : ""
                                }`;
                            }
                            try {
                                selectedProducts = [...condition.products];
                                if (this.javascriptExecuter(conditionString)) {
                                    selectedProducts.forEach((product) => {
                                        let parsedQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(product)
                                                : this.isJson(product.quantity)
                                                ? this.result(product.quantity)
                                                : product.quantity;
                                        if (
                                            this.cart.products.some(
                                                (cp) => cp.id == product.id
                                            )
                                        ) {
                                            const foundProductIndex =
                                                this.cart.products.findIndex(
                                                    (selectedProduct) =>
                                                        selectedProduct.id ==
                                                        product.id
                                                );
                                            const foundProduct = {
                                                ...this.cart.products[
                                                    foundProductIndex
                                                ],
                                            };
                                            foundProduct.quantity =
                                                +parsedQuantity;
                                            foundProduct?.belongsTo?.forEach(
                                                (conditionId) => {
                                                    if (
                                                        conditionId !=
                                                        condition.id
                                                    ) {
                                                        let foundCondition = {};
                                                        for (
                                                            let i = 0;
                                                            i <
                                                            this.questions
                                                                .length;
                                                            i++
                                                        ) {
                                                            const question =
                                                                this.questions[
                                                                    i
                                                                ];
                                                            foundCondition =
                                                                (
                                                                    question?.value ??
                                                                    question
                                                                ).configuration.conditions.find(
                                                                    (cond) =>
                                                                        cond.id ==
                                                                        conditionId
                                                                ) ??
                                                                (
                                                                    question?.value ??
                                                                    question
                                                                ).configuration.options.find(
                                                                    (op) =>
                                                                        op.id ==
                                                                        conditionId
                                                                ) ??
                                                                {};
                                                            if (
                                                                Object.keys(
                                                                    foundCondition
                                                                ).length > 0
                                                            ) {
                                                                break;
                                                            }
                                                        }
                                                        const foundProductOther =
                                                            conditionId ===
                                                            "manual"
                                                                ? this.manualProducts.find(
                                                                      (pr) =>
                                                                          pr.id ==
                                                                          product.id
                                                                  )
                                                                : {
                                                                      ...foundCondition?.products?.find(
                                                                          (
                                                                              pr
                                                                          ) =>
                                                                              pr.id ==
                                                                                  product.id ??
                                                                              {}
                                                                      ),
                                                                  };
                                                        const parsedQuantityFoundProduct =
                                                            this.checkForFormula(
                                                                foundProductOther.quantity
                                                            )
                                                                ? this.executeFormula(
                                                                      foundProductOther
                                                                  )
                                                                : this.isJson(
                                                                      foundProductOther.quantity
                                                                  )
                                                                ? this.result(
                                                                      foundProductOther.quantity
                                                                  )
                                                                : foundProductOther.quantity;
                                                        foundProduct.quantity =
                                                            +foundProduct.quantity +
                                                            +parsedQuantityFoundProduct;
                                                    }
                                                }
                                            );
                                            if (
                                                !foundProduct?.belongsTo?.includes(
                                                    condition.id
                                                )
                                            ) {
                                                const salePriceCondition =
                                                    +product.salePrice *
                                                        +parsedQuantity -
                                                    (+product.salePrice *
                                                        +parsedQuantity *
                                                        +condition.discount) /
                                                        100;
                                                foundProduct.salePrice = (
                                                    +foundProduct.salePrice +
                                                    +salePriceCondition
                                                ).toString();
                                            } else {
                                                const salePriceProduct =
                                                    +product.salePrice *
                                                        +parsedQuantity -
                                                    (+product.salePrice *
                                                        +parsedQuantity *
                                                        +condition.discount) /
                                                        100;
                                                foundProduct.salePrice = (
                                                    +foundProduct.salePrice +
                                                    +salePriceProduct
                                                ).toString();
                                                const previousQuantity =
                                                    this.checkForFormula(
                                                        product.quantity
                                                    )
                                                        ? this.executeFormula(
                                                              product,
                                                              event.target
                                                                  .dataset
                                                                  .previousValue
                                                          )
                                                        : this.isJson(
                                                              product.quantity
                                                          )
                                                        ? this.result(
                                                              product.quantity,
                                                              event.target
                                                                  .dataset
                                                                  .previousValue
                                                          )
                                                        : product.quantity;
                                                const salePriceProductPrevious =
                                                    +product.salePrice *
                                                    +previousQuantity;
                                                foundProduct.salePrice = (
                                                    +foundProduct.salePrice -
                                                    +salePriceProductPrevious
                                                ).toString();
                                            }
                                            foundProduct?.belongsTo?.push(
                                                condition.id
                                            );
                                            const uniqueItems = Array.from(
                                                new Set(foundProduct?.belongsTo)
                                            );
                                            foundProduct.belongsTo = [
                                                ...uniqueItems,
                                            ];
                                            this.cart.products[
                                                foundProductIndex
                                            ] = {
                                                ...foundProduct,
                                            };
                                        } else {
                                            const cartProduct = { ...product };
                                            cartProduct.belongsTo = [
                                                condition.id,
                                            ];
                                            cartProduct.quantity =
                                                +parsedQuantity;
                                            cartProduct.salePrice =
                                                +parsedQuantity *
                                                    +product.salePrice -
                                                (+parsedQuantity *
                                                    +product.salePrice *
                                                    +condition.discount) /
                                                    100;
                                            this.cart.products.push({
                                                ...cartProduct,
                                            });
                                        }
                                    });
                                    this.$nextTick(() => {
                                        if (
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]
                                        ) {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        } else {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ] = {
                                                options: {},
                                                conditions: {},
                                            };
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        }
                                    });
                                    this.selectedQuestion.next = condition.next;
                                } else {
                                    selectedProducts.forEach((product) => {
                                        const foundProductIndex =
                                            this.cart.products.findIndex(
                                                (selectedProduct) =>
                                                    selectedProduct.id ==
                                                    product.id
                                            );
                                        if (foundProductIndex == -1) return;
                                        const foundProduct = {
                                            ...this.cart.products[
                                                foundProductIndex
                                            ],
                                        };
                                        if (
                                            foundProduct?.belongsTo?.includes(
                                                condition.id
                                            )
                                        ) {
                                            let parsedQuantityPrevious =
                                                this.checkForFormula(
                                                    product.quantity
                                                )
                                                    ? this.executeFormula(
                                                          product,
                                                          +event.target.dataset
                                                              .previousValue
                                                      )
                                                    : this.isJson(
                                                          product.quantity
                                                      )
                                                    ? this.result(
                                                          product.quantity,
                                                          +event.target.dataset
                                                              .previousValue
                                                      )
                                                    : product.quantity;
                                            let salePriceProductPrevious =
                                                +product.salePrice *
                                                    +parsedQuantityPrevious -
                                                (+product.salePrice *
                                                    +parsedQuantityPrevious *
                                                    +condition.discount) /
                                                    100;
                                            foundProduct.quantity =
                                                +foundProduct.quantity -
                                                parsedQuantityPrevious;
                                            foundProduct.salePrice = (
                                                +foundProduct.salePrice -
                                                +salePriceProductPrevious
                                            ).toString();
                                            foundProduct.belongsTo =
                                                foundProduct?.belongsTo?.filter(
                                                    (id) => id != condition.id
                                                );
                                            if (
                                                !foundProduct?.belongsTo?.length
                                            ) {
                                                this.cart.products.splice(
                                                    foundProductIndex,
                                                    1
                                                );
                                            } else {
                                                this.cart.products[
                                                    foundProductIndex
                                                ] = foundProduct;
                                            }
                                        }
                                    });
                                    delete this.selectedInputs?.[
                                        this.selectedQuestion.id
                                    ]?.[condition.id];
                                }
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    );
                } else if (
                    this.selectedQuestion?.configuration?.type === "text-input"
                ) {
                    const option =
                        this.selectedQuestion.configuration.options[index];
                    let selectedProducts = [...option.products];
                    if (event?.data?.length) {
                        this.selectedQuestion.next = option.next;
                        this.$nextTick(() => {
                            if (this.selectedInputs[this.selectedQuestion.id]) {
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            } else {
                                this.selectedInputs[this.selectedQuestion.id] =
                                    {
                                        options: {},
                                        conditions: {},
                                    };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id] = { ...option };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][option.id].value = event.target.value;
                            }
                        });
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            if (
                                this.cart.products.some(
                                    (cp) => cp.id == product.id
                                )
                            ) {
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                foundProduct.quantity = +parsedQuantity;
                                foundProduct?.belongsTo?.forEach((optionId) => {
                                    if (optionId != option.uuid) {
                                        let foundOption = {};
                                        for (
                                            let i = 0;
                                            i < this.questions.length;
                                            i++
                                        ) {
                                            const question = this.questions[i];
                                            foundOption =
                                                (
                                                    question?.value ?? question
                                                ).configuration.options.find(
                                                    (op) => op.id == optionId
                                                ) ??
                                                (
                                                    question?.value ?? question
                                                ).configuration.conditions.find(
                                                    (cond) =>
                                                        cond.id == optionId
                                                ) ??
                                                {};
                                            if (
                                                Object.keys(foundOption)
                                                    .length > 0
                                            ) {
                                                break;
                                            }
                                        }
                                        const foundProductOther =
                                            optionId === "manual"
                                                ? this.manualProducts.find(
                                                      (pr) =>
                                                          pr.id == product.id
                                                  )
                                                : {
                                                      ...foundOption?.products?.find(
                                                          (pr) =>
                                                              pr.id ==
                                                                  product.id ??
                                                              {}
                                                      ),
                                                  };
                                        const parsedQuantityFoundProduct =
                                            this.checkForFormula(
                                                foundProductOther.quantity
                                            )
                                                ? this.executeFormula(
                                                      foundProductOther
                                                  )
                                                : this.isJson(
                                                      foundProductOther.quantity
                                                  )
                                                ? this.result(
                                                      foundProductOther.quantity
                                                  )
                                                : foundProductOther.quantity;
                                        foundProduct.quantity =
                                            +foundProduct.quantity +
                                            +parsedQuantityFoundProduct;
                                    }
                                });
                                if (
                                    !foundProduct.belongsTo.includes(
                                        option.uuid
                                    )
                                ) {
                                    const salePriceProduct =
                                        +product.salePrice * +parsedQuantity;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice +
                                        +salePriceProduct
                                    ).toString();
                                }
                                foundProduct?.belongsTo?.push(option.uuid);
                                const uniqueItems = Array.from(
                                    new Set(foundProduct?.belongsTo)
                                );
                                foundProduct.belongsTo = [...uniqueItems];
                                this.cart.products[foundProductIndex] = {
                                    ...foundProduct,
                                };
                            } else {
                                const cartProduct = { ...product };
                                cartProduct.belongsTo = [option.uuid];
                                cartProduct.quantity = +parsedQuantity;
                                cartProduct.salePrice =
                                    +parsedQuantity * +product.salePrice;
                                this.cart.products.push({ ...cartProduct });
                            }
                        });
                    } else {
                        delete this.selectedInputs?.[
                            this.selectedQuestion.id
                        ]?.[option.id];
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            if (foundProductIndex == -1) return;
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            if (
                                foundProduct?.belongsTo?.includes(option.uuid)
                            ) {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.quantity =
                                    +foundProduct.quantity - parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice - +salePriceProduct
                                ).toString();
                                foundProduct.belongsTo =
                                    foundProduct?.belongsTo?.filter(
                                        (id) => id != option.uuid
                                    );
                                if (!foundProduct?.belongsTo?.length) {
                                    this.cart.products.splice(
                                        foundProductIndex,
                                        1
                                    );
                                } else {
                                    this.cart.products[foundProductIndex] =
                                        foundProduct;
                                }
                            }
                        });
                    }
                    this.selectedQuestion.configuration.conditions.forEach(
                        (condition) => {
                            let conditionString = "";
                            for (let i = 0; i < condition.options.length; i++) {
                                const option = condition.options[i];
                                conditionString += `(${
                                    option.condition === "contains" ? "" : "!"
                                }'${option.option.value}'.includes('${
                                    option.value
                                }'))${
                                    condition.options[i + 1]
                                        ? " " +
                                          condition.options[i + 1].operator +
                                          " "
                                        : ""
                                }`;
                            }
                            try {
                                let selectedProducts = JSON.parse(
                                    JSON.stringify([...condition.products])
                                );
                                if (this.javascriptExecuter(conditionString)) {
                                    selectedProducts.forEach((product) => {
                                        let parsedQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(product)
                                                : this.isJson(product.quantity)
                                                ? this.result(product.quantity)
                                                : product.quantity;
                                        if (
                                            this.cart.products.some(
                                                (cp) => cp.id == product.id
                                            )
                                        ) {
                                            const foundProductIndex =
                                                this.cart.products.findIndex(
                                                    (selectedProduct) =>
                                                        selectedProduct.id ==
                                                        product.id
                                                );
                                            const foundProduct = {
                                                ...this.cart.products[
                                                    foundProductIndex
                                                ],
                                            };
                                            foundProduct.quantity =
                                                +parsedQuantity;
                                            foundProduct?.belongsTo?.forEach(
                                                (conditionId) => {
                                                    if (
                                                        conditionId !=
                                                        condition.id
                                                    ) {
                                                        const foundCondition =
                                                            this.selectedQuestion.configuration.conditions.find(
                                                                (cond) =>
                                                                    cond.id ==
                                                                    conditionId
                                                            ) ??
                                                            this.selectedQuestion.configuration.options.find(
                                                                (op) =>
                                                                    op.id ==
                                                                    conditionId
                                                            );
                                                        const foundProductOther =
                                                            conditionId ===
                                                            "manual"
                                                                ? this.manualProducts.find(
                                                                      (pr) =>
                                                                          pr.id ==
                                                                          product.id
                                                                  )
                                                                : {
                                                                      ...foundCondition?.products?.find(
                                                                          (
                                                                              pr
                                                                          ) =>
                                                                              pr.id ==
                                                                                  product.id ??
                                                                              {}
                                                                      ),
                                                                  };
                                                        const parsedQuantityFoundProduct =
                                                            this.checkForFormula(
                                                                foundProductOther.quantity
                                                            )
                                                                ? this.executeFormula(
                                                                      foundProductOther
                                                                  )
                                                                : this.isJson(
                                                                      foundProductOther.quantity
                                                                  )
                                                                ? this.result(
                                                                      foundProductOther.quantity
                                                                  )
                                                                : foundProductOther.quantity;
                                                        foundProduct.quantity =
                                                            +foundProduct.quantity +
                                                            +parsedQuantityFoundProduct;
                                                    }
                                                }
                                            );
                                            if (
                                                !foundProduct?.belongsTo?.includes(
                                                    condition.id
                                                )
                                            ) {
                                                const salePriceCondition =
                                                    +product.salePrice *
                                                        +parsedQuantity -
                                                    (+product.salePrice *
                                                        +parsedQuantity *
                                                        +condition.discount) /
                                                        100;
                                                foundProduct.salePrice = (
                                                    +foundProduct.salePrice +
                                                    +salePriceCondition
                                                ).toString();
                                            }
                                            foundProduct?.belongsTo?.push(
                                                condition.id
                                            );
                                            const uniqueItems = Array.from(
                                                new Set(foundProduct?.belongsTo)
                                            );
                                            foundProduct.belongsTo = [
                                                ...uniqueItems,
                                            ];
                                            this.cart.products[
                                                foundProductIndex
                                            ] = {
                                                ...foundProduct,
                                            };
                                        } else {
                                            const cartProduct = { ...product };
                                            cartProduct.belongsTo = [
                                                condition.id,
                                            ];
                                            cartProduct.quantity =
                                                +parsedQuantity;
                                            cartProduct.salePrice =
                                                +parsedQuantity *
                                                    +product.salePrice -
                                                (+parsedQuantity *
                                                    +product.salePrice *
                                                    +condition.discount) /
                                                    100;
                                            this.cart.products.push({
                                                ...cartProduct,
                                            });
                                        }
                                    });
                                    this.$nextTick(() => {
                                        if (
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]
                                        ) {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        } else {
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ] = {
                                                options: {},
                                                conditions: {},
                                            };
                                            this.selectedInputs[
                                                this.selectedQuestion.id
                                            ]["conditions"][condition.id] =
                                                condition;
                                        }
                                    });
                                    this.selectedQuestion.next = condition.next;
                                } else {
                                    selectedProducts.forEach((product) => {
                                        let parsedQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(product)
                                                : this.isJson(product.quantity)
                                                ? this.result(product.quantity)
                                                : product.quantity;
                                        const foundProductIndex =
                                            this.cart.products.findIndex(
                                                (selectedProduct) =>
                                                    selectedProduct.id ==
                                                    product.id
                                            );
                                        if (foundProductIndex == -1) return;
                                        const foundProduct = {
                                            ...this.cart.products[
                                                foundProductIndex
                                            ],
                                        };
                                        if (
                                            foundProduct?.belongsTo?.includes(
                                                condition.id
                                            )
                                        ) {
                                            foundProduct.quantity =
                                                +foundProduct.quantity -
                                                parsedQuantity;
                                            const salePriceCondition =
                                                +product.salePrice *
                                                    +parsedQuantity -
                                                (+product.salePrice *
                                                    +parsedQuantity *
                                                    +condition.discount) /
                                                    100;
                                            foundProduct.salePrice = (
                                                +foundProduct.salePrice -
                                                +salePriceCondition
                                            ).toString();
                                            foundProduct.belongsTo =
                                                foundProduct?.belongsTo?.filter(
                                                    (id) => id != condition.id
                                                );
                                            if (
                                                !foundProduct?.belongsTo?.length
                                            ) {
                                                this.cart.products.splice(
                                                    foundProductIndex,
                                                    1
                                                );
                                            } else {
                                                this.cart.products[
                                                    foundProductIndex
                                                ] = foundProduct;
                                            }
                                        }
                                    });
                                    delete this.selectedInputs?.[
                                        this.selectedQuestion.id
                                    ]?.[condition.id];
                                }
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    );
                }
                this.$emit("cartParent", this.cart);
                this.$nextTick(() => {
                    this.$emit("selectedInputs", { ...this.selectedInputs });
                });
            },
            back() {
                const selectedQuestion = this.questions.find(
                    (question) =>
                        (question?.value?.id ?? question?.id) ==
                        this.selectedQuestion.back
                );
                this.selectedQuestion =
                    selectedQuestion?.value ??
                    selectedQuestion ??
                    this.selectedQuestion;
                this.$emit("selectedQuestionParent", this.selectedQuestion);
            },
            next() {
                const selectedQuestionId = this.selectedQuestion.id;
                let selectedQuestion = this.questions.find(
                    (question) =>
                        (question?.value?.id ?? question?.id) ==
                        (this.selectedQuestion.next ||
                            (() => {
                                let questionIndex = this.questions.findIndex(
                                    (q) =>
                                        (q?.value?.id ?? q?.id) ==
                                        this.selectedQuestion.id
                                );
                                if (
                                    questionIndex ==
                                    this.questions.length - 1
                                ) {
                                    return this.selectedQuestion.id;
                                } else {
                                    return (
                                        this.questions[questionIndex + 1]?.value
                                            ?.id ??
                                        this.questions[questionIndex + 1]?.id
                                    );
                                }
                            })())
                );
                this.selectedQuestion =
                    selectedQuestion?.value ?? selectedQuestion;
                this.selectedQuestion.back = selectedQuestionId;
                this.$emit("selectedQuestionParent", this.selectedQuestion);
            },
            result(quantity, customValue) {
                let numberInputs = JSON.parse(quantity);
                let result = 0;
                numberInputs.forEach((input) => {
                    if (input.operator) {
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${+this.calculateResult(input, customValue)}`
                        );
                    } else {
                        result = +this.calculateResult(input, customValue);
                    }
                });
                return result;
            },
            calculateResult(numberInput, customValue) {
                let result = 0;
                if (!numberInput.parenthesis.length) {
                    let foundOption = {};
                    for (let i = 0; i < this.questions.length; i++) {
                        const question = this.questions[i];
                        foundOption =
                            (
                                question?.value ?? question
                            ).configuration.options.find(
                                (op) => op.uuid === numberInput.id
                            ) ?? {};
                        if (Object.keys(foundOption).length > 0) {
                            break;
                        }
                    }
                    if (customValue) {
                        result = +(customValue ?? numberInput.value);
                    } else result = +(foundOption?.value ?? numberInput.value);
                } else {
                    let foundOption = {};
                    for (let i = 0; i < this.questions.length; i++) {
                        const question = this.questions[i];
                        foundOption =
                            (
                                question?.value ?? question
                            ).configuration.options.find(
                                (op) => op.uuid === numberInput.id
                            ) ?? {};
                        if (Object.keys(foundOption).length > 0) {
                            break;
                        }
                    }

                    result = +(foundOption?.value ?? numberInput.value);
                    numberInput.parenthesis.forEach((input) => {
                        // result = this.calculateResult(input);
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${this.calculateResult(input, customValue)}`
                        );
                    });
                }
                return result;
            },
            isJson(value) {
                try {
                    return typeof JSON.parse(value) === "object";
                } catch (e) {
                    return false;
                }
            },
            checkForFormula(formula) {
                if (this.isJson(formula)) return false;
                try {
                    return parseInt(formula) ? false : true;
                } catch (e) {
                    return false;
                }
            },
            executeFormula(item, customValue) {
                if (!item.quantity) return 1;
                if (this.checkForFormula(item.quantity)) {
                    let matches = item.quantity.match(
                        /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                    );
                    let quantity = item.quantity;
                    matches.forEach((match) => {
                        let foundOption = {};
                        for (let i = 0; i < this.questions.length; i++) {
                            const question = this.questions[i];
                            foundOption =
                                (
                                    question?.value ?? question
                                )?.configuration?.options.find(
                                    (op) => op.uuid === match
                                ) ?? {};
                            if (Object.keys(foundOption).length > 0) {
                                break;
                            }
                        }
                        quantity = quantity.replace(
                            "$" + match,
                            "$" + foundOption?.title.toLowerCase()
                        );
                        if (customValue) {
                            quantity = quantity.replace(
                                "$" + foundOption?.title.toLowerCase(),
                                +customValue
                            );
                        } else {
                            quantity = quantity.replace(
                                "$" + foundOption?.title.toLowerCase(),
                                +foundOption.value
                            );
                        }
                    });
                    try {
                        quantity = this.javascriptExecuter(quantity.slice(1));
                    } catch (e) {
                        console.log(e);
                        quantity = 1;
                    }
                    return quantity;
                } else {
                    return item.quantity;
                }
            },
        },
    };
    Details = {
        template: `
            <h5
            :style="
                'color: ' +
                stylesConfiguration.productDetails?.cardHeaderTextColor +
                '; background-color: ' +
                stylesConfiguration.productDetails?.cardHeaderBgColor
            "
            class="text-gray-900 text-md leading-tight font-medium card-title pb-2 pt-2 text-center"
        >
          {{ translate('Product Details') }}  
        </h5>
        <div
            class="p-2"
            v-html="htmlFromText(selectedQuestion?.productDetails)"
        ></div>
            `,
        props: ["selectedQuestionParent", "stylesConfigurationParent"],
        emits: ["selectedQuestionParent"],
        watch: {
            selectedQuestionParent(val) {
                this.selectedQuestion = val;
            },
            stylesConfigurationParent(val) {
                this.stylesConfiguration = val;
            },
        },
        mounted() {
            this.selectedQuestion = this.selectedQuestionParent;
            this.stylesConfiguration = this.stylesConfigurationParent;
        },
        data() {
            return {
                selectedQuestion: null,
                stylesConfiguration: {},
            };
        },
        methods: {
            translate(text) {
                return text;
            },
            htmlFromText(text) {
                try {
                    let article = document.createElement("article");
                    let quill = new window.VueQuill.Quill(article, {
                        readOny: true,
                    });
                    quill.setContents(JSON.parse(text));
                    return quill.root.innerHTML;
                } catch (err) {
                    return "";
                }
            },
            updateSelectedQuestion(event) {
                this.selectedQuestion.productDetails = event;
                this.$emit("selectedQuestionParent", this.selectedQuestion);
            },
        },
    };
    Cart = {
        template: `
            <div>
            <div
                v-if="flashMessage.type === 'success' && flashMessage.show"
                :class="'flex items-center justify-between mb-8 max-w-3xl bg-green-500 rounded flash-message'"
            >
                <div class="flex items-center">
                    <svg
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
                    </svg>
                    <div class="py-4 text-white text-sm font-medium">
                        {{ flashMessage.message }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        flashMessage = {
                            show: false,
                            type: '',
                            message: '',
                        }
                    "
                >
                    <svg
                        class="block w-2 h-2 fill-green-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
            <div
                v-else-if="flashMessage.type === 'error' && flashMessage.show"
                :class="'flex items-center justify-between mb-8 max-w-3xl bg-red-500 rounded flash-message'"
            >
                <div class="flex items-center">
                    <svg
                        class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"
                        />
                    </svg>
                    <div
                        v-if="flashMessage.type === 'error'"
                        class="py-4 text-white text-sm font-medium"
                    >
                        {{ flashMessage.message }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="
                        flashMessage = {
                            show: false,
                            type: '',
                            message: '',
                        }
                    "
                >
                    <svg
                        class="block w-2 h-2 fill-red-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
        </div>
            <div class="relative">
            <h5
              :style="
                'color: ' +
                stylesConfiguration.cart?.cardHeaderTextColor +
                '; background-color: ' +
                stylesConfiguration.cart?.cardHeaderBgColor
              "
              class="text-gray-900 text-md leading-tight font-medium card-title pb-2 pt-2 text-center"
            >
              {{ translate("Shopping Cart") }}
            </h5>
          </div>
          <div class="flex flex-col h-full">
            <table class="whitespace-nowrap">
              <thead>
                <tr>
                  <th
                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  >
                    {{ translate("Product name") }}
                  </th>
                  <th
                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  >
                    {{ translate("Quantity") }}
                  </th>
                  <th
                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  >
                    {{ translate("Product price") }}
                  </th>
                </tr>
              </thead>
        
              <tbody>
                <tr
                  v-for="(product, index) in cart.products ?? []"
                  :key="index"
                  :tabindex="index"
                  class="focus:outline-none h-16 border border-gray-100 rounded"
                >
                  <td class="pl-5">
                    <div class="md:w-2/3">
                    <div
                    style="width: 13ch; white-space: break-spaces"
                    class="md:w-2/3"
                >
                    {{ product.name }}
                </div>
                    </div>
                  </td>
                  <td class="pl-5">
                    <div class="md:w-2/3">
                      <input
                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight"
                        type="number"
                        style="width: 13ch"
                        readonly
                        :value="
                          checkForFormula(product.quantity)
                            ? executeFormula(product)
                            : isJson(product.quantity)
                            ? result(product.quantity)
                            : product.quantity
                        "
                      />
                    </div>
                  </td>
                  <td class="pl-5">
                    <div class="md:w-2/3">
                      <input
                        style="width: 13ch"
                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight"
                        type="text"
                        :title="product.salePrice"
                        :value="product.salePrice"
                        readonly
                      />
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div>
              <div style="justify-self: center; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));" class="p-2 grid gap-2"">
                <div v-if="cart.addProductsManually" class="flex justify-center p-2">
                  <button
                    style="width: 170px; word-break: break-word"
                    @click="toggleProductsModal = true"
                    type="button"
                    class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                  >
                    {{ translate("Add Products") }}
                  </button>
                </div>
                <div
                  v-for="(config, index) in cart.config"
                  :key="'button-' + index"
                  class="p-2"
                  style="justify-self: center"
                >
                  <div v-if="config?.title?.length">
                    <button
                      @click="callAPI(config)"
                      v-if="!config.isExpertMode"
                      type="button"
                      style="width: 170px; word-break: break-word"
                      class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                    >
                      {{ config?.title }}
                    </button>
                    <button
                      @click="runCustomCode(config)"
                      v-else
                      type="button"
                      style="width: 170px; word-break: break-word"
                      class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                    >
                      {{ config?.title }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <select-product-modal
            v-if="toggleProductsModal"
            @refresh="refresh($event.page)"
            @selected="addProducts"
            @cancelled="toggleProductsModal = false"
            :selectedItems="manualProducts"
            :products="products"
            :formulaInput="false"
          ></select-product-modal>
            `,
        components: {
            "select-product-modal": this.SelectProductsModal,
        },
        props: [
            "manualProductsParent",
            "minimizeCart",
            "selectedInputs",
            "cartParent",
            "stylesConfigurationParent",
            "selectedQuestionParent",
            "questionsParent",
        ],
        watch: {
            selectedQuestionParent(val) {
                this.selectedQuestion = val;
            },
            stylesConfigurationParent(val) {
                this.stylesConfiguration = val;
            },
            questionsParent(val) {
                this.questions = val;
            },
            cartParent: {
                handler: function (val) {
                    this.cart = this.cartParent;
                    try {
                        const surveyJsonLocal = { ...surveyJson };
                        surveyJsonLocal.cart.products = [...this.cart.products];
                        localStorage.setItem(
                            "surveyJson",
                            JSON.stringify(surveyJsonLocal)
                        );
                    } catch (e) {
                        console.log(e);
                    }
                },
                deep: true,
            },
        },
        emits: ["cartParent", "manualProducts"],
        async mounted() {
            this.manualProducts = [...this.manualProductsParent];
            this.selectedQuestion = this.selectedQuestionParent;
            this.cart = this.cartParent;
            this.stylesConfiguration = this.stylesConfigurationParent;
            this.refresh(1);
        },
        data() {
            return {
                flashMessage: {
                    show: false,
                    type: "",
                    message: "",
                },
                code: "",
                questions: [],
                manualProducts: [],
                products: {
                    data: [],
                    links: [],
                },
                toggleProductsModal: false,
                selectedQuestion: null,
                selectedAction: {},
                errorList: {},
                toggleModal: false,
                toggleError: false,
                cart: [],
                stylesConfiguration: {},
            };
        },
        methods: {
            async refresh(page) {
                try {
                    const response = await fetch(
                        `${baseUrl}/api/public/products-with-quantity?page=${page}`,
                        {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                            },
                        }
                    );
                    const res = await response.json();
                    this.products = res?.products ?? this.products;
                } catch (e) {
                    console.log(e);
                }
            },
            translate(text) {
                return text;
            },
            callAPI(config) {
                fetch(config?.route ?? "", {
                    method: "POST",
                    mode: "cors", // no-cors, *cors, same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: {
                        products: this.cart.products,
                        selectedInputs: this.selectedInputs,
                    },
                })
                    .then((res) => {
                        const response = res.json();
                        this.flashMessage = {
                            show: true,
                            type: "success",
                            message: config?.successFeedback?.length
                                ? config?.successFeedback
                                : response?.data?.message,
                        };
                    })
                    .catch((e) => {
                        this.flashMessage = {
                            show: true,
                            type: "error",
                            message: e?.message,
                        };
                    });
                setTimeout(() => {
                    this.flashMessage = {
                        show: false,
                        message: "",
                        type: "",
                    };
                }, 5000);
            },
            addProducts(products) {
                const modifiedProducts = products.map((product) => {
                    return {
                        ...product,
                    };
                });
                //remove the previous manual products
                this.manualProducts.forEach((product) => {
                    const foundProductIndex = this.cart.products.findIndex(
                        (selectedProduct) => selectedProduct.id == product.id
                    );
                    if (foundProductIndex == -1) return;
                    const foundProduct = {
                        ...this.cart.products[foundProductIndex],
                    };
                    if (foundProduct?.belongsTo?.includes("manual")) {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        const salePriceProduct =
                            +product.salePrice * +parsedQuantity;
                        foundProduct.quantity =
                            +foundProduct.quantity - +parsedQuantity;
                        foundProduct.salePrice = (
                            +foundProduct.salePrice - +salePriceProduct
                        ).toString();
                        foundProduct.belongsTo =
                            foundProduct?.belongsTo?.filter(
                                (id) => id != "manual"
                            );
                        if (!foundProduct?.belongsTo?.length) {
                            this.cart.products.splice(foundProductIndex, 1);
                        } else {
                            this.cart.products[foundProductIndex] =
                                foundProduct;
                        }
                    }
                });
                this.manualProducts = modifiedProducts;
                this.manualProducts.forEach((product) => {
                    if (this.cart.products.some((cp) => cp.id == product.id)) {
                        const foundProductIndex = this.cart.products.findIndex(
                            (selectedProduct) =>
                                selectedProduct.id == product.id
                        );
                        const foundProduct = {
                            ...this.cart.products[foundProductIndex],
                        };
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        foundProduct.quantity = +parsedQuantity;
                        foundProduct?.belongsTo?.forEach((optionId) => {
                            if (optionId !== "manual") {
                                let foundOption = {};
                                for (
                                    let i = 0;
                                    i < this.questions.length;
                                    i++
                                ) {
                                    const question = this.questions[i];
                                    foundOption =
                                        (
                                            question?.value ?? question
                                        ).configuration.options.find(
                                            (op) => op.id == optionId
                                        ) ??
                                        (
                                            question?.value ?? question
                                        ).configuration.conditions.find(
                                            (cond) => cond.id == optionId
                                        ) ??
                                        {};
                                    if (Object.keys(foundOption).length > 0) {
                                        break;
                                    }
                                }
                                const foundProductOther = {
                                    ...foundOption?.products?.find(
                                        (pr) => pr.id == product.id ?? {}
                                    ),
                                };
                                const parsedQuantityFoundProduct =
                                    this.checkForFormula(
                                        foundProductOther.quantity
                                    )
                                        ? this.executeFormula(foundProductOther)
                                        : this.isJson(
                                              foundProductOther.quantity
                                          )
                                        ? this.result(
                                              foundProductOther.quantity
                                          )
                                        : foundProductOther.quantity;
                                foundProduct.quantity =
                                    +foundProduct.quantity +
                                    +parsedQuantityFoundProduct;
                            }
                        });
                        if (!foundProduct.belongsTo.includes("manual")) {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            const salePriceProduct =
                                +product.salePrice * +parsedQuantity;
                            foundProduct.salePrice = (
                                +foundProduct.salePrice + +salePriceProduct
                            ).toString();
                        }
                        foundProduct?.belongsTo?.push("manual");
                        const uniqueItems = Array.from(
                            new Set(foundProduct?.belongsTo)
                        );
                        foundProduct.belongsTo = [...uniqueItems];
                        this.cart.products[foundProductIndex] = {
                            ...foundProduct,
                        };
                    } else {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        const cartProduct = { ...product };
                        cartProduct.belongsTo = ["manual"];
                        cartProduct.quantity = +parsedQuantity;
                        cartProduct.salePrice =
                            +parsedQuantity * +product.salePrice;
                        this.cart.products.push({ ...cartProduct });
                    }
                });
                this.toggleProductsModal = false;
                this.$emit("manualProducts", this.manualProducts);
            },
            result(quantity, customValue) {
                let numberInputs = JSON.parse(quantity);
                let result = 0;
                numberInputs.forEach((input) => {
                    if (input.operator) {
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${+this.calculateResult(input, customValue)}`
                        );
                    } else {
                        result = +this.calculateResult(input, customValue);
                    }
                });
                return result;
            },
            calculateResult(numberInput, customValue) {
                let result = 0;
                if (!numberInput?.parenthesis?.length) {
                    let foundOption = {};
                    for (let i = 0; i < this.questions.length; i++) {
                        const question = this.questions[i];
                        foundOption =
                            (
                                question?.value ?? question
                            ).configuration.options.find(
                                (op) => op.uuid === numberInput.id
                            ) ?? {};
                        if (Object.keys(foundOption).length > 0) {
                            break;
                        }
                    }
                    if (customValue) {
                        result = +(customValue ?? numberInput.value);
                    } else result = +(foundOption?.value ?? numberInput.value);
                } else {
                    let foundOption = {};
                    for (let i = 0; i < this.questions.length; i++) {
                        const question = this.questions[i];
                        foundOption =
                            (
                                question?.value ?? question
                            ).configuration.options.find(
                                (op) => op.uuid === numberInput.id
                            ) ?? {};
                        if (Object.keys(foundOption).length > 0) {
                            break;
                        }
                    }
                    result = +(foundOption?.value ?? numberInput.value);
                    numberInput.parenthesis.forEach((input) => {
                        // result = this.calculateResult(input);
                        this.javascriptExecuter(
                            `result = result ${
                                input.operator
                            } ${this.calculateResult(input, customValue)}`
                        );
                    });
                }
                return result;
            },
            isJson(value) {
                try {
                    return typeof JSON.parse(value) === "object";
                } catch (e) {
                    return false;
                }
            },
            checkForFormula(formula) {
                if (this.isJson(formula)) return false;
                try {
                    return parseInt(formula) ? false : true;
                } catch (e) {
                    return false;
                }
            },
            executeFormula(item, customValue) {
                if (!item.quantity) return 1;
                if (this.checkForFormula(item.quantity)) {
                    let matches = item.quantity.match(
                        /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                    );
                    let quantity = item.quantity;
                    matches.forEach((match) => {
                        let foundOption = {};
                        for (let i = 0; i < this.questions.length; i++) {
                            const question = this.questions[i];
                            foundOption =
                                (
                                    question?.value ?? question
                                ).configuration.options.find(
                                    (op) => op.uuid === match
                                ) ?? {};
                            if (Object.keys(foundOption).length > 0) {
                                break;
                            }
                        }
                        quantity = quantity.replace(
                            "$" + match,
                            "$" + foundOption?.title.toLowerCase()
                        );
                        if (customValue) {
                            quantity = quantity.replace(
                                "$" + foundOption?.title.toLowerCase(),
                                +customValue
                            );
                        } else {
                            quantity = quantity.replace(
                                "$" + foundOption?.title.toLowerCase(),
                                +foundOption.value
                            );
                        }
                    });
                    try {
                        quantity = this.javascriptExecuter(quantity.slice(1));
                    } catch (e) {
                        console.log(e);
                        quantity = 1;
                    }
                    return quantity;
                } else {
                    return item.quantity;
                }
            },
            javascriptExecuter(obj) {
                let f = new Function('"use strict"; ' + obj);
                return f();
            },
            runCustomCode(config) {
                let functionString = `${config.code}; \n${
                    config?.functionName
                }(${(() => {
                    let argumentString = "";
                    for (let i = 0; i < config?.arguments?.length; i++) {
                        argumentString += `${(() => {
                            if (
                                typeof config?.arguments?.[i]?.value ===
                                "string"
                            ) {
                                return `'${config?.arguments?.[i]?.value}'`;
                            } else return config?.arguments?.[i]?.value;
                        })()}${config.arguments[i + 1] ? "," : ""}`;
                    }
                    return argumentString;
                })()})`;
                this.javascriptExecuter(functionString);
            },
            cartUpdated() {
                this.$emit("cartParent", this.cart);
            },
            inputWidth(index, productName = "name") {
                const width = this.cart.products[index][productName]?.length;
                return { width: parseInt(width) + 4 + "ch" };
            },
        },
    };
    Sections = {
        template: `<div
            :data-division="sectionDivision()"
            :style="
                (layout.customCss ?? '') +
                'grid-template-' +
                layoutType() +
                's: ' +
                sectionDivision()
            "
            class="grid gap-2"
        >
            <Sections
                v-for="(section, index) in (layout?.children ?? [])"
                :key="'section-' + index"
                :layout="section"
                :stepsParent="steps"
                :selectedQuestionParent="selectedQuestion"
                :selectedChapterParent="selectedChapter"
                :cartParent="cart"
                :validatorParent="validator"
                :stylesConfigurationParent="stylesConfiguration"
                :questionsParent="questions"
                :manualProducts="manualProducts"
                @manualProducts="$emit('manualProducts', $event)"
                :selectedInputs="selectedInputs"
                @selectedInputs="$emit('selectedInputs', $event)"
                :minimizeSteps="minimizeSteps"
                :minimizeCart="minimizeCart"
                @minimizeCart="$emit('minimizeCart', $event)"
                @stepsParent="
                    steps = $event;
                    $emit('stepsParent', steps);
                "
                @selectedQuestionParent="
                    selectedQuestion = $event;
                    $emit('selectedQuestionParent', selectedQuestion);
                "
                @selectedChapterParent="
                    selectedChapter = $event;
                    $emit('selectedChapterParent', selectedChapter);
                "
                @cartParent="
                    cart = $event;
                    $emit('cartParent', cart);
                "
                @validatorParent="
                    validator = $event;
                    $emit('validatorParent', validator);
                "
                @stylesConfigurationParent="
                    stylesConfiguration = $event;
                    $emit('stylesConfigurationParent', stylesConfiguration);
                "
                @questionsParent="
                    questions = $event;
                    $emit('questionsParent', questions);
                "
            />
            <div v-if="layout.contains">
                <div
                    v-if="layout.contains === 'steps'"
                    :style="
                        'color: ' +
                        stylesConfiguration.steps?.cardTextColor +
                        '; background-color: ' +
                        stylesConfiguration.steps?.cardBgColor +
                        '; min-height: 100%'
                    "
                    class="rounded-md shadow-xl card"
                >
                    <Steps
                        :minimizeStepsParent="minimizeSteps"
                        :stepsParent="steps"
                        :selectedQuestionParent="selectedQuestion"
                        :selectedChapterParent="selectedChapter"
                        :questionsParent="questions"
                        :stylesConfigurationParent="stylesConfiguration"
                        :validatorParent="validator"
                        @stepsParent="
                            steps = $event;
                            $emit('stepsParent', steps);
                        "
                        @selectedQuestionParent="
                            selectedQuestion = $event;
                            $emit('selectedQuestionParent', selectedQuestion);
                        "
                        @selectedChapterParent="
                            selectedChapter = $event;
                            $emit('selectedChapterParent', selectedChapter);
                        "
                        @questionsParent="
                            questions = $event;
                            $emit('questionsParent', questions);
                        "
                        @stylesConfigurationParent="
                            stylesConfiguration = $event;
                            $emit('stylesConfigurationParent', stylesConfiguration);
                        "
                        @validatorParent="
                            validator = $event;
                            $emit('validatorParent', validator);
                        "
                    />
                </div>
                <div
                    v-if="layout.contains === 'question'"
                    :style="
                        'color: ' +
                        stylesConfiguration.questionDetails?.cardTextColor +
                        '; background-color: ' +
                        stylesConfiguration.questionDetails?.cardBgColor +
                        '; min-height: 100%'
                    "
                    class="rounded-md shadow-md card"
                >
                    <Question
                        @selectedInputs="$emit('selectedInputs', $event)"
                        :manualProducts="manualProducts"
                        :selectedQuestionParent="selectedQuestion"
                        :questionsParent="questions"
                        :validatorParent="validator"
                        :cartParent="cart"
                        @selectedQuestionParent="
                            selectedQuestion = $event;
                            $emit('selectedQuestionParent', selectedQuestion);
                        "
                        @questionsParent="
                            questions = $event;
                            $emit('questionsParent', questions);
                        "
                        @validatorParent="
                            validator = $event;
                            $emit('validatorParent', validator);
                        "
                        @cartParent="
                            cart = $event;
                            $emit('cartParent', cart);
                        "
                    />
                </div>
                <div
                    v-if="layout.contains === 'details'"
                    class="rounded-md shadow-xl card"
                    :style="
                        'min-height: 500px; color:' +
                        stylesConfiguration.productDetails?.cardTextColor +
                        '; background-color: ' +
                        stylesConfiguration.productDetails?.cardBgColor +
                        '; min-height: 100%'
                    "
                >
                    <Details
                        :selectedQuestionParent="selectedQuestion"
                        :stylesConfigurationParent="stylesConfiguration"
                        @selectedQuestionParent="
                            selectedQuestion = $event;
                            $emit('selectedQuestionParent', selectedQuestion);
                        "
                    />
                </div>
                <div
                    v-if="layout.contains === 'cart' && !minimizeCart"
                    :style="
                        'color: ' +
                        stylesConfiguration.cart?.cardTextColor +
                        '; background-color: ' +
                        stylesConfiguration.cart?.cardBgColor +
                        '; min-height: 100%'
                    "
                    class="rounded-md shadow-xl card"
                >
                    <Cart
                        @minimizeCart="$emit('minimizeCart', $event)"
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
            </div>
        </div>`,
        name: "Sections",
        components: {
            Steps: this.Steps,
            Question: this.Question,
            Details: this.Details,
            Cart: this.Cart,
        },
        props: [
            "minimizeSteps",
            "minimizeCart",
            "layout",
            "stepsParent",
            "cartParent",
            "stylesConfigurationParent",
            "selectedQuestionParent",
            "questionsParent",
            "validatorParent",
            "selectedChapterParent",
            "manualProducts",
            "selectedInputs",
        ],
        emits: [
            "stepsParent",
            "cartParent",
            "stylesConfigurationParent",
            "questionsParent",
            "validatorParent",
            "selectedQuestionParent",
            "selectedChapterParent",
        ],
        watch: {
            cartParent(val) {
                this.cart = val;
            },
            stepsParent(val) {
                this.steps = val;
            },
            selectedQuestionParent(val) {
                this.selectedQuestion = val;
            },
            stylesConfigurationParent(val) {
                this.stylesConfiguration = val;
            },
            validatorParent(val) {
                this.validator = val;
            },
            questionsParent(val) {
                this.questions = val;
            },
            selectedChapterParent(val) {
                this.selectedChapter = val;
            },
        },
        mounted() {
            try {
                localStorage.setItem("surveyJson", JSON.stringify(surveyJson));
            } catch (e) {
                console.log(e);
            }
            this.steps = this.stepsParent;
            this.cart = this.cartParent;
            this.stylesConfiguration = this.stylesConfigurationParent;
            this.questions = this.questionsParent;
            this.validator = this.validatorParent;
            this.selectedQuestion = this.selectedQuestionParent;
            this.selectedChapter = this.selectedChapterParent;
        },
        data() {
            return {
                selectedChapter: null,
                steps: [],
                cart: [],
                stylesConfiguration: {},
                selectedQuestion: null,
                questions: [],
                validator: {},
            };
        },
        methods: {
            layoutType() {
                let type = this.layout.type;
                (this.layout?.children ?? []).forEach((layout) => {
                    type = layout.type;
                });
                return type;
            },
            sectionDivision() {
                let sectionDivision = "";
                for (let i = 0; i < (this.layout?.children ?? []).length; i++) {
                    sectionDivision += `${this.layout.children[i].value}${
                        this.layout.children[i + 1] ? " " : ""
                    }`;
                }
                return sectionDivision;
            },
        },
    };
    createSurvey() {
        const { createApp } = Vue;

        createApp({
            template: `
                <div style="word-break: break-word; overflow-x: hidden; padding: 1.5rem;">
                <div
                  v-if="cartToggle && minimizeCart"
                  class="absolute bg-white rounded shadow-lg border border-blue-100"
                  style="width: 30%; right: 0%; height: 100%; z-index: 1"
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
                <div
                  v-if="minimizeCart"
                  :style="
                    'right: ' +
                    (cartToggle ? '30' : '0') +
                    '%; top: 30%; background: #ed7d31;'
                  "
                  class="absolute card px-3 py-2 bg-white-500 rounded shadow-md cursor-pointer"
                  @click="cartToggle = !cartToggle"
                >
                <svg fill="#2957a4" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                width="20px" height="20px" viewBox="0 0 902.86 902.86"
                xml:space="preserve">
           <g>
               <g>
                   <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z
                        M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z"/>
                   <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717
                       c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744
                       c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742
                       C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744
                       c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z
                        M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742
                       S619.162,694.432,619.162,716.897z"/>
               </g>
           </g>
           </svg>
                </div>
                <Sections
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
                  :selectedInputs="selectedInputs"
                  @selectedInputs="selectedInputs = $event"
                  :minimizeCart="minimizeCart"
                  @minimizeCart="minimizeCart = $event"
                  @stepsParent="steps = $event"
                  @selectedQuestionParent="selectedQuestion = $event"
                  @selectedChapterParent="selectedChapter = $event"
                  @cartParent="cart = $event"
                  @validatorParent="validator = $event"
                  @stylesConfigurationParent="stylesConfiguration = $event"
                  @questionsParent="questions = $event"
                />
              </div>
                `,
            data() {
                return {
                    cartToggle: false,
                    minimizeCart: false,
                    minimizeSteps: false,
                    manualProducts: [],
                    selectedInputs: {},
                    products: {
                        data: [],
                        links: [],
                    },
                    toggleError: false,
                    errorList: {},
                    validator: {},
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
                        layout: {},
                    },
                    chapters: [],
                    option: {},
                    details: "",
                    questions: [],
                    selectedQuestion: null,
                };
            },
            components: {
                Sections: this.Sections,
                Cart: this.Cart,
            },
            mounted() {
                this.steps = surveyJson?.steps ?? [];
                this.stylesConfiguration =
                    surveyJson?.stylesConfiguration ?? this.stylesConfiguration;
                this.selectedQuestion =
                    this.steps?.[0]?.type === "question"
                        ? this.steps?.[0]?.value ?? null
                        : this.steps?.[0]?.value?.questions?.[0] ?? null;
                this.cart = surveyJson?.cart ?? this.cart;
                this.steps.forEach((step) => {
                    if (step.type === "question") {
                        this.questions = [...this.questions, { ...step.value }];
                    } else if (step.type === "chapter") {
                        this.chapters = [...this.chapters, { ...step.value }];
                        this.questions = [
                            ...this.questions,
                            ...step.value.questions.map((question) => {
                                if (question.type) {
                                    return question.value ?? question;
                                }
                                return question;
                            }),
                        ];
                    }
                });
                this.layout = surveyJson?.stylesConfiguration?.layout ?? {};
                this.minimizeCart = this.cart?.minimizeCart;
                this.minimizeSteps = this.cart?.minimizeSteps;
                this.questions.forEach((question) => {
                    this.validator[question.id] = {
                        text: {
                            required: true,
                            error: false,
                            message: "Text is a required field",
                        },
                        description: {
                            required: true,
                            error: false,
                            message: "Description is a required field",
                        },
                        value: {
                            required: true,
                            error: false,
                            message: "Value is a required field",
                        },
                    };
                });
                let products = [];
                this.questions.forEach((question) => {
                    question.configuration.options.forEach((option) => {
                        products = [...products, ...option.products];
                    });
                    question.configuration.conditions.forEach((condition) => {
                        products = [...products, ...condition.products];
                    });
                });
                products = Array.from(
                    new Set(products.map((product) => product.id))
                ).map((productId) => {
                    const product = products.find((pr) => pr.id == productId);
                    return {
                        id: product.id,
                        articleNumber: product.articleNumber,
                        name: product.name,
                        listingPrice: product.listingPrice,
                        status: product.status,
                        salePrice: product.salePrice,
                        profit: product.profit,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        parentId: product.parentId,
                        belongsTo: product?.belongsTo ?? [],
                    };
                });
                this.products = [...products];
            },
            methods: {
                result(quantity) {
                    let numberInputs = JSON.parse(quantity);
                    let result = 0;
                    numberInputs.forEach((input) => {
                        if (input.operator) {
                            this.javascriptExecuter(
                                `result = result ${
                                    input.operator
                                } ${+this.calculateResult(input)}`
                            );
                        } else {
                            result = +this.calculateResult(input);
                        }
                    });
                    return result;
                },
                calculateResult(numberInput) {
                    let result = 0;
                    if (!numberInput.parenthesis.length) {
                        let foundOption = {};
                        for (let i = 0; i < this.questions.length; i++) {
                            const question = this.questions[i];
                            foundOption =
                                (
                                    question?.value ?? question
                                ).configuration.options.find(
                                    (op) => op.uuid === numberInput.id
                                ) ?? {};
                            if (Object.keys(foundOption).length > 0) {
                                break;
                            }
                        }
                        result = +(foundOption?.value ?? numberInput.value);
                    } else {
                        let foundOption = {};
                        for (let i = 0; i < this.questions.length; i++) {
                            const question = this.questions[i];
                            foundOption =
                                (
                                    question?.value ?? question
                                ).configuration.options.find(
                                    (op) => op.uuid === numberInput.id
                                ) ?? {};
                            if (Object.keys(foundOption).length > 0) {
                                break;
                            }
                        }
                        result = +(foundOption?.value ?? numberInput.value);
                        numberInput.parenthesis.forEach((input) => {
                            // result = this.calculateResult(input);
                            this.javascriptExecuter(
                                `result = result ${
                                    input.operator
                                } ${this.calculateResult(input)}`
                            );
                        });
                    }
                    return result;
                },
                isJson(value) {
                    try {
                        return typeof JSON.parse(value) === "object";
                    } catch (e) {
                        return false;
                    }
                },
                checkForFormula(formula) {
                    if (this.isJson(formula)) return false;
                    try {
                        return parseInt(formula) ? false : true;
                    } catch (e) {
                        return false;
                    }
                },
                executeFormula(item) {
                    if (this.checkForFormula(item.quantity)) {
                        let matches = item.quantity.match(
                            /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                        );
                        let quantity = item.quantity;
                        matches.forEach((match) => {
                            const foundOption =
                                this.selectedQuestion.configuration.options.find(
                                    (option) =>
                                        option.title.toLowerCase() ===
                                        match.toLowerCase()
                                );
                            quantity = quantity.replace(
                                "$" + match,
                                +foundOption.value
                            );
                        });
                        try {
                            quantity = this.javascriptExecuter(quantity.slice(1));
                        } catch (e) {
                            console.log(e);
                            quantity = 1;
                        }
                        return quantity;
                    } else {
                        return item.quantity;
                    }
                },
            },
        }).mount(this.container);
    }
}

export default Survey;
