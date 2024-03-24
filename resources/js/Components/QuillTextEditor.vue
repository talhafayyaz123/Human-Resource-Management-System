<template>
    <div :class="$attrs.class">
        <div class="mb-1 items-center" :class="['flex', !!label ? 'justify-between' : 'justify-end']">
            <label v-if="label" class="" :for="id"
                >{{
                    label
                }}&nbsp;<span v-if="required" style="color: red">*</span></label
            >
            <div v-if="!isReadonly" class="checkbox-group view-source">
                <input
                    v-model="viewSource"
                    class="checkbox-input"
                    id="view-source"
                    type="checkbox"
                />
                <label class="checkbox-label" for="view-source">{{
                    $t("view source")
                }}</label>
                
            </div>
        </div>
        <QuillEditor
            :readOnly="isReadonly"
            v-bind="{ ...$attrs }"
            ref="quillEditor"
            @ready="ready"
            @textChange="textChange"
            :content="content"
            :contentType="!!viewSource ? 'text' : contentType"
            :options="options"
            :style="{ height: height }"
            class="form-quilleditor"
        />
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { Delta, QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import DragAndDropModule from "quill-drag-and-drop-module";
import { v4 as uuid } from "uuid";
import Quill from "quill";
import ImageResize from "@taoqf/quill-image-resize-module";
import QuillMarkdown from "quilljs-markdown";
import ImageUploader from "quill-image-uploader";
import "quill-image-uploader/dist/quill.imageUploader.min.css";
import "quilljs-markdown/dist/quilljs-markdown-common-style.css";
Quill.register("modules/imageResize", ImageResize);
Quill.register("modules/QuillMarkdown", QuillMarkdown, true);
Quill.register("modules/imageUploader", ImageUploader);

export default {
    components: {
        QuillEditor,
    },
    inheritAttrs: true,
    data() {
        return {
            viewSource: false,
            options: {
                debug: "info",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"], // toggled buttons
                        ["blockquote", "code-block"],

                        [{ header: 1 }, { header: 2 }], // custom button values
                        [{ list: "ordered" }, { list: "bullet" }],
                        [{ script: "sub" }, { script: "super" }], // superscript/subscript
                        [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
                        [{ direction: "rtl" }], // text direction

                        [{ size: ["small", false, "large", "huge"] }], // custom dropdown
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        ["link", "image", "video", "formula"], // add's image support
                        [{ color: [] }, { background: [] }], // dropdown with defaults from theme
                        [{ font: [] }],
                        [{ align: [] }],

                        ["clean"], // remove formatting button
                    ],
                    dragAndDrop: {
                        draggables: [
                            {
                                content_type_pattern: "^image/",
                                tag: "img",
                                attr: "src",
                            },
                        ],
                        onDrop: function (file) {
                            return DragAndDropModule.utils
                                .getFileDataUrl(file)
                                .then(function (base64_content) {
                                    console.log(base64_content);
                                    return base64_content;
                                })
                                .then(function (response_from_do_something) {
                                    return response_from_do_something;
                                })
                                .catch(function (err) {
                                    return false;
                                });
                        },
                        container: document.getElementById(
                            "#drag-and-drop-container"
                        ),
                    },
                    imageResize: {
                        modules: ["Resize", "DisplaySize", "Toolbar"],
                    },
                    QuillMarkdown: {
                        ignoreTags: ["pre", "strikethrough"], // @option - if you need to ignore some tags.

                        tags: {
                            // @option if you need to change for trigger pattern for some tags.
                            // blockquote: {
                            //     pattern: /^(\|){1,6}\s/g,
                            // },
                            // bold: {
                            //     pattern: /^(\|){1,6}\s/g,
                            // },
                            // italic: {
                            //     pattern: /(\_){1}(.+?)(?:\1){1}/g,
                            // },
                        },
                    },
                    imageUploader: {
                        upload: (file) => {
                            return new Promise(async (resolve, reject) => {
                                const formData = new FormData();
                                formData.append("file", file);
                                try {
                                    // if saveUploadedImagesToStorage is set to true then upload the image to server else convert to base64
                                    if (this.saveUploadedImagesToStorage) {
                                        const response =
                                            await this.$store.dispatch(
                                                "myTasks/imageUpload",
                                                formData
                                            );
                                        resolve(response?.data?.file);
                                    } else {
                                        const base64 =
                                            await this.convertBlobToBase64(
                                                file
                                            );
                                        resolve(base64);
                                    }
                                } catch (e) {
                                    reject(e);
                                }
                            });
                        },
                    },
                },
                placeholder: this.isReadonly
                    ? ""
                    : this.$t("Type here") + "...",
                readOnly: false,
                theme: "snow",
            },
        };
    },
    props: {
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `quill-text-editor-${uuid()}`;
            },
        },
        type: {
            type: String,
            default: "text",
        },
        isReadonly: {
            type: Boolean,
            default: false,
        },
        error: String,
        label: String,
        content: { type: String, default: "{}" },
        modelValue: String,
        selectedQuestion: Object,
        contentType: {
            type: String,
            default: "html",
        },
        height: {
            type: String,
            default: "10rem",
        },
        saveUploadedImagesToStorage: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["update:modelValue"],
    watch: {
        /**
         * rerenders quill when 'view source' is toggled
         * @param {val} editor content
         */
        viewSource(val) {
            this.$refs.quillEditor.reinit();
            if (!val) {
                this.$refs.quillEditor.setHTML(this.content);
            }
        },
        selectedQuestion() {
            try {
                this.$refs.quillEditor.setContents(
                    this.contentType === "delta"
                        ? new Delta(JSON.parse(this.content))
                        : this.content
                );
            } catch (e) {
                this.$refs.quillEditor.setContents(
                    this.contentType === "delta"
                        ? new Delta(this.conten ?? "{}")
                        : this.content
                );
            }
        },
    },
    mounted() {
        this.$refs.quillEditor.focus();
    },
    methods: {
        addTooltipsToToolbar() {
            try {
                let toolbarTooltips = {
                    font: "Select a font",
                    size: "Select a font size",
                    header: "Select the text style",
                    bold: "Bold",
                    italic: "Italic",
                    underline: "Underline",
                    strike: "Strikethrough",
                    color: "Select a text color",
                    video: "Enter a video URL",
                    background: "Select a background color",
                    script: {
                        sub: "Subscript",
                        super: "Superscript",
                    },
                    list: {
                        ordered: "Numbered list",
                        bullet: "Bulleted list",
                    },
                    indent: {
                        "-1": "Decrease indent",
                        "+1": "Increase indent",
                    },
                    direction: {
                        rtl: "Text direction (right to left | left to right)",
                        ltr: "Text direction (left ro right | right to left)",
                    },
                    align: "Text alignment",
                    link: "Insert a link",
                    image: "Insert an image",
                    formula: "Insert a formula",
                    clean: "Clear format",
                    "add-table": "Add a new table",
                    "table-row": "Add a row to the selected table",
                    "table-column": "Add a column to the selected table",
                    "remove-table": "Remove selected table",
                    help: "Show help",
                };

                let showTooltip = (which, el) => {
                    if (which == "button") {
                        var tool = el.className.replace("ql-", "");
                    } else if (which == "span") {
                        var tool = el.className.replace("ql-", "");
                        tool = tool.substr(0, tool.indexOf(" "));
                    }
                    if (tool) {
                        //if element has value attribute.. handling is different
                        //buttons without value
                        if (el.value == "") {
                            if (toolbarTooltips[tool])
                                el.setAttribute("title", toolbarTooltips[tool]);
                        }
                        //buttons with value
                        else if (typeof el.value !== "undefined") {
                            if (toolbarTooltips[tool][el.value])
                                el.setAttribute(
                                    "title",
                                    toolbarTooltips[tool][el.value]
                                );
                        }
                        //default
                        else el.setAttribute("title", toolbarTooltips[tool]);
                    }
                };

                let toolbarElement = document.querySelector(".ql-toolbar");
                if (toolbarElement) {
                    let matchesButtons =
                        toolbarElement.querySelectorAll("button");
                    for (let el of matchesButtons) {
                        showTooltip("button", el);
                    }
                    //for submenus inside
                    let matchesSpans = toolbarElement.querySelectorAll(
                        ".ql-toolbar > span > span"
                    );
                    for (let el of matchesSpans) {
                        showTooltip("span", el);
                    }
                }
            } catch (e) {}
        },
        textChange({ delta, oldContents, source }) {

            this.$emit(
                "delta",
                this.contentType === "delta"
                    ? JSON.stringify(this.$refs.quillEditor.getContents())
                    : this.$refs.quillEditor.getContents()
            );
        
        },
        ready() {
            try {
                this.$refs.quillEditor.setContents(
                    this.contentType === "delta"
                        ? new Delta(JSON.parse(this.content))
                        : this.content
                );
                this.addTooltipsToToolbar();
            } catch (e) {
                this.$refs.quillEditor.setContents(
                    this.contentType === "delta"
                        ? new Delta(this.conten ?? "{}")
                        : this.content
                );
            }
        },
    },
};
</script>
