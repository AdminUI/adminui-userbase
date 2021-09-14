<template>
    <section class="container-fluid mt-4">
        <div class="row">
            <div class="col-12 col-lg-4">
                <Listbox
                    :items="addressOptions"
                    v-model="selectedAddress"
                    :highlight-colour="colours.primary"
                    :selected-colour="colours.primary"
                    :selected-opacity="0.9"
                    selected-text-colour="white"
                >
                    <template #header>
                        <h6 class="ma-2">Saved Addresses</h6>
                    </template>

                    <template #footer>
                        <div class="text-centre mt-4 mb-2">
                            <vue-button tile :background="colours.secondary" @click="createNewAddress" :dark="false"
                                >Create New Address</vue-button
                            >
                        </div>
                    </template>
                </Listbox>
            </div>
            <div class="col-12 col-lg-8">
                <div class="elevate-4 px-4 pt-2 pb-4">
                    <h5
                        class="text-centre mb-2 text-uppercase"
                        :style="{
                            color: colours.primary,
                        }"
                    >
                        {{ selectedAddress ? selectedAddressObject?.nickname : "Select An Address" }}
                    </h5>
                    <vue-slide-up-down responsive :modelValue="true">
                        <table v-if="selectedAddress" :key="selectedAddress">
                            <tbody>
                                <tr>
                                    <td>Company Name</td>
                                    <td>{{ selectedAddressObject?.company }}</td>
                                </tr>
                                <tr>
                                    <td>Address Line 1</td>
                                    <td>{{ selectedAddressObject?.address }}</td>
                                </tr>
                                <tr v-if="selectedAddressObject?.address_2">
                                    <td>Address Line 2</td>
                                    <td>{{ selectedAddressObject?.address_2 }}</td>
                                </tr>
                                <tr>
                                    <td>Town / City</td>
                                    <td>{{ selectedAddressObject?.town }}</td>
                                </tr>
                                <tr>
                                    <td>County</td>
                                    <td>{{ selectedAddressObject?.county }}</td>
                                </tr>
                                <tr>
                                    <td>Postcode</td>
                                    <td>{{ selectedAddressObject?.postcode }}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>United Kingdom</td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-slide-up-down>
                    <div class="d-flex justify-content-between mt-4 mb-2">
                        <div
                            v-tooltip.bottom="{
                                value: 'You cannot delete your only address',
                                disabled: !addresses || addresses.length > 1,
                            }"
                        >
                            <vue-button
                                text
                                :background="colours.primary"
                                @click="showConfirmDeleteModal = true"
                                :dark="false"
                                :disabled="!selectedAddress || addresses.length <= 1"
                                >Delete Address</vue-button
                            >
                        </div>
                        <vue-button
                            tile
                            :background="colours.secondary"
                            @click="editSelectedAddress"
                            :dark="false"
                            :disabled="!selectedAddress"
                            >Edit Address</vue-button
                        >
                    </div>
                </div>
            </div>
        </div>
        <teleport to="#vue-modals">
            <vue-modal v-model="showNewAddressModal" width="500px" title="Create New Address">
                <vue-form v-model:isValid="newAddress.isValid" @submit="doCreateAddress">
                    <vue-input-text
                        name="nickname"
                        class="mb-2"
                        v-model="newAddress.nickname"
                        floating
                        label="Nickname"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.nickname"
                    ></vue-input-text>
                    <vue-input-text
                        name="company"
                        class="mb-2"
                        v-model="newAddress.company"
                        floating
                        label="Company Name"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.company"
                    ></vue-input-text>
                    <vue-input-text
                        name="address"
                        class="mb-2"
                        v-model="newAddress.address"
                        floating
                        label="Address"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.address"
                    ></vue-input-text>
                    <vue-input-text
                        name="address_2"
                        class="mb-2"
                        v-model="newAddress.address_2"
                        floating
                        label="Address Line 2"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.address_2"
                    ></vue-input-text>
                    <vue-input-text
                        name="town"
                        class="mb-2"
                        v-model="newAddress.town"
                        floating
                        label="Town / City"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.town"
                    ></vue-input-text>
                    <vue-input-text
                        name="county"
                        class="mb-2"
                        v-model="newAddress.county"
                        floating
                        label="County"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.county"
                    ></vue-input-text>
                    <vue-input-text
                        name="postcode"
                        class="mb-2"
                        v-model="newAddress.postcode"
                        floating
                        label="Postcode"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.postcode"
                    ></vue-input-text>
                    <vue-input-text
                        name="phone"
                        class="mb-2"
                        v-model="newAddress.phone"
                        floating
                        label="Phone"
                        :active-colour="colours.primary"
                        :error="newAddress.errors.phone"
                    ></vue-input-text>
                    <div class="text-centre">
                        <vue-button tile :background="colours.primary" type="submit">Create Address</vue-button>
                    </div>
                </vue-form>
            </vue-modal>
            <vue-modal v-model="showEditAddressModal" width="500px" :title="'Edit ' + editAddress.nickname">
                <vue-form v-model:isValid="editAddress.isValid" @submit="doEditAddress">
                    <vue-input-text
                        name="nickname"
                        class="mb-2"
                        v-model="editAddress.nickname"
                        floating
                        label="Nickname"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.nickname"
                    ></vue-input-text>
                    <vue-input-text
                        name="company"
                        class="mb-2"
                        v-model="editAddress.company"
                        floating
                        label="Company Name"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.company"
                    ></vue-input-text>
                    <vue-input-text
                        name="address"
                        class="mb-2"
                        v-model="editAddress.address"
                        floating
                        label="Address"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.address"
                    ></vue-input-text>
                    <vue-input-text
                        name="address_2"
                        class="mb-2"
                        v-model="editAddress.address_2"
                        floating
                        label="Address Line 2"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.address_2"
                    ></vue-input-text>
                    <vue-input-text
                        name="town"
                        class="mb-2"
                        v-model="editAddress.town"
                        floating
                        label="Town / City"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.town"
                    ></vue-input-text>
                    <vue-input-text
                        name="county"
                        class="mb-2"
                        v-model="editAddress.county"
                        floating
                        label="County"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.county"
                    ></vue-input-text>
                    <vue-input-text
                        name="postcode"
                        class="mb-2"
                        v-model="editAddress.postcode"
                        floating
                        label="Postcode"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.postcode"
                    ></vue-input-text>
                    <vue-input-text
                        name="phone"
                        class="mb-2"
                        v-model="editAddress.phone"
                        floating
                        label="Phone"
                        :active-colour="colours.primary"
                        :error="editAddress.errors.phone"
                    ></vue-input-text>
                    <div class="text-centre">
                        <vue-button tile :background="colours.primary" type="submit">Edit Address</vue-button>
                    </div>
                </vue-form>
            </vue-modal>
            <vue-modal
                v-model="showConfirmDeleteModal"
                width="500px"
                :title="'Delete ' + selectedAddressObject?.nickname"
            >
                <p>Are you sure you want to delete this address?</p>
                <p>This action cannot be undone.</p>
                <template #actions>
                    <div class="d-flex justify-content-between w-100 mb-2">
                        <vue-button tile :background="colours.secondary" @click="showConfirmDeleteModal = false"
                            >Cancel</vue-button
                        >
                        <vue-button tile :background="colours.primary" @click="doDeleteAddress"
                            >Delete Address</vue-button
                        >
                    </div>
                </template>
            </vue-modal>
        </teleport>
    </section>
