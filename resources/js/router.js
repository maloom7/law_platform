import Vue from 'vue';
import Router from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import LegalCases from './components/LegalCases.vue';
import Tasks from './components/Tasks.vue';
import Contracts from './components/Contracts.vue';
import Clients from './components/Clients.vue';
import Invoices from './components/Invoices.vue';
import Services from './components/Services.vue';
import PerformanceIndicators from './components/PerformanceIndicators.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    { path: '/', component: Dashboard },
    { path: '/legal-cases', component: LegalCases },
    { path: '/tasks', component: Tasks },
    { path: '/contracts', component: Contracts },
    { path: '/clients', component: Clients },
    { path: '/invoices', component: Invoices },
    { path: '/services', component: Services },
    { path: '/performance-indicators', component: PerformanceIndicators },
  ],
});