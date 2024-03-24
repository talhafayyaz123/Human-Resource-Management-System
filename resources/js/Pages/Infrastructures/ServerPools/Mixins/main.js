export default {
    methods: {
        formatCompactTimestamp(timestamp) {
            const secondsInMinute = 60;
            const secondsInHour = 60 * secondsInMinute;
            const secondsInDay = 24 * secondsInHour;

            const days = Math.floor(timestamp / secondsInDay);
            const hours = Math.floor(
                (timestamp % secondsInDay) / secondsInHour
            );
            const minutes = Math.floor(
                (timestamp % secondsInHour) / secondsInMinute
            );

            return `${days}d ${hours}h ${minutes}m`;
        },
        openMobaURL(name, ip, user = "root", sshPort = 22) {
            var url =
                "mobaxterm:" +
                encodeURIComponent(
                    name +
                        "=#109#0%" +
                        ip +
                        "%" +
                        sshPort +
                        "%" +
                        user +
                        "%%-1%-1%%%%%0%0%0%C:\\id_rsa%%-1%0%0%0%%1080%%0%0%1%#MobaFont%10%0%0%-1%15%236,236,236%30,30,30%180,180,192%0%-Q1%0%%xterm%-1%0%_Std_Colors_0_%80%24%0%1%-1%<none>%%0%0%-1%-1#0# #-1"
                );
            window.open(url);
        },
    },
};
