<template>
  <div class="dashboard">
    <h1>Payroll Dashboard</h1>
    <!-- Your dashboard content -->
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import http from '@/utils/http'

const router = useRouter()

onMounted(async () => {
  try {
    // Verify authentication
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }

    // You can add API calls here to fetch dashboard data
  } catch (error) {
    console.error('Dashboard error:', error)
  }
})
</script>

<style scoped>
/* Your styles here */
</style> 