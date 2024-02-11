import { createRouter, createWebHistory } from "vue-router";

import InvoiceIndex from "../components/invoice/InvoiceIndex.vue";
import InvoiceDetail from "../components/invoice/InvoiceDetail.vue";
import InvoiceAddNew from "../components/invoice/InvoiceAddNew.vue";
import InvoiceEdit from "../components/invoice/InvoiceEdit.vue";
import NotFoundPage from "../components/NotFoundPage.vue";

const routes = [
    {
        path: "/",
        component: InvoiceIndex,
    },
    {
        path: "/invoice/show/:id",
        component: InvoiceDetail,
        props: true,
    },
    {
        path: "/invoice/addnew/",
        component: InvoiceAddNew,
    },
    {
        path: "/invoice/edit/:id",
        component: InvoiceEdit,
        props: true,
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
