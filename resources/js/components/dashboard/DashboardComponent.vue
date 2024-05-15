<template>
  <div class="row">
    <div class="col-lg-8 mb-4 order-0">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Hello, {{ user.firstName }}!</h5>
              <p class="mb-4">You have <span class="fw-medium">{{ pending_deals_count }}</span> pending deals today.
                Check them now.</p>
              <router-link to="/deals?status=pending" class="btn btn-sm btn-outline-primary">View Deals</router-link>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src='/assets/img/illustrations/man-with-laptop-light.png' height="140" alt="View Badge User" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="/assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded">
                </div>
                <div class="dropdown">
                  <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Won</span>
              <h3 class="card-title mb-2">{{ dealCount.won }}</h3>
              <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> +2.80%</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="/assets/img/icons/unicons/wallet-info.png" alt="Credit Card" class="rounded">
                </div>
                <div class="dropdown">
                  <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <span>In Progress</span>
              <h3 class="card-title text-nowrap mb-1">{{ dealCount.inProgress }}</h3>
              <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.42%</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
      <div class="card">
        <div class="row row-bordered g-0">
          <div class="col-md-8">
            <h5 class="card-header m-0 me-2 pb-3">Total Deals</h5>
            <div class="px-2">
              <!-- bar graph -->
              <DealsAnnualBarGraph />
            </div>
          </div>
          <div class="col-md-4">
            <div class="card-body">
              <div class="text-center">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="growthReportId"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    2024
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                    <a class="dropdown-item" href="javascript:void(0);">2023</a>
                    <a class="dropdown-item" href="javascript:void(0);">2022</a>
                  </div>
                </div>
              </div>
            </div>
            <div></div>
            <div class="text-center fw-medium pt-3 mb-2">62% Company Growth</div>
            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
              <div class="d-flex">
                <div class="me-2">
                  <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>2022</small>
                  <h6 class="mb-0">{{ deals.length }}</h6>
                </div>
              </div>
              <div class="d-flex">
                <div class="me-2">
                  <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>2023</small>
                  <h6 class="mb-0">{{ deals.length }}</h6>
                </div>
              </div>
            </div>
            <div>
              <DealsRadialBar />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
      <div class="row">
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="/assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded">
                </div>
                <div class="dropdown">
                  <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <span class="d-block mb-1">Lost</span>
              <h3 class="card-title text-nowrap mb-2">{{ dealCount.lost }}</h3>
              <small class="text-danger fw-medium"><i class='bx bx-down-arrow-alt'></i> -14.82%</small>
            </div>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="/assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded">
                </div>
                <div class="dropdown">
                  <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="cardOpt1">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Overall Deals</span>
              <h3 class="card-title mb-2">{{ deals.length }}</h3>
              <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> +28.14%</small>
            </div>
          </div>
        </div>
        <div class="col-12 mb-4">
          <v-card class="mx-auto" max-width="600">
            <v-toolbar color="light">
              <v-btn icon variant="text">
                <v-icon>mdi-menu</v-icon>
              </v-btn>

              <v-toolbar-title>Tasks & Events</v-toolbar-title>

              <v-spacer></v-spacer>

              <v-btn icon variant="text">
                <v-icon>mdi-magnify</v-icon>
              </v-btn>

              <v-btn icon variant="text">
                <v-icon>mdi-view-module</v-icon>
              </v-btn>
            </v-toolbar>

            <v-list>

              <v-list-item v-for="event in events" :key="event.name" :subtitle="event.time" :title="event.event">
                <template v-slot:prepend>
                  <v-avatar color="primary lighten-1">
                    <v-icon color="white">mdi-calendar</v-icon>
                  </v-avatar>
                </template>

                <template v-slot:append>
                  {{ event.time }}
                </template>
              </v-list-item>
            </v-list>
          </v-card>
        </div>

      </div>
    </div>
  </div>

  <div class="row">
    <!-- Deals/Services Statistics -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Service Statistics</h5>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
              <a class="dropdown-item" href="javascript:void(0);">Select All</a>
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            <!-- SERVICE statistics items -->
            <li v-for="(service, index) in serviceStatistics" :key="index" class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span :class="'avatar-initial rounded bg-label-' + service.color"><i
                    :class="'bx ' + service.icon"></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ service.title }}</h6>
                  <small class="text-muted">{{ service.change }}</small>
                </div>
                <div class="user-progress">
                  <small class="fw-medium">{{ service.progress }}</small>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!--/ Deals/Services Statistics -->
    <!-- Overview -->
    <div class="col-md-6 col-lg-4 order-1 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income"
                aria-selected="true">Won</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab">Lost</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab">In Progress</button>
            </li>
          </ul>
        </div>
        <div class="card-body px-0">
          <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
              <div class="d-flex p-4 pt-3">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="assets/img/icons/unicons/wallet.png" alt="User">
                </div>
                <div>
                  <small class="text-muted d-block">Growth Rate</small>
                  <div class="d-flex align-items-center">
                    <small class="text-success fw-medium">
                      <i class='bx bx-chevron-up'></i>
                      +2.9%
                    </small>
                  </div>
                </div>
              </div>
              <div>
                <DealsDoughnut />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Overview -->
    <!-- Transactions -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Most Recent</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
              <a class="dropdown-item" href="#">Last 28 Days</a>
              <a class="dropdown-item" href="#">Last Month</a>
              <a class="dropdown-item" href="#">Last Year</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            <li v-for="(deal, index) in mostRecentDeals" :key="index" class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img :src="getIcon(deal.status)" alt="User" class="rounded">
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0 text-muted">{{ deal.title }}</h6>
                  <small :class=getStatusColorClass(deal.status) class="text-muted d-block mb-1">{{ deal.status
                    }}</small>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">{{ formatDate(deal.created_at) }}</h6>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import DealsAnnualBarGraph from '@/components/charts/DealsAnnualBarGraph.vue';
