<template>
    <DataTable
        class="p-datatable-sm"
        :value="notifications.data"
        :total-records="notifications.total || 0"
        lazy
        :loading="loading"
        striped-rows
        data-key="id"
        v-model:selection="selectedNotifications"
        v-model:expandedRows="expandedNotifications"
        :paginator="true"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        :rows="options.perPage"
        :rowsPerPageOptions="[10, 20, 50]"
        @row-expand="readNotificationSingle"
        @page="onPage($event)"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
    >
        <template #header>
            <div class="d-flex align-items-centre justify-content-end">
                <Checkbox
                    v-model="showReadNotifications"
                    :size="16"
                    :tick-colour="colours.primary"
                    name="show_read"
                    label="Show Read Notifications"
                    @update:modelValue="loadNotifications"
                ></Checkbox>
            </div>
        </template>
        <Column selectionMode="multiple" headerStyle="width: 3em"></Column>
        <Column style="width: 3em;">
            <template #body="{ data }">
                <div style="margin-bottom: -4px">
                    <svg-icon
                        v-if="data.data.icon === 'success'"
                        type="mdi"
                        size="small"
                        :path="mdiCheckUnderline"
                        class="user-c-success"
                    ></svg-icon>
                    <svg-icon
                        v-else-if="data.data.icon === 'error'"
                        type="mdi"
                        size="small"
                        :path="mdiAlertCircleOutline"
                        class="user-c-error"
                    ></svg-icon>
                    <svg-icon
                        v-else-if="data.data.icon === 'warning'"
                        type="mdi"
                        size="small"
                        :path="mdiAlert"
                        class="user-c-warning"
                    ></svg-icon>
                    <svg-icon
                        v-else
                        type="mdi"
                        size="small"
                        :path="mdiInformationOutline"
                        class="user-c-info"
                    ></svg-icon>
                </div>
            </template>
        </Column>
        <Column field="data.title" header="Subject">
            <template #body="slotProps">
                <span
                    :class="{ 'font-weight-bold': !slotProps.data.read_at }"
                >{{ slotProps.data.data.title }}</span>
            </template>
        </Column>
        <Column style="width: 3em;" :exportable="false">
            <template #body="slotProps">
                <vue-button
                    icon
                    text
                    size="x-small"
                    @click="deleteNotificationSingle(slotProps.data.id)"
                    v-tooltip="'Delete Notification'"
                >
                    <i class="mdi mdi-sm mdi-delete filter-max-red"></i>
                </vue-button>
            </template>
        </Column>
        <Column :expander="true" header="Read" headerStyle="width: 4rem" />
        <template #empty>
            <div class="empty-data-table">You don't have any notifications</div>
        </template>
        <template #expansion="slotProps">
            <div class="notification-content">{{ slotProps.data.data.content }}</div>
        </template>
        <template #paginatorLeft>
            <div class="d-flex align-items-center">
                <Dropdown
                    v-model="batchAction"
                    :options="batchOptions"
                    scroll-height="400px"
                    placeholder="Select Action"
                    class="ml-0"
                    style="width:200px"
                />
                <vue-button
                    v-if="batchAction === 'Delete Notifications' && selectedNotifications.length > 0"
                    tile
                    background="red"
                    @click="() => deleteNotifications()"
                >Delete {{ selectedNotifications.length }} Notification{{ selectedNotifications.length !== 1 ? 's' : '' }}</vue-button>
                <vue-button
                    v-else-if="batchAction === 'Mark as Read' && selectedNotifications.length > 0"
                    tile
                    background="steelblue"
                    @click="() => readNotifications()"
                >Mark {{ selectedNotifications.length }} Notification{{ selectedNotifications.length !== 1 ? 's' : '' }} as Read</vue-button>
            </div>
        </template>
    </DataTable>
</template>

<script setup>
import SvgIcon from "vue3-icon";
import { mdiCheckUnderline, mdiInformationOutline, mdiAlert, mdiAlertCircleOutline } from "@mdi/js";
import { ref, reactive, inject } from "vue";
import Checkbox from "@dsmdesign/vue3-input-checkbox";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { api } from "@/js/plugins/axios";
import DataTable from "primevue/datatable/sfc";
import Column from "primevue/column/sfc";
import Dropdown from "primevue/dropdown/sfc";

const colours = inject("colours");
const routes = inject("routes");
const snackbar = useSnackbar();

const showReadNotifications = ref(true);

const selectedNotifications = ref([]);
const expandedNotifications = ref([]);
const markedAsRead = ref([]);
const notifications = ref([]);
const loading = ref(true);
const options = reactive({
    perPage: 10
})
const currentPage = ref(1);

const batchAction = ref(null);
const batchOptions = [
    "Mark as Read",
    "Delete Notifications"
]

const loadNotifications = ({ page = currentPage.value, rows = options.perPage } = {}) => {
    return api.get(routes.notifications, {
        params: {
            page,
            pagination: rows,
            action: showReadNotifications.value === true ? 'default' : 'unread'
        }
    })
        .then(res => {
            res.data.data.forEach(n => {
                n.is_read = !!n.read_at;
            })
            notifications.value = res.data;
            options.perPage = parseInt(res.data.per_page, 10);
        })
        .finally(() => loading.value = false);
}

const readNotifications = (ids) => {
    if (!ids) ids = selectedNotifications.value.map(n => n.id);
    markedAsRead.value.push(...ids);
    return api.patch(routes.notifications, {
        ids
    })
        .then(res => {
            if (selectedNotifications.value.length > 1) {
                snackbar.add({
                    type: 'success',
                    text: `${ids.length} notification${ids.length !== 1 ? 's' : ''} marked as read`
                })
            }
            selectedNotifications.value = [];
            return loadNotifications();
        })
}

const readNotificationSingle = (event) => {
    if (!!event.read_at || markedAsRead.value.includes(event.data.id)) return false;
    readNotifications([event.data.id]);
}

const deleteNotifications = (ids) => {
    if (!ids) ids = selectedNotifications.value.map(n => n.id);
    api.delete(routes.notifications, {
        data: { ids }
    })
        .then(res => {
            selectedNotifications.value = [];
            snackbar.add({
                type: 'success',
                text: `Deleted ${ids.length} notification${ids.length !== 1 ? 's' : ''}`
            })
        })
        .then(loadNotifications)
}

const deleteNotificationSingle = async (id) => {
    try {
        await deleteNotifications([id]);
    }
    catch {
        snackbar.add({
            type: 'negative',
            text: `Unable to delete notification`
        })
    }
}

const onPage = (event) => {
    currentPage.value = event.page + 1;
    loadNotifications({ page: currentPage.value, rows: event.rows })
}

loadNotifications();
</script>

<style lang="scss">
.notification-content {
    padding: 1rem;
}
</style>