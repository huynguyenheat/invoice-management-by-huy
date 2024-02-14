import "./bootstrap";
import { createApp } from "vue";
import App from "./components/App.vue";
import router from "./router/index.js";
import axios from "axios";

// Lấy token CSRF từ thẻ meta trong mã HTML
const csrfToken = document.head.querySelector(
    'meta[name="csrf-token"]'
).content;

// Thiết lập một interceptor trước cho Axios để thêm token CSRF vào mỗi yêu cầu
axios.interceptors.request.use((config) => {
    config.headers["X-CSRF-TOKEN"] = csrfToken;
    return config;
});
createApp(App).use(router).mount("#app");
