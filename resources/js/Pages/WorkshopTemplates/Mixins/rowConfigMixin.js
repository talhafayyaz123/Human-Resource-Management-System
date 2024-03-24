export default {
    methods: {
        /**
         * gets the selected row from the document and get it's offset width and divide it equally amongst the columns
         * @param {widget} the row of which the columns are being equally sized
         */
        sizeColumnsEqually(widget) {
            // the timeout is added to make sure that the row is loaded in the DOM
            setTimeout(() => {
                let row = "";
                let width = "";
                if (widget.configuration.equallySizedColumns) {
                    row = document.querySelector("#row-" + widget.id);
                    width =
                        (row?.offsetWidth ?? 0) /
                        (widget?.columns?.length ?? 0);
                }
                widget.columns.forEach((column) => {
                    if (widget.configuration.equallySizedColumns)
                        column.configuration.inlineCSS.width = width;
                    column.configuration.equallySizedColumns =
                        widget.configuration.equallySizedColumns;
                });
            }, 500);
        },
    },
};
