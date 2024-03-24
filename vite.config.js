import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "tailwindcss";
import i18n from "laravel-vue-i18n/vite";

export default defineConfig({
    base: process.env.APP_ENV === "production" ? 'https://admin.docshero.de/' : 'http://127.0.0.1:8000/',
    plugins: [
        vue(),
        i18n(),
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css","resources/js/assets/scss/style.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        https: process.env.APP_ENV === "production",
    }
});
