<template>
  <div v-if="isLoading">
    <p>Loading form ...</p>
  </div>
  <div v-else class="pet-details">
    <h2 v-if="pet.id">Edit {{ pet.name }}</h2>
    <h2 v-else>Create Pet</h2>

    <div v-if="pet.id" class="pet-id">
      <p><strong>Pet ID:</strong> {{ pet.id }}</p>
    </div>

    <form @submit.prevent="savePet" class="pet-form">
      <div v-for="(type, key) in schema" :key="key" class="form-row">
        <!-- Select dropdown for status -->
        <div v-if="key === 'status'">
          <label :for="key">{{ formatKey(key) }}:</label>
          <select v-model="selectedStatusId" id="status">
            <option v-for="status in statuses" :key="status.id" :value="status.id">
              {{ status.name }}
            </option>
          </select>
        </div>

        <!-- Select dropdown for category -->
        <div v-else-if="key === 'category'">
          <label :for="key">{{ formatKey(key) }}:</label>
          <select v-model="pet[key]" id="category">
            <option v-for="category in categories" :key="category.id" :value="category">
              {{ category.name }}
            </option>
          </select>
        </div>

        <!-- Dynamic input for photoUrls -->
        <div v-else-if="key === 'photoUrls'">
          <label>{{ formatKey(key) }}:</label>
          <div v-for="(url, index) in pet[key]" :key="index" class="photo-url-input">
            <input
                v-model="pet[key][index]"
                type="text"
                :id="`photoUrl-${index}`"
                placeholder="Enter photo URL"
            />
            <button type="button" @click="removePhotoUrl(index)">Remove</button>
          </div>
          <button type="button" @click="addPhotoUrl">Add Photo URL</button>
        </div>

        <!-- Multi-select for tags -->
        <div v-else-if="key === 'tags'">
          <label>{{ formatKey(key) }}:</label>
          <multiselect
              v-model="pet.tags"
              :options="tags"
              :multiple="true"
              :close-on-select="false"
              label="name"
              track-by="id"
              placeholder="Search and select tags"
              id="tags"
          />
        </div>

        <!-- Input for string/number fields -->
        <div v-else-if="typeof type === 'string'">
          <label :for="key">{{ formatKey(key) }}:</label>
          <input
              v-model="pet[key]"
              :type="type === 'int' ? 'number' : 'text'"
              :id="key"
              :placeholder="`Enter ${formatKey(key)}`"
          />
        </div>

        <!-- Nested objects -->
        <div v-else-if="typeof type === 'object' && !Array.isArray(type)">
          <fieldset>
            <legend>{{ formatKey(key) }}</legend>
            <div v-for="(nestedType, nestedKey) in type" :key="nestedKey">
              <label :for="`${key}-${nestedKey}`">{{ formatKey(nestedKey) }}</label>
              <input
                  v-model="pet[key][nestedKey]"
                  :type="nestedType === 'int' ? 'number' : 'text'"
                  :id="`${key}-${nestedKey}`"
                  :placeholder="`Enter ${formatKey(nestedKey)}`"
              />
            </div>
          </fieldset>
        </div>
      </div>

      <button type="submit">{{ pet.id ? 'Update Pet' : 'Create Pet' }}</button>
    </form>

    <button @click="goBack">Back to Pet List</button>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Multiselect from 'vue-multiselect';

const route = useRoute();
const router = useRouter();

const isLoading = ref(true);
const pet = ref({});
const schema = ref({});
const categories = ref([]);
const tags = ref([]);
const statuses = ref([]);
const selectedStatusId = ref(null);

const formatKey = (key) => key.charAt(0).toUpperCase() + key.slice(1);

const syncStatusId = () => {
  if (!pet.value || statuses.value.length === 0) return;
  const matchingStatus = statuses.value.find((status) => status.name === pet.value.status);
  selectedStatusId.value = matchingStatus ? matchingStatus.id : null;
};

const fetchSchema = async () => {
  try {
    const response = await fetch('/api/pet/schema');
    const data = await response.json();
    schema.value = data;

    // Initialize the pet object based on schema
    Object.entries(data).forEach(([key, type]) => {
      if (typeof type === 'string') pet.value[key] = type === 'int' ? 0 : '';
      else if (Array.isArray(type)) pet.value[key] = [];
      else if (typeof type === 'object') pet.value[key] = {};
    });
  } catch (error) {
    console.error('Error fetching schema:', error);
  }
};

