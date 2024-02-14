<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let form = ref([]);
const onRegister = async () => {
    let formData = new FormData();
    formData.append("name", form.value.name);
    formData.append("email", form.value.email);
    formData.append("password", form.value.password);
    await axios.post("/api/register/", formData);
    router.push("/invoice/list");
};
</script>
<template>
    <div class="container">
        <div class="logo">INVOICE MANAGEMENT</div>
        <div class="register__introduce">
            <h1>Sign Up</h1>
            <p>Please fill in this form to create an account.</p>
        </div>

        <label><b>Name</b></label>
        <input
            type="text"
            placeholder="Enter Name"
            class="register__input"
            v-model="form.name"
        />

        <label><b>Email</b></label>
        <input
            type="text"
            placeholder="Enter Email"
            class="register__input"
            v-model="form.email"
        />

        <label><b>Password</b></label>
        <input
            type="password"
            placeholder="Enter Password"
            class="register__input"
            v-model="form.password"
        />
        <div>
            <button type="button" class="register__btn" @click="onRegister">
                Register
            </button>
            <button type="submit" class="register__btn cancel">Cancel</button>
        </div>
    </div>
</template>
