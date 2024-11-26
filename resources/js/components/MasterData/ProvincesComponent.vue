<style>
.add-btn {
    border: none;
    width: 125px;
    height: 40px;
    transform: translate(170%, 100%);
    z-index: 1;
}
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
</style>

<template>
    <div>

        <div class="pt-3">
            <!-- Breadcrumb Section -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Masterdata</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Regions</li>
                </ol>
            </nav>

            <div class="card p-5">
                <h2 class="title-header">Provinces</h2>
                <br />
                <button
                    class="button"
                    v-on:click="showModal"
                    style="background-color: #6baf6b; color: white; border-radius: 5px;">
                    + New Province
                </button>
                <br />

                <div class="row">
                    <div class="col-md-12">
                        <v-client-table 
                        :data="data" 
                        :columns="columns" 
                        :options="options">

                            <template slot="actions" slot-scope="row">
                                <button
                                    class="btn btn-link text-green"
                                    v-on:click="edit(row)"
                                    style="font-size: 13px; font-weight: 600">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>

                                <button
                                    class="btn btn-link text-red"
                                    v-on:click="destroy(row)"
                                    style="font-size: 13px; font-weight: 600">
                                    <i class="fa fa-trash mr-2" aria-hidden="true"></i> Delete
                                </button>

                            </template>
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal" id="create-province">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="title">Create Province</p>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="region">Regions</label>
                            <select id="region_id" class="form-control" v-model="region_id">
                                <option v-for="region in regions" :value="region.id">{{ region.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Province Name</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{ 'border-danger': errors.name }"
                                v-model="name">

                            <p class="text-danger" v-if="errors.name">{{ errors.name?.[0] }}</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" v-on:click="closeModal">Close</button>
                        <button type="button" class="btn btn-primary" v-on:click="store" v-if="!isEdit">Save</button>
                        <button type="button" class="btn btn-primary" v-on:click="update" v-else>Update</button>
                    </div>
                </div>
            </div>
        </div>

        <loading
            :active="isLoading"
            style="z-index: 99999 !important;"
            :can-cancel="false"
            :is-full-page="fullPage"
        ></loading>
        
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
    components: {
        Loading,
    },
    props: ["regions"],
    data() {
        return {
            isLoading: false,
            fullPage: true,
            isEdit: false,
            errors: [],
            id: "",
            region_id: "",
            name: "",
            data: [],
            columns: ["id", "region_name", "name", "actions"],
            options: {
                headings: {
                    id: "ID",
                    region_name: "Region",
                    name: "Province Name",
                    actions: "Actions",
                },
                filterable: false,
                sortable: ["id", "name"],
            },
        };
    },
    // computed: {
    //     tableData() {
    //         return this.data.map((item) => ({
    //             ...item,
    //             region_name: this.regions.find((r) => r.id === item.region_id)?.name || "N/A",
    //         }));
    //     },
    // },
    methods: {
        init() {
            this.name = "";
            this.region_id = "";
            this.errors = [];
        },

        show() {
            axios.get("/master_data/provinces/show").then((response) => {
                console.log(response);
                this.data = response.data.data;
            });
        },

        showModal() {
            this.isEdit = false;
            this.init();
            $("#create-province").modal("show");
        },

        closeModal() {
            $("#create-province").modal("hide");
        },

        store() {
            this.isLoading = true;
            axios.post("/master_data/provinces/store", {
                    name: this.name,
                    region_id: this.region_id,
                })
                .then((response) => {
                    this.$fire({
                        title: "Successfully Saved!",
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

        destroy(data) {
            if (!data?.row?.id) {
                console.error("Invalid data passed to destroy method.");
                return;
            }
            axios.get("/master_data/provinces/destroy/" + data.row.id)
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
                        text: "Failed to delete province.",
                        type: "error",
                        timer: 3000,
                    });
                });
        },

        edit(data) {
            this.isEdit = true;
            this.id = data.row.id;
            this.name = data.row.name;
            this.region_id = data.row.region_id;
            $("#create-province").modal("show");
        },

        update() {
            this.isLoading = true;
            axios.put("/master_data/provinces/update/" + this.id, {
                    name: this.name,
                    region_id: this.region_id,
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
