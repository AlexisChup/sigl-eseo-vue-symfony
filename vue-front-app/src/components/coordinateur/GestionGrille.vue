<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Grille d'évaluation</h1>
    </div>
    <div class="row justify-content-around m-0 p-0">
      <div class="case rounded shadow border col-12 col-md-3 p-3">
        <div class="row">
          <div class="col-12 mb-3 text-center">
            <div v-if="isNewGrilleLoading">
              <div
                class="spinner-border text-primary"
                role="status"
                style="width: 2rem; height: 2rem"
              >
                <span class="sr-only">Loading...</span>
              </div>
            </div>
            <div v-if="!isNewGrilleLoading">
              <input
                type="button"
                class="col-12 btn btn-primary"
                value="Ajouter une grille"
                v-on:click="openAjouterGrilleModal()"
              />
            </div>
          </div>
        </div>
        <div
          class=""
          v-for="(grille_st, index) of grilles"
          v-bind:key="index"
        >
          <button
            class="col-12 btn btn-outline-primary my-1"
            v-on:click="showGrille(grille_st)"
          >
            {{ grille_st.grille.nom }}
          </button>
        </div>
      </div>
      <div class="case rounded shadow border col-12 col-md-8 p-3">
        <div v-if="grilleShow != null && grilleShow.grille != null">
          <h2 class="row col-12 m-0 p-0 justify-content-between">
            <p class="col-10 text-start">
              {{ grilleShow.grille.nom }}
            </p>
            <div class="row col-2 justify-content-between">
              <div class="">
                <i class="fa-solid fa-pencil" style="cursor: pointer"></i>
              </div>
              <div class="">
                <i
                  class="fa-solid fa-trash"
                  style="cursor: pointer"
                  v-on:click="openRemoveGrilleModal(grilleShow)"
                ></i>
              </div>
            </div>
          </h2>

          <div
            class="case rounded shadow border col-12 p-3 mb-3"
            v-for="(categ_st, index) in grilleShow.categories"
            :key="index"
          >
            <h3 class="row col-12 m-0 p-0 justify-content-between">
              <p class="col-10 m-0 text-start">
                {{ categ_st.categorie.contenu }}
              </p>
              <div class="row col-2 justify-content-between">
                <div>
                  <i
                    class="fa-solid fa-pencil"
                    style="cursor: pointer"
                  ></i>
                </div>
                <div>
                  <i
                    class="fa-solid fa-trash"
                    style="cursor: pointer"
                    v-on:click="openRemoveCategoryModal(categ_st)"
                  ></i>
                </div>
              </div>
            </h3>
            <hr />
            <div
              class="row m-0 p-0 mb-3"
              v-for="(critere, index) in categ_st.criteres"
              :key="index"
            >
              <textarea
                class="form-control text-start col-12 col-md-9"
                v-model="critere.contenu"
                v-on:change="modifierCritere(critere)"
              />
              <div class="col-6 col-md-3">
                <div class="row col-12 m-0 p-0 justify-content-between">
                  <p class="col-6 mb-1 p-0 px-1">Coef.</p>
                  <div>
                    <i
                      class="fa-solid fa-trash"
                      style="cursor: pointer"
                      v-on:click="openRemoveCritereModal(critere)"
                    ></i>
                  </div>
                  <input
                    class="form-control col-12"
                    type="number"
                    step="0.01"
                    v-model="critere.coefficient"
                    v-on:change="modifierCritere(critere)"
                  />
                </div>
              </div>
            </div>
            <div>
              <input
                type="button"
                class="btn btn-primary mt-1"
                value="Ajouter un critère"
                v-on:click="ajouterCritere(grilleShow, categ_st)"
              />
            </div>
          </div>
          <div>
            <input
              type="button"
              class="btn btn-primary my-1"
              value="Ajouter une catégorie"
              v-on:click="openAjouterCategoryModal()"
            />
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="modalAjoutGrille"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalAjoutGrilleLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalAjoutGrille
          :type="typeAjoutModal"
          :confirmAjouterGrille="confirmAjouterGrille"
          :confirmAjouterCategory="confirmAjouterCategory"
        />
      </div>
    </div>
    <div
      class="modal fade"
      id="modalRemove"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalRemoveLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalRemove
          :callbackConfirm="confirmRemoveGrille"
          :name="grilleToRemove.grille.nom"
        />
      </div>
    </div>
    <div
      class="modal fade"
      id="modalRemoveCat"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalRemoveLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalRemove
          :callbackConfirm="confirmRemoveCategory"
          :name="categoryToRemove.categorie.contenu"
        />
      </div>
    </div>
    <div
      class="modal fade"
      id="modalRemoveCrit"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalRemoveLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalRemove
          :callbackConfirm="confirmRemoveCritere"
          :name="critereToRemove.contenu"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import ModalAjoutGrille from "./ModalAjoutGrille";
import ModalRemove from "../utils/ModalRemove";

