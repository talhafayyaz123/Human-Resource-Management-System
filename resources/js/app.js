import "./bootstrap";

import Prototypes from "./utils/Prototypes";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { i18nVue } from "laravel-vue-i18n";

/* From the FontAwesome Docs */
import { library } from "@fortawesome/fontawesome-svg-core";

import mitt from "mitt";

// quill editor css
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "quilljs-markdown/dist/quilljs-markdown-common-style.css";

// resources/js/app.js
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap/dist/js/bootstrap';

import "@/assets/scss/style.scss";

/* import specific icons */
import CustomIcon from "./Components/Icon.vue";
import {
    faTrashCan,
    faHardDrive,
    faPenToSquare,
    faCircle,
    faArrowAltCircleDown,
} from "@fortawesome/free-regular-svg-icons";
import {
    faCaretDown,
    faTriangleExclamation,
    faArrowsSpin,
    faPrint,
    faExclamationTriangle,
    faAnglesLeft,
    faAnglesRight,
    faPhone,
    faUsersGear,
    faKey,
    faIdCardClip,
    faTrash,
    faCheck,
    faXmark,
    faUsersRectangle,
    faGrip,
    faEye,
    faChevronDown,
    faChevronRight,
    faArrowsLeftRight,
    faUser,
    faPlus,
    faFilePen,
    faTicket,
    faCirclePlus,
    faPercent,
    faPalette,
    faGear,
    faEllipsisVertical,
    faCamera,
    faBusinessTime,
    faUmbrellaBeach,
    faPlane,
    faBox,
    faMoneyCheckDollar,
    faClipboardQuestion,
    faDatabase,
    faFileShield,
    faMoneyBillTrendUp,
    faPencil,
    faFolderOpen,
    faFolderPlus,
    faFileCirclePlus,
    faHandHoldingDollar,
    faCloud,
    faCar,
    faHouse,
    faGears,
    faExpand,
    faLocationDot,
    faEnvelope,
    faMobile,
    faMapPin,
    faWindowMinimize,
    faMaximize,
    faCartShopping,
    faClipboard,
    faTruck,
    faMapLocation,
    faUsersViewfinder,
    faBuildingColumns,
    faUserTie,
    faChartSimple,
    faFilterCircleDollar,
    faHourglass,
    faGaugeSimpleHigh,
    faSortUp,
    faSortDown,
    faUsersBetweenLines,
    faFileSignature,
    faArrowUp,
    faArrowDown,
    faFileWord,
    faHandPointer,
    faHeading,
    faParagraph,
    faCheckSquare,
    faCircleDot,
    faTable,
    faKeyboard,
    fa1,
    faNewspaper,
    faUpDownLeftRight,
    faClipboardList,
    faCertificate,
    faHandshake,
    faCartPlus,
    faCircleMinus,
    faChartLine,
    faPeopleGroup,
    faFont,
    faGraduationCap,
    faBookOpen,
    faQuestion,
    faCloudArrowUp,
    faClock,
    faPaperPlane,
    faFileCircleCheck,
    faClockRotateLeft,
    faListCheck,
    faFileCsv,
    faCartFlatbed,
    faInfoCircle,
    faRefresh,
    faTerminal,
    faDownload,
} from "@fortawesome/free-solid-svg-icons";

