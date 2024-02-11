<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let invoice = ref([]);
const props = defineProps({
    id: {
        type: String,
        default: "",
    },
});

onMounted(async () => {
    getInvoiceById();
});

const getInvoiceById = async () => {
    let response = await axios.get(`/api/invoice/detail/${props.id}`);
    invoice.value = response.data.invoice;
    // console.log(invoice.value);
};
const exportPDF = () => {
    window.print();
    router.push("/");
};
</script>
<template>
    <div class="container__detail">
        <div class="header__detail--main">
            <div class="header__detail--title">INVOICE DETAIL</div>
            <div>
                <span>#ID: </span>
                <span>{{ invoice.id }}</span>
            </div>
            <input
                type="button"
                value="Export PDF"
                class="btn__pdf"
                @click="exportPDF()"
            />
        </div>
        <div>
            <div class="customer__detail">
                <div class="customer__numdate--row">
                    <label>Customer Name: </label>
                    <p v-if="invoice.customer">
                        {{ invoice.customer.first_name }}
                    </p>
                </div>
                <div>
                    <div class="customer__numdate--row">
                        <label>Number: </label>
                        <p>{{ invoice.number }}</p>
                    </div>
                    <div class="customer__numdate--row">
                        <label>Date: </label>
                        <p>{{ invoice.date }}</p>
                    </div>
                    <div class="customer__numdate--row">
                        <label>Due-Date: </label>
                        <p>{{ invoice.due_date }}</p>
                    </div>
                    <div class="customer__numdate--row">
                        <label>Reference: </label>
                        <p>{{ invoice.reference }}</p>
                    </div>
                </div>
            </div>
            <table class="invoice--item__table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Item Description</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in invoice.invoice_items"
                        :key="index"
                    >
                        <td>{{ item.id }}</td>
                        <td>{{ item.product.description }}</td>
                        <td>{{ item.product.unit_price }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.quantity * item.product.unit_price }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="subtotal">
                <p>Thanks you for your Business</p>
                <div>
                    <div class="subtotal__item">
                        <p>SubTotal:</p>
                        <p>{{ invoice.sub_total }}</p>
                    </div>
                    <div class="subtotal__item">
                        <p>Discount:</p>
                        <p>{{ invoice.discount }}</p>
                    </div>
                </div>
            </div>
            <div class="total">
                <div class="total__term">
                    <p>Terms:</p>
                    <p>{{ invoice.terms }}</p>
                </div>
                <div class="total__item">
                    <p>Total:</p>
                    <p>{{ invoice.total }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
