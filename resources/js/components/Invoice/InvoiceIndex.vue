<script setup>
import Header from "./Header.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

let invoiceList = ref([]);
let searchKeyword = ref([]);

onMounted(async () => {
    getInvoiceList();
});

const getInvoiceList = async () => {
    let response = await axios.get("/api/invoice/");
    invoiceList.value = response.data;
    //console.log(response);
};
const onSearch = async () => {
    let response = await axios.get("/api/invoice/search/", {
        params: {
            keyword: searchKeyword.value,
        },
    });
    invoiceList.value = response.data;
};
</script>
<template>
    <Header />
    <div class="container">
        <!-- INVOICE HEADER -->
        <div class="InvoiceHeaderList">
            <div>Invoice List</div>
            <input class="btn addnew" type="button" value="Add New Invoice" />
        </div>
        <!-- INVOICE SEARCH  -->
        <div class="searchArea">
            <input
                class="btn"
                type="button"
                value="Search"
                @click="onSearch()"
            />
            <input
                class="searchField"
                type="text"
                placeholder="Input to search"
                v-model="searchKeyword"
            />
        </div>
        <!-- INVOICE LIST -->
        <div>
            <div class="listHeader">
                <p>Invoice ID</p>
                <p>Invoice Number</p>
                <p>Customer Name</p>
                <p>Date</p>
                <p>Duedate</p>
                <p>Total</p>
            </div>
            <div
                class="listItem"
                v-for="invoice in invoiceList"
                :key="invoice.id"
            >
                <a href="#">{{ invoice.id }}</a>
                <p>{{ invoice.number }}</p>
                <p>{{ invoice.customer.first_name }}</p>
                <p>{{ invoice.date }}</p>
                <p>{{ invoice.due_date }}</p>
                <p>{{ invoice.total }}</p>
            </div>
        </div>
    </div>
</template>
