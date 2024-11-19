<template>
  <div class="table-container">
    <h1>PETS</h1>

    <div class="actions">
      <button v-if="!loading" @click="createPet" class="add-pet-btn">+ Create Pet</button>
    </div>

    <!-- Search Section -->
    <div v-if="!loading" class="search-container">
      <select v-model="searchType" @change="clearSearch" class="search-select">
        <option value="status">Status</option>
        <option value="tags">Tags</option>
        <option value="id">ID</option>
      </select>

      <!-- Dynamic Search Inputs -->
      <div v-if="searchType === 'status'">
        <select v-model="searchValue" class="search-input">
          <option value="" disabled>Select Status</option>
          <option v-for="status in statuses" :key="status" :value="status.name">{{ status.name }}</option>
        </select>
      </div>

      <div v-if="searchType === 'tags'">
        <multiselect
            v-model="searchValue"
            :options="tagsOptions"
            :multiple="true"
            placeholder="Select Tags"
            class="search-input"
            label="name"
            track-by="id"
        />
      </div>

      <div v-if="searchType === 'id'">
        <input
            v-model="searchValue"
            type="text"
            placeholder="Enter Pet ID"
            class="search-input"
        />
      </div>

      <!-- Search Button -->
      <button @click="searchPets" class="search-btn">Search</button>

      <button @click="clearSearch" class="clear-btn">Clear</button>
    </div>

    <!-- Show loading message when pets data is being fetched -->
    <div v-if="loading" class="loading-message">
      Loading pets ...
    </div>

    <!-- Only show table when pets are loaded -->
    <table v-else class="pet-table">
      <thead>
      <tr>
        <th v-for="(col, index) in columns" :key="index">{{ col }}</th>
        <th>Actions</th> <!-- Column for Delete button -->
      </tr>
      </thead>
      <tbody>
      <tr
          v-for="(pet, index) in paginatedPets"
          :key="pet.id"
          @click="editPet(pet.id)"
          class="clickable-row"
      >
        <td v-for="col in columns" :key="col">
              <span v-if="typeof pet[col] === 'string' || typeof pet[col] === 'number'">
                {{ pet[col] }}
              </span>
          <span v-else-if="pet[col] && pet[col].name">
                {{ pet[col].name }}
              </span>
          <span v-else-if="Array.isArray(pet[col]) && pet[col][0]?.name">
                {{ pet[col].map(item => item.name).join(', ') }}
              </span>
          <span v-else>
                {{ pet[col] }}
              </span>
        </td>
        <td>
          <button @click.stop="confirmDelete(pet)" class="delete-btn">Delete</button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Show pagination only after pets data is loaded -->
    <div v-if="!loading" class="pagination">
      <button @click="changePage('prev')" :disabled="currentPage === 1" class="pagination-btn">Previous</button>
      <span class="pagination-info">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="changePage('next')" :disabled="currentPage === totalPages" class="pagination-btn">Next</button>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal">
        <h2>Are you sure you want to delete this pet?</h2>
        <div class="modal-actions">
          <button @click="deletePet" class="confirm-btn">Yes, Delete</button>
          <button @click="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css'; // Import styles

