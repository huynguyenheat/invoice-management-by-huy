<script setup>
import Header from "./Header.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

let router = useRouter();
let invoiceList = ref([]);
let searchKeyword = ref([]);

onMounted(async () => {
    getInvoiceList();
    window.addEventListener("keydown", handleKeyDown);
});

const handleKeyDown = (e) => {
    if (e.key === "Escape") {
        togglePopupMenu();
    }
};

const getInvoiceList = async () => {
    let response = await axios.get("/api/invoice/");
    invoiceList.value = response.data;
};
const onSearch = async () => {
    let response = await axios.get("/api/invoice/search/", {
        params: {
            keyword: searchKeyword.value,
        },
    });
    invoiceList.value = response.data;
};
const createInvoice = () => {
    router.push("/invoice/addnew");
};
const togglePopupMenu = (index) => {
    invoiceList.value.forEach((invoice, i) => {
        if (i === index) {
            invoice.isShowMenu = !invoice.isShowMenu;
        } else {
            invoice.isShowMenu = false;
        }
    });
};
const showInvoiceDetail = (id) => {
    router.push("/invoice/show/" + id);
};
const deleteInvoice = async (id) => {
    await axios.get("/api/invoice/delete/" + id);
    await getInvoiceList();
};
</script>
<template>
    <Header />
    <div class="container">
        <!-- INVOICE HEADER -->
        <div class="InvoiceHeaderList">
            <div>Invoice List</div>
            <input
                class="btn addnew"
                type="button"
                value="Add New Invoice"
                @click="createInvoice"
            />
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
                v-for="(invoice, index) in invoiceList"
                :key="index"
            >
                <div class="btn__invoice">
                    <input
                        type="button"
                        :value="invoice.id"
                        @click="togglePopupMenu(index)"
                        class="btn__invoice--id"
                    />
                    <div
                        v-if="invoice.isShowMenu"
                        class="overlay"
                        @click.self="togglePopupMenu(index)"
                    ></div>
                    <ul
                        class="popup__invoice--id"
                        v-if="invoice.isShowMenu"
                        @keydown.esc="togglePopupMenu(index)"
                    >
                        <li>
                            <a href="#" @click="showInvoiceDetail(invoice.id)"
                                >View</a
                            >
                        </li>
                        <li>
                            <router-link :to="'/invoice/edit/' + invoice.id"
                                >Edit</router-link
                            >
                        </li>
                        <li>
                            <a href="#" @click="deleteInvoice(invoice.id)"
                                >Delete</a
                            >
                        </li>
                    </ul>
                </div>
                <p>{{ invoice.number }}</p>
                <p>{{ invoice.customer.first_name }}</p>
                <p>{{ invoice.date }}</p>
                <p>{{ invoice.due_date }}</p>
                <p>{{ invoice.total }}</p>
            </div>
        </div>
    </div>
</template>
