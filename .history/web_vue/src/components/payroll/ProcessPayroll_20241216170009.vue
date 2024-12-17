<script lang="ts">
import { defineComponent, ref } from 'vue'
import http from '../../utils/http'
import Modal from '../shared/Modal'

export default defineComponent({
  name: 'ProcessPayroll',
  components: { Modal },
  emits: ['close', 'processed'],
  setup(_, { emit }) {
    const loading = ref(false)
    const error = ref('')

    const processPayroll = async () => {
      try {
        loading.value = true
        await http.post('/payrolls/process')
        emit('processed')
        emit('close')
      } catch (err: any) {
        error.value = err.response?.data?.message || 'Failed to process payroll'
      } finally {
        loading.value = false
      }
    }

    return {
      loading,
      error,
      processPayroll
    }
  }
})
</script>

<template>
  <Modal @close="$emit('close')">
    <template #header>Process Payroll</template>
    <template #body>
      <div v-if="error" class="error">{{ error }}</div>
      <p>Are you sure you want to process payroll?</p>
    </template>
    <template #footer>
      <button 
        :disabled="loading" 
        @click="processPayroll"
        class="btn-primary"
      >
        {{ loading ? 'Processing...' : 'Process' }}
      </button>
      <button @click="$emit('close')">Cancel</button>
    </template>
  </Modal>
</template> 