export default {
  name: "GestionGrille",
  components: { ModalAjoutGrille, ModalRemove },

  data() {
    return {
      isNewGrilleLoading: false,

      newGrille: { nom: "" },
      newCategorie: { grille: Object, contenu: "" },
      newCritere: { grille: Object, categorie: Object, contenu: "" },
      lastCritereContenu: "",

      grilles: [],
      grilleShow: Object,
      grilleToRemove: {
        grille: {
          nom: "",
        },
      },
      categoryToRemove: { categorie: { contenu: "" } },
      critereToRemove: {},

      typeAjoutModal: "grille",
    };
  },

  created() {
    this.getGrille().then(() => {
      if (this.grilles.length > 0) {
        this.showGrille(this.grilles[0]);
      }
    });
  },

  methods: {
    openRemoveCritereModal(critere) {
      this.critereToRemove = critere;
      $("#modalRemoveCrit").modal("show");
    },

    confirmRemoveCritere() {
      $("#modalRemoveCrit").modal("hide");
      this.removeCritere(this.critereToRemove);
    },

    async removeCritere(critere) {
      try {
        const response = await API_Helper.delete(
          "/critere/remove/" + critere.id,
          critere
        );
        await this.getGrille();
        await this.showGrilleId(this.grilleShow.grille.id);
        createToast("Le critère a été supprimé", { type: "success" });
      } catch (error) {
        createToast("Impossible de supprimer le critère", { type: "danger" });
        console.log(error);
      }
    },

    openRemoveCategoryModal(categ_st) {
      this.categoryToRemove = categ_st;
      $("#modalRemoveCat").modal("show");
    },

    confirmRemoveCategory() {
      $("#modalRemoveCat").modal("hide");
      this.removeCategorie(this.categoryToRemove);
    },

    async removeCategorie(categ_st) {
      try {
        const response = await API_Helper.delete(
          "/critere/remove/" + categ_st.categorie.id,
          categ_st.categorie
        );
        await this.getGrille();
        await this.showGrilleId(this.grilleShow.grille.id);
        createToast("La catégorie a été supprimé", { type: "success" });
      } catch (error) {
        createToast("Impossible de supprimer la catégorie", { type: "danger" });
        console.log(error);
      }
    },

    openRemoveGrilleModal(grille_st) {
      this.grilleToRemove = grille_st;
      $("#modalRemove").modal("show");
    },

    confirmRemoveGrille() {
      $("#modalRemove").modal("hide");
      this.removeGrille(this.grilleToRemove);
    },

    async removeGrille(grille_st) {
      try {
        const response = await API_Helper.delete(
          "/grille/remove/" + grille_st.grille.id,
          grille_st.grille
        );
        await this.getGrille();
        if (this.grilles.length > 0) {
          await this.showGrille(this.grilles[0]);
        }
        createToast("La grille a été supprimé", { type: "success" });
      } catch (error) {
        createToast("Impossible de supprimer la grille", { type: "danger" });
        console.log(error);
      }
    },

    async modifierCritere(critere) {
      try {
        critere.coefficient =
          critere.coefficient == "" ? null : critere.coefficient;
        const response = await API_Helper.put(
          "/critere/update/" + critere.id,
          critere
        );
      } catch (error) {
        createToast("Impossible de modifier le critère", { type: "danger" });
        console.log(error);
      }
    },

    async ajouterCritere(grille_st, categ_st) {
      this.newCritere.grille = grille_st.grille;
      this.newCritere.parent = categ_st.categorie;
      this.newCritere.contenu = "";

      try {
        const response = await API_Helper.post(
          "/critere/create",
          this.newCritere
        );
        await this.getGrille();
        await this.showGrilleId(this.grilleShow.grille.id);
        createToast("Le critère a été ajouté", { type: "success" });
      } catch (error) {
        createToast("Impossible d'ajouter le critère", { type: "danger" });
        console.log(error);
      }
    },

    openAjouterCategoryModal() {
      this.typeAjoutModal = "category";
      $("#modalAjoutGrille").modal("show");
    },

    confirmAjouterCategory(nom) {
      this.ajouterCategorie(nom);
      $("#modalAjoutGrille").modal("hide");
    },

    async ajouterCategorie(nom) {
      this.newCategorie.grille = this.grilleShow.grille;
      this.newCategorie.contenu = nom;

      if (this.newCategorie.contenu !== null) {
        try {
          const response = await API_Helper.post(
            "/critere/create",
            this.newCategorie
          );
          await this.getGrille();
          await this.showGrilleId(this.grilleShow.grille.id);
          createToast("La catégorie a été créé", { type: "success" });
        } catch (error) {
          createToast("Impossible de créer la catégorie", { type: "danger" });
          console.log(error);
        }
      }
    },

    async showGrille(grille_st) {
      this.grilleShow = grille_st;
    },

    async showGrilleId(idGrille) {
      this.showGrille(
        this.grilles.find((grille) => grille.grille.id === idGrille)
      );
    },

    async getGrille() {
      this.grilles = (await API_Helper.get("/grille/grille/criteres")).data;
    },

    openAjouterGrilleModal() {
      this.typeAjoutModal = "grille";
      $("#modalAjoutGrille").modal("show");
    },

    confirmAjouterGrille(nom) {
      this.ajouterGrille(nom);
      $("#modalAjoutGrille").modal("hide");
    },

    async ajouterGrille(nom) {
      if (nom !== null) {
        this.isNewGrilleLoading = true;
        this.newGrille.nom = nom;
        try {
          const response = await API_Helper.post(
            "/grille/create",
            this.newGrille
          );
          await this.getGrille();
          await this.showGrilleId(response.data.id);
          createToast("La grille d'évaluation a été créé", { type: "success" });
        } catch (error) {
          createToast("Impossible de créer la grille d'évaluation", {
            type: "danger",
          });
          console.log(error);
        } finally {
          this.isNewGrilleLoading = false;
        }
      }
    },
  },
};
</script>

<style scoped></style>