const fetchData = async () => {
  try {
    const [categoriesResponse, tagsResponse, statusesResponse] = await Promise.all([
      fetch('/api/categories').then((res) => res.json()),
      fetch('/api/tags').then((res) => res.json()),
      fetch('/api/statuses').then((res) => res.json()),
    ]);
    categories.value = categoriesResponse;
    tags.value = tagsResponse;
    statuses.value = statusesResponse;
  } catch (error) {
    console.error('Error fetching dropdown data:', error);
  }
};

const fetchPet = async (id) => {
  try {
    const response = await fetch(`/api/pet/${id}`);
    const data = await response.json();
    pet.value = data;
    syncStatusId();
  } catch (error) {
    console.error('Error fetching pet:', error);
  }
};

const savePet = async () => {
  const selectedStatus = statuses.value.find((status) => status.id === selectedStatusId.value);
  pet.value.status = selectedStatus ? selectedStatus.name : pet.value.status;

  const url = `/api/pet`;
  const method = pet.value.id ? 'PUT' : 'POST';

  try {
    await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(pet.value),
    });
    alert('Pet saved successfully!');
    goBack();
  } catch (error) {
    console.error(`Error saving pet:`, error);
    alert(`Failed to save pet.`);
  }
};

const addPhotoUrl = () => pet.value.photoUrls.push('');
const removePhotoUrl = (index) => pet.value.photoUrls.splice(index, 1);

const goBack = () => router.push('/');

onMounted(async () => {
  try {
    await fetchSchema();
    await fetchData();
    if (route.params.id) {
      await fetchPet(route.params.id);
    }
  } catch (error) {
    console.error('Error loading data:', error);
  } finally {
    isLoading.value = false; // Ensure the loading state is updated
  }
});

watch([statuses, pet], syncStatusId);
</script>


<style scoped>
.pet-details {
  padding: 20px;
  background: #f9f9f9;
  border-radius: 8px;
}

button {
  margin-top: 20px;
  padding: 10px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 5px;
}

button:hover {
  background-color: #45a049;
}

.pet-form {
  display: block;
  width: 600px;
  margin: 0 auto;
}

.form-row {
  margin-bottom: 20px;
}

label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block;
}

input[type="text"],
input[type="number"],
select {
  padding: 8px;
  width: 100%;
  max-width: 100%;
  border-radius: 5px;
  border: 1px solid #ddd;
  box-sizing: border-box; /* Include padding and border in the element's total width */
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
  outline: none;
  border-color: #007bff;
}

button[type="submit"] {
  background-color: #007bff;
  color: white;
  font-size: 16px;
  cursor: pointer;
  border-radius: 5px;
  padding: 10px 20px;
}

button[type="submit"]:hover {
  background-color: #0056b3;
}

select {
  padding: 8px;
  width: 100%;
  max-width: 100%;
  border-radius: 5px;
  border: 1px solid #ddd;
  box-sizing: border-box; /* Include padding and border in the element's total width */
}

select:focus {
  outline: none;
  border-color: #007bff;
}

.photo-url-container {
  margin-bottom: 20px;
}

.photo-url-input {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  gap: 10px; /* This ensures a gap between the input and the button */
}

.photo-url-input input {
  flex: 1; /* This allows the input to take up the available space */
}

.photo-url-input button {
  background-color: #f44336;
  color: white;
  padding: 5px 10px;
  margin-top: 0px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.photo-url-input button:hover {
  background-color: #d32f2f;
}

/* Ensure the multiselect input has full width and doesn't affect layout */
.multiselect {
  width: 100%; /* Full width inside form container */
  max-width: 100%; /* Prevents expanding beyond the form's width */
}

.multiselect__single {
  min-height: 40px; /* Fix the height of the input */
  padding: 5px;
}

/* Restrict the dropdown expansion, to avoid the container from growing */
.multiselect__content {
  width: 100% !important;
  max-width: 100% !important;
}

</style>
