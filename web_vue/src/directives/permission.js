import { useAuthStore } from '../store/auth'

export const permission = {
  mounted(el, binding) {
    const auth = useAuthStore()
    if (!auth.hasPermission(binding.value)) {
      el.style.display = 'none'
    }
  }
} 