import DealsDoughnut from '@/components/charts/DealsDoughnut.vue';
import DealsRadialBar from '@/components/charts/DealsRadialBar.vue';


export default {
  name: 'Dashboard',
  components: {
    DealsAnnualBarGraph,
    DealsDoughnut,
    DealsRadialBar
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      user: {
        firstName: this.user.name.split(' ')[0],
        lastName: this.user.name.split(' ')[1],
        email: this.user.email,
        phone: this.user.phone,
      },
      serviceStatistics: [],
      events: [],
      deals: [],
      dealCount: {
        won: 0,
        lost: 0,
        initiated: 0,
        inProgress: 0
      },
      statistics: [],
      pending_deals_count: 0,
      mostRecentDeals: [],
      services_statistics: [],
    }
  },
  mounted() {
    this.fetchStatistics();
    this.fetchServiceStatistics();
    this.fetchEvents();
  },
  methods: {
    fetchDeals() {
      axios.get('/api/v1/deals')
        .then(response => {
          this.dealCount = {
            won: 0,
            lost: 0,
            inProgress: 0
          };
          this.deals = response.data.deals;
          this.deals.forEach(deal => {
            if (deal.status === 'Won') {
              this.dealCount.won++;
            } else if (deal.status === 'Lost') {
              this.dealCount.lost++;
            } else if (deal.status === 'InProgress') {
              this.dealCount.inProgress++;
            }
          });

          console.log(this.deals);
          console.log(this.dealCount);
        })
        .catch(error => {
          console.log(error);
        });
    },
    fetchEvents() {
      axios.get('/api/v1/calendar')
        .then(response => {
          this.events = response.data.events;

          console.log('Fetched events :' + this.events);
        })
        .catch(error => {
          console.log(error);
        });
    },
    fetchServiceStatistics() {
      axios.get('/api/v1/service-statistics')
        .then(response => {
          this.serviceStatistics = response.data.serviceStatistics;
        })
        .catch(error => {
          console.error('Error fetching service statistics:', error);
        });
    },

    fetchStatistics() {
      axios.get('/api/v1/statistics')
        .then(response => {
          this.mostRecentDeals = response.data.recent_deals;

          this.dealCount = {
            won: 0,
            lost: 0,
            inProgress: 0
          };

          this.mostRecentDeals.forEach(deal => {
            if (deal.status === 'Won') {
              this.dealCount.won++;
            } else if (deal.status === 'Lost') {
              this.dealCount.lost++;
            } else if (deal.status === 'InProgress') {
              this.dealCount.inProgress++;
            }
          });

          console.log('Deal counts:', this.dealCount);
        })
        .catch(error => {
          console.error('Error fetching statistics:', error);
        });
    },

    getIcon(status) {
      const iconMap = {
        'Won': '/assets/img/icons/unicons/paypal.png',
        'InProgress': '/assets/img/icons/unicons/wallet.png',
        'Lost': '/assets/img/icons/unicons/wallet.png',
        'Initiated': '/assets/img/icons/unicons/wallet.png',
      };
      return iconMap[status] || '/assets/img/icons/unicons/default.png';
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    },
    getStatusColorClass(status) {
      const colorMap = {
        'Won': 'text-success',
        'Lost': 'text-warning',
        'InProgress': 'text-primary',
      };
      return colorMap[status] || 'text-muted';
    }

  },
}
</script>
