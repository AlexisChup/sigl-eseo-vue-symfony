<template>
<div class="row m-0">
  <div v-if="titre !== ''" class="col-12 row m-0 justify-content-start align-self-start">
    <h2>{{ titre }}</h2>
  </div>
  <div class="col-12 row m-0">
    <div class="case rounded shadow border p-1 p-md-3 col-12 mb-3 ">
      <slot/>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: "Widget",
  props: {
    titre: '',
  },
}
</script>

<style scoped>

</style>