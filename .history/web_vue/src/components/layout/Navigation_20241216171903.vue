<script setup lang="ts">
import { useAuthStore } from '../'
import { useRouter } from 'vue-router'

interface MenuItem {
  name: string
  path: string
  permission: string | null
  icon?: string
}

const auth = useAuthStore()
const router = useRouter()

const menuItems: MenuItem[] = [
  { name: 'Dashboard', path: '/', permission: null, icon: 'dashboard' },
  { name: 'Departments', path: '/departments', permission: 'departments.view', icon: 'building' },
  { name: 'Employees', path: '/employees', permission: 'employees.view', icon: 'users' },
  { name: 'Payroll', path: '/payroll', permission: 'payroll.view', icon: 'money-bill' },
  { name: 'Settings', path: '/settings', permission: 'settings.manage', icon: 'cog' }
]

const canAccess = (permission: string | null): boolean => {
  if (!permission) return true
  return auth.hasPermission(permission)
}

const isActive = (path: string): boolean => {
  return router.currentRoute.value.path.startsWith(path)
}
</script>

<template>
  <nav class="navigation">
    <div class="nav-header">
      <img src="@/assets/logo.svg" alt="Logo" class="logo" />
      <h1 class="app-name">HR System</h1>
    </div>

    <ul class="nav-items">
      <li v-for="item in menuItems" :key="item.name">
        <router-link
          v-if="canAccess(item.permission)"
          :to="item.path"
          :class="['nav-link', { active: isActive(item.path) }]"
        >
          <i v-if="item.icon" :class="['fas', `fa-${item.icon}`]"></i>
          <span>{{ item.name }}</span>
        </router-link>
      </li>
    </ul>

    <div class="nav-footer">
      <button @click="auth.logout" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </button>
    </div>
  </nav>
</template>

<style scoped>
.navigation {
  width: 250px;
  height: 100vh;
  background: #1a1a1a;
  color: white;
  display: flex;
  flex-direction: column;
}

.nav-header {
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo {
  width: 32px;
  height: 32px;
}

.app-name {
  font-size: 1.25rem;
  font-weight: 600;
}

.nav-items {
  flex: 1;
  padding: 1rem 0;
  list-style: none;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  color: #a3a3a3;
  text-decoration: none;
  transition: all 0.2s;
}

.nav-link:hover,
.nav-link.active {
  color: white;
  background: rgba(255, 255, 255, 0.1);
}

.nav-link i {
  width: 20px;
  text-align: center;
}

.nav-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-btn {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: none;
  border: none;
  color: #a3a3a3;
  cursor: pointer;
  transition: color 0.2s;
}

.logout-btn:hover {
  color: white;
}
</style> 