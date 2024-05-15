import './bootstrap'
import { createApp } from 'vue'
import ToastrPlugin from './toastr-plugin'
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp({})

app.use(VCalendar, {})
app.use(vuetify)
app.use(ToastrPlugin)

import DashboardComponent from './components/dashboard/DashboardComponent.vue';
import BranchComponent from './components/branches/BranchComponent.vue';
import CalendarComponent from './components/calendars/CalendarComponent.vue';
import DealComponent from './components/deals/DealComponent.vue';
import UserComponent from './components/users/UserComponent.vue';
import TaskComponent from './components/tasks/TaskComponent.vue';
import IndustryComponent from './components/industries/IndustryComponent.vue';
import ServiceComponent from './components/services/ServiceComponent.vue';
import ReportComponent from './components/reports/ReportComponent.vue';


app.component('dashboard-component', DashboardComponent);
app.component('branch-component', BranchComponent);
app.component('calendar-component', CalendarComponent);
app.component('deal-component', DealComponent);
app.component('user-component', UserComponent);
app.component('task-component', TaskComponent);
app.component('industry-component', IndustryComponent);
app.component('service-component', ServiceComponent);
app.component('report-component', ReportComponent);


app.mount('#app');
