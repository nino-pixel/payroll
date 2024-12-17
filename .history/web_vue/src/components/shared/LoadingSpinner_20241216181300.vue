<script setup lang="ts">
defineProps<{
  size?: 'small' | 'medium' | 'large'
  color?: string
}>()
</script>

<template>
  <div 
    class="spinner" 
    :class="size || 'medium'"
    :style="{ borderTopColor: color || '#4f46e5' }"
  ></div>
</template>

<style scoped>
.spinner {
  display: inline-block;
  border: 3px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top-color: currentColor;
  animation: spin 0.8s ease-in-out infinite;
}

.small {
  width: 16px;
  height: 16px;
  border-width: 2px;
}

.medium {
  width: 24px;
  height: 24px;
  border-width: 3px;
}

.large {
  width: 32px;
  height: 32px;
  border-width: 4px;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style> 