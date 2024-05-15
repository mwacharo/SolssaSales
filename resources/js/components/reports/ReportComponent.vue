<template>
  <v-container-fluid>
    <v-card class="elevation-2 report-card">
      <v-card-title class="headline">Generate Sales Report</v-card-title>
      <v-form @submit.prevent="generateReport">
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-select
                v-model="selectedSalesPerson"
                :items="salesPersons"
                item-value="id"
                item-title="name"
                label="Select Sales Person"
                outlined
                dense
                multiple
                prepend-icon="mdi-account"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="selectedBranchId"
                :items="branches"
                item-value="id"
                item-title="name"
                label="Select a Branch"
                outlined
                dense
                multiple
                prepend-icon="mdi-domain"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <!-- <v-text-field
                    v-model="startSelectedDate"
                    label="Select Start Date"
                    outlined
                    dense
                    prepend-icon="mdi-calendar"
                    readonly
                    v-on="on"
                    type="date"
                  ></v-text-field> -->

              <v-text-field
                v-model="startSelectedDate"
                label="Close Date"
                prepend-icon="mdi-calendar"
                type="date"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="endSelectedDate"
                label="Select End Date"
                prepend-icon="mdi-calendar"
                type="date"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="selectedStatus"
                :items="statuses"
                label="Select Status"
                outlined
                dense
                multiple
                prepend-icon="mdi-progress-check"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="selectedPriority"
                :items="priority"
                label="Priority"
                outlined
                dense
                multiple
                prepend-icon="mdi-flag"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="selectedIndustry"
                :items="industries"
                item-title="name"
                label="Select Industry"
                outlined
                multiple
                dense
                prepend-icon="mdi-domain"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="selectedService"
                :items="services"
                item-title="name"
                label="Select Services"
                outlined
                multiple
                dense
                prepend-icon="mdi-domain"
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
        <v-card-actions>
          <v-btn type="submit" color="primary" class="mr-4">
            <v-icon left>mdi-file-document</v-icon>
            Generate Report
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-container-fluid>
</template>

<script>
import axios from 'axios';
export default {
  props: {
    user_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      // users:[],
      selectedSalesPerson: null,
      selectedBranchId: null,
      selectedService: null,
      services: [], // Populate this array with service data
      salesPersons: [], // Populate this array with sales person data
      startSelectedDate: null,
      endSelectedDate: null,

      // dateMenu: false,
      selectedStatus: null,
      statuses: ['Initiated', 'InProgress', 'Won', 'Lost'], // Add more statuses if needed
      priority: ['Low', 'High', 'Medium'],
      selectedIndustry: null,
      industries: [], // Populate this array with industry data
      service_ids: [],
      branch_ids: [],
      branches: [], // Initialize as an empty array
      selectedPriority: []
    };
  },
  created() {
    this.fetchUsers();
    this.fetchBranches();
    this.fetchServices();
    this.fetchIndustries();
  },
  methods: {
    generateReport() {
      axios
        .post('/api/reports/generate', {
          sales_person_id: this.selectedSalesPerson,
          branch_id: this.selectedBranchId,
          status: this.selectedStatus,
          industry_id: this.selectedIndustry,
          service_id: this.selectedService,
          start_date: this.startSelectedDate,
          end_date: this.endSelectedDate,
          priority: this.selectedPriority
        })
        .then(response => {
          console.log(response);
          // Use the URL from the response to trigger a file download
          if (response.data.url) {
            window.location.href = response.data.url; // Redirect to trigger download
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
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

    fetchUsers() {
      const url = '/api/v1/users';
      axios
        .get(url)
        .then(response => {
          // Assuming the response data structure matches the provided example
          if (response.data.message === 'Users fetched successfully') {
            this.salesPersons = response.data.data.map(user => ({
              id: user.id,
              name: user.name
            }));
            console.log('Users fetched successfully:', this.salesPersons);
          } else {
            console.error('Error fetching users:', response.data.message);
          }
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.report-card {
  width: 100%;
  margin: 0 auto;
  padding: 24px;
}
</style>
