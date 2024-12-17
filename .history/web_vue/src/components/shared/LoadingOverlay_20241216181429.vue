<script setup lang="ts">
import LoadingSpinner from './LoadingSpinner.vue'

defineProps<{
  message?: string
}>()
</script>

<template>
  <div class="loading-overlay">
    <div class="loading-content">
      <LoadingSpinner size="large" color="white" />
      <p v-if="message" class="loading-message">{{ message }}</p>
    </div>
  </div>
</template>

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.loading-message {
  color: white;
  font-size: 1.125rem;
  font-weight: 500;
}
</style> 