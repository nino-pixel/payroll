<script setup lang="ts">
import { ref, defineComponent } from 'vue'
import http from '../../utils/http'
import Modal from '../shared/Modal.vue'
import LoadingSpinner from '../shared/LoadingSpinner.vue'
import LoadingOverlay from '../shared/LoadingOverlay.vue'

defineComponent({
  name: 'ProcessPayroll'
})

const emit = defineEmits(['close', 'processed'])

const loading = ref(false)
const processingPayroll = ref(false)
const error = ref('')

const processPayroll = async () => {
  try {
    processingPayroll.value = true
    await http.post('/payrolls/process')
    emit('processed')
    emit('close')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to process payroll'
  } finally {
    processingPayroll.value = false
  }
}
</script>

<template>
  <Modal @close="$emit('close')">
    <LoadingOverlay 
      v-if="processingPayroll" 
      message="Processing payroll..." 
    />
    <template #header>Process Payroll</template>
    <template #body>
      <div v-if="error" class="error">{{ error }}</div>
      <p>Are you sure you want to process payroll?</p>
    </template>
    <template #footer>
      <button 
        :disabled="processingPayroll" 
        @click="processPayroll"
        class="btn-primary"
      >
        <LoadingSpinner 
          v-if="processingPayroll" 
          size="small" 
          color="white" 
        />
        <span>{{ processingPayroll ? 'Processing...' : 'Process' }}</span>
      </button>
      <button @click="$emit('close')">Cancel</button>
    </template>
  </Modal>
</template> 

<style scoped>
.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.error {
  color: #dc2626;
  padding: 0.75rem;
  background: #fee2e2;
  border-radius: 4px;
  margin-bottom: 1rem;
}
</style> 