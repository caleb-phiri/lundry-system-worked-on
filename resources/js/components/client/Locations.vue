<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link class="navbar-brand" to="/client">
          <i class="fas fa-tshirt me-2"></i>Laundry Logistics
        </router-link>
      </div>
    </nav>

    <div class="container mt-4">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header bg-white">
              <h5 class="mb-0">Add New Location</h5>
            </div>
            <div class="card-body">
              <form @submit.prevent="addLocation">
                <div class="mb-3">
                  <label class="form-label">Location Name</label>
                  <input type="text" class="form-control" v-model="newLocation.name" placeholder="e.g., Home, Office" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <textarea class="form-control" v-model="newLocation.address" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Contact Phone</label>
                  <input type="tel" class="form-control" v-model="newLocation.contact_phone" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Special Instructions</label>
                  <textarea class="form-control" v-model="newLocation.instructions" rows="2" placeholder="Gate code, landmarks, etc."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                  {{ loading ? 'Adding...' : 'Add Location' }}
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-white">
              <h5 class="mb-0">My Locations</h5>
            </div>
            <div class="card-body">
              <div v-if="locations.length === 0" class="text-center py-4">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <p>No locations added yet</p>
              </div>
              <div v-for="location in locations" :key="location.id" class="mb-3">
                <div class="border rounded p-3">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <h6 class="mb-1">
                        {{ location.name }}
                        <span v-if="location.is_default" class="badge bg-success ms-2">Default</span>
                      </h6>
                      <p class="mb-1 text-muted">{{ location.address }}</p>
                      <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ location.contact_phone }}</p>
                      <p v-if="location.instructions" class="mb-0 small text-muted">
                        <i class="fas fa-info-circle me-2"></i>{{ location.instructions }}
                      </p>
                    </div>
                    <div>
                      <button v-if="!location.is_default" class="btn btn-sm btn-outline-success me-2" @click="setDefault(location.id)">
                        <i class="fas fa-check"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" @click="deleteLocation(location.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
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
      newLocation: {
        name: '',
        address: '',
        contact_phone: '',
        instructions: ''
      },
      loading: false
    };
  },
  mounted() {
    this.loadLocations();
  },
  methods: {
    async loadLocations() {
      try {
        const response = await axios.get('/locations');
        this.locations = response.data;
      } catch (error) {
        Swal.fire('Error', 'Failed to load locations', 'error');
      }
    },
    async addLocation() {
      this.loading = true;
      try {
        await axios.post('/locations', this.newLocation);
        Swal.fire('Success', 'Location added successfully', 'success');
        this.newLocation = {
          name: '',
          address: '',
          contact_phone: '',
          instructions: ''
        };
        this.loadLocations();
      } catch (error) {
        Swal.fire('Error', error.response?.data?.message || 'Failed to add location', 'error');
      } finally {
        this.loading = false;
      }
    },
    async setDefault(id) {
      try {
        await axios.put(`/locations/${id}/default`);
        this.loadLocations();
        Swal.fire('Success', 'Default location updated', 'success');
      } catch (error) {
        Swal.fire('Error', 'Failed to set default location', 'error');
      }
    },
    async deleteLocation(id) {
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/locations/${id}`);
          this.loadLocations();
          Swal.fire('Deleted!', 'Location has been deleted.', 'success');
        } catch (error) {
          Swal.fire('Error', 'Failed to delete location', 'error');
        }
      }
    }
  }
};
</script>