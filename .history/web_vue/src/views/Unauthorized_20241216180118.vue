<template>
  <div class="unauthorized">
    <h1>Unauthorized Access</h1>
    <p>You don't have permission to access this page.</p>
    <router-link to="/" class="back-link">
      <i class="fas fa-arrow-left"></i>
      Return to Dashboard
    </router-link>
  </div>
</template>

<style scoped>
.unauthorized {
  text-align: center;
  padding: 4rem 2rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 2rem;
  color: #4f46e5;
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
}
</style> 