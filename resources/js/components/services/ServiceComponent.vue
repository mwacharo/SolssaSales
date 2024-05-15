<template>
  <v-container-fluid>
    <v-toolbar flat>
      <v-toolbar-title>Services</v-toolbar-title>
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="addService">
        <v-icon>mdi-plus-box</v-icon>
      </v-btn>
      <v-btn color="primary" @click="refreshServices">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </v-toolbar>
    <v-text-field v-model="search" label="Search" clearable @input="filterServices" dense></v-text-field>
    <v-data-table :headers="headers" :items="filteredServices">
      <template v-slot:item="{ item, index }">
        <tr>
          <td>{{ index + 1 }}</td>
          <td>{{ item.name }}</td>
          <td>
            <v-icon color="blue" class="mx-1" @click="editService(item)">mdi-pen</v-icon>
            <v-icon color="red" class="mx-1" @click="deleteService(item)">mdi-delete</v-icon>
          </td>
        </tr>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="text-h5">{{ formTitle }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editedService.name" label="Service Name"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click.prevent="closeDialog">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click.prevent="saveService">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-alert text="Are you sure you want to delete this service?!" title="Delete Service" type="error">
        <template v-slot:actions>
          <v-btn color="blue darken-1" text @click="closeDelete">
            <v-icon left>mdi-close</v-icon>
            Cancel
          </v-btn>
          <v-btn color="blue darken-1" text @click="deleteServiceConfirm">
            <v-icon left>mdi-check</v-icon>
            OK
          </v-btn>
        </template>
      </v-alert>
    </v-dialog>
  </v-container-fluid>
</template>

<script>
export default {
  props: {
    user_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      search: '',
      headers: [
        { title: '#', value: 'index' },
        { title: 'Service Name', value: 'name' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      services: [],
      editedIndex: -1,
      editedService: {
        name: ''
      },
      defaultService: {
        name: ''
      },
      dialog: false,
      dialogDelete: false
    };
  },
  computed: {
    filteredServices() {
      if (!this.search) return this.services;
      return this.services.filter(service => service.name.toLowerCase().includes(this.search.toLowerCase()));
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New Service' : 'Edit Service';
    }
  },
  created() {
    this.fetchServices();
  },
  methods: {
    fetchServices() {
      axios.get('/api/v1/services')
        .then(response => {
          this.services = response.data.services;
        })
        .catch(error => {
          console.error('Error fetching services:', error);
        });
    },
    addService() {
      this.editedService = { ...this.defaultService };
      this.dialog = true;
    },
    editService(service) {
      this.editedIndex = this.services.indexOf(service);
      this.editedService = { ...service };
      this.dialog = true;
    },
    deleteService(service) {
      this.editedIndex = this.services.indexOf(service);
      this.editedService = { ...service };
      this.dialogDelete = true;
    },
    saveService() {
      if (this.editedIndex > -1) {
        axios
          .put(`/api/v1/services/${this.editedService.id}`, this.editedService)
          .then(response => {
            this.fetchServices();
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error updating service:', error);
          });
        this.services[this.editedIndex] = { ...this.editedService };
        this.closeDialog();
      } else {
        axios
          .post('/api/v1/services', this.editedService)
          .then(response => {
            this.fetchServices();
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error adding service:', error);
          });
        this.services.push({ ...this.editedService });
        this.closeDialog();
      }
    },
    deleteServiceConfirm() {
      axios
        .delete(`/api/v1/services/${this.editedService.id}`)
        .then(response => {
          this.services.splice(this.editedIndex, 1);
          this.closeDelete();
        })
        .catch(error => {
          console.error('Error deleting service:', error);
        });
      this.closeDelete();
    },
    closeDialog() {
      this.dialog = false;
      this.editedIndex = -1;
      this.editedService = { ...this.defaultService };
    },
    closeDelete() {
      this.dialogDelete = false;
      this.editedService = { ...this.defaultService };
      this.editedIndex = -1;
    },
    refreshServices() {
      this.fetchServices();
    },
    filterServices() { }
  }
};
</script>