export default {
  components: {
    Multiselect, // Register the multiselect component
  },
  data() {
    return {
      pets: [],
      currentPage: 1,
      itemsPerPage: 10,
      columns: [],
      loading: true, // Initialize loading flag to true
      showModal: false,  // For showing the confirmation modal
      petToDelete: null, // The pet to be deleted
      searchType: 'id', // Default search type
      searchValue: '', // Search input value (status, tags, or id)
      statuses: [], // List of statuses (fetched from API)
      tagsOptions: [], // List of tags (fetched from API)
      originalPets: [] // Store the original pets list for resetting
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.pets.length / this.itemsPerPage);
    },
    paginatedPets() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.pets.slice(start, end);
    },
  },
  methods: {
    async fetchPets() {
      this.loading = true; // Set loading to true when starting to fetch data
      const response = await fetch('/api/pets');
      const data = await response.json();
      this.pets = data;
      this.originalPets = [...data];

      if (this.pets.length > 0) {
        this.columns = Object.keys(this.pets[0]);
      }

      this.loading = false; // Set loading to false after data is fetched
    },
    async fetchStatuses() {
      try {
        const response = await fetch('/api/statuses');
        this.statuses = await response.json();
      } catch (error) {
        console.error("Failed to fetch statuses:", error);
        this.statuses = []; // Fallback to an empty list
      }
    },
    async fetchTags() {
      try {
        const response = await fetch('/api/tags');
        this.tagsOptions = await response.json();
      } catch (error) {
        console.error("Failed to fetch tags:", error);
        this.tagsOptions = []; // Fallback to an empty list
      }
    },
    changePage(direction) {
      if (direction === 'prev' && this.currentPage > 1) {
        this.currentPage--;
      } else if (direction === 'next' && this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    editPet(petId) {
      this.$router.push(`/pet/${petId}`);
    },
    createPet() {
      this.$router.push('/pet/create'); // Replace with your desired route for creating a new pet
    },
    confirmDelete(pet) {
      this.petToDelete = pet; // Store pet information to be deleted
      this.showModal = true;  // Show the confirmation modal
    },
    async deletePet() {
      // Perform deletion on the backend (optional)
      const response = await fetch(`/api/pet/${this.petToDelete.id}`, {
        method: 'DELETE',
      });

      if (response.ok) {
        // Remove the pet from the list
        this.pets = this.pets.filter(pet => pet.id !== this.petToDelete.id);
        this.showModal = false; // Close the modal after deletion
      } else {
        alert('Failed to delete pet');
      }
    },
    cancelDelete() {
      this.showModal = false; // Close the modal without deleting
    },
    async searchPets() {
      if (!this.searchValue || (Array.isArray(this.searchValue) && this.searchValue.length === 0)) {
        this.pets = [...this.originalPets];
        this.currentPage = 1; // Reset pagination
        return;
      }

      let url = '';
      if (this.searchType === 'status') {
        url = `/api/pet/findByStatus?status=${this.searchValue}`;
      } else if (this.searchType === 'tags') {
        const tagsQuery = this.searchValue.map(tag => `tags[]=${tag.name}`).join('&');
        url = `/api/pet/findByTags?${tagsQuery}`;
      } else if (this.searchType === 'id') {
        url = `/api/pet/${this.searchValue}`;
      }

      if (url) {
        this.loading = true;
        const response = await fetch(url);
        const data = await response.json();

        if (!Array.isArray(data)) {
          this.pets = [data];
        } else {
          this.pets = data;
        }

        this.loading = false;
      }
    },
    clearSearch() {
      this.searchValue = ''; // Reset the search value
      this.pets = [...this.originalPets]; // Restore the original pets list
      this.currentPage = 1; // Reset pagination
    },
  },
  mounted() {
    this.fetchPets();
    this.fetchStatuses();
    this.fetchTags();
  },
};
</script>

<style scoped>
.table-container {
  max-width: 1200px;
  min-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.pet-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Arial', sans-serif;
  color: #333;
  margin-bottom: 20px;
}

.pet-table th,
.pet-table td {
  padding: 12px 15px;
  text-align: left;
}

.pet-table th {
  background-color: #007bff;
  color: white;
  text-transform: uppercase;
  font-size: 14px;
}

.pet-table tr:nth-child(even) {
  background-color: #f2f2f2;
}

.pet-table tr:hover {
  background-color: #f1f1f1;
}

.pet-table td {
  border-bottom: 1px solid #ddd;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination-btn {
  padding: 10px 20px;
  margin: 0 5px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.pagination-btn:disabled {
  background-color: #d3d3d3;
  cursor: not-allowed;
}

.pagination-btn:hover {
  background-color: #0056b3;
}

.pagination-info {
  font-size: 14px;
  margin: 0 10px;
}

.clickable-row {
  cursor: pointer;
}

.clickable-row:hover {
  background-color: #f1f1f1;
}

.loading-message {
  font-size: 18px;
  color: #007bff;
  text-align: center;
  margin-bottom: 20px;
}

.actions {
  display: flex;
  justify-content: flex-start;  /* Align the button to the left */
  margin-bottom: 10px;
}

.add-pet-btn {
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  transition: background-color 0.3s ease;
}

.add-pet-btn:hover {
  background-color: #218838;
}

.delete-btn {
  padding: 5px 10px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 12px;
}

.delete-btn:hover {
  background-color: #c82333;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  text-align: center;
}

.modal-actions {
  display: flex;
  justify-content: space-around;
  margin-top: 20px;
}

.confirm-btn {
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.confirm-btn:hover {
  background-color: #218838;
}

.cancel-btn {
  padding: 10px 20px;
  background-color: #ccc;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.cancel-btn:hover {
  background-color: #bbb;
}

.add-pet-btn {
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.delete-btn {
  padding: 6px 10px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.pagination-btn {
  padding: 10px 20px;
  margin: 0 5px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.search-container {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.search-select, .search-input {
  padding: 10px;
  margin-right: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
}

.search-btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.search-btn:hover {
  background-color: #0056b3;
}

.search-select {
  width: 150px;
}

.search-input {
  width: 400px;
}

.search-btn {
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
}

.clear-btn {
  padding: 10px 20px;
  background-color: #f8f9fa;
  color: #007bff;
  border: 1px solid #007bff;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  margin-left: 10px;
}

.clear-btn:hover {
  background-color: #e2e6ea;
}

</style>
