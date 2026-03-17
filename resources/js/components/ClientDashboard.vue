<template>
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link class="navbar-brand" to="/client">Laundry Logistics</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/client/orders">My Orders</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/client/wallet">Wallet</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/client/locations">Locations</router-link>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h5>Create New Order</h5>
            </div>
            <div class="card-body">
              <form @submit.prevent="createOrder">
                <!-- Location Selection -->
                <div class="mb-3">
                  <label class="form-label">Pickup Location</label>
                  <select v-model="form.pickup_location_id" class="form-select" required>
                    <option value="">Select pickup location</option>
                    <option v-for="location in locations" :key="location.id" :value="location.id">
                      {{ location.name }} - {{ location.address }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Delivery Location</label>
                  <select v-model="form.delivery_location_id" class="form-select" required>
                    <option value="">Select delivery location</option>
                    <option v-for="location in locations" :key="location.id" :value="location.id">
                      {{ location.name }} - {{ location.address }}
                    </option>
                  </select>
                </div>

                <!-- Basket Selection -->
                <div class="mb-3">
                  <label class="form-label">Basket Size</label>
                  <select v-model="form.basket_id" class="form-select" @change="calculatePrice" required>
                    <option value="">Select basket size</option>
                    <option v-for="basket in baskets" :key="basket.id" :value="basket.id">
                      {{ basket.name }} ({{ basket.size }}) - K{{ basket.base_price }}
                    </option>
                  </select>
                </div>

                <!-- Special Items -->
                <div class="mb-3">
                  <label class="form-label">Special Items (Optional)</label>
                  <div v-for="item in specialItems" :key="item.id" class="form-check">
                    <input 
                      type="checkbox" 
                      class="form-check-input" 
                      :id="'item-' + item.id"
                      :value="item.id"
                      v-model="form.special_items"
                      @change="calculatePrice"
                    >
                    <label class="form-check-label" :for="'item-' + item.id">
                      {{ item.name }} (+K{{ item.surcharge }})
                    </label>
                  </div>
                </div>

                <!-- Price Display -->
                <div v-if="pricing" class="alert alert-info">
                  <h6>Price Breakdown:</h6>
                  <p class="mb-1">Subtotal: K{{ pricing.subtotal }}</p>
                  <p class="mb-1">Surcharges: K{{ pricing.surcharges }}</p>
                  <h5 class="mt-2">Total: K{{ pricing.total }}</h5>
                </div>

                <!-- Schedule -->
                <div class="mb-3">
                  <label class="form-label">Pickup Date & Time</label>
                  <input 
                    type="datetime-local" 
                    v-model="form.pickup_scheduled_at" 
                    class="form-control" 
                    required
                  >
                </div>

                <!-- Payment Options -->
                <div class="mb-3">
                  <label class="form-label">Payment Method</label>
                  <select v-model="form.payment_method" class="form-select" required>
                    <option value="">Select payment method</option>
                    <option value="mtn">MTN Mobile Money</option>
                    <option value="airtel">Airtel Money</option>
                    <option value="zamtel">Zamtel Money</option>
                    <option value="wallet">Wallet Balance (K{{ walletBalance }})</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Payment Type</label>
                  <div class="form-check">
                    <input 
                      type="radio" 
                      class="form-check-input" 
                      v-model="form.payment_type" 
                      value="prepaid"
                      id="prepaid"
                    >
                    <label class="form-check-label" for="prepaid">Pay Before Collection</label>
                  </div>
                  <div class="form-check">
                    <input 
                      type="radio" 
                      class="form-check-input" 
                      v-model="form.payment_type" 
                      value="postpaid"
                      id="postpaid"
                    >
                    <label class="form-check-label" for="postpaid">Pay After Collection</label>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Special Instructions</label>
                  <textarea v-model="form.special_instructions" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="loading">
                  {{ loading ? 'Creating...' : 'Create Order' }}
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
              <div v-if="recentOrders.length === 0" class="text-muted">
                No recent orders
              </div>
              <div v-for="order in recentOrders" :key="order.id" class="mb-3">
                <div class="card">
                  <div class="card-body">
                    <h6 class="card-title">Order #{{ order.order_number }}</h6>
                    <p class="mb-1">Status: 
                      <span :class="'badge bg-' + getStatusColor(order.status)">
                        {{ formatStatus(order.status) }}
                      </span>
                    </p>
                    <p class="mb-1">Total: K{{ order.total }}</p>
                    <p class="mb-1">Payment: 
                      <span :class="'badge bg-' + getPaymentStatusColor(order.payment_status)">
                        {{ order.payment_status }}
                      </span>
                    </p>
                    <router-link :to="'/client/orders/' + order.id" class="btn btn-sm btn-outline-primary">
                      View Details
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      locations: [],
      baskets: [],
      specialItems: [],
      recentOrders: [],
      walletBalance: 0,
      pricing: null,
      loading: false,
      form: {
        pickup_location_id: '',
        delivery_location_id: '',
        basket_id: '',
        special_items: [],
        pickup_scheduled_at: '',
        payment_method: '',
        payment_type: 'prepaid',
        special_instructions: ''
      }
    };
  },
  mounted() {
    this.loadData();
    this.loadWalletBalance();
  },
  methods: {
    async loadData() {
      try {
        const [locations, baskets, items, orders] = await Promise.all([
          axios.get('/locations'),
          axios.get('/baskets'),
          axios.get('/special-items'),
          axios.get('/orders?limit=5')
        ]);
        
        this.locations = locations.data;
        this.baskets = baskets.data;
        this.specialItems = items.data;
        this.recentOrders = orders.data.data;
      } catch (error) {
        Swal.fire('Error', 'Failed to load data', 'error');
      }
    },
    async loadWalletBalance() {
      try {
        const response = await axios.get('/wallet/balance');
        this.walletBalance = response.data.balance;
      } catch (error) {
        console.error('Failed to load wallet balance');
      }
    },
    async calculatePrice() {
      if (!this.form.basket_id) return;
      
      try {
        const response = await axios.get('/orders/calculate-price', {
          params: {
            basket_id: this.form.basket_id,
            special_items: this.form.special_items
          }
        });
        this.pricing = response.data;
      } catch (error) {
        Swal.fire('Error', 'Failed to calculate price', 'error');
      }
    },
    async createOrder() {
      if (!this.pricing) {
        Swal.fire('Error', 'Please calculate price first', 'error');
        return;
      }

      this.loading = true;
      try {
        const response = await axios.post('/orders', this.form);
        Swal.fire('Success', 'Order created successfully', 'success');
        this.$router.push('/client/orders/' + response.data.order.id);
      } catch (error) {
        Swal.fire('Error', error.response?.data?.message || 'Failed to create order', 'error');
      } finally {
        this.loading = false;
      }
    },
    formatStatus(status) {
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
    getPaymentStatusColor(status) {
      const colors = {
        'pending': 'warning',
        'paid': 'success',
        'failed': 'danger',
        'refunded': 'info',
        'partially_paid': 'warning'
      };
      return colors[status] || 'secondary';
    },
    logout() {
      axios.post('/logout').then(() => {
        localStorage.removeItem('token');
        this.$router.push('/login');
      });
    }
  }
};
</script>