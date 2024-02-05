import { createRouter, createWebHistory } from "vue-router";

import InvoiceIndex from "../components/invoice/InvoiceIndex.vue";
import InvoiceAddNew from "../components/invoice/InvoiceAddNew.vue";
import NotFoundPage from "../components/NotFoundPage.vue";

const routes = [
    {
        path: "/",
        component: InvoiceIndex,
    },
    {
        path: "/invoice/addnew/",
        component: InvoiceAddNew,
    },
    {
        path: "/:pathMatch(.*)*",
        component: NotFoundPage,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
