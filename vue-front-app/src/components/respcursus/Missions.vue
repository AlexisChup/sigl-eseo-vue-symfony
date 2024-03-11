<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Missions des apprentis</h1>
    </div>
    <div class="col-12 col-md-6 mb-3">
      <label for="promotion" name="promotion"> Promotion :</label>
      <select name="promotion" class="form-control" @change="onChange($event)">
        <option
          v-for="(promo, index) in promotions"
          :key="index"
          :value="promo.id"
        >
          {{ promo.nom }}
        </option>
      </select>
    </div>
    <div class="row">
      <div class="case rounded shadow border p-1 p-md-3">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" scope="col">Apprenti</th>
              <th class="text-center" scope="col">Email</th>
              <th class="text-center" scope="col">Missions</th>
              <th class="text-center" scope="col">Validées</th>
              <th class="text-center" scope="col"></th>
            </tr>
          </thead>
          <tbody v-if="apprentis">
            <tr v-for="(apprenti_list, index) in apprentis" :key="index">
              <td class="text-center" scope="row">
                {{ apprenti_list.utilisateur.prenom }}
                <span style="text-transform: uppercase">{{
                  apprenti_list.utilisateur.nom
                }}</span>
              </td>
              <td class="text-center">{{ apprenti_list.utilisateur.email }}</td>
              <td class="text-center">{{ apprenti_list.mission }}</td>
              <td class="text-center" v-if="apprenti_list.missionValide">
                <i class="fa-solid fa-check text-success"></i>
              </td>
              <td
                class="text-center"
                v-if="
                  !apprenti_list.missionValide &&
                  apprenti_list.missionValide != null
                "
              >
                <i class="fa-solid fa-xmark text-danger"></i>
              </td>
              <td
                class="text-center"
                v-if="apprenti_list.missionValide == null"
              >
                <i class="fa-solid fa-question text-warning"></i>
              </td>
              <td>
                <i
                  v-on:click="editMissionsById(apprenti_list.id)"
                  style="cursor: pointer"
                  class="fa fa-pencil d-flex justify-content-center"
                  aria-hidden="true"
                ></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="ModalMission"
      tabindex="-1"
      role="dialog"
      aria-labelledby="ModalEditMissionLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalMission
          v-bind:apprenti="apprenti"
          @getMissionsValides="getMissionsValides"
          v-bind:action="'edit'"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import "mosha-vue-toastify/dist/style.css";
import ModalMission from "@/components/respcursus/ModalMission";

export default {
  name: "Mission",
  components: { ModalMission },
  data() {
    return {
      promotions: [],
      promotion: {
        id: "",
        nom: "",
        annee: "",
      },
      apprentis: [],
      apprenti: {
        id: "",
        mission: "",
        mission_valide: "",
        maitreApprentissage: {
          id: "",
          entreprise: {
            nom: "",
          },
        },
        utilisateur: {
          id: "",
          email: "",
          nom: "",
          prenom: "",
          adresse: "",
        },
      },
    };
  },
  methods: {
    async getMissionsValides() {
      this.requestDone = false;
      this.apprentis = [];
      this.apprentis = await API_Helper.get("/apprenti/apprenti")
        .then((response) => {
          return response.data;
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
      this.requestDone = true;
    },

    apprentiByPromo(idToShow) {
      this.apprentis = [];
      API_Helper.get("/apprenti/promotion/" + idToShow)
        .then((response) => {
          this.apprentis = response.data;
        })
        .catch((e) => {});
    },

    getPromotion() {
      this.promotions = [];
      API_Helper.get("/promotion/promotions")
        .then((response) => {
          this.promotions = response.data;
          this.apprentiByPromo(this.promotions[0].id);
        })
        .catch((e) => {});
    },

    editMissionsById(idToEdit) {
      API_Helper.get("/apprenti/" + idToEdit)
        .then((response) => {
          this.apprenti = response.data;
          this.modalAction = "edit";
          $("#ModalMission").modal("show");
        })
        .catch((e) => {});
    },

    async mounted() {
      this.requestDone = false;
      await this.getMissionsValides();
    },

    onChange(event) {
      this.apprentiByPromo(event.target.value);
    },
  },

  onBeforeUpdate() {},

  created() {
    this.getPromotion();
  },
};
</script>

<style scoped></style>
