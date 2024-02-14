<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let form = ref([]);
const onLogin = async () => {
    await axios.get("/sanctum/csrf-cookie");

    let formData = new FormData();
    formData.append("email", form.email);
    formData.append("password", form.password);
    await axios.post("/api/login/", formData);
};

// .then((response) => {
//     return response(
//         {
//             message: "Ok Credentials",
//         },
//         201
//     );
// })
// .catch((error) => {
//     if (error.response.status === 401) {
//         console.log("Error: opps Unauthorized");
//         alert("You inut wrong login info");
//     }
//     // });
//router.push("/invoice/list");
</script>
<template>
    <div class="container">
        <div class="logo">INVOICE MANAGEMENT</div>
        <label><b>Email</b></label>
        <input
            type="text"
            placeholder="Enter Email"
            class="login__input"
            v-model="form.email"
        />
        <label><b>Password</b></label>
        <input
            type="password"
            placeholder="Enter Password"
            class="login__input"
            v-model="form.password"
        />

        <input
            type="button"
            class="login__btn"
            @click="onLogin"
            value="Login"
        />
    </div>
</template>