/* add icons to the library */
library.add(
    faDownload,
    faTerminal,
    faInfoCircle,
    faCartFlatbed,
    faTriangleExclamation,
    faListCheck,
    faArrowsSpin,
    faClockRotateLeft,
    faFileCircleCheck,
    faFileCsv,
    faPaperPlane,
    faClock,
    faCloudArrowUp,
    faQuestion,
    faBookOpen,
    faGraduationCap,
    faFont,
    faPeopleGroup,
    faChartLine,
    faCircleMinus,
    faCartPlus,
    faHandshake,
    faCertificate,
    faClipboardList,
    faUpDownLeftRight,
    faNewspaper,
    fa1,
    faKeyboard,
    faTable,
    faCircleDot,
    faCheckSquare,
    faParagraph,
    faHeading,
    faHandPointer,
    faFileWord,
    faFileSignature,
    faUsersBetweenLines,
    faSortUp,
    faSortDown,
    faPrint,
    faExclamationTriangle,
    faGaugeSimpleHigh,
    faHourglass,
    faFilterCircleDollar,
    faChartSimple,
    faUserTie,
    faBuildingColumns,
    faUsersViewfinder,
    faMapLocation,
    faTruck,
    faClipboard,
    faCartShopping,
    faMaximize,
    faWindowMinimize,
    faMapPin,
    faMobile,
    faEnvelope,
    faLocationDot,
    faExpand,
    faGears,
    faCar,
    faHouse,
    faCloud,
    faHandHoldingDollar,
    faFolderPlus,
    faFileCirclePlus,
    faFolderOpen,
    faPencil,
    faMoneyBillTrendUp,
    faFileShield,
    faDatabase,
    faClipboardQuestion,
    faMoneyCheckDollar,
    faBox,
    faPlane,
    faUmbrellaBeach,
    faEye,
    faTrashCan,
    faHardDrive,
    faPenToSquare,
    faUsersGear,
    faPhone,
    faKey,
    faIdCardClip,
    faTrash,
    faCheck,
    faXmark,
    faUsersRectangle,
    faGrip,
    faChevronDown,
    faChevronRight,
    faArrowsLeftRight,
    faUser,
    faCircle,
    faPlus,
    faFilePen,
    faTicket,
    faCirclePlus,
    faPercent,
    faArrowAltCircleDown,
    faPalette,
    faGear,
    faAnglesLeft,
    faAnglesRight,
    faEllipsisVertical,
    faCamera,
    faBusinessTime,
    faArrowUp,
    faArrowDown,
    faRefresh,
    faCaretDown
);

/** importing vue js */
import { createApp } from "vue";

import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import EditHistory from "@/Components/EditHistory.vue";

const emitter = mitt();

const app = createApp(App)
    .use(router)
    .use(store)
    .use(i18nVue, {
        lang: localStorage.getItem("language") || "en",
        resolve: async (lang) => {
            const langs = import.meta.glob("../../lang/*.json");
            return await langs[`../../lang/${lang}.json`]();
        },
    });

app.component("font-awesome-icon", FontAwesomeIcon);

app.component("datepicker", VueDatePicker);

app.component("EditHistory", EditHistory);

app.component("CustomIcon", CustomIcon);

app.use(VueSweetalert2);

app.config.globalProperties.$can = Prototypes.can;
app.config.globalProperties.$hasRole = Prototypes.hasRole;
app.config.globalProperties.$formatter = Prototypes.formatter;
app.config.globalProperties.$dateFormatter = Prototypes.dateFormatter;
app.config.globalProperties.$dashedToRegularFormat =
    Prototypes.dashedToRegularFormat;
app.config.globalProperties.$emitter = emitter;

