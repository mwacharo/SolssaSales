<template>
  <v-card>
    <v-layout>
      <v-app>
        <v-card>
          <v-row class="my-1">
            <v-chip class="ma-2" color="red" label @click="sortByStatus('Lost')">
              <v-icon icon="mdi-close-circle" start></v-icon>
              Lost: {{ deals_data.lost }}
            </v-chip>
            <v-chip class="ma-2" color="blue" label @click="sortByStatus('Initiated')">
              <v-icon icon="mdi-label" start></v-icon>
              Initiated: {{ deals_data.initiated }}
            </v-chip>
            <v-chip class="ma-2" color="orange darken-2" label @click="sortByStatus('Proposal')">
              <v-icon icon="mdi-progress-wrench" start></v-icon>
              In Progress: {{ deals_data.inProgress }}
            </v-chip>
            <v-chip class="ma-2" color="green darken-2" label @click="sortByStatus('Won')">
              <v-icon icon="mdi-trophy-variant" start></v-icon>
              Won: {{ deals_data.won }}
            </v-chip>
          </v-row>
        </v-card>

        <v-main>
          <v-navigation-drawer v-model="drawer" location="right" temporary :width="400">
            <v-list>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Filter</v-list-item-title>
                  <v-select
                    v-model="selectedService"
                    :items="services"
                    item-title="name"
                    item-value="id"
                    label="Service(s)"
                    placeholder="Select Services"
                    dense
                    multiple
                    chips
                    small-chips
                    deletable-chips
                  ></v-select>

                  <v-select
                    v-model="selectedIndustry"
                    :items="industries"
                    item-title="name"
                    item-value="id"
                    label="Type of Industry"
                    placeholder="Select Industry"
                    dense
                    multiple
                    chips
                    small-chips
                    deletable-chips
                    clearable
                  ></v-select>

                  <v-select
                    v-model="selectedUser"
                    :items="users"
                    item-title="name"
                    item-value="id"
                    label="Sales Rep"
                    placeholder="Select Sales Rep"
                    dense
                    multiple
                    chips
                    small-chips
                    deletable-chips
                    clearable
                  ></v-select>

                  <v-select
                    v-model="selectedBranch"
                    :items="branches"
                    item-title="name"
                    item-value="id"
                    label="Branch"
                    placeholder="Select Branches"
                    dense
                    multiple
                    chips
                    small-chips
                    deletable-chips
                    clearable
                  ></v-select>

                  <v-col cols="12">
                    <v-text-field v-model="close_date" placeholder="Close Date" label="Close Date" type="date">
                    </v-text-field>
                  </v-col>

                  <v-col cols="12">
                    <v-text-field v-model="start_date" placeholder="Start Date" label="Start Date" type="date">
                    </v-text-field>
                  </v-col>

                  <v-col cols="12">
                    <v-text-field v-model="end_date" placeholder="End Date" label="End Date" type="date">
                    </v-text-field>
                  </v-col>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-card-actions class="justify-end">
              <v-btn color="red darken-1" text @click.prevent="closeDrawer">Close</v-btn>
              <v-btn color="blue darken-1" text @click.prevent="searchDeals">Search</v-btn>
            </v-card-actions>
          </v-navigation-drawer>
          <v-toolbar flat>
            <v-toolbar-title>Deals</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>

            <v-tooltip location="bottom">
              <template v-slot:activator="{ props }">
                <v-btn icon v-bind="props" @click="DealImport(item)" variant="text">
                  <v-icon color="success"> mdi-book </v-icon>
                </v-btn>
              </template>

              <span> Import Deal</span>
            </v-tooltip>

            <v-btn color="primary" @click="addDeal">
              <v-icon>mdi-plus</v-icon>
            </v-btn>

            <v-btn color="primary" @click="refreshDeals">
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
            <v-btn color="primary" @click.stop="drawer = !drawer">
              <v-icon>mdi-filter</v-icon>
            </v-btn>
          </v-toolbar>

          <v-text-field v-model="search" label="Search" clearable @input="filterDeals" dense></v-text-field>
          <v-responsive>
            <v-data-table :headers="headers" :items="SearchDeals">
              <template v-slot:item.status="{ item }">
                <v-chip :color="getStatusColor(item.status)" @click="openStatusModal(item)">
                  {{ item.status }}
                </v-chip>
              </template>

              <template v-slot:item.priority="{ item }">
                <v-chip :color="getPriorityColor(item.priority)">
                  {{ item.priority }}
                </v-chip>
              </template>

              <template v-slot:item.actions="{ item }">
                <div class="d-flex align-center">
                  <v-icon class="mx-1" color="blue" @click="editDeal(item)">mdi-pencil</v-icon>
                  <!-- <v-icon class="mx-1" color="green" @click="editDeal(item)">mdi-history</v-icon> -->
                  <v-icon class="mx-1" color="red" @click="deleteDeal(item)">mdi-delete</v-icon>
                </div>
              </template>
            </v-data-table>
          </v-responsive>

          <v-dialog v-model="dialog" max-width="800">
            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field v-model="editedItem.title" label="Title" prepend-icon="mdi-account"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field v-model="editedItem.email" label="Email" prepend-icon="mdi-email"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="editedItem.website"
                        label="Website (optional)"
                        prepend-icon="mdi-web"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="editedItem.phone"
                        label="Phone (optional)"
                        prepend-icon="mdi-phone"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="editedItem.contact_person"
                        label="Contact Person (optional)"
                        prepend-icon="mdi-account"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="editedItem.close_date"
                        label="Close Date"
                        prepend-icon="mdi-calendar"
                        type="date"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-textarea
                        v-model="editedItem.comment"
                        label="Comment (optional)"
                        prepend-icon="mdi-comment"
                      ></v-textarea>
                    </v-col>
                    <v-col cols="12">
                      <v-subheader>Services</v-subheader>
                      <v-row>
                        <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                          <v-checkbox
                            v-model="editedItem.service_ids"
                            :label="service.name"
                            :value="service.id"
                            multiple
                          >
                          </v-checkbox>
                        </v-col>
                      </v-row>
                    </v-col>
                    <v-col cols="12">
                      <v-subheader>Industries</v-subheader>
                      <v-row>
                        <v-col cols="12" md="4" v-for="industry in industries" :key="industry.id">
                          <v-checkbox
                            v-model="editedItem.industry_ids"
                            :label="industry.name"
                            :value="industry.id"
                            multiple
                          ></v-checkbox>
                        </v-col>
                      </v-row>
                    </v-col>
                    <v-col cols="12">
                      <v-subheader>Branches</v-subheader>
                      <v-row>
                        <v-col cols="12" md="4" v-for="branch in branches" :key="branch.id">
                          <v-checkbox
                            v-model="editedItem.branch_ids"
                            :label="branch.name"
                            :value="branch.id"
                            multiple
                          ></v-checkbox>
                        </v-col>
                      </v-row>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="editedItem.deal_source"
                        label="Deal Source"
                        prepend-icon="mdi-source-branch"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        v-model="editedItem.priority"
                        :items="['High', 'Medium', 'Low']"
                        label="Priority"
                        prepend-icon="mdi-flag"
                        dense
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions class="justify-end">
                <v-btn color="red darken-1" text @click.prevent="closeDialog">Close</v-btn>
                <v-btn color="blue darken-1" text @click.prevent="saveDeal">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-alert text="Are you sure you want to delete this item?!" title="Delete Deal" type="error">
              <template v-slot:actions>
                <v-btn color="blue darken-1" text @click="closeDelete">
                  <v-icon left>mdi-close</v-icon>
                  Close
                </v-btn>
                <v-btn color="blue darken-1" text @click="deleteDealConfirm">
                  <v-icon left>mdi-check</v-icon>
                  OK
                </v-btn>
              </template>
            </v-alert>
          </v-dialog>
          <v-dialog v-model="statusModal" max-width="400">
            <v-card>
              <v-card-title>Select Status</v-card-title>
              <v-card-text>
                <v-combobox
                  v-model="selectedStatus"
                  :items="statusOptions"
                  outlined
                  :value="selectedDeal.status"
                ></v-combobox>
              </v-card-text>
              <v-card-actions class="justify-end">
                <v-btn color="red" text @click="statusModal = false">Close</v-btn>
                <v-btn color="blue darken-1" text @click="submitStatus">Submit</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-main>
      </v-app>
    </v-layout>

    <DealsImport ref="dealsImportComponent" />

  </v-card>
