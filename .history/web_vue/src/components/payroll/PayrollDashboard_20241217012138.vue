<template>
  <div class="dashboard">
    <h1>Payroll Dashboard</h1>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <!-- Your dashboard content -->
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import http from '@/utils/http'

const router = useRouter()
const loading = ref(true)

onMounted(async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }
    
    // Add your dashboard data fetching logic here
    loading.value = false
  } catch (error) {
    console.error('Dashboard error:', error)
    loading.value = false
  }
})
</script>

<style scoped>
.dashboard {
  padding: 20px;
}
</style> 