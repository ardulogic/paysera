<template>
  <header class="header">
    <div class="container">
      <div class="logo">
          <router-link :to="{ name: 'home' }" class="nav-link">
              <img :src="logo" id="header-logo" alt="Paysera Logo"/>
          </router-link>
      </div>

      <nav class="nav-wrapper">
          <router-link :to="{ name: 'admin' }" class="nav-link">Dashboard</router-link>
      </nav>
    </div>
  </header>
</template>

<script setup>
import {useRouter} from 'vue-router'
const router = useRouter()
import logo from '@assets/logo.webp'
</script>

<style lang="scss" scoped>
@use '@styleVars/base' as *;

$header-mobile-breakpoint: 430px;
$header-height: 80px;

.header {
  padding: 0 16px;
  border-bottom: 1px solid $border-color;
  background-color: $bg-white;
  box-shadow: $shadow-sm;

  .container {
    max-width: 1024px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .logo {
    display: flex;
    justify-content: center;

    img {
      max-height: calc($header-height / 2)
    }
  }

  .nav-wrapper {
    display: flex;
    height: $header-height;
    gap: 1.5rem;
    align-items: center;

    .nav-link {
      color: $color-text;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;

      &:hover {
        color: $color-primary;
      }
    }

    .welcome-text {
      color: $color-text-muted;
      font-size: 0.95rem;
    }

  }
}
</style>
