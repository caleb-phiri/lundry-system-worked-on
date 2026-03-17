<template>
  <div class="card">
    <div class="card-header bg-white">
      <h5 class="mb-0">Create New Order</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="submitOrder">
        <!-- Step 1: Location Selection -->
        <div v-if="step === 1">
          <h6 class="mb-3">Step 1: Select Locations</h6>
          
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

          <div class="text-end">
            <button type="button" class="btn btn-primary" @click="step = 2" :disabled="!form.pickup_location_id || !form.delivery_location_id">
              Next <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </div>
        </div>

        <!-- Step 2: Basket Selection -->
        <div v-if="step === 2">
          <h6 class="mb-3">Step 2: Select Basket Size</h6>
          
          <div class="row">
            <div v-for="basket in baskets" :key="basket.id" class="col-md-4 mb-3">
              <div 
                class="card h-100" 
                :class="{ 'border-primary': form.basket_id === basket.id }"
                @click="selectBasket(basket)"
                style="cursor: pointer;"
              >
                <div class="card-body text-center">
                  <i class="fas fa-shopping-basket fa-3x mb-2" :class="form.basket_id === basket.id ? 'text-primary' : 'text-secondary'"></i>
                  <h6>{{ basket.name }}</h6>
                  <p class="mb-1">Capacity: {{ basket.capacity_kg }}kg</p>
                  <h5 class="text-primary">K{{ basket.base_price }}</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-secondary" @click="step = 1">
              <i class="fas fa-arrow-left me-2"></i>Back
            </button>
            <button type="button" class="btn btn-primary" @click="step = 3" :disabled="!form.basket_id">
              Next <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </div>
        </div>

        <!-- Step 3: Special Items -->
        <div v-if="step === 3">
          <h6 class="mb-3">Step 3: Special Items (Optional)</h6>
          
          <div class="row">
            <div v-for="item in specialItems" :key="item.id" class="col-md-6 mb-3">
              <div class="form-check">
                <input 
                  type="checkbox" 
                  class="form-check-input" 
                  :id="'item-' + item.id"
                  :value="item.id"
                  v-model="form.special_items"
                  @change="calculatePrice"
                >
                <label class="form-check-label" :for="'item-' + item.id">
                  {{ item.name }} 
                  <span class="badge bg-info">+K{{ item.surcharge }}</span>
                </label>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-secondary" @click="step = 2">
              <i class="fas fa-arrow-left me-2"></i>Back
            </button>
            <button type="button" class="btn btn-primary" @click="step = 4">
              Next <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </div>
        </div>

        <!-- Step 4: Schedule & Payment -->
        <div v-if="step === 4">
          <h6 class="mb-3">Step 4: Schedule & Payment</h6>
          
          <!-- Price Summary -->
          <div v-if="pricing" class="alert alert-info mb-3">
            <h6 class="mb-2">Order Summary</h6>
            <p class="mb-1">Basket: {{ selectedBasket?.name }} - K{{ pricing.subtotal }}</p>
            <p class="mb-1">Surcharges: K{{ pricing.surcharges }}</p>
            <hr class="my-2">
            <h5 class="mb-0">Total: K{{ pricing.total }}</h5>
          </div>

          <div class="mb-3">
            <label class="form-label">Pickup Date & Time</label>
            <input 
              type="datetime-local" 
              v-model="form.pickup_scheduled_at" 
              class="form-control" 
              :min="minDateTime"
              required
            >
          </div>

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
              <label class="form-check-label" for="prepaid">
                Pay Before Collection <small class="text-muted">(Get 5% discount)</small>
              </label>
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
            <textarea v-model="form.special_instructions" class="form-control" rows="2" placeholder="Any special instructions for pickup/delivery?"></textarea>
          </div>

          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-secondary" @click="step = 3">
              <i class="fas fa-arrow-left me-2"></i>Back
            </button>
            <button type="submit" class="btn btn-success" :disabled="loading">
              <i class="fas fa-check me-2"></i>{{ loading ? 'Creating...' : 'Place Order' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  props: {
    onOrderCreated: Function
  },
  data() {
    return {
      step: 1,
      locations: [],
      baskets: [],
      specialItems: [],
      selectedBasket: null,
      pricing: null,
      walletBalance: 0,
      loading: false,
      minDateTime: new Date().toISOString().slice(0, 16),
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
        const [locations, baskets, items] = await Promise.all([
          axios.get('/locations'),
          axios.get('/baskets'),
          axios.get('/special-items')
        ]);
        
        this.locations = locations.data;
        this.baskets = baskets.data;
        this.specialItems = items.data;
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
    selectBasket(basket) {
      this.form.basket_id = basket.id;
      this.selectedBasket = basket;
      this.calculatePrice();
    },
    async calculatePrice() {
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
    async submitOrder() {
      if (!this.pricing) {
        Swal.fire('Error', 'Please calculate price first', 'error');
        return;
      }

      this.loading = true;
      try {
        const response = await axios.post('/orders', this.form);
        
        Swal.fire({
          icon: 'success',
          title: 'Order Created!',
          text: `Your order #${response.data.order.order_number} has been created successfully.`,
          showConfirmButton: true
        });
        
        this.$emit('orderCreated');
        this.resetForm();
        this.step = 1;
        
      } catch (error) {
        Swal.fire('Error', error.response?.data?.message || 'Failed to create order', 'error');
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.form = {
        pickup_location_id: '',
        delivery_location_id: '',
        basket_id: '',
        special_items: [],
        pickup_scheduled_at: '',
        payment_method: '',
        payment_type: 'prepaid',
        special_instructions: ''
      };
      this.pricing = null;
      this.selectedBasket = null;
    }
  },
  watch: {
    'form.basket_id': function() {
      this.calculatePrice();
    },
    'form.special_items': function() {
      this.calculatePrice();
    }
  }
};
</script>