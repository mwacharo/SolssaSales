<template>
  <v-container-fluid>
    <v-toolbar flat>
      <v-toolbar-title>Users</v-toolbar-title>
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="addUser">
        <v-icon>mdi-account-plus</v-icon>
      </v-btn>
      <v-btn color="primary" @click="refreshUsers">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </v-toolbar>
    <v-text-field v-model="search" label="Search" clearable @input="filterUsers" dense></v-text-field>
    <v-data-table :headers="headers" :items="filteredUsers">
      <template v-slot:item.status="{ item }">
        <v-icon :color="item.status ? 'success' : 'error'">
          {{ item.status ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline' }}
        </v-icon>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon color="blue" @click="editItem(item)">mdi-pen</v-icon>
        <v-icon class="mx-2" :class="{ 'text-success': item.active, 'text-error': !item.active }" size="small"
          @click="toggleUserStatus(item)">
          {{ item.active ? 'mdi-circle' : 'mdi-circle' }}
        </v-icon>
        <v-icon color="red" @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">{{ formTitle }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editedItem.name" placeholder="John Doe" label="Name"
                  prepend-icon="mdi-account"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editedItem.email" placeholder="user@example.com" label="Email"
                  prepend-icon="mdi-email"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editedItem.phone" placeholder="+254.." label="Phone"
                  prepend-icon="mdi-phone"></v-text-field>
              </v-col>
              <v-col v-if="editedIndex === -1" cols="12">
                <v-switch v-model="editedItem.sendCredentials" label="Send Credentials" color="success"></v-switch>
              </v-col>

            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="justify-end">
          <v-btn color="red darken-1" text @click.prevent="closeDialog">close</v-btn>
          <v-btn color="blue darken-1" text @click.prevent="saveItem">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-alert text="Are you sure you want to delete this item?!" title="Delete User" type="error">
        <template v-slot:actions>
          <v-btn color="blue darken-1" text @click="closeDelete">
            <v-icon left>mdi-close</v-icon>
            Cancel
          </v-btn>
          <v-btn color="blue darken-1" text @click="deleteItemConfirm">
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
        { title: 'Name', value: 'name' },
        { title: 'Email', value: 'email' },
        { title: 'Phone', value: 'phone' },
        { title: 'Status', value: 'status' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      users: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        email: '',
        phone: '',
        sendCredentials: true,
        status: false
      },
      defaultItem: {
        name: '',
        email: '',
        phone: '',
        status: false
      },
      dialog: false,
      dialogDelete: false
    };
  },
  computed: {
    filteredUsers() {
      if (!this.search) return this.users;
      return this.users.filter(user =>
        user.name.toLowerCase().includes(this.search.toLowerCase()) ||
        user.email.toLowerCase().includes(this.search.toLowerCase()) ||
        user.phone.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New User' : 'Edit User';
    }
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      const url = '/api/v1/users';
      axios.get(url)
        .then(response => {
          this.users = response.data.data.map((user, index) => ({
            ...user,
            index: index + 1,
            statusIcon: user.status ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline'
          }));
        })
        .catch(error => {
          console.log(error);
        });
    },
    addUser() {
      this.editedItem = { ...this.defaultItem };
      this.dialog = true;
    },
    editItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },
    deleteItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = { ...item };
      this.dialogDelete = true;
    },
    saveItem() {
      if (this.editedIndex > -1) {
        const url = `/api/v1/users/${this.editedItem.id}`;
        axios.put(url, this.editedItem)
          .then(response => {
            this.$toastr.success(response.data.message);
            Object.assign(this.users[this.editedIndex], response.data.user);
            this.closeDialog();
          })
          .catch(error => {
            this.$toastr.error('Error updating user');
            console.error('Error updating user:', error);
          });
      } else {
        const url = '/api/v1/users';
        axios.post(url, this.editedItem)
          .then(response => {
            this.$toastr.success(response.data.message);
            this.users.push(response.data.user);
            this.fetchUsers(); // Fetch users after adding a new user
            this.closeDialog();
          })
          .catch(error => {
            this.$toastr.error('Error adding user');
            console.error('Error adding user:', error);
          });
      }
    },


    deleteItemConfirm() {
      this.users.splice(this.editedIndex, 1);
      this.closeDelete();
    },
    closeDialog() {
      this.dialog = false;
      this.editedItem = { ...this.defaultItem };
      this.editedIndex = -1;
    },
    closeDelete() {
      this.dialogDelete = false;
      this.editedItem = { ...this.defaultItem };
      this.editedIndex = -1;
    },
    refreshUsers() {
      this.fetchUsers();
    },
    toggleStatus(item) {
      item.status = !item.status;
    },
    filterUsers() {
    }
  }
};
</script>
