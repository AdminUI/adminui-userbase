<template>
    <DataTable
        :value="orders.data"
        :rows="options.perPage"
        v-model:filters="options.filters"
        @page="updateOrders('page', $event)"
        @sort="updateOrders('sort', $event)"
        :lazy="true"
        :paginator="true"
        :totalRecords="options.total"
        :sortField="options.orderBy"
        :sortOrder="options.sortOrder"
        :first="options.first"
        class="orders-table mt-2"
        auto-layout
        :rowsPerPageOptions="[1, 10, 20, 50]"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
    >
        <Column field="date" header="Order Placed">
            <template #body="slotProps">
                {{ date(slotProps.data.created_at) }}
            </template>
        </Column>
        <Column field="items" header="Items">
            <template #body="{ data }">
                <div class="order-items-grid">
                    <div class="order-item" v-for="item in data.order_items">
                        {{ item.qty }}x {{ item.product_name }}
                    </div>
                </div>
            </template>
        </Column>
        <Column field="subtotal" header="Subtotal (inc VAT)">
            <template #body="{ data }">
                {{ currency(data.subtotal_inc_vat) }}
            </template>
        </Column>
        <Column field="postage" header="Postage">
            <template #body="{ data }">
                {{ currency(data.subtotal_exc_vat) }}
            </template>
        </Column>
        <Column field="order_status" header="Status">
            <template #body="{ data: { order_status: status } }">
                <Chip
                    v-if="status === 'pending'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-timer-outline"
                />
                <Chip
                    v-else-if="status === 'paid'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-credit-card-outline"
                />
                <Chip
                    v-else-if="status === 'processing'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-reload"
                />
                <Chip
                    v-else-if="status === 'dispatched'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-truck"
                />
                <Chip
                    v-else-if="status === 'refunded'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-credit-card-refund-outline"
                />
                <Chip
                    v-else-if="status === 'cancelled'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-close"
                />
                <Chip
                    v-else-if="status === 'completed'"
                    :label="status"
                    :class="'chip-' + status"
                    icon="mdi mdi-sm light mdi-check"
                />
            </template>
        </Column>
        <Column header="Actions" bodyStyle="text-align: center">
            <template #body="{ data }">
                <vue-button
                    tag="a"
                    size="small"
                    :href="`/order/invoice/${data.uuid}`"
                    icon
                    target="_blank"
                    background="var(--primary)"
                >
                    <svg-icon type="mdi" :path="mdiPrinter"></svg-icon>
                </vue-button>
            </template>
        </Column>
    </DataTable>
</template>

<script setup>
import { ref, reactive } from "vue";
import { api } from "@/js/plugins/axios";
import { mdiPrinter } from "@mdi/js";
import { currency } from "@dsmdesign/vue3-helpers";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Chip from "primevue/chip";

const centre = {
    textAlign: "center",
};

const updateOrders = (clicked = "", ev = {}) => {
    let sortOrder;
    if (clicked === "sort" && options.orderBy === ev.sortField) {
        sortOrder = options.sortOrder === 1 ? -1 : 1;
    } else sortOrder = ev.sortOrder;

    api.get("/user/api/orders", {
        params: {
            pagination: ev.rows || options.perPage,
            order_by: ev.sortField,
            order: sortOrder === 1 ? "asc" : "desc",
            page: ev.page || ev.page === 0 ? ev.page + 1 : options.page,
        },
    }).then((res) => {
        options.perPage = parseInt(res.data.meta.per_page, 10);
        localStorage.setItem("order-history-per-page", options.perPage);
        options.page = res.data.meta.current_page;
        options.first = res.data.meta.from - 1;
        options.total = res.data.meta.total;
        orders.value = res.data;
    });
};

const options = reactive({
    perPage: localStorage.getItem("order-history-per-page") || 10,
    page: 0,
    first: 0,
    filters: [],
    total: 0,
    orderBy: "",
    sortOrder: "asc",
});

const orders = ref([]);

const date = (d) =>
    new Date(d).toLocaleDateString(undefined, {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });

updateOrders();
</script>

<style lang="scss">
.orders-table {
    td,
    th {
        font-size: 1rem;
    }
}

[class*="chip-"] {
    color: #fefefe;
}

.chip-pending {
    background-color: #3b82f6;
}

.chip-paid {
    background-color: #10b981;
}

.chip-processing {
    background-color: #1d4ed8;
}

.chip-dispatched {
    background-color: #059669;
}

.chip-completed {
    background-color: #065f46;
}

.chip-cancelled {
    background-color: #ef4444;
}

.chip-refunded {
    background-color: #991b1b;
}
</style>
