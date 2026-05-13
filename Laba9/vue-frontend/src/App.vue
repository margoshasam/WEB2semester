<template>
  <SiteHeader />
  <main class="container mt-4 mb-4">
    <RouterView />
  </main>
  <SiteFooter />
</template>

<script setup>
import { RouterView } from 'vue-router'
import SiteHeader from './components/SiteHeader.vue'
import SiteFooter from './components/SiteFooter.vue'
</script>
