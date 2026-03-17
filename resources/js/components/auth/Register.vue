<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Register for Laundry Logistics</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="handleRegister">
              <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="name" 
                  v-model="form.name"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                  type="email" 
                  class="form-control" 
                  id="email" 
                  v-model="form.email"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input 
                  type="tel" 
                  class="form-control" 
                  id="phone" 
                  v-model="form.phone"
                  placeholder="+260XXXXXXXXX"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="password" 
                  v-model="form.password"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="password_confirmation" 
                  v-model="form.password_confirmation"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="referral_code" 
                  v-model="form.referral_code"
                  placeholder="Enter referral code if you have one"
                >
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary" :disabled="loading">
                  {{ loading ? 'Registering...' : 'Register' }}
                </button>
              </div>
            </form>
            <hr>
            <div class="text-center">
              <p>Already have an account? <router-link to="/login">Login here</router-link></p>
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
      form: {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        referral_code: ''
      },
      loading: false
    };
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      try {
        const response = await axios.post('/register', this.form);
        
        Swal.fire('Success', 'Registration successful! Please login.', 'success');
        this.$router.push('/login');
        
      } catch (error) {
        const errors = error.response?.data?.errors;
        let message = 'Registration failed';
        
        if (errors) {
          message = Object.values(errors).flat().join('\n');
        }
        
        Swal.fire('Error', message, 'error');
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>