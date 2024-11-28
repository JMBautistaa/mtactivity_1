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
                    <li class="breadcrumb-item active" aria-current="page">Departments</li>
                </ol>
            </nav>
            <div class="d-flex">
                <button class="add-btn" @click="showModal">
                    + Create Department
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

            <div class="modal" id="create-dept">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>{{ isEdit ? 'EDIT DEPARTMENT' : 'CREATE DEPARTMENT' }}</b></h5>
                            <button type="button" class="btn-close" @click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="company">Companies</label>
                                <select id="company_id" class="form-control" 
                                    :class="{'border-danger': errors.company_id}"  
                                    v-model="company_id">
                                    <option v-for="company in companies" :value="company.id">{{ company.name }}</option>
                                </select>
                                <p class="text-danger" v-if="errors.company_id">{{ errors.company_id[0] }}</p>
                            </div>

                            <div class="form-group">
                                <label for="name">Department Name</label>
                                <input type="text" class="form-control" 
                                    :class="{'border-danger': errors.name}" 
                                    v-model="name">
                                <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
                            </div>

                            <div class="form-group">
                                <label for="budget">Budget</label>
                                <input type="number" class="form-control" 
                                    :class="{'border-danger': errors.budget}" 
                                    v-model="budget">
                                <p class="text-danger" v-if="errors.budget">{{ errors.budget[0] }}</p>
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
    props: ["companies"],
    data() {
        return {
            isLoading: false,
            fullPage: true,
            isEdit: false,
            errors: {},
            id: '',
            company_id : '',
            name: '',
            budget: '',
            data: [],
            columns: ['id', 'company_name', 'name', 'creation_date', 'actions'],
            options: {
                headings: {
                    id: 'ID',
                    company_name: 'Company Name',
                    name: 'Department Name',
                    creation_date: 'Created date',
                    actions: 'Actions',
                },
                filterable: true,
            },
        };
    },
    methods: {
        init() {
            this.company_id = '',
            this.name = '';
            this.budget = '';
            this.errors = [];
        },
        show() {
            axios.get('/master_data/departments/show').then(response => {
                this.data = response.data.data;
            });
        },
        showModal() {
            this.isEdit = false;
            this.init();
            $('#create-dept').modal('show');
        },
        closeModal() {
            $('#create-dept').modal('hide');
        },
        
        store() {
            this.isLoading = true;
            axios.post('/master_data/departments/store', {
                company_id: this.company_id,
                name: this.name,
                budget: this.budget,
            }).then((response) => {
                this.$fire({
                    title: 'Successfully Saved!',
                    text: response.data.message,
                    type: 'success',
                    timer: 3000,
                });
                this.show();
                this.init();
                this.closeModal();

                this.errors = {};
            }).catch((error) => {
                if (error.response && error.response.status === 422 && error.response.data.message === 'Cannot save the same shits') {
                    this.$fire({
                        title: 'Error!',
                        text: 'A department with the same name already exists for this company.',
                        type: 'error',
                        timer: 3000,
                    });
                } else if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else if (error.response && error.response.data.message) {
                    this.$fire({
                        title: 'Error!',
                        text: error.response.data.message,
                        type: 'error',
                        timer: 3000,
                    });
                }

            }).finally(() => {
                this.isLoading = false;
            });
        },

        destroy(data) {
            if (!data?.row?.id) {
                console.error("Invalid data passed to destroy method.");
                return;
            }
            this.$swal({
                title: "Are you sure?",
                text: "Are you sure you want to delete this Department?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get("/master_data/departments/destroy/" + data.row.id)
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
                                text: "Failed to delete Department.",
                                type: "error",
                                timer: 3000,
                            });
                        });
                }
            });
        },
        edit(data) {
            this.isEdit = true;
            this.id = data.row.id;
            this.company_id = data.row.company_id;
            this.name = data.row.name;
            this.budget = data.row.budget;
            $('#create-dept').modal('show');
        },

        update() {
            this.isLoading = true;
            axios.put("/master_data/departments/update/" + this.id, {
                    company_id: this.company_id,
                    name: this.name,
                    budget: this.budget,
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


