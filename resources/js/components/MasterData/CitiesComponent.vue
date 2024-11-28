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

/* Button Style from Code 2 */
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
                    <li class="breadcrumb-item active" aria-current="page">Cities</li>
                </ol>
            </nav>

            <div class="d-flex">
                <button class="add-btn" v-on:click="showModal(false)">
                    + Create new City
                </button>
            </div>

            <!-- Vue Table Component -->
            <v-client-table 
            :data="dataTable" 
            :columns="columns" 
            :options="options">
                <!-- Actions Column Template -->
                <template slot="actions" slot-scope="row">
                    <button
                        class="btn btn-link text-green"
                        v-on:click="showModal(true, row)"
                        style="font-size: 13px; font-weight: 600;">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </button>
                    <button
                        class="btn btn-link text-red"
                        v-on:click="destroy(row)"
                        style="font-size: 13px; font-weight: 600;">
                        <i class="fa fa-trash mr-2" aria-hidden="true"></i> Delete
                    </button>
                </template>
            </v-client-table>

            <!-- Modal for Create/Edit City -->
            <div class="modal" id="create-city">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="title">{{ isEdit ? 'Edit City' : 'Create City' }}</p>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="region">Region</label>
                                <select id="region_id" class="form-control" v-model="region_id" v-on:change="getProvincesByRegion">
                                    <option value="" disabled>Select Region</option>
                                    <option v-for="value in regions" :value="value.id">{{ value.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
                                <select id="province_id" class="form-control" v-model="province_id">
                                    <option value="" disabled>Select Province</option>
                                    <option v-for="value in provinces" :value="value.id">{{ value.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">City Name</label>
                                <input type="text" id="name" class="form-control" v-model="name" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="closeModal">Close</button>
                            <button type="button" class="btn btn-primary" v-on:click="isEdit ? update() : store()">{{ isEdit ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['regions'],
    data() {
        return {
            isEdit: false,
            id: '',
            region_id: '',
            province_id: '',
            name: '',
            provinces: [],
            dataTable: [],
            columns: ['id', 'region_name', 'province_name', 'name', 'actions'],
            options: {
                headings: {
                    id: 'Id',
                    region_name: 'Region',
                    province_name: 'Province',
                    name: 'City',
                    actions: 'Actions'
                }
            }
        };
    },
    methods: {
        show() {
            axios.get('/master_data/cities/show').then(response => {
                this.dataTable = response.data.data;
            });
        },
        showModal(isEdit, data = null) {
            this.isEdit = isEdit;
            if (isEdit && data) {
                this.edit(data);
            } else {
                this.resetForm();
            }
            $('#create-city').modal('show');
        },
        closeModal() {
            $('#create-city').modal('hide');
            this.resetForm();
        },
        getProvincesByRegion() {
            axios.get('/master_data/cities/getProvincesByRegion/' + this.region_id)
                .then(response => {
                    this.provinces = response.data.data;
                })
                .catch(error => {
                    console.error('Error fetching provinces:', error);
                });
        },
        store() {axios.post("/master_data/cities/store", {
                name: this.name,
                province_id: this.province_id,
            })
                .then((response) => {
                    this.$fire({
                        title: "Successfully Saved!",
                        text: response.data.message,
                        type: "success",
                        timer: 3000,
                    });
                    this.show();
                    this.closeModal();
                })
                .catch((error) => {
                    console.error('Error storing city:', error.response.data);
                });
        },
        edit(data) {
            this.id = data.row.id;
            axios.get('/master_data/cities/edit/' + this.id)
            .then(response => {
                const city = response.data.data;
                this.region_id = city.province.region.id;
                this.province_id = city.province_id;
                this.name = city.name;
                this.getProvincesByRegion(this.region_id);
            }).catch(error => {
                console.error('There was an error fetching the data:', error);
            });
        },
        update() {
            axios.put("/master_data/cities/update/" + this.id, {
                name: this.name,
                region_id: this.region_id,
                province_id: this.province_id,
            })
                .then((response) => {
                    this.$fire({
                        title: "Successfully Updated!",
                        text: response.data.message,
                        type: "success",
                        timer: 3000,
                    });
                    this.show();
                    this.closeModal();
                })
                .catch((error) => {
                    console.error('Error updating city:', error.response.data);
                });
        },
        destroy(data) {
            if (!data?.row?.id) {
                console.error("Invalid data passed to destroy method.");
                return;
            }
            axios.get("/master_data/cities/destroy/" + data.row.id)
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
                        text: "Failed to delete city.",
                        type: "error",
                        timer: 3000,
                    });
                });
        },
        resetForm() {
            this.isEdit = false;
            this.id = '';
            this.region_id = '';
            this.province_id = '';
            this.name = '';
            this.provinces = [];
        }
    },
    mounted() {
        this.show();
    },
};

  </script>


