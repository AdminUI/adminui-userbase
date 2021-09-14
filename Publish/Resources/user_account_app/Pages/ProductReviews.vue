<template>
    <DataTable
        autoLayout
        class="p-datatable-sm mt-4"
        :value="reviews.data"
        :total-records="reviews.meta.total || 0"
        lazy
        :loading="loading"
        striped-rows
        data-key="id"
        editMode="cell"
        v-model:selection="selectedReviews"
        :paginator="true"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        :rows="options.perPage"
        :rowsPerPageOptions="[10, 20, 50]"
        @page="onPage($event)"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
    >
        <Column selectionMode="multiple"></Column>
        <Column field="product.media">
            <template #body="{ data }">
                <figure class="dsm__product-review--image">
                    <img :src="data.product.media[0].url.thumbnail" />
                </figure>
            </template>
        </Column>
        <Column field="product.name" header="Product Name">
            <template #body="{ data }">
                <h5>{{ data.product.name }}</h5>
            </template>
        </Column>
        <Column field="rating" header="Rating">
            <template #body="{ data }">
                <vue-input-rating
                    size="24px"
                    gap="0.5em"
                    :modelValue="data.rate"
                    @update:modelValue="debouncedUpdateReview(data, 'rating', $event)"
                ></vue-input-rating>
            </template>
        </Column>
        <Column field="comment" header="Comment">
            <template #body="{ data }">{{ data.comment }}</template>
            <template #editor="{ data }">
                <InputText
                    :style="{ width: '100%' }"
                    :modelValue="data.comment"
                    @update:modelValue="debouncedUpdateReview(data, 'comment', $event)"
                />
            </template>
        </Column>
        <Column field="actions" headerStyle="width: 50px">
            <template #body="{ data }">
                <vue-button
                    background="#ff0000"
                    size="x-small"
                    icon
                    @click="deleteReview(data)"
                    class="mr-2"
                >
                    <svg-icon type="mdi" :path="mdiDelete" />
                </vue-button>
            </template>
        </Column>
        <template #empty>
            <div class="empty-data-table py-4">You don't have any product reviews</div>
        </template>
        <template #paginatorLeft>
            <div class="d-flex align-items-center">
                <transition name="fade">
                    <vue-button
                        v-if="selectedReviews.length > 0"
                        tile
                        background="red"
                        @click="() => deleteReviews()"
                    >Delete {{ selectedReviews.length }} Review{{ selectedReviews.length !== 1 ? 's' : '' }}</vue-button>
                </transition>
            </div>
        </template>
    </DataTable>
    <Modal v-model="showConfirmDelete">
        <template #title>
            <h5 class="mr-3">Confirm Delete</h5>
        </template>
        <p>
            Are you sure you want to delete
            <span v-if="confirmDeletePlural">these reviews</span>
            <span v-else>this review</span>?
        </p>
        <p>This cannot be undone.</p>
        <template #actions>
            <div class="d-flex justify-space-between">
                <vue-button
                    v-if="confirmDeletePlural"
                    uppercase
                    tile
                    type="submit"
                    background="var(--primary)"
                    @click="doDeleteReviews"
                >Delete {{ selectedReviews.length }} Review{{ selectedReviews.value !== 1 ? 's' : '' }}</vue-button>
                <vue-button
                    v-else
                    uppercase
                    tile
                    type="submit"
                    background="var(--primary)"
                    @click="doDeleteReview"
                >Delete Review</vue-button>
            </div>
        </template>
    </Modal>
</template>

<script setup>
/* *********************************************************
 * MODULE IMPORTS
********************************************************* */
import { inject, ref, reactive } from "vue";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import Modal from "@dsmdesign/vue3-modal";
import { api } from "@/js/plugins/axios";
import DataTable from "primevue/datatable/sfc";
import Column from "primevue/column/sfc";
import InputText from "primevue/inputtext/sfc";
import debounce from "lodash.debounce";
import { mdiPencil, mdiDelete } from "@mdi/js";

/* *********************************************************
 * CONSTANTS
********************************************************* */
const snackbar = useSnackbar();
const routes = inject("routes");
const reviews = ref({
    data: [],
    links: {},
    meta: {}
});

/* *********************************************************
 * TABLE REFERENCES
********************************************************* */
const currentPage = ref(1);
const options = reactive({
    perPage: 10
})
const selectedReviews = ref([]);

/* *********************************************************
 * TABLE HYDRATION
********************************************************* */
const loadUserReviews = ({ page = currentPage.value, rows = options.perPage } = {}) => {
    return api.get(routes.reviews, {
        params: {
            page,
            per_page: rows
        }
    })
        .then(res => {
            options.perPage = parseInt(res.data.meta.per_page, 10);
            reviews.value = res.data;
        })
}

/* *********************************************************
 * TABLE FUNCTIONS
********************************************************* */
const onPage = (event) => {
    currentPage.value = event.page + 1;
    loadUserReviews({ page: currentPage.value, rows: event.rows })
}

/* *********************************************************
 * INITIAL PAGE LOAD
********************************************************* */
loadUserReviews();

/* *********************************************************
 * UPDATE REVIEW
********************************************************* */
const updateReview = (review, field, value) => {
    const data = {
        product_id: review.id
    }
    data[field] = value;
    api.patch(routes.review, {
        ...data
    })
        .then(res => {
            const r = reviews.value.data.find(rv => rv.id === review.id);
            r[field] = value;
        })
}

const debouncedUpdateReview = debounce(updateReview, 500);

/* *********************************************************
 * DELETE SINGLE REVIEW
********************************************************* */
const confirmDeleteId = ref();
const showConfirmDelete = ref(false);
const confirmDeletePlural = ref(false);

const deleteReview = (review) => {
    confirmDeletePlural.value = false;
    showConfirmDelete.value = true;
    confirmDeleteId.value = review.id;
}

const doDeleteReview = () => {
    doDeleteReviews([confirmDeleteId.value]);
    confirmDeleteId.value = null;
}

/* *********************************************************
 * DELETE MULTIPLE REVIEWS
********************************************************* */

const deleteReviews = () => {
    confirmDeletePlural.value = true;
    showConfirmDelete.value = true;
}

const doDeleteReviews = (ids) => {
    return api.delete(routes.reviews, {
        data: {
            ids: ids || selectedReviews.value.map(rev => rev.id)
        }
    })
        .then(res => {
            res.data.results.forEach(result => {
                if (result.status !== 'success') {
                    //
                }
            })
            return loadUserReviews()
        })
        .then(res => {
            selectedReviews.value = [];
            showConfirmDelete.value = false;
            const msg = confirmDeletePlural.value === true ? "Reviews have been deleted" : "Review was deleted";
            snackbar.add({
                type: 'success',
                text: msg
            })
        })
}
</script>

<style lang="scss">
.dsm__product-review--image {
    max-height: 100px;
    max-width: 100px;

    img {
        height: 100%;
        width: auto;
    }
}
</style>