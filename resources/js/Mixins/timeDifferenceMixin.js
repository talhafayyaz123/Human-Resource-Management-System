export default {
    methods: {
        calculateTimeDifference(startTime, endTime) {
            if (
                !startTime ||
                !endTime ||
                !startTime.match(/^\d{2}:\d{2}$/) ||
                !endTime.match(/^\d{2}:\d{2}$/)
            ) {
                return 0; // Handle invalid input
            }

            startTime = startTime.split(":");
            endTime = endTime.split(":");
            var startDate = new Date(0, 0, 0, startTime[0], startTime[1], 0);
            var endDate = new Date(0, 0, 0, endTime[0], endTime[1], 0);

            if (startDate > endDate) {
                return 0; // Handle startTime > endTime
            }

            var diff = endDate.getTime() - startDate.getTime();
            var hours = diff / 1000 / 60 / 60;
            return isNaN(hours) ? 0 : hours;
        },
    },
};
