<script setup>
import Header from "./Header.vue";
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let customers = ref([]);
let existInvoice = ref([]);
let showModal = ref(false);
let products = ref([]);

const props = defineProps({
    id: {
        type: String,
        default: "",
    },
});

onMounted(async () => {
    getListCustomer();
    getInvoice();
    getListProduct();
});

const getListCustomer = async () => {
    let response = await axios.get("/api/customer/list/");
    customers.value = response.data;
};
const getInvoice = async () => {
    let response = await axios.get(`/api/invoice/getexist/${props.id}`);
    existInvoice.value = response.data.invoice;
    // itemCart.value = response.data.invoice.invoice_items;
    console.log(existInvoice.value);
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
    let itemAdd = {
        invoice_id: existInvoice.id,
        product_id: product.id,
        product: {
            item_code: product.item_code,
            unit_price: product.unit_price,
        },
        quantity: "",
    };

    existInvoice.value.invoice_items.push(itemAdd);
};
const deleteItem = (id, i) => {
    existInvoice.value.invoice_items.splice(i, 1);
    if (id != undefined) {
        axios.get("/api/invoiceitem/delete/" + id);
    }
};
const subTotal = () => {
    let subtotal = 0;
    if (existInvoice.value.invoice_items) {
        existInvoice.value.invoice_items.map((data) => {
            if (data.product) {
                subtotal = data.product.unit_price * data.quantity + subtotal;
            }
        });
    }
    return subtotal;
};
const total = () => {
    return subTotal() - existInvoice.value.discount;
};
const onEditInvoice = () => {
    //alert(JSON.stringify(existInvoice.value.invoice_items));
    let subtotal = 0;
    subtotal = subTotal();
    let totals = 0;
    totals = total();
    const formData = new FormData();
    formData.append("customer_id", existInvoice.value.customer_id);
    formData.append("date", existInvoice.value.date);
    formData.append("due_date", existInvoice.value.due_date);
    formData.append("number", existInvoice.value.number);
    formData.append("reference", existInvoice.value.reference);
    formData.append("terms", existInvoice.value.terms);
    formData.append("sub_total", subtotal);
    formData.append("discount", existInvoice.value.discount);
    formData.append("total", totals);
    formData.append(
        "invoice_item",
        JSON.stringify(existInvoice.value.invoice_items)
    );
    axios.post(`/api/invoice/edit/${props.id}`, formData);
    router.push("/");
};
</script>
<template>
    <Header />
    <div class="new--invoice">
        <!-- INVOICE HEADER -->
        <div class="invoice--header">
            <div>
                <p>Customer Name</p>
                <select v-model="existInvoice.customer_id">
                    <option
                        selected
                        v-if="existInvoice.customer"
                        :value="existInvoice.customer_id"
                    >
                        {{ existInvoice.customer.first_name }}
                    </option>
                    <option
                        :value="customer.id"
                        v-for="customer in customers"
                        :key="customer.id"
                    >
                        {{ customer.first_name }}
                    </option>
                </select>
            </div>
            <div>
                <p>Date</p>
                <input
                    type="date"
                    class="input--header"
                    v-model="existInvoice.date"
                />
                <p>DueDate</p>
                <input type="date" v-model="existInvoice.due_date" />
            </div>
            <div>
                <p>Number</p>
                <input
                    type="text"
                    class="input--header"
                    v-model="existInvoice.number"
                />
                <p>Reference</p>
                <input type="text" v-model="existInvoice.reference" />
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
            <!-- <div
                class="message--empty"
                v-if="existInvoice.invoice_items.length == 0"
            >
                Product List is empty now. Please click "Add New Product" to add
                product into invoice.
            </div> -->
            <div
                class="table--item"
                v-for="(item, i) in existInvoice.invoice_items"
                :key="item.id"
            >
                <p>{{ item.product_id }}</p>
                <p v-if="item.product">{{ item.product.item_code }}</p>
                <p v-if="item.product">{{ item.product.unit_price }}</p>
                <div>
                    <input
                        type="text"
                        v-model="item.quantity"
                        placeholder="0"
                    />
                </div>
                <p v-if="item.quantity">
                    {{ item.product.unit_price * item.quantity }}
                </p>
                <p v-else>0</p>
                <input
                    type="button"
                    class="btn__delete--item"
                    @click="deleteItem(item.id, i)"
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
                    v-model="existInvoice.terms"
                />
            </div>
            <div class="total--area">
                <div class="total--field">
                    <span>SubTotal</span>
                    <p>{{ subTotal() }}</p>
                </div>
                <div class="total--field">
                    <span>Discount</span>
                    <input type="text" v-model="existInvoice.discount" />
                </div>
                <div class="total--field">
                    <span>Total</span>
                    <p class="total--field--summary">{{ total() }}</p>
                </div>
                <input
                    class="btn__add--invoice"
                    type="button"
                    value="Update Invoice"
                    @click="onEditInvoice"
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
