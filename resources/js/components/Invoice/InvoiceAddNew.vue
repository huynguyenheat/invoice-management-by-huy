<script setup>
import Header from "./Header.vue";
</script>
<template>
    <Header />
    <div class="container">
        <!-- INVOICE HEADER -->
        <div class="InvoiceHeader">
            <div>Customer Name</div>
            <div>
                <div>Date</div>
                <div>DueDate</div>
            </div>
            <div>
                <div>Number</div>
                <div>Reference</div>
            </div>
        </div>
        <!-- INVOICE ITEM -->
        <div>
            <div>Add New Product</div>
            <div>
                <div>Product ID</div>
                <div>Item Code</div>
                <div>Unit Price</div>
                <div>Quantity</div>
                <div>Amount</div>
            </div>
        </div>
        <!-- INVOICE SUMMARIZE -->
        <div>
            <div>Terms</div>
            <div>
                <div>SubTotal</div>
                <div>Discount</div>
                <div>Total</div>
            </div>
        </div>
    </div>
</template>
