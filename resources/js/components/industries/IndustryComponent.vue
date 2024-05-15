<template>
  <v-container-fluid>
    <v-toolbar flat>
      <v-toolbar-title>Industries</v-toolbar-title>
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="addIndustry">
        <v-icon>mdi-plus</v-icon>
        Add Industry
      </v-btn>
      <v-btn color="primary" @click="refreshIndustries">
        <v-icon>mdi-refresh</v-icon>
        Refresh
      </v-btn>
    </v-toolbar>
    <v-text-field v-model="search" label="Search" clearable @input="filterIndustries" dense></v-text-field>
    <v-data-table :headers="headers" :items="filteredIndustries">
      <template v-slot:item="{ item, index }">
        <tr>
          <td>{{ index + 1 }}</td>
          <td>{{ item.name }}</td>
          <td>
            <v-icon color="blue" @click="editIndustry(item)">mdi-pencil</v-icon>
            <v-icon color="red" @click="deleteIndustry(item)">mdi-delete</v-icon>
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
                <v-text-field v-model="editedIndustry.name" label="Industry Name"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click.prevent="closeDialog">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click.prevent="saveIndustry">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-alert text="Are you sure you want to delete this industry?!" title="Delete Industry" type="error">
        <template v-slot:actions>
          <v-btn color="blue darken-1" text @click="closeDelete">
            <v-icon left>mdi-close</v-icon>
            close
          </v-btn>
          <v-btn color="blue darken-1" text @click="deleteIndustryConfirm">
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
        { title: 'Industry Name', value: 'name' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      industries: [],
      editedIndex: -1,
      editedIndustry: {
        name: '',
      },
      defaultIndustry: {
        name: '',
      },
      dialog: false,
      dialogDelete: false
    };
  },
  computed: {
    filteredIndustries() {
      if (!this.search) return this.industries;
      return this.industries.filter(industry =>
        industry.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New Industry' : 'Edit Industry';
    }
  },
  created() {
    this.fetchIndustries();
  },
  methods: {
    fetchIndustries() {
      axios.get('/api/v1/industries')
        .then(response => {
          this.industries = response.data.industries;
        })
        .catch(error => {
          console.error('Error fetching industries:', error);
        });
    },
    addIndustry() {
      this.editedIndustry = { ...this.defaultIndustry };
      this.dialog = true;
    },
    editIndustry(industry) {
      this.editedIndex = this.industries.indexOf(industry);
      this.editedIndustry = { ...industry };
      this.dialog = true;
    },
    deleteIndustry(industry) {
      this.editedIndex = this.industries.indexOf(industry);
      this.editedIndustry = { ...industry };
      this.dialogDelete = true;
    },
    saveIndustry() {
      if (this.editedIndex > -1) {
        axios.put(`/api/v1/industries/${this.editedIndustry.id}`, this.editedIndustry)
          .then(response => {
            this.fetchIndustries();
            this.$toastr.success('Industry updated successfully');
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error updating industry:', error);
          });
        this.industries[this.editedIndex] = { ...this.editedIndustry };
        this.closeDialog();
      } else {
        axios.post('/api/v1/industries', this.editedIndustry)
          .then(response => {
            this.fetchIndustries();
            this.closeDialog();
          })
          .catch(error => {
            this.$toastr.error('Error updating Industry');
            console.error('Error adding industry:', error);
          });
        this.industries.push({ ...this.editedIndustry });
        this.closeDialog();
      }
    },
    deleteIndustryConfirm() {
      axios.delete(`/api/v1/industries/${this.editedIndustry.id}`)
        .then(response => {
          this.industries.splice(this.editedIndex, 1);
          this.closeDelete();
        })
        .catch(error => {
          console.error('Error deleting industry:', error);
        });
      this.closeDelete();
    },
    closeDialog() {
      this.dialog = false;
      this.editedIndex = -1;
      this.editedIndustry = { ...this.defaultIndustry };
    },
    closeDelete() {
      this.dialogDelete = false;
      this.editedIndustry = { ...this.defaultIndustry };
      this.editedIndex = -1;
    },
    refreshIndustries() {
      this.fetchIndustries();
    },
    filterIndustries() {}
  }
};
</script>