</template>

<script>
import DealsImport from '@/components/deals/DealsImport.vue';

export default {

  components: {
     DealsImport
  },
  
  props: {
    user_id: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      search: '',
      // contains industry ,service ,users and branch  values
      filter: [],
      headers: [
        { title: '#', value: 'index' },
        { title: 'Title', value: 'title' },
        { title: 'Contact Person', value: 'contact_person' },
        { title: 'Email', value: 'email' },
        { title: 'Phone', value: 'phone' },

        // { title: 'Website', value: 'website' },
        { title: 'Sales Rep.', value: 'user.name' },
        // { title: 'Branch', value: 'branches.name'},

        // { title: 'Deal Source', value: 'deal_source' },
        { title: 'Comments', value: 'comment' },
        { title: 'Status', value: 'status' },
        { title: 'Priority', value: 'priority' },
        { title: 'Close Date', value: 'close_date' },
        { title: 'Actions', value: 'actions', sortable: false }
      ],
      deals_data: {
        initiated: 0,
        lost: 0,
        inProgress: 0,
        won: 0,
        total: 0
      },
      deals: [],
      statusModal: false,
      selectedDeal: null,
      statusOptions: ['Initiated', 'Won', 'Lost','Proposal'],
      editedIndex: -1,
      editedItem: {
        title: '',
        email: '',
        user_id: this.user_id,
        website: '',
        service_ids: [],
        branch_ids: [],
        industry_ids: [],
        deal_source: '',
        phone: '',
        status: '',
        comment: '',
        priority: '',
        contact_person: '',
        close_date: ''
      },
      // nav
      drawer: false,
      group: null,
      users: [],

      defaultItem: {
        title: '',
        email: '',
        website: '',
        service_ids: [],
        branch_ids: [],
        industry_ids: [],
        deal_source: '',
        user_id: this.user_id,
        phone: '',
        status: '',
        comment: '',
        priority: '',
        contact_person: '',
        close_date: ''
      },
      branches: [],
      services: [],
      industries: [],
      dialog: false,
      dialogDelete: false
    };
  },
  computed: {
    SearchDeals() {
      if (!this.search) return this.deals;
      return this.deals.filter(
        deal =>
          deal.title.toLowerCase().includes(this.search.toLowerCase()) ||
          deal.email.toLowerCase().includes(this.search.toLowerCase()) ||
          deal.phone.toLowerCase().includes(this.search.toLowerCase()) ||
          deal.user.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },

    formTitle() {
      return this.editedIndex === -1 ? 'New Deal' : 'Edit Deal';
    }
  },
  created() {
    this.fetchDeals();
    this.fetchBranches();
    this.fetchServices();
    this.fetchIndustries();
    this.fetchUsers();
  },
  methods: {
    fetchDeals() {
      const url = '/api/v1/deals';
      axios
        .get(url)
        .then(response => {
          this.deals = response.data.deals.map((deal, index) => ({
            ...deal,
            index: index + 1,
            statusIcon: deal.status ? 'mdi-checkbox-marked-circle' : 'mdi-checkbox-blank-circle-outline'
          }));

          // Calculate values for deals_data
          this.calculateDealsData();
        })
        .catch(error => {
          console.log(error);
        });
    },

    fetchUsers() {
      const url = '/api/v1/users';

      axios
        .get(url)
        .then(response => {
          this.users = response.data.data.map((user, index) => ({
            ...user,
            index: index + 1
          }));
        })
        .catch(error => {
          console.log(error);
        });
    },
    calculateDealsData() {
      this.deals_data.initiated = this.deals.filter(deal => deal.status === 'Initiated').length;
      this.deals_data.lost = this.deals.filter(deal => deal.status === 'Lost').length;
      this.deals_data.inProgress = this.deals.filter(deal => deal.status === 'Proposal').length;
      this.deals_data.won = this.deals.filter(deal => deal.status === 'Won').length;
      // Calculate total
      this.deals_data.total = this.deals.length;
    },

    fetchBranches() {
      const url = '/api/v1/branches';
      axios
        .get(url)
        .then(response => {
          this.branches = response.data.branches;
        })
        .catch(error => {
          console.log(error);
        });
    },
    fetchServices() {
      const url = '/api/v1/services';
      axios
        .get(url)
        .then(response => {
          this.services = response.data.services;
        })
        .catch(error => {
          console.log(error);
        });
    },
    fetchIndustries() {
      const url = '/api/v1/industries';
      axios
        .get(url)
        .then(response => {
          this.industries = response.data.industries;
        })
        .catch(error => {
          console.log(error);
        });
    },
    addDeal() {
      this.editedItem = { ...this.defaultItem };
      this.dialog = true;
    },
    
    DealImport() {
  this.$refs.dealsImportComponent.show();
  this.importDialog = true;
},
    openStatusModal(item) {
      this.selectedDeal = item;
      this.statusModal = true;
    },

    getStatusColor(status) {
      switch (status.toLowerCase()) {
        case 'initiated':
          return 'primary';
        case 'inprogress':
          return 'orange darken-2';
        case 'won':
          return 'green darken-2';
        case 'lost':
          return 'red darken-2';
        default:
          return 'grey darken-2';
      }
    },

    getPriorityColor(priority) {
      switch (priority.toLowerCase()) {
        case 'high':
          return 'green';
        case 'medium':
          return 'blue';
        case 'low':
          return 'red ';
        default:
          return 'grey';
      }
    },
    prepareEditedItem(deal) {
      this.editedItem = { ...deal }; // Create a new object to avoid mutating the original deal
      this.editedItem.service_ids = deal.services.map(service => service.id);
      this.editedItem.industry_ids = deal.industries.map(industry => industry.id);
      this.editedItem.branch_ids = deal.branches.map(branch => branch.id);
    },

    editDeal(deal) {
      this.editedIndex = this.deals.indexOf(deal);
      this.editedItem = { ...deal };
      this.prepareEditedItem(deal);
      this.dialog = true;
    },
    deleteDeal(deal) {
      this.editedIndex = this.deals.indexOf(deal);
      this.editedItem = { ...deal };
      this.dialogDelete = true;
    },

    deleteDealConfirm() {
      axios
        .delete(`/api/v1/deals/${this.editedItem.id}`)
        .then(response => {
          this.$toastr.success(response.data.message);
          this.fetchDeals();
          this.closeDelete();
        })
        .catch(error => {
          console.error('Error deleting deal:', error);
          this.$toastr.error('Failed to delete the deal!');
        });
    },
    saveDeal() {
      this.editedItem.user_id = this.user_id;

      if (this.editedIndex > -1) {
        const url = `/api/v1/deals/${this.editedItem.id}`;
        axios
          .put(url, {
            ...this.editedItem,
            service_ids: this.editedItem.service_ids,
            branch_ids: this.editedItem.branch_ids,
            industries_ids: this.editedItem.industry_ids
          }) // <-- Added closing parenthesis here
          .then(response => {
            this.fetchDeals();
            this.$toastr.success('Deal updated successfully');
            this.closeDialog();
          })
          .catch(error => {
            this.$toastr.error('Error updating deal');
            console.error('Error updating deal:', error);
          });
      } else {
        const url = '/api/v1/deals';

        axios
          .post('/api/v1/deals', {
            branch_ids: this.editedItem.branch_ids,
            service_ids: this.editedItem.service_ids,
            industry_ids: this.editedItem.industry_ids,

            title: this.editedItem.title,
            email: this.editedItem.email,
            website: this.editedItem.website,
            deal_source: this.editedItem.deal_source,
            phone: this.editedItem.phone,
            status: this.editedItem.status,
            comment: this.editedItem.comment,
            priority: this.editedItem.priority,
            contact_person: this.editedItem.contact_person,
            close_date: this.editedItem.close_date,
            user_id: this.editedItem.user_id
          })
          .then(response => {
            this.$toastr.success(response.data.message);
            this.fetchDeals();
            this.closeDialog();
          })
          .catch(error => {
            this.$toastr.error('Error adding deal');
            console.error('Error adding deal:', error);
          });
      }
    },
    sortByStatus(status) {
      this.deals = this.deals.filter(deal => deal.status === status);
    },
    deleteDealConfirm() {
      this.deals.splice(this.editedIndex, 1);
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
    refreshDeals() {
      this.fetchDeals();
    },
    toggleDealStatus(deal) {
      deal.status = !deal.status;
    },

    closeDrawer() {
      this.drawer = false;
    },
    searchDeals() {
      const payload = {
        service_ids: this.selectedService,
        industry_ids: this.selectedIndustry,
        user_id: this.selectedUser,
        branch_ids: this.selectedBranch,
        start_date: this.start_date,
        end_date: this.end_date,
        close_date: this.close_date
      };

      axios
        .post('/api/deals/search', payload)
        .then(response => {
          this.deals = response.data.deals.map((deal, index) => ({
            ...deal,
            index: index + 1
          }));
          // this.calculateDealsData();
        })
        .catch(error => {
          console.error('Error searching deals:', error);
        });
    },
    submitStatus() {
      if (!this.selectedStatus) {
        this.$toastr.error('Please select a status');
        return;
      }
      const url = `/api/v1/deals/${this.selectedDeal.id}/update-status`;
      const payload = {
        status: this.selectedStatus,
        user_id: this.user_id
      };
      axios
        .put(url, payload)
        .then(response => {
          this.$toastr.success(response.data.message);
          this.fetchDeals();
          this.statusModal = false;
          this.selectedDeal.status = this.selectedStatus;
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.message) {
            this.$toastr.error(error.response.data.message);
          } else {
            this.$toastr.error('Failed to update status');
          }
          console.error('Error updating status:', error);
          this.selectedDeal.status = this.selectedStatus;
        });
    }
  }
};
</script>
