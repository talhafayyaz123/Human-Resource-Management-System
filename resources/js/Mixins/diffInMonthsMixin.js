export default {
    methods: {
        /**
         * calculates the difference between two dates in months
         * @param {startDate} the start date
         * @param {endDate} the end date
         * @returns float/int
         */
        getMonthDifference(startDate=new Date(), endDate= new Date()) {
            var startYear = startDate.getFullYear();
            var startMonth = startDate.getMonth();

            var endYear = endDate.getFullYear();
            var endMonth = endDate.getMonth();

            var monthDiff =
                (endYear - startYear) * 12 + (endMonth - startMonth);
            
            return monthDiff + 1;
        },
    },
};