</template>

<script setup>
/* *********************************************************
 * MODULE IMPORTS
 ********************************************************* */
import { ref, computed, inject } from "vue";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import Listbox from "@dsmdesign/vue3-input-listbox";

/* *********************************************************
 * LOCAL IMPORTS
 ********************************************************* */
import { api } from "@/js/plugins/axios";
import { useForm } from "@dsmdesign/vue3-helpers";

const snackbar = useSnackbar();
const colours = inject("colours");
const routes = inject("routes");

/* *********************************************************
 * ADDRESS SELECTION
 ********************************************************* */
const addresses = ref([]);
const selectedAddress = ref(null);

const selectedAddressObject = computed(() => {
    return addresses.value.find((address) => {
        return address.id === selectedAddress.value;
    });
});

const addressOptions = computed(() =>
    addresses.value.map((address) => {
        return {
            label: address.nickname || address.address,
            value: address.id,
        };
    })
);

/* *********************************************************
 * CREATE ADDRESS
 ********************************************************* */
const showNewAddressModal = ref(false);

const newAddress = useForm({
    url: routes.address,
    method: "POST",
    fields: {
        account_id: "",
        country_id: 222,
        nickname: "",
        company: "",
        address: "",
        address_2: "",
        town: "",
        county: "",
        postcode: "",
        phone: "",
    },
});

