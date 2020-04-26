<template>
  <div id="app">
    <router-view />
    <store-dialogs />
  </div>
</template>

<script>
export default {
  components: {
    StoreDialogs: () => import('@/components/root/StoreDialogs')
  }
}
</script>
