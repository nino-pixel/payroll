<template>
  <div class="dashboard">
    <h1>Dashboard</h1>
    <div v-if="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="user">
      Welcome, {{ user.name }}!
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import http from '@/utils/http'

const loading = ref(true)
const error = ref('')
const user = ref(null)

onMounted(async () => {
  try {
    const response = await http.get('/user')
    user.value = response.data.data
    console.log('User data:', response.data)
  } catch (err: any) {
    console.error('Dashboard error:', err)
    error.value = err.response?.data?.message || 'Failed to load user data'
  } finally {
    loading.value = false
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