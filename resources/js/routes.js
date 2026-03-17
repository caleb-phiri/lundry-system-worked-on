import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';
import ClientDashboard from './components/client/Dashboard.vue';
import DriverDashboard from './components/driver/Dashboard.vue';
import AdminDashboard from './components/admin/Dashboard.vue';
import Orders from './components/client/Orders.vue';
import OrderDetails from './components/client/OrderDetails.vue';
import Wallet from './components/client/Wallet.vue';
import Locations from './components/client/Locations.vue';

const routes = [
    // Auth routes
    { path: '/login', component: Login, meta: { guest: true } },
    { path: '/register', component: Register, meta: { guest: true } },
    
    // Client routes
    { path: '/client', component: ClientDashboard, meta: { requiresAuth: true, role: 'client' } },
    { path: '/client/orders', component: Orders, meta: { requiresAuth: true, role: 'client' } },
    { path: '/client/orders/:id', component: OrderDetails, meta: { requiresAuth: true, role: 'client' } },
    { path: '/client/wallet', component: Wallet, meta: { requiresAuth: true, role: 'client' } },
    { path: '/client/locations', component: Locations, meta: { requiresAuth: true, role: 'client' } },
    
    // Driver routes
    { path: '/driver', component: DriverDashboard, meta: { requiresAuth: true, role: 'driver' } },
    { path: '/driver/orders', component: () => import('./components/driver/Orders.vue'), meta: { requiresAuth: true, role: 'driver' } },
    
    // Admin routes
    { path: '/admin', component: AdminDashboard, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/admin/orders', component: () => import('./components/admin/Orders.vue'), meta: { requiresAuth: true, role: 'admin' } },
    { path: '/admin/drivers', component: () => import('./components/admin/Drivers.vue'), meta: { requiresAuth: true, role: 'admin' } },
    { path: '/admin/reports', component: () => import('./components/admin/Reports.vue'), meta: { requiresAuth: true, role: 'admin' } },
    
    // Redirects
    { path: '/', redirect: '/login' },
    { path: '/:pathMatch(.*)*', redirect: '/login' }
];

export default routes;