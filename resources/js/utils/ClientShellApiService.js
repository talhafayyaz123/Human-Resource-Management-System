import axios from "axios";
import store from "../store";

let docApiService = axios.create({
  baseURL: import.meta.env.VITE_CLIENT_SHELL_SERVICE_URL,
  headers: {
    "X-Requested-With": "XMLHttpRequest",
  },
});

docApiService.interceptors.request.use(function (config) {
  const token = localStorage.getItem("token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});
docApiService.interceptors.response.use(
  function (response) {
    store.commit("isLoading", false);
    if (
      response?.status == 200 &&
      (response?.config?.url === "/login" ||
        response?.config?.url === "/refresh-token")
    ) {
      //localStorage.setItem("token", response?.data?.token);
      //localStorage.setItem("refresh_token", response?.data?.refresh_token);
     
    
    
    }
    return response;
  },
  async function (error) {
    store.commit("isLoading", false);
    if (
      (error.response.status === 401 || error.response.status === 419) &&
      error.config.url === "/login"
    ) {
      store.dispatch("auth/refreshToken", {
        refresh_token: localStorage.getItem("refresh_token"),
      });
    }
    let jsonData = await error.response?.data?.text();
    let parsedResponse = {};
    if (jsonData) {
      parsedResponse = JSON.parse(jsonData) ?? {};
    }
    store.dispatch(
      "showResponse",
      {
        type: "error",
        message: parsedResponse?.message ?? {},
        errors: parsedResponse?.errors ?? {},
      },
      {
        root: true,
      }
    );

    return Promise.reject(error);
  }
);

export default docApiService;
