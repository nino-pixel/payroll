<template>
  <div class="dashboard">
    <h1>Payroll Dashboard</h1>
    <div v-if="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="data">{{ data }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import http from '@/utils/http'

const loading = ref(true)
const error = ref('')
const data = ref(null)

onMounted(async () => {
  try {
    // Test authentication
    const response = await http.get('/test-auth')
    data.value = response.data
    loading.value = false
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load dashboard'
    loading.value = false
    console.error('Dashboard error:', err)
  }
})
</script>

<style scoped>
.dashboard {
  padding: 20px;
}
.error {
  color: red;
  margin: 10px 0;
}
</style> 