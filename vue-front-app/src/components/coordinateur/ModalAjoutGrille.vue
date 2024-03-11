<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5
        v-if="this.type === 'grille'"
        class="modal-title"
        id="modalAjoutGrille"
      >
        Veuillez saisir le nom de la grille d'évaluation
      </h5>
      <h5
        v-if="this.type === 'category'"
        class="modal-title"
        id="modalAjoutGrille"
      >
        Veuillez saisir le nom de la catégorie
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="container">
        <label v-if="this.type === 'grille'">Nom de la grille</label>
        <label v-if="this.type === 'category'">Nom de la catégorie</label>
        <input
          type="text"
          id="grilleNom"
          class="form-control form-control-sm"
          v-model="grilleNom"
          v-on:change="handleInput"
        />
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <button class="btn btn-success ml-2" v-on:click="modalConfirm()">
        Ajouter
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "ModalAjoutGrille",
  props: {
    confirmAjouterGrille: { type: Function },
    confirmAjouterCategory: { type: Function },
    type: String,
  },
  methods: {
    handleInput(event) {
      this.grilleNom = event.target.value;
    },
    modalConfirm() {
      if (this.type === "grille") {
        this.confirmAjouterGrille(this.grilleNom);
      } else if (this.type === "category") {
        this.confirmAjouterCategory(this.grilleNom);
      }
      this.grilleNom = "";
    },
  },
  data() {
    return {
      grilleNom: "",
    };
  },
};
</script>
<style></style>