app.mixin({
    methods: {
        javascriptExecuter(obj) {
            return Function('"use strict";return (' + obj + ")").call(this);
        },
        /**
         * Sums up the result of applying a function to each element in an array.
         *
         * @param {Array} arr - The input array.
         * @param {Function} iteratee - The function invoked per iteration.
         * @returns {number} - The sum of the results.
         */
        sumBy(arr, iteratee) {
            return arr.reduce((sum, el) => sum + iteratee(el), 0);
        },

        /**
         * Creates an object composed of the object properties predicate returns
         * truthy for. The predicate is invoked with three arguments: (value, key, object).
         *
         * @param {Object} obj - The input object.
         * @returns {Object} - The new object.
         */
        pickBy(obj) {
            const result = {};
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    result[key] = obj[key];
                }
            }
            return result;
        },

        /**
         * Creates an object with the same keys as the input object and values generated
         * by applying a function to each value in the input object.
         *
         * @param {Object} obj - The input object.
         * @param {Function} iteratee - The function invoked per iteration.
         * @returns {Object} - The new object.
         */
        mapValues(obj, iteratee) {
            const result = {};
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    result[key] = iteratee(obj[key], key, obj);
                }
            }
            return result;
        },

        /**
         * Creates a throttled version of a function that limits the rate it is called.
         *
         * @param {Function} func - The function to throttle.
         * @param {number} wait - The time to wait between function calls in milliseconds.
         * @returns {Function} - The throttled function.
         */
        throttle(func, wait) {
            let isThrottled = false;
            let lastArgs;
            let lastThis;

            return function (...args) {
                if (!isThrottled) {
                    func.apply(this, args);
                    isThrottled = true;
                    lastArgs = args;
                    lastThis = this;

                    setTimeout(() => {
                        isThrottled = false;
                        if (lastArgs) {
                            func.apply(lastThis, lastArgs);
                            lastArgs = null;
                            lastThis = null;
                        }
                    }, wait);
                } else {
                    lastArgs = args;
                    lastThis = this;
                }
            };
        },

        /**
         * Creates a deep clone of an object or array.
         *
         * @param {Object|Array} obj - The object or array to clone.
         * @returns {Object|Array} - The deep clone of the input object or array.
         */
        cloneDeep(obj) {
            if (obj === null || typeof obj !== "object") {
                return obj;
            }

            if (Array.isArray(obj)) {
                const newArray = [];
                for (let i = 0; i < obj.length; i++) {
                    newArray[i] = this.cloneDeep(obj[i]);
                }
                return newArray;
            }

            const newObj = {};
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    newObj[key] = this.cloneDeep(obj[key]);
                }
            }

            return newObj;
        },

        /**
         * converts blob to base64 and returns the base64 string
         * @param {data} blob data to be converted to base64
         * @returns
         */
        convertBlobToBase64(data) {
            return new Promise((resolve, reject) => {
                var reader = new FileReader();
                reader.readAsDataURL(data);
                reader.onerror = (err) => {
                    reject(err);
                };
                reader.onloadend = () => {
                    resolve(reader.result);
                };
            });
        },
        /**
         * limits the input to alphabets
         * @param {event} keypress event
         */
        limitToAlphabets(event) {
            if (
                (event.charCode >= 65 && event.charCode <= 90) ||
                (event.charCode >= 97 && event.charCode <= 122)
            ) {
                return;
            }
            event.preventDefault();
        },
        /**
         * limits the number to positive inputs only
         * @param {event} keypress event
         */
        limitToPositiveNumbers(event) {
            if (event.charCode < 48 || event.charCode > 58) {
                event.preventDefault();
            }
        },
        /**
         * Used to format a number based on the selected language
         *
         * @param {value} the number to be formatted
         * @param {minimumFractionDigits} the minimum number of digits allowed after the floating point
         * @param {maximumFractionDigits} the maximum number of digits allowed after the floating point
         * @public This is a public method
         */
        numberFormatter(value, minimumFractionDigits, maximumFractionDigits) {
            let formattedNumber = value;
            if (this.globalLanguage === "en") {
                formattedNumber = new Intl.NumberFormat("en-GB", {
                    minimumFractionDigits: minimumFractionDigits,
                    maximumFractionDigits: maximumFractionDigits,
                }).format(value);
            } else if (this.globalLanguage === "de") {
                formattedNumber = new Intl.NumberFormat("de-DE", {
                    minimumFractionDigits: minimumFractionDigits,
                    maximumFractionDigits: maximumFractionDigits,
                }).format(value);
            }
            return formattedNumber;
        },
        sort(columnName, tableName = "", variableName = "form") {
            try {
                this[variableName].sortBy = columnName;
                this[variableName].sortOrder =
                    this[variableName].sortOrder === "asc" ? "desc" : "asc";
                const key = `sort_${tableName}`;
                localStorage.setItem(`${key}_column`, columnName); // Store the column name
                localStorage.setItem(
                    `${key}_order`,
                    this[variableName].sortOrder
                ); // Store the sort order
            } catch (e) {
                console.log(e);
            }
        },
        formatDateLite(date, showTime = false) {
            try {
                const m = date.getMonth() + 1;
                const d = date.getDate();
                return `${date.getFullYear()}-${m < 10 ? "0" : ""}${m}-${
                    d < 10 ? "0" : ""
                }${d}${showTime ? " " + date.toLocaleTimeString() : ""}`;
            } catch (e) {
                return date;
            }
        },
        dateFormat() {
            let language = store.getters["language"];
            if (language === "en") {
                return "yyyy-MM-dd";
            } else if (language === "de") {
                return "dd.MM.yyyy";
            }
        },
    },
    computed: {
        globalLanguage() {
            return store.getters["language"];
        },
    },
});

app.config.unwrapInjectedRef = true;

app.mount("#app");
/** */
