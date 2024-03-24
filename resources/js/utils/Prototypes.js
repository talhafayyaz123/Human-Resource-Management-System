import store from "../store";

const hasRole = (roleName) => {
    let roles = JSON.parse(localStorage.getItem("roles") ?? "[]");
    return roles.indexOf(roleName) !== -1;
};

const formatter = (
    number,
    language = "en",
    currency = "EUR",
    simpleNumber,
    minimumFractionDigits = 2,
    maximumFractionDigits = 20
) => {
    const languages = {
        en: "GB",
        de: "DE",
    };
    let formattedNumber = number;
    try {
        let obj = {};
        if (!simpleNumber) {
            obj = {
                style: "currency",
                currency: currency,
            };
        }
        formattedNumber = new Intl.NumberFormat(
            language + "-" + languages[language],
            {
                ...obj,
                minimumFractionDigits: minimumFractionDigits,
                maximumFractionDigits: maximumFractionDigits,
            }
        ).format(number);
    } catch (e) {
        console.log(e);
    } finally {
        return formattedNumber;
    }
};

const dateFormatter = (date, language = "en") => {
    let formattedDate = '';

    try {
        const dateTemp = adjustForDST(new Date(date));
        const formatDate = (dateTemp, lang) => {
            const dateString = dateTemp.toLocaleDateString(lang);
            const timeString = dateTemp.toLocaleTimeString(lang);
            return date.includes(':') ? `${dateString} ${timeString.replace(/\//g, '.')}` : dateString;
        };
        
        if (date && !isNaN(dateTemp)) {
            formattedDate = language === "de" ? formatDate(dateTemp, "de-DE") : formatDate(dateTemp, "en-US");
        } 
        // else {
        //     throw new Error('Invalid date format');
        // }
    } catch (e) {
        console.error(e);
    } finally {
        return formattedDate;
    }
};

const  adjustForDST = (date) => {
    // Get the current time zone offset in minutes
    var standardOffset = new Date(date.getFullYear(), 0, 1).getTimezoneOffset();
  
    // Check if daylight saving time is in effect
    var isDST = date.getTimezoneOffset() < standardOffset;
  
    // Adjust the date for daylight saving time
    var adjustedDate = new Date(date.getTime() + (isDST ? -1 : 1) * 60 * 60 * 1000);
  
    return adjustedDate;
  }


/**
 * converts dashed string to capitalizes string without dashes
 * @param {string} string to be converted
 * @returns capitalized string without dashes
 */
const dashedToRegularFormat = (string) => {
    try {
        // Split the input string by dashes
        const words = string.split("-");

        // Capitalize each word and join them with spaces
        const capitalizedWords = words.map(
            (word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        );

        // Join the capitalized words to form the final string
        const result = capitalizedWords.join(" ");

        return result;
    } catch (e) {
        return string;
    }
};

export default {
    dashedToRegularFormat: dashedToRegularFormat,
    dateFormatter: dateFormatter,
    formatter: formatter,
    hasRole: hasRole,
    can: (permissionName) => {
        let permissions = store.getters["auth/userPermissions"];
        return permissions.indexOf(permissionName) !== -1 || hasRole("admin");
    },
};
