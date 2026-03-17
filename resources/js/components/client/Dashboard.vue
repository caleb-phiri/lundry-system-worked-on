<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link class="navbar-brand" to="/client">
          <i class="fas fa-tshirt me-2"></i>Laundry Logistics
        </router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/client/orders">
                <i class="fas fa-shopping-bag me-1"></i>My Orders
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/client/wallet">
                <i class="fas fa-wallet me-1"></i>Wallet 
                <span v-if="walletBalance !== null">(K{{ walletBalance }})</span>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/client/locations">
                <i class="fas fa-map-marker-alt me-1"></i>Locations
              </router-link>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user me-1"></i>{{ user ? user.name : 'User' }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" @click.prevent="logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading dashboard...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ error }}
        <button class="btn btn-sm btn-outline-danger ms-3" @click="loadDashboardData">Retry</button>
      </div>

      <!-- Dashboard Content -->
      <template v-else>
        <!-- Welcome Banner -->
        <div v-if="user && user.referral_code" class="alert alert-success mb-4">
          <h5><i class="fas fa-gift me-2"></i>Refer & Earn!</h5>
          <p class="mb-0">
            Share your referral code <strong>{{ user.referral_code }}</strong> with friends. 
            Get K20 when they place their first order!
          </p>
        </div>

        <div class="row">
          <div class="col-md-8">
            <!-- Order Form Component -->
            <order-form @orderCreated="refreshOrders"></order-form>
          </div>
          
          <div class="col-md-4">
            <!-- Recent Orders -->
            <div class="card">
              <div class="card-header bg-white">
                <h5 class="mb-0">Recent Orders</h5>
              </div>
              <div class="card-body">
                <div v-if="recentOrders.length === 0" class="text-muted text-center py-4">
                  <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                  <p>No orders yet</p>
                </div>
                <div v-else>
                  <div v-for="order in recentOrders" :key="order.id" class="mb-3">
                    <div class="border rounded p-3">
                      <div class="d-flex justify-content-between align-items-start">
                        <div>
                          <h6 class="mb-1">Order #{{ order.order_number }}</h6>
                          <p class="mb-1 small text-muted">{{ formatDate(order.created_at) }}</p>
                        </div>
                        <span :class="'badge bg-' + getStatusColor(order.status)">
                          {{ formatStatus(order.status) }}
                        </span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold">K{{ order.total }}</span>
                        <router-link :to="'/client/orders/' + order.id" class="btn btn-sm btn-outline-primary">
                          View Details
                        </router-link>
                      </div>
                    </div>
                  </div>
                  <div class="text-center mt-3">
                    <router-link to="/client/orders" class="btn btn-link">View All Orders</router-link>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
              <div class="card-header bg-white">
                <h5 class="mb-0">Quick Stats</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-6 mb-3">
                    <h3 class="text-primary">{{ stats.totalOrders || 0 }}</h3>
                    <small class="text-muted">Total Orders</small>
                  </div>
                  <div class="col-6 mb-3">
                    <h3 class="text-success">{{ stats.completedOrders || 0 }}</h3>
                    <small class="text-muted">Completed</small>
                  </div>
                  <div class="col-6">
                    <h3 class="text-info">K{{ stats.totalSpent || 0 }}</h3>
                    <small class="text-muted">Total Spent</small>
                  </div>
                  <div class="col-6">
                    <h3 class="text-warning">K{{ stats.referralEarnings || 0 }}</h3>
                    <small class="text-muted">Referral Earnings</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import OrderForm from './OrderForm.vue';

export default {
  name: 'ClientDashboard',
  components: {
    OrderForm
  },
  data() {
    return {
      user: null,
      walletBalance: null,
      recentOrders: [],
      stats: {
        totalOrders: 0,
        completedOrders: 0,
        totalSpent: 0,
        referralEarnings: 0
      },
      loading: true,
      error: null
    };
  },
  async mounted() {
    await this.initialize();
  },
  methods: {
    async initialize() {
      try {
        // Get user from localStorage
        const userStr = localStorage.getItem('user');
        if (userStr) {
          this.user = JSON.parse(userStr);
        } else {
          // Try to fetch user from API if not in localStorage
          await this.fetchUser();
        }
        
        await this.loadDashboardData();
      } catch (err) {
        console.error('Initialization error:', err);
        this.error = 'Failed to initialize dashboard';
      } finally {
        this.loading = false;
      }
    },
    
    async fetchUser() {
      try {
        const response = await axios.get('/me');
        this.user = response.data;
        localStorage.setItem('user', JSON.stringify(response.data));
      } catch (err) {
        console.error('Failed to fetch user:', err);
        throw err;
      }
    },
    
    async loadDashboardData() {
      this.loading = true;
      this.error = null;
      
      try {
        const [ordersResponse, walletResponse, statsResponse] = await Promise.all([
          axios.get('/orders?limit=5').catch(err => {
            console.warn('Orders fetch failed:', err);
            return { data: { data: [] } };
          }),
          axios.get('/wallet/balance').catch(err => {
            console.warn('Wallet fetch failed:', err);
            return { data: { balance: 0 } };
          }),
          axios.get('/client/stats').catch(err => {
            console.warn('Stats fetch failed:', err);
            return { data: {
              totalOrders: 0,
              completedOrders: 0,
              totalSpent: 0,
              referralEarnings: 0
            }};
          })
        ]);
        
        this.recentOrders = ordersResponse.data.data || [];
        this.walletBalance = walletResponse.data.balance || 0;
        this.stats = statsResponse.data || this.stats;
        
      } catch (err) {
        console.error('Failed to load dashboard data:', err);
        this.error = 'Failed to load some data. Please refresh the page.';
      } finally {
        this.loading = false;
      }
    },
    
    refreshOrders() {
      this.loadDashboardData();
    },
    
    formatDate(date) {
      if (!date) return 'N/A';
      try {
        return new Date(date).toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      } catch (err) {
        return 'Invalid date';
      }
    },
    
    formatStatus(status) {
      if (!status) return 'Unknown';
      return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    
    getStatusColor(status) {
      const colors = {
        'pickup_scheduled': 'warning',
        'driver_assigned': 'info',
        'collected': 'primary',
        'in_laundry': 'secondary',
        'out_for_delivery': 'info',
        'delivered': 'success',
        'cancelled': 'danger'
      };
      return colors[status] || 'secondary';
    },
    
    async logout() {
      try {
        await axios.post('/logout');
      } catch (err) {
        console.error('Logout error:', err);
      } finally {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
        this.$router.push('/login');
      }
    }
  }
};
</script>

<style scoped>
.card {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 1rem;
}

.badge {
  font-size: 0.75rem;
  padding: 0.35em 0.65em;
}

.btn-link {
  text-decoration: none;
}

.btn-link:hover {
  text-decoration: underline;
}

.navbar {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.dropdown-menu {
  min-width: 120px;
}
</style>