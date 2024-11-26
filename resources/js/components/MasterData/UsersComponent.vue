<style >
    .add-btn {
        border: none;
        width: 125px;
        Height : 40px;
        transform: translate(170%, 100%);
        z-index: 1;
    }
    .card {
        border-radius: 10px;
    }
    .title-header{
        font-weight: bold;
        margin: 0;
        padding: 0;
        font-size: 30px;
    }
    .VueTables__search-field label{
        display: none;
    }
</style>


<template>
    <div class="pt-3">
        <div class="card p-5 ">
            <h2 class="title-header">Users</h2>
            <button v-on:click="createRecord" class="add-btn" style="background-color: #6BAF6B; color: white;  border-radius: 5px;">
                + Create New
            </button>
            <div class="row">
                <div class="col-md-12">
                    <v-client-table
                            :data="tableData"
                            :columns="columns"
                            :options="options">
                            <template slot="actions" slot-scope="row">
                                <button class="btn btn-link text-green" style="font-size: 13px; font-weight: 600" v-on:click="editRecord(row.row.id)"><i class="fas fa-edit mr-2"></i> Edit</button>
                                <button class="btn btn-link text-red" style="font-size:     13px; font-weight: 600"  v-on:click="deleteRecord(row.row.id)"><i class="fa fa-trash mr-2" aria-hidden="true"></i> Delete</button>
                            </template>

                        </v-client-table>
                </div>
            </div>
        </div>
        
        <div class="modal" id="create-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data">
                        <div class="modal-body p-1">
                            <h5 class="modal-title text-center pt-4" style="font-weight:bold">Add User</h5>
                            <div class="container mb-5">
                                <div class="row pt-5 pl-5 pr-5">
                                    <div class="col-md-12">
                                        <label>Name</label>
                                        <input type="text" class="form-control" v-model="formData.name">
                                        <span style="font-size: 12px; color: red;" :error="errors" v-if="errors.name">{{ errors.name[0] }}</span>
                                    </div>
                                </div>
                                <div class="row pl-5 pr-5">
                                    <div class="col-md-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" v-model="formData.email">
                                        <span style="font-size: 12px; color: red;" :error="errors" v-if="errors.email">{{ errors.email[0] }}</span>
                                    </div>
                                </div>
                                <div class="row pl-5 pr-5" v-if="isCreate">
                                    <div class="col-md-12">
                                        <label>Password</label>
                                        <input type="password" class="form-control" v-model="formData.password" >
                                        <span style="font-size: 12px; color: red;" :error="errors" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-75" style="margin:0 auto">
                                <div class="pt-2 pb-5 w-100 row justify-content-center">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100" type="button"  data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn w-100" type="submit" style="background: #070151; color: white;">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="vld-parent">
            <loading :active="isLoading"
                     :can-cancel="false"
                     :is-full-page="fullPage">
            </loading>
        </div>
    </div>

</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Swal from 'sweetalert2';



export default {

    data() {
        
        return {
            imageData: null,
            columns: ['id','name','email','actions'],
            tableData: [],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Name',
                    email: 'Email',
                    actions: 'Actions',
                },
                sortable: ['name','email'],
                filterable: ['name','email'],
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
                    },
                    created_at: function(h, row) {
                        return row.created_at !== null ? moment(row.created_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    },
                    updated_at: function(h, row) {
                        return row.updated_at !== null ? moment(row.updated_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    }
                },
                texts : {
                    filter: 'Search:',
                },
            },
            errors : [],
            isCreate : false,
            formData: {
                id:null,
                name:null,
                email:null,
                password:null,
            },
            isLoading: false,
            fullPage: true,
        }
    },
    components: {
        Loading
    },
    methods: {
        loadRecords() {
            this.isLoading = true;
            
            axios.get('/master_data/users/getrecords').then(function (res) {
                this.tableData = res.data.user;
            }
                .bind(this)).finally(() => {this.isLoading = false});
        },
        

        createRecord() {

            $('#create-modal').modal('show');
            this.formData.name=null;
            this.formData.email=null;
            this.formData.password=null;
            this.isCreate = true;

        },

        submitForm() {
            axios.post('/master_data/users/store', this.formData).then(response => {
                    // Close modal and show success message
                    $("#create-modal").modal("hide");
                    this.$toastr.Add({
                        title: "System Info",
                        msg: response.data.message,
                        clickClose: false,
                        timeout: 3000,
                        progressBarValue: 0,
                        position: "toast-top-right",
                        type: "success",
                        preventDuplicates: true,
                        classNames: ["animated", "zoomInUp"],
                    });
                    this.loadRecords();
                })
                .catch(error => {
                    // Handle validation errors
                    if (error.response && error.response.status === 400 && error.response.data.message === 'Email already exists') {
                        // Show specific notification for duplicate email
                        this.$toastr.Add({
                            title: "Duplicate Entry",
                            msg: "This email is already in use.",
                            clickClose: false,
                            timeout: 3000,
                            progressBarValue: 0,
                            position: "toast-top-right",
                            type: "warning",
                            preventDuplicates: true,
                            classNames: ["animated", "zoomInUp"],
                        });
                    } else if (error.response && error.response.data.errors) {
                        // Show validation errors if present
                        this.errors = error.response.data.errors;
                    } else {
                        // Show generic error message
                        this.messageBox('Error', 'Something went wrong!', 'error');
                    }
                });
        },

        submitForm2() {
            axios.post('/master_data/users/update', this.formData)
                .then(response => {
                    // Close modal and show success message
                    $("#show-modal").modal("hide");
                    this.$toastr.Add({
                        title: "System Info",
                        msg: response.data.message,
                        clickClose: false,
                        timeout: 3000,
                        progressBarValue: 0,
                        position: "toast-top-right",
                        type: "success",
                        preventDuplicates: true,
                        classNames: ["animated", "zoomInUp"],
                    });
                    this.loadRecords();
                })
                .catch(error => {
                    // Handle duplicate email error
                    if (error.response && error.response.status === 400 && error.response.data.message === 'Email already exists') {
                        this.$toastr.Add({
                            title: "Duplicate Entry",
                            msg: "This email is already in use.",
                            clickClose: false,
                            timeout: 3000,
                            progressBarValue: 0,
                            position: "toast-top-right",
                            type: "warning",
                            preventDuplicates: true,
                            classNames: ["animated", "zoomInUp"],
                        });
                    } else if (error.response && error.response.data.errors) {
                        // Show validation errors if present
                        this.errors = error.response.data.errors;
                    } else {
                        // Show generic error message
                        this.messageBox('Error', 'Something went wrong!', 'error');
                    }
                });
        },


        editRecord(id) {

            axios.get('/master_data/users/edit/' + id)
            .then(response => {
                this.isCreate = false;
                this.formData = response.data.data;
                $('#create-modal').modal('show');

            })

        },


        deleteRecord(id) {

            if(confirm("Do you really want to delete?")){

                axios.get('/master_data/users/destroy/' + id)
                .then(response => {

                    // $("#show-modal").modal("hide");
                    this.$toastr.Add({
                            title: "System Info",
                            msg: response.data.message,
                            clickClose: false,
                            timeout: 3000,
                            progressBarValue: 0,
                            position: "toast-top-right",
                            type: "success",
                            preventDuplicates: true,
                            classNames: ["animated", "zoomInUp"],
                    });
                    this.loadRecords();
                    // this.messageBoxWithReload('Deleted', 'Data Successfully Deleted!', 'success');

                })
            }
        },

    },

    mounted() {
        this.isLoading = false;
        this.loadRecords();
    }
}
</script>

<style scoped>
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
</style>