const createNewAddress = () => {
    showNewAddressModal.value = true;
};

const doCreateAddress = async () => {
    try {
        const result = await newAddress.submit();
        if (result.data.address && result.data.address.id) {
            addresses.value.push(result.data.address);
        }

        snackbar.add({
            type: "success",
            text: `The address "${newAddress.nickname || newAddress.address}" has been created`,
        });
    } catch (e) {
        console.log(e);
    } finally {
        showNewAddressModal.value = false;
    }
};

/* *********************************************************
 * EDIT ADDRESS
 ********************************************************* */
const showEditAddressModal = ref(false);

const editSelectedAddress = () => {
    if (!selectedAddressObject.value) {
        return false;
    }
    showEditAddressModal.value = true;

    ["nickname", "company", "address", "address_2", "town", "county", "postcode", "phone"].forEach((field) => {
        editAddress[field] = selectedAddressObject.value[field];
    });

    editAddress.address_id = selectedAddressObject.value.id;
};

const editAddress = useForm({
    url: routes.address,
    method: "PATCH",
    fields: {
        account_id: "",
        address_id: null,
        country_id: 222,
        nickname: "",
        company: "",
        address: "",
        address_2: "",
        town: "",
        county: "",
        postcode: "",
        phone: "",
    },
});

const doEditAddress = async () => {
    try {
        const result = await editAddress.submit();
        if (result.data.address && result.data.address.id) {
            const existingIndex = addresses.value.findIndex((address) => address.id === editAddress.address_id);
            addresses.value.splice(existingIndex, 1, result.data.address);
        }
        selectedAddress.value = null;

        snackbar.add({
            type: "success",
            text: `The address "${editAddress.nickname || editAddress.address}" has been edited`,
        });
    } catch (e) {
        console.log(e);
    } finally {
        showEditAddressModal.value = false;
    }
};

/* *********************************************************
 * DELETE ADDRESS
 ********************************************************* */
const showConfirmDeleteModal = ref(false);

const doDeleteAddress = () => {
    api.delete(routes.address, {
        data: {
            address_id: selectedAddressObject.value.id,
        },
    })
        .then((res) => {
            if (res.data.status === "success") {
                addresses.value = addresses.value.filter((address) => {
                    return address.id !== selectedAddressObject.value.id;
                });
                selectedAddress.value = null;

                snackbar.add({
                    type: "success",
                    text: `Address deleted successfully`,
                });
            }
        })
        .finally(() => {
            showConfirmDeleteModal.value = false;
        });
};

/* *********************************************************
 * FETCH USER ACCOUNTS / ADDRESSES
 ********************************************************* */
api.get(routes.accounts)
    .then((res) => {
        const id = res.data?.[0]?.id;
        newAddress.account_id = editAddress.account_id = id;

        if (id) {
            return api.get(routes.addresses.replace("##account##", id));
        } else throw new Error();
    })
    .then((res) => {
        addresses.value = res?.data || [];
        if (addresses.value.length === 0) throw new Error();
        else {
            selectedAddress.value = addressOptions.value[0].value;
        }
    })
    .catch((e) => {
        snackbar.add({
            type: "error",
            text: "There was a problem fetching your addresses. Please try again later.",
        });
    });
</script>

<style lang="scss" scoped>
td:first-child {
    width: 1px;
    white-space: nowrap;
    padding-right: 1em;
    border-right: 1px solid rgba(0, 0, 0, 0.05);
}

td:last-child {
    padding-left: 1em;
}
</style>
