<template>
  <div class="centered-view">
    <div class="content">
      <slot/>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@styleVars/base" as *;

.centered-view {
  background-color: $bg-light;
  min-height: calc(100vh - $height-header);

  display: flex;
  justify-content: center;
  align-items: center;

  .content {
    background: $bg-white;
    padding: 2.5rem 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 400px;
    text-align: center;
  }
}
</style>
