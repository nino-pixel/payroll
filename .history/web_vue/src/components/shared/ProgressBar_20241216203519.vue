<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ProgressBar',
  props: {
    progress: {
      type: Number,
      required: true
    },
    label: {
      type: String,
      default: ''
    }
  }
})
</script>

<template>
  <div class="progress-container">
    <div class="progress-label">
      {{ label }} {{ progress }}%
    </div>
    <div class="progress-bar">
      <div 
        class="progress-fill"
        :style="{ width: `${progress}%` }"
      />
    </div>
  </div>
</template>

<style scoped>
.progress-container {
  width: 100%;
  margin: 1rem 0;
}

.progress-label {
  font-size: 0.875rem;
  color: #374151;
  margin-bottom: 0.5rem;
}

.progress-bar {
  width: 100%;
  height: 0.5rem;
  background: #e5e7eb;
  border-radius: 0.25rem;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #4f46e5;
  transition: width 0.3s ease;
}
</style> 