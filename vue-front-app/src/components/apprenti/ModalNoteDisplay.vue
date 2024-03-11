<template>
  <div class="modal-dialog-centered">
    <div v-if="note.id" class="modal-content" id="modal-note-periode">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCreateNoteLabel">
          {{ note.titre }}
        </h5>
      </div>
      <div class="modal-body">
        <div class="container">
          {{ note.contenu }}
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <p>Semestre {{ note.semestre }}</p>
        <p>{{ note.date.slice(0, 10) }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ModalNoteDisplay",
  props: {
    note: Object
  }
}
</script>

<style scoped>
.modal-body {
  min-height: 200px;
}
</style>