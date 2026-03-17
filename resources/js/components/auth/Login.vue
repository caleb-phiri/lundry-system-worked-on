<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Login to Laundry Logistics</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="email" class="form-label">Email or Phone</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="email" 
                  v-model="form.email"
                  placeholder="Enter email or phone"
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
                  placeholder="Enter password"
                  required
                >
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary" :disabled="loading">
                  {{ loading ? 'Logging in...' : 'Login' }}
                </button>
              </div>
            </form>
            <hr>
            <div class="text-center">
              <p>Don't have an account? <router-link to="/register">Register here</router-link></p>
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
        email: '',
        password: ''
      },
      loading: false
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      try {
        const response = await axios.post('/login', this.form);
        
        // Store token and user data
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        
        // Set default authorization header
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
        
        // Redirect based on role
        this.redirectBasedOnRole(response.data.user.role);
        
      } catch (error) {
        Swal.fire('Error', error.response?.data?.message || 'Login failed', 'error');
      } finally {
        this.loading = false;
      }
    },
    redirectBasedOnRole(role) {
      switch(role) {
        case 'admin':
          this.$router.push('/admin');
          break;
        case 'driver':
          this.$router.push('/driver');
          break;
        default:
          this.$router.push('/client');
      }
    }
  }
};
</script>