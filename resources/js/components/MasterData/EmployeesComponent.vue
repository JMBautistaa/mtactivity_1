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
            <li class="breadcrumb-item active" aria-current="page">User</li>
          </ol>
        </nav>
  
        <div class="d-flex">
          <button class="add-btn" v-on:click="showModal(false)">
            + Create User
          </button>
        </div>
  
        <v-client-table :data="dataTable" :columns="columns" :options="options">
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
  
        <div class="modal" id="create-emp">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <p class="title">{{ isEdit ? 'Edit Employee' : 'Create Employee' }}</p>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="company">Company</label>
                  <select id="company_id" class="form-control" v-model="company_id" v-on:change="getDepartmentsByCompany">
                        <option value="" disabled>Select Company</option>
                        <option v-for="value in companies" :value="value.id">{{ value.name }}</option>
                    </select>
                </div>
  
                <div class="form-group">
                  <label for="department">Department</label>
                  <select id="department_id" class="form-control" v-model="department_id">
                    <option value="" disabled>Select Department</option>
                    <option v-for="value in departments" :value="value.id">{{ value.name }}</option>
                  </select>
                </div>
  
                <div class="form-group">
                  <label for="full_name">Full Name</label>
                  <input type="text" id="full_name" class="form-control" v-model="full_name" />
                </div>
  
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" id="email" class="form-control" v-model="email" />
                </div>
  
                <div class="form-group">
                  <label for="phone_number">Phone Number</label>
                  <input type="number" id="phone_number" class="form-control" v-model="phone_number" />
                </div>
  
                <div class="form-group">
                  <label for="job_title">Job Title</label>
                  <input type="text" id="job_title" class="form-control" v-model="job_title" />
                </div>
  
                <div class="form-group">
                  <label for="hire_date">Hire Date</label>
                  <input type="date" id="hire_date" class="form-control" v-model="hire_date" />
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
  props: ["companies"],
  data() {
    return {
      isEdit: false,
      id: "",
      company_id: "",
      department_id: "",
      full_name: "",
      email: "",
      phone_number: "",
      job_title: "",
      hire_date: "",
      departments: [],
      dataTable: [],
      errors: [],
      columns: [
        "id",
        "company_name",
        "department_name",
        "full_name",
        "email",
        "phone_number",
        "job_title",
        "hire_date",
        "actions",
      ],
      options: {
        headings: {
          id: "ID",
          company_name: "Company",
          department_name: "Department",
          full_name: "Full Name",
          email: "Email",
          phone_number: "Phone Number",
          job_title: "Job Title",
          hire_date: "Hire Date",
          actions: "Actions",
        },
      },
    };
  },
  methods: {
    init() {
            this.company_id = '',
            this.department_id = '',
            this.full_name = '';
            this.email = '';
            this.phone_number = '';
            this.job_title ='';
            this.hire_date = '';
            this.errors = [];
        },
    show() {
      axios.get('/master_data/employees/show').then((response) => {
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
            $('#create-emp').modal('show');
        },
        closeModal() {
            $('#create-emp').modal('hide');
            this.resetForm();
        },
    getDepartmentsByCompany(){
      axios.get('/master_data/employees/getDepartmentsByCompany/' + this.company_id)
                .then(response => {
                    this.departments = response.data.data;
                })
                .catch(error => {
                    console.error('Error fetching departments:', error);
                });
        },
    store() {
      axios.post("/master_data/employees/store", {
          company_id: this.company_id,
          department_id: this.department_id,
          full_name: this.full_name,
          email: this.email,
          phone_number: this.phone_number,
          job_title: this.job_title,
          hire_date: this.hire_date,
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
          console.error("Error storing employee:", error.response.data);
        });
    },
    edit(data) {
      this.id = data.row.id;
      axios.get("/master_data/employees/edit/" + this.id)
        .then((response) => {
          const emp = response.data.data;
          this.company_id = emp.department.company.id;
          this.department_id = emp.department.id;
          this.full_name = emp.full_name;
          this.email = emp.email;
          this.phone_number = emp.phone_number;
          this.job_title = emp.job_title;
          this.hire_date = emp.hire_date;
          this.getDepartmentsByCompany(this.company_id);
        })
        .catch((error) => {
          console.error("Error fetching employee data:", error);
        });
    },
    update() {
      axios.put("/master_data/employees/update/" + this.id, {
          company_id: this.company_id,
          department_id: this.department_id,
          full_name: this.full_name,
          email: this.email,
          phone_number: this.phone_number,
          job_title: this.job_title,
          hire_date: this.hire_date,
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
        console.error("Error updating employee:", error.response ? error.response.data : error);
});

    },
    destroy(data) {
            if (!data?.row?.id) {
                console.error("Invalid data passed to destroy method.");
                return;
            }
            axios.get("/master_data/employees/destroy/" + data.row.id)
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
      this.id = "";
      this.company_id = "";
      this.department_id = "";
      this.full_name = "";
      this.email = "";
      this.phone_number = "";
      this.job_title = "";
      this.hire_date = "";
      this.departments = [];
    },
  },
  mounted() {
    this.show();
  },
};
</script>

  


