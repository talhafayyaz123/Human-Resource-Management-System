<template>
    <div id="monaco-container" :style="`height: ${height}; width: 95%`"></div>
</template>

<script>
import loader from "@monaco-editor/loader";

let editor = null;

export default {
    props: {
        selectedFile: String,
        codeString: String,
        readOnly: Boolean,
        language: {
            type: String,
            default: "javascript",
        },
        height: {
            type: String,
            default: "500px",
        },
    },
    watch: {
        selectedFile(val) {
            if (val === "page") {
                this.convertedStringChanged("");
            }
        },
        codeString(val) {
            // if editor is not null then set the model wheen codeString has changed
            if (editor) this.setModel(val);
        },
    },
    mounted() {
        this.loadEditor();
        this.observer = new ResizeObserver((entries) => {
            editor?.layout();
        });
        this.observer.observe(document.querySelector("#monaco-container"));
    },
    beforeDestroy() {
        editor.dispose();
        try {
            this.observer.unobserve(
                document.querySelector("#monaco-container")
            );
        } catch (err) {
            //
        }
    },
    methods: {
        /**
         * Sets the model when the widget string is changed
         * @params widgetString: converted flutter string or pubspec yaml string
         */
        convertedStringChanged(widgetString) {
            this.convertedString =
                widgetString || localStorage.getItem("convertedCode");
            // checks if editor has loaded
            if (editor) this.setModel(""); // sets the model of the editor
        },
        setModel(code) {
            editor.setModel(
                monaco.editor.createModel(
                    code,
                    this.languageEnums[this.language]
                )
            );
            // automatically run the formatter after the model is set
            setTimeout(() => {
                editor.getAction("editor.action.formatDocument").run();
            }, 100);
        },
        loadEditor() {
            loader.init().then((monaco) => {
                const editorOptions = {
                    minimap: { enabled: true },
                    theme: "vs-dark",
                    language: this.languageEnums[this.language],
                };

                if (this.language === "php") {
                    monaco.languages.register({ id: "php" });

                    // Register a tokens provider for the language
                    monaco.languages.setMonarchTokensProvider("php", {
                        tokenizer: {
                            root: [
                                // [/<\?((php)|=)?/, { token: '@rematch', switchTo: '@phpInSimpleState.root' }],
                                { include: "phpRoot" },
                                [/<!DOCTYPE/, "metatag.html", "@doctype"],
                                [/<!--/, "comment.html", "@comment"],
                                [
                                    /(<)(\w+)(\/>)/,
                                    [
                                        "delimiter.html",
                                        "tag.html",
                                        "delimiter.html",
                                    ],
                                ],
                                [
                                    /(<)(script)/,
                                    [
                                        "delimiter.html",
                                        { token: "tag.html", next: "@script" },
                                    ],
                                ],
                                [
                                    /(<)(style)/,
                                    [
                                        "delimiter.html",
                                        { token: "tag.html", next: "@style" },
                                    ],
                                ],
                                [
                                    /(<)([:\w]+)/,
                                    [
                                        "delimiter.html",
                                        {
                                            token: "tag.html",
                                            next: "@otherTag",
                                        },
                                    ],
                                ],
                                [
                                    /(<\/)(\w+)/,
                                    [
                                        "delimiter.html",
                                        {
                                            token: "tag.html",
                                            next: "@otherTag",
                                        },
                                    ],
                                ],
                                [/</, "delimiter.html"],
                                [/[^<]+/], // text
                            ],
                            doctype: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo: "@phpInSimpleState.comment",
                                    },
                                ],
                                [/[^>]+/, "metatag.content.html"],
                                [/>/, "metatag.html", "@pop"],
                            ],
                            comment: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo: "@phpInSimpleState.comment",
                                    },
                                ],
                                [/-->/, "comment.html", "@pop"],
                                [/[^-]+/, "comment.content.html"],
                                [/./, "comment.content.html"],
                            ],
                            otherTag: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo: "@phpInSimpleState.otherTag",
                                    },
                                ],
                                [/\/?>/, "delimiter.html", "@pop"],
                                [/"([^"]*)"/, "attribute.value"],
                                [/'([^']*)'/, "attribute.value"],
                                [/[\w\-]+/, "attribute.name"],
                                [/=/, "delimiter"],
                                [/[ \t\r\n]+/],
                            ],
                            // -- BEGIN <script> tags handling
                            // After <script
                            script: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo: "@phpInSimpleState.script",
                                    },
                                ],
                                [/type/, "attribute.name", "@scriptAfterType"],
                                [/"([^"]*)"/, "attribute.value"],
                                [/'([^']*)'/, "attribute.value"],
                                [/[\w\-]+/, "attribute.name"],
                                [/=/, "delimiter"],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@scriptEmbedded.text/javascript",
                                        nextEmbedded: "text/javascript",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /(<\/)(script\s*)(>)/,
                                    [
                                        "delimiter.html",
                                        "tag.html",
                                        {
                                            token: "delimiter.html",
                                            next: "@pop",
                                        },
                                    ],
                                ],
                            ],
                            // After <script ... type
                            scriptAfterType: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.scriptAfterType",
                                    },
                                ],
                                [/=/, "delimiter", "@scriptAfterTypeEquals"],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@scriptEmbedded.text/javascript",
                                        nextEmbedded: "text/javascript",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/script\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            // After <script ... type =
                            scriptAfterTypeEquals: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.scriptAfterTypeEquals",
                                    },
                                ],
                                [
                                    /"([^"]*)"/,
                                    {
                                        token: "attribute.value",
                                        switchTo: "@scriptWithCustomType.$1",
                                    },
                                ],
                                [
                                    /'([^']*)'/,
                                    {
                                        token: "attribute.value",
                                        switchTo: "@scriptWithCustomType.$1",
                                    },
                                ],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@scriptEmbedded.text/javascript",
                                        nextEmbedded: "text/javascript",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/script\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            // After <script ... type = $S2
                            scriptWithCustomType: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.scriptWithCustomType.$S2",
                                    },
                                ],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@scriptEmbedded.$S2",
                                        nextEmbedded: "$S2",
                                    },
                                ],
                                [/"([^"]*)"/, "attribute.value"],
                                [/'([^']*)'/, "attribute.value"],
                                [/[\w\-]+/, "attribute.name"],
                                [/=/, "delimiter"],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/script\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            scriptEmbedded: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInEmbeddedState.scriptEmbedded.$S2",
                                        nextEmbedded: "@pop",
                                    },
                                ],
                                [
                                    /<\/script/,
                                    {
                                        token: "@rematch",
                                        next: "@pop",
                                        nextEmbedded: "@pop",
                                    },
                                ],
                            ],
                            // -- END <script> tags handling
                            // -- BEGIN <style> tags handling
                            // After <style
                            style: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo: "@phpInSimpleState.style",
                                    },
                                ],
                                [/type/, "attribute.name", "@styleAfterType"],
                                [/"([^"]*)"/, "attribute.value"],
                                [/'([^']*)'/, "attribute.value"],
                                [/[\w\-]+/, "attribute.name"],
                                [/=/, "delimiter"],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@styleEmbedded.text/css",
                                        nextEmbedded: "text/css",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /(<\/)(style\s*)(>)/,
                                    [
                                        "delimiter.html",
                                        "tag.html",
                                        {
                                            token: "delimiter.html",
                                            next: "@pop",
                                        },
                                    ],
                                ],
                            ],
                            // After <style ... type
                            styleAfterType: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.styleAfterType",
                                    },
                                ],
                                [/=/, "delimiter", "@styleAfterTypeEquals"],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@styleEmbedded.text/css",
                                        nextEmbedded: "text/css",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/style\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            // After <style ... type =
                            styleAfterTypeEquals: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.styleAfterTypeEquals",
                                    },
                                ],
                                [
                                    /"([^"]*)"/,
                                    {
                                        token: "attribute.value",
                                        switchTo: "@styleWithCustomType.$1",
                                    },
                                ],
                                [
                                    /'([^']*)'/,
                                    {
                                        token: "attribute.value",
                                        switchTo: "@styleWithCustomType.$1",
                                    },
                                ],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@styleEmbedded.text/css",
                                        nextEmbedded: "text/css",
                                    },
                                ],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/style\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            // After <style ... type = $S2
                            styleWithCustomType: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInSimpleState.styleWithCustomType.$S2",
                                    },
                                ],
                                [
                                    />/,
                                    {
                                        token: "delimiter.html",
                                        next: "@styleEmbedded.$S2",
                                        nextEmbedded: "$S2",
                                    },
                                ],
                                [/"([^"]*)"/, "attribute.value"],
                                [/'([^']*)'/, "attribute.value"],
                                [/[\w\-]+/, "attribute.name"],
                                [/=/, "delimiter"],
                                [/[ \t\r\n]+/],
                                [
                                    /<\/style\s*>/,
                                    { token: "@rematch", next: "@pop" },
                                ],
                            ],
                            styleEmbedded: [
                                [
                                    /<\?((php)|=)?/,
                                    {
                                        token: "@rematch",
                                        switchTo:
                                            "@phpInEmbeddedState.styleEmbedded.$S2",
                                        nextEmbedded: "@pop",
                                    },
                                ],
                                [
                                    /<\/style/,
                                    {
                                        token: "@rematch",
                                        next: "@pop",
                                        nextEmbedded: "@pop",
                                    },
                                ],
                            ],
                            // -- END <style> tags handling
                            phpInSimpleState: [
                                [/<\?((php)|=)?/, "metatag.php"],
                                [
                                    /\?>/,
                                    {
                                        token: "metatag.php",
                                        switchTo: "@$S2.$S3",
                                    },
                                ],
                                { include: "phpRoot" },
                            ],
                            phpInEmbeddedState: [
                                [/<\?((php)|=)?/, "metatag.php"],
                                [
                                    /\?>/,
                                    {
                                        token: "metatag.php",
                                        switchTo: "@$S2.$S3",
                                        nextEmbedded: "$S3",
                                    },
                                ],
                                { include: "phpRoot" },
                            ],
                            phpRoot: [
                                [
                                    /[a-zA-Z_]\w*/,
                                    {
                                        cases: {
                                            "@phpKeywords": {
                                                token: "keyword.php",
                                            },
                                            "@phpCompileTimeConstants": {
                                                token: "constant.php",
                                            },
                                            "@default": "identifier.php",
                                        },
                                    },
                                ],
                                [
                                    /[$a-zA-Z_]\w*/,
                                    {
                                        cases: {
                                            "@phpPreDefinedVariables": {
                                                token: "variable.predefined.php",
                                            },
                                            "@default": "variable.php",
                                        },
                                    },
                                ],
                                // brackets
                                [/[{}]/, "delimiter.bracket.php"],
                                [/[\[\]]/, "delimiter.array.php"],
                                [/[()]/, "delimiter.parenthesis.php"],
                                // whitespace
                                [/[ \t\r\n]+/],
                                // comments
                                [/(#|\/\/)$/, "comment.php"],
                                [/(#|\/\/)/, "comment.php", "@phpLineComment"],
                                // block comments
                                [/\/\*/, "comment.php", "@phpComment"],
                                // strings
                                [/"/, "string.php", "@phpDoubleQuoteString"],
                                [/'/, "string.php", "@phpSingleQuoteString"],
                                // delimiters
                                [
                                    /[\+\-\*\%\&\|\^\~\!\=\<\>\/\?\;\:\.\,\@]/,
                                    "delimiter.php",
                                ],
                                // numbers
                                [/\d*\d+[eE]([\-+]?\d+)?/, "number.float.php"],
                                [
                                    /\d*\.\d+([eE][\-+]?\d+)?/,
                                    "number.float.php",
                                ],
                                [
                                    /0[xX][0-9a-fA-F']*[0-9a-fA-F]/,
                                    "number.hex.php",
                                ],
                                [/0[0-7']*[0-7]/, "number.octal.php"],
                                [/0[bB][0-1']*[0-1]/, "number.binary.php"],
                                [/\d[\d']*/, "number.php"],
                                [/\d/, "number.php"],
                            ],
                            phpComment: [
                                [/\*\//, "comment.php", "@pop"],
                                [/[^*]+/, "comment.php"],
                                [/./, "comment.php"],
                            ],
                            phpLineComment: [
                                [/\?>/, { token: "@rematch", next: "@pop" }],
                                [/.$/, "comment.php", "@pop"],
                                [/[^?]+$/, "comment.php", "@pop"],
                                [/[^?]+/, "comment.php"],
                                [/./, "comment.php"],
                            ],
                            phpDoubleQuoteString: [
                                [/[^\\"]+/, "string.php"],
                                [/@escapes/, "string.escape.php"],
                                [/\\./, "string.escape.invalid.php"],
                                [/"/, "string.php", "@pop"],
                            ],
                            phpSingleQuoteString: [
                                [/[^\\']+/, "string.php"],
                                [/@escapes/, "string.escape.php"],
                                [/\\./, "string.escape.invalid.php"],
                                [/'/, "string.php", "@pop"],
                            ],
                        },
                        phpKeywords: [
                            "abstract",
                            "and",
                            "array",
                            "as",
                            "break",
                            "callable",
                            "case",
                            "catch",
                            "cfunction",
                            "class",
                            "clone",
                            "const",
                            "continue",
                            "declare",
                            "default",
                            "do",
                            "else",
                            "elseif",
                            "enddeclare",
                            "endfor",
                            "endforeach",
                            "endif",
                            "endswitch",
                            "endwhile",
                            "extends",
                            "false",
                            "final",
                            "for",
                            "foreach",
                            "function",
                            "global",
                            "goto",
                            "if",
                            "implements",
                            "interface",
                            "instanceof",
                            "insteadof",
                            "namespace",
                            "new",
                            "null",
                            "object",
                            "old_function",
                            "or",
                            "private",
                            "protected",
                            "public",
                            "resource",
                            "static",
                            "switch",
                            "throw",
                            "trait",
                            "try",
                            "true",
                            "use",
                            "var",
                            "while",
                            "xor",
                            "die",
                            "echo",
                            "empty",
                            "exit",
                            "eval",
                            "include",
                            "include_once",
                            "isset",
                            "list",
                            "require",
                            "require_once",
                            "return",
                            "print",
                            "unset",
                            "yield",
                            "__construct",
                        ],
                        phpCompileTimeConstants: [
                            "__CLASS__",
                            "__DIR__",
                            "__FILE__",
                            "__LINE__",
                            "__NAMESPACE__",
                            "__METHOD__",
                            "__FUNCTION__",
                            "__TRAIT__",
                        ],
                        phpPreDefinedVariables: [
                            "$GLOBALS",
                            "$_SERVER",
                            "$_GET",
                            "$_POST",
                            "$_FILES",
                            "$_REQUEST",
                            "$_SESSION",
                            "$_ENV",
                            "$_COOKIE",
                            "$php_errormsg",
                            "$HTTP_RAW_POST_DATA",
                            "$http_response_header",
                            "$argc",
                            "$argv",
                        ],
                        escapes:
                            /\\(?:[abfnrtv\\"']|x[0-9A-Fa-f]{1,4}|u[0-9A-Fa-f]{4}|U[0-9A-Fa-f]{8})/,
                    });
                }

                editor = monaco.editor.create(
                    document.getElementById("monaco-container"),
                    editorOptions
                );
                this.$nextTick(() => {
                    this.setModel(this.codeString);
                    monaco.editor.onDidChangeMarkers(([uri]) => {
                        const markers = monaco.editor.getModelMarkers({
                            resource: uri,
                        });
                        this.containsError = markers.length > 0;
                        this.$emit("containsError", this.containsError);
                    });
                    editor.onDidChangeModelContent((e) => {
                        this.$emit("contentChange", editor.getValue());
                    });
                });
            });
        },
    },
    data() {
        return {
            convertedString: "",
            languageEnums: {
                js: "javascript",
                css: "css",
                html: "html",
                dart: "dart",
                yaml: "yaml",
                json: "json",
                xml: "xml",
                php: "php",
            },
            observer: null,
            containsError: false,
        };
    },
};
</script>

<style scoped></style>
