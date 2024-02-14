<script setup>
import Header from "./Header.vue";
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let customers = ref([]);
let lastInvoice = ref([]);
let form = ref([]);
let showModal = ref(false);
let products = ref([]);
let itemCart = ref([]);

let newInvoiceNumber = computed(() => {
    const id = parseInt(lastInvoice.value.id);
    if (!isNaN(id)) {
        return "INV-" + (id + 1);
    } else {
        return "INV-??";
    }
});

onMounted(() => {
    getListCustomer();
    getLastInvoice();
    getListProduct();
});

const getListCustomer = async () => {
    let response = await axios.get("/api/customer/list/");
    customers.value = response.data;
};
const getLastInvoice = async () => {
    let response = await axios.get("/api/invoice/lastinvoice/");
    lastInvoice.value = response.data;
};
const showModalProductSelect = () => {
    showModal.value = !showModal.value;
};
const closeModalProductSelect = () => {
    showModal.value = !showModal.value;
};
const getListProduct = async () => {
    let response = await axios.get("/api/product/list/");
    products.value = response.data;
};
const addItemCart = (product) => {
    itemCart.value.push(product);
};
const deleteItem = (i) => {
    itemCart.value.splice(i, 1);
};
const subTotal = () => {
    let subtotal = 0;
    itemCart.value.map((data) => {
        subtotal = data.unit_price * data.quantity + subtotal;
    });
    return subtotal;
};
const total = () => {
    return subTotal() - form.value.discount;
};
const onSaveInvoice = async () => {
    await axios.get("/sanctum/csrf-cookie");
    let subtotal = 0;
    subtotal = subTotal();
    let totals = 0;
    totals = total();
    const formData = new FormData();
    formData.append("customer_id", form.value.customer_id);
    formData.append("date", form.value.date);
    formData.append("due_date", form.value.due_date);
    formData.append("number", newInvoiceNumber.value);
    formData.append("reference", form.value.reference);
    formData.append("terms", form.value.terms);
    formData.append("sub_total", subtotal);
    formData.append("discount", form.value.discount);
    formData.append("total", totals);
    formData.append("invoice_item", JSON.stringify(itemCart.value));
    await axios.post("/api/invoice/addnew/post/", formData);
    // .catch((error) => {
    //     if (error.response.status === 401) {
    //         console.log("Error: opps Unauthorized");
    //         alert("You dont have add new permission");
    //     }
    // });
    router.push("/invoice/list");
};
</script>
<template>
    <Header />
    <div class="new--invoice">
        <!-- INVOICE HEADER -->
        <div class="invoice--header">
            <div>
                <p>Customer Name</p>
                <select v-model="form.customer_id">
                    <option disabled selected>Select customer</option>
                    <option
                        v-for="(customer, i) in customers"
                        :key="i"
                        :value="customer.id"
                    >
                        {{ customer.first_name }}
                    </option>
                </select>
            </div>
            <div>
                <p>Date</p>
                <input type="date" class="input--header" v-model="form.date" />
                <p>DueDate</p>
                <input type="date" v-model="form.due_date" />
            </div>
            <div>
                <p>Number</p>
                <input
                    type="text"
                    class="input--header"
                    v-model="newInvoiceNumber"
                />
                <p>Reference</p>
                <input type="text" v-model="form.reference" />
            </div>
        </div>
        <!-- INVOICE ITEM -->
        <div class="invoice--item">
            <input
                class="btn__add--item"
                type="button"
                value="Add New Product"
                @click="showModalProductSelect"
            />
            <div class="table--header">
                <p>Product ID</p>
                <p>Item Code</p>
                <p>Unit Price</p>
                <p>Quantity</p>
                <p>Amount</p>
            </div>
            <div class="message--empty" v-if="itemCart.length === 0">
                Product List is empty now. Please click "Add New Product" to add
                product into invoice.
            </div>
            <div
                class="table--item"
                v-for="(item, i) in itemCart"
                :key="item.id"
            >
                <p>{{ item.id }}</p>
                <p>{{ item.item_code }}</p>
                <p>{{ item.unit_price }}</p>
                <div><input type="text" v-model="item.quantity" /></div>
                <p v-if="item.quantity">
                    {{ item.unit_price * item.quantity }}
                </p>
                <p v-else>0</p>
                <input
                    type="button"
                    class="btn__delete--item"
                    @click="deleteItem(i)"
                    value="x"
                />
            </div>
        </div>

        <!-- INVOICE SUMMARIZE -->
        <div class="invoice--summary">
            <div>
                <p>Terms</p>
                <textarea
                    class="terms--area"
                    placeholder="Input terms"
                    v-model="form.terms"
                />
            </div>
            <div class="total--area">
                <div class="total--field">
                    <span>SubTotal</span>
                    <p>{{ subTotal() }}</p>
                </div>
                <div class="total--field">
                    <span>Discount</span>
                    <input type="text" v-model="form.discount" />
                </div>
                <div class="total--field">
                    <span>Total</span>
                    <p class="total--field--summary">{{ total() }}</p>
                </div>
                <input
                    class="btn__add--invoice"
                    type="button"
                    value="Add New Invoice"
                    @click="onSaveInvoice"
                />
            </div>
        </div>

        <!-- MODAL ADD PRODUCT -->
        <div
            class="modal"
            :class="{ showModal: showModal }"
            @click.self="closeModalProductSelect"
        >
            <div class="modal--content">
                <div class="modal--header">
                    <div>List Product</div>
                    <div
                        class="btn__close--modal"
                        @click="closeModalProductSelect"
                    >
                        x
                    </div>
                </div>
                <div class="modal--product--list">
                    <div
                        class="product--item"
                        v-for="product in products"
                        :key="product.id"
                    >
                        <div>{{ product.id }} {{ product.item_code }}</div>
                        <div class="add--product" @click="addItemCart(product)">
                            +
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
