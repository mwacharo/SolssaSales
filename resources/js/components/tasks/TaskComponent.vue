<template>
  <v-container-fluid>
    <v-toolbar flat>
      <v-toolbar-title>Tasks</v-toolbar-title>
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="addTask">
        <v-icon>mdi-plus</v-icon>
        Add Task
      </v-btn>
      <v-btn color="primary" @click="refreshTasks">
        <v-icon>mdi-refresh</v-icon>
        Refresh
      </v-btn>
    </v-toolbar>
    <v-text-field v-model="search" label="Search" clearable @input="filterTasks" dense></v-text-field>
    <v-data-table :headers="headers" :items="tasks">
      <template v-slot:item="{ item }">
        <tr>
          <td>{{ item.index }}</td>
          <td>{{ item.task_description }}</td>
          <td>{{ item.due_date }}</td>
          <td>{{ item.user.name ?? '..' }}</td>
          <td>{{ item.deal_id ?? '..' }}</td>
          <td>
            <v-icon :color="item.completed ? 'success' : 'error'">
              {{ item.completed ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline' }}
            </v-icon>
          </td>
          <td>
            <v-icon color="blue" class="mx-1" @click="editTask(item)">mdi-pen</v-icon>
            <v-icon color="success" class="mx-1" @click="editTask(item)">mdi-calendar</v-icon>
            <v-icon color="red" class="mx-1" @click="deleteTask(item)">mdi-delete</v-icon>
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
                <v-text-field v-model="editedItem.task_description" placeholder="Task Description"
                  label="Task Description"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editedItem.due_date" placeholder="Close Date" label="Close Date" type="date">
                </v-text-field>
              </v-col>

              <v-col cols="12">
                <v-select v-model="editedItem.deal_id" :items="deals" label="Deal" item-title="title"
                  placeholder="Select Deal"></v-select>
              </v-col>

              <v-col cols="12">
                <v-checkbox v-model="editedItem.completed" label="Completed"></v-checkbox>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click.prevent="closeDialog">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click.prevent="saveTask">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-alert text="Are you sure you want to delete this task?!" title="Delete Task" type="error">
        <template v-slot:actions>
          <v-btn color="blue darken-1" text @click="closeDelete">
            <v-icon left>mdi-close</v-icon>
            Cancel
          </v-btn>
          <v-btn color="blue darken-1" text @click="deleteTaskConfirm">
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
      type: [Number],
      required: true
    }
  },
  data() {
    return {
      search: '',
      headers: [
        { title: '#', value: 'index' },
        { title: 'Task Description', value: 'task_description' },
        { title: 'Due Date', value: 'due_date' },
        { title: 'User', value: 'user.name' },
        { title: 'Deal', value: 'deal.name' },
        { title: 'Completed', value: 'completed' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      tasks: [],
      editedIndex: -1,
      editedItem: {
        task_description: '',
        due_date: null,
        deal_id: '',
        user_id: this.user_id,
        completed: false
      },
      deals: [],
      defaultItem: {
        task_description: '',
        due_date: null,
        deal_id: '',
        user_id: this.user_id,
        completed: false
      },
      dialog: false,
      dialogDelete: false,
      dateMenu: false
    };
  },
  computed: {
    filteredTasks() {
      if (!this.search) return this.tasks;
      return this.tasks.filter(task =>
        task.task_description.toLowerCase().includes(this.search.toLowerCase()) ||
        task.user.name.toLowerCase().includes(this.search.toLowerCase()) ||
        task.deal.title.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New Task' : 'Edit Task';
    }
  },
  created() {
    this.fetchTasks();
    this.fetchDeals();
  },
  methods: {
    fetchTasks() {
      const url = '/api/v1/tasks';
      axios.get(url)
        .then(response => {
          this.tasks = response.data.tasks.map((task, index) => ({
            ...task,
            index: index + 1,
            statusIcon: task.completed ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline'
          }));
        })
        .catch(error => {
          console.error('Error fetching tasks:', error);
        });
    },
    fetchDeals() {
      const url = '/api/v1/deals';
      axios.get(url)
        .then(response => {
          this.deals = response.data.deals; // Corrected assignment to this.deals
        })
        .catch(error => {
          console.error('Error fetching deals:', error);
        });
    },

    addTask() {
      this.editedItem = { ...this.defaultItem };
      this.dialog = true;
    },
    editTask(item) {
      this.editedIndex = this.tasks.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },
    deleteTask(item) {
      this.editedIndex = this.tasks.indexOf(item);
      this.editedItem = { ...item };
      this.dialogDelete = true;
    },
    saveTask() {
      if (this.editedIndex > -1) {
        const url = `/api/v1/tasks/${this.editedItem.id}`;
        axios.put(url, this.editedItem)
          .then(response => {
            this.$toastr.success(response.data.message);
            this.tasks.push(response.data.task);
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error updating task:', error);
            this.$toastr.error('Error updating task');
          });
      } else {
        const url = '/api/v1/tasks';
        axios.post(url, this.editedItem)
          .then(response => {
            this.$toastr.success(response.data.message);
            this.fetchTasks();
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error adding task:', error);
            this.$toastr.error('Error adding task');
          });
      }
    },
    deleteTaskConfirm() {
      this.tasks.splice(this.editedIndex, 1);
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
    refreshTasks() {
      this.fetchTasks();
    }
  }
};
</script>
