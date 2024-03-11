<template>
  <div>
    <div>
      <h1 class="text-center">Jurys</h1>
    </div>
    <div class="case rounded shadow border pt-1 p-md-3">
      <liste-jurys />
    </div>
  </div>
</template>

<script>
import ListeJurys from "@/components/ListeJurys";

export default {
  name: "GestionJurys",
  components: { ListeJurys },
};
</script>

<style scoped></style>
