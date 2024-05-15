<template>
  <v-container-fluid>
    <v-toolbar flat>
      <v-toolbar-title>Events Calendar</v-toolbar-title>
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="addEvent">
        <v-icon>mdi-plus</v-icon>
        Add Event
      </v-btn>
      <v-btn color="primary" @click="refreshEvents">
        <v-icon>mdi-refresh</v-icon>
        Refresh
      </v-btn>
    </v-toolbar>
    <v-text-field v-model="search" label="Search" clearable @input="filterEvents" dense></v-text-field>
    <v-data-table :headers="headers" :items="filteredEvents">
      <template v-slot:item.status="{ item }">
        <v-icon :color="item.status ? 'success' : 'error'">
          {{ item.status ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline' }}
        </v-icon>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon color="blue" @click="editEvent(item)">mdi-pencil</v-icon>
        <v-icon color="red" @click="deleteEvent(item)">mdi-delete</v-icon>
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
                <v-text-field prepend-icon="mdi-calendar" v-model="editedItem.event" placeholder="Event"
                  label="Event Name"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-menu v-model="dateMenu" :close-on-content-click="false" :nudge-right="40"
                  transition="scale-transition" offset-y>
                  <template v-slot:activator="{ on }">
                    <v-text-field v-model="editedItem.date" label="Date" type="date" prepend-icon="mdi-calendar"
                      v-on="on"></v-text-field>
                  </template>
                  <v-date-picker v-model="editedItem.date" no-title scrollable></v-date-picker>
                </v-menu>
              </v-col>

              <v-col cols="12">
                <v-menu v-model="timeMenu" :close-on-content-click="false" :nudge-right="40"
                  transition="scale-transition" offset-y>
                  <template v-slot:activator="{ on }">
                    <v-text-field v-model="editedItem.time" placeholder="11:00:00" label="Time" prepend-icon="mdi-clock"
                      v-on="on">
                    </v-text-field>
                  </template>
                  <v-time-picker v-model="editedItem.time" format="24hr" scrollable></v-time-picker>
                </v-menu>
              </v-col>

              <v-col cols="12">
                <v-textarea prepend-icon="mdi-comment" v-model="editedItem.notes" placeholder="Notes"
                  label="Notes"></v-textarea>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="justify-end">
          <v-btn color="red" text @click.prevent="closeDialog">close</v-btn>
          <v-btn color="blue darken-1" text @click.prevent="saveEvent">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-alert text="Are you sure you want to delete this event?!" title="Delete Event" type="error">
        <template v-slot:actions>
          <v-btn color="blue darken-1" text @click="closeDelete">
            <v-icon left>mdi-close</v-icon>
            Cancel
          </v-btn>
          <v-btn color="blue darken-1" text @click="deleteEventConfirm">
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
        { title: 'Event', value: 'event' },
        { title: 'Date', value: 'date' },
        { title: 'Time', value: 'time' },
        { title: 'Completed', value: 'status' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      events: [],
      editedIndex: -1,
      editedItem: {
        event: '',
        date: null,
        time: null,
        status: false,
        notes: ''
      },
      defaultItem: {
        event: '',
        date: null,
        time: null,
        status: false,
        notes: ''
      },
      dialog: false,
      dialogDelete: false,
      dateMenu: false,
      timeMenu: false
    };
  },
  computed: {
    filteredEvents() {
      if (!this.search) return this.events;
      return this.events.filter(event =>
        event.event.toLowerCase().includes(this.search.toLowerCase()) ||
        event.notes.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New Event' : 'Edit Event';
    }
  },
  created() {
    this.fetchEvents();
  },
  methods: {
    fetchEvents() {
      const url = '/api/v1/calendar';
      axios.get(url)
        .then(response => {
          if (Array.isArray(response.data)) {
            this.events = response.data.map((event, index) => ({
              ...event,
              index: index + 1,
              statusIcon: event.status ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline'
            }));
          } else {
            console.error('Error fetching events: Response data is not an array.');
          }
        })
        .catch(error => {
          console.error('Error fetching events:', error);
        });
    },

    addEvent() {
      this.editedItem = { ...this.defaultItem };
      this.dialog = true;
    },
    editEvent(item) {
      this.editedIndex = this.events.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },
    deleteEvent(item) {
      this.editedIndex = this.events.indexOf(item);
      this.editedItem = { ...item };
      this.dialogDelete = true;
    },
    saveEvent() {
      if (this.editedIndex > -1) {
        const url = `/api/v1/calendar/${this.editedItem.id}`;
        axios.put(url, this.editedItem)
          .then(response => {
            this.fetchEvents();
            this.$toastr.success('Event updated successfully');
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error updating event:', error);
            this.$toastr.error('Error updating event');
          });
      } else {
        const url = '/api/v1/calendar';
        axios.post(url, this.editedItem)
          .then(response => {
            this.$toastr.success('Event created successfully');
            this.events.push(response.data);
            this.closeDialog();
          })
          .catch(error => {
            console.error('Error adding event:', error);
            this.$toastr.error('Error adding event');
          });
      }
    },
    deleteEventConfirm() {
      this.events.splice(this.editedIndex, 1);
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
    refreshEvents() {
      this.fetchEvents();
    },
    toggleStatus(item) {
      item.status = !item.status;
    },
    filterEvents() {
    }
  }
};
</script>
