import axios from "axios";
import store from "../store";
import router from "../router";
let apiService = axios.create({
    baseURL: "/api",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});

apiService.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    config.headers.language = localStorage.getItem("language") ?? "en";
    return config;
});
apiService.interceptors.response.use(
    function (response) {
        store.commit("isLoading", false);
        if (response?.data?.message) {
            store.dispatch(
                "showResponse",
                {
                    type: "success",
                    message: response?.data?.message ?? "",
                    link: response?.data?.link ?? "",
                },
                {
                    root: true,
                }
            );
        }
        return response;
    },
    function (error) {
        store.commit("isLoading", false);
        if (
            (error.response.status === 403 || error.response.status === 419) &&
            error.config.url === "/login"
        ) {
            store.dispatch("auth/refreshToken", {
                refresh_token: localStorage.getItem("refresh_token"),
            });
        }
       
        
        if(error?.response?.data?.message=='Invalid token provided!' && error.response.status ==403 ){
            router.push({ name: "Login" });
        }else if(error?.response?.data?.message=='Invalid token provided!'){
            router.push({ name: "Login" });
        }else if(error.response.status ==419 ){
            router.push({ name: "Login" });
        }


        store.dispatch(
            "showResponse",
            {
                type: "error",
                message: error?.response?.data?.message ?? {},
                errors: error?.response?.data?.errors ?? {},
                error: error,
                link: error?.response?.data?.link ?? "",
            },
            {
                root: true,
            }
        );

        return Promise.reject(error);
    }
);

export default apiService;
