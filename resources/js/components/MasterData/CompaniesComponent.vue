<style>
    /* Shared Styles */
    .card {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .title-header {
        font-weight: bold;
        margin: 0;
        padding: 0;
        font-size: 30px;
    }
    .VueTables__search-field label {
        display: none;
    }
    .base-image-input {
        display: block;
        width: 200px;
        height: 200px;
        cursor: pointer;
        background-size: cover;
        background-position: center center;
    }
    .placeholder {
        background: #F0F0F0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #333;
        font-size: 18px;
        font-family: Helvetica;
    }
    .placeholder:hover {
        background: #E0E0E0;
    }
    .file-input {
        display: none;
    }
    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        margin: auto;
    }
    .breadcrumb {
        font-size: 15px;
        font-weight: bold;
    }
    .breadcrumb-item.active {
        color: #28a745;
    }
    .add-btn {
        border: none;
        width: 125px;
        height: 40px;
        background-color: #6baf6b;
        color: white;
        border-radius: 5px;
        font-size: 14px;
        transform: translate(170%, 100%);
        z-index: 1;
    }
</style>

<template>
    <div class="container pt-3">
        <div class="card p-5">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Companies</li>
                </ol>
            </nav>
            <div class="d-flex">
                <button class="add-btn" @click="showModal">
                    + Create Company
                </button>
            </div>

            <v-client-table 
                :data="data" 
                :columns="columns" 
                :options="options">
                <template slot="actions" slot-scope="row">
                    <button class="btn btn-link text-green" @click="edit(row)" style="font-size: 13px; font-weight: 600;">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </button>
                    <button class="btn btn-link text-red" @click="destroy(row)" style="font-size: 13px; font-weight: 600;">
                        <i class="fa fa-trash mr-2"></i> Delete
                    </button>
                </template>
            </v-client-table>

            <div class="modal" id="create-company">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>{{ isEdit ? 'EDIT COMPANY' : 'CREATE COMPANY' }}</b></h5>
                            <button type="button" class="btn-close" @click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" 
                                    :class="{'border-danger': errors.name}" 
                                    v-model="name">
                                <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" 
                                    :class="{'border-danger': errors.address}" 
                                    v-model="address">
                                <p class="text-danger" v-if="errors.address">{{ errors.address[0] }}</p>
                            </div>

                            <div class="form-group">
                                <label for="established_date">Established Date</label>
                                <input type="date" class="form-control" 
                                    :class="{'border-danger': errors.established_date}" 
                                    v-model="established_date">
                                <p class="text-danger" v-if="errors.established_date">{{ errors.established_date[0] }}</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                            <button type="button" class="btn btn-primary" @click="isEdit ? update() : store()">
                                {{ isEdit ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <loading :active="isLoading" :can-cancel="false" :is-full-page="fullPage" style="z-index: 99999 !important;">
            </loading>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: { Loading },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            isEdit: false,
            errors: {},
            id: '',
            name: '',
            address: '',
            established_date: '',
            data: [],
            columns: ['id', 'name', 'address', 'established_date', 'actions'],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Company Name',
                    address: 'Company Address',
                    established_date: 'Established Date',
                    actions: 'Actions',
                },
                filterable: true,
            },
        };
    },
    methods: {
        formatDateForDisplay(dateString) 
        { let dateObject = new Date(dateString); 
            return dateObject.toLocaleDateString("en-US", { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' });
            },
        init() {
            this.name = '';
            this.address = '';
            this.established_date = '';
        },
        show() {
            axios.get('/master_data/companies/show').then(response => {
                this.data = response.data.data;
            });
        },
        showModal() {
            this.isEdit = false;
            this.init();
            $('#create-company').modal('show');
        },
        closeModal() {
            $('#create-company').modal('hide');
        },
        store() {
            this.isLoading = true;
            axios.post('/master_data/companies/store', {
                name: this.name,
                address: this.address,
                established_date: this.established_date,
            }).then((response) => {
                // Notify success
                this.$fire({
                    title: 'Successfully Saved!',
                    text: response.data.message,
                    type: 'success',
                    timer: 3000,
                });
                this.show();
                this.init();
                this.closeModal();
            }).catch((error) => {
                this.errors = error.response.data.errors || {};
            }).finally(() => {
                this.isLoading = false;
            });
        },
        destroy(data) {
            if (!data?.row?.id) {
                console.error("Invalid data passed to destroy method.");
                return;
            }
            axios.get("/master_data/companies/destroy/" + data.row.id)
                .then((response) => {
                    this.$fire({
                        title: "Successfully Deleted!",
                        text: response.data.message,
                        type: "success",
                        timer: 3000,
                    });
                    this.show();
                })
                .catch(() => {
                    this.$fire({
                        title: "Error!",
                        text: "Failed to delete Company.",
                        type: "error",
                        timer: 3000,
                    });
                });
        },
        edit(data) {
            this.isEdit = true;
            this.id = data.row.id;
            this.name = data.row.name;
            this.address = data.row.address;
            this.established_date = data.row.established_date;
            $("#create-company").modal("show");
        },

        update() {
            this.isLoading = true;
            axios.put("/master_data/companies/update/" + this.id, {
                    name: this.name,
                    address: this.address,
                    established_date: this.established_date,
                })
                .then((response) => {
                    this.$fire({
                        title: "Successfully Updated!",
                        text: response.data.message,
                        type: "success",
                        timer: 3000,
                    });
                    this.show();
                    this.init();
                    this.closeModal();
                }).catch((error) => {
                    this.errors = error.response.data.errors;
                }).finally(() => {
                    this.isLoading = false;
                });
        },
    },
    mounted() {
        this.show();
    },
};
</script>
