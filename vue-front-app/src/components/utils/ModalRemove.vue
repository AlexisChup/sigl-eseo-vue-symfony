<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalRemoveLabel">Supprimer {{ name }}</h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">Voulez-vous vraiment supprimer {{ name }} ?</div>
    <div class="modal-footer">
      <div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Fermer
        </button>
      </div>
      <div>
        <button class="btn btn-danger ml-2" v-on:click="modalConfirm">
          Supprimer
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "ModalRemove",
  props: {
    name: String,
    callbackConfirm: { type: Function },
  },
  methods: {
    modalConfirm() {
      this.callbackConfirm();
    },
  },
};
</script>
<style scoped></style>
