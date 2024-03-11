<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Informations de l'apprenti</h1>
    </div>

    <div class="row justify-content-between">
      <div class="case rounded shadow border p-1 p-md-3 col-12 col-md-5 mb-3">
        <div class="mb-3">
          <label for="promo" name="promo">Promotion</label>
          <select
            class="form-control"
            name="promo"
            v-model="id_promo"
            id="elpromo"
          >
            <option
              v-for="(promo, index) in promoList"
              :key="index"
              v-bind:value="promo.id"
            >
              {{ promo.annee }} - {{ promo.nom }}
            </option>
          </select>
        </div>
      </div>

      <div class="case rounded shadow border p-1 p-md-3 col-12 col-md-6 mb-3">
        <div class="mb-3">
          <label for="ma" Name="ma">Maître d'apprentissage</label>
          <select class="form-control" name="ma" v-model="id_ma" id="elma">
            <option
              v-for="(ma, index) in maList"
              :key="index"
              v-bind:value="ma.id"
            >
              {{ ma.prenom }} {{ ma.nom }}
            </option>
          </select>
        </div>

        <div class="mb-3">
          <label for="tp" Name="tp">Tuteur pédagogique</label>
          <select class="form-control" name="ma" v-model="id_tp" id="eltp">
            <option v-for="(tp, index) in tpList" :key="index" v-bind:value="tp.id">
              {{ tp.prenom }} {{ tp.nom }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="case rounded shadow border p-1 p-md-3 col-12 mb-3">
        <div class="row">
          <h2 class="col-12 col-md-10">Missions</h2>
          <div class="col-2">
            <div class="form-group">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  v-model="mission_valide"
                />
                <label class="form-check-label" for="invalidCheck"
                  >Validées</label
                >
              </div>
            </div>
          </div>
        </div>
        <hr class="mt-2" />
        <textarea
          class="form-control"
          type="Text"
          Name="mission"
          v-model="mission"
          placeholder="Description des missions confiées à l'apprenti"
        />
      </div>
    </div>

    <div class="row mb-3">
      <button class="btn btn-primary btn-block" v-on:click="enregistrer()">
        Enregistrer
      </button>
    </div>

    <div class="row">
      <div class="case rounded shadow border p-1 p-md-3 col-12 mb-3">
        <h2>Ajouter un jury</h2>
        <hr />
        <div class="row m-0 justify-content-between">
          <div class="p-1 p-md-3 col-12 col-md-5 mb-3">
            <div class="mb-3">
              <label for="evenement" Name="evenement">Evenement</label>
              <select class="form-control" Name="evenement">
                <option value="S07">S07 - Soutenance de synthèse</option>
                <option value="S08">S08 - Soutenance avant-PING</option>
                <option value="S09">S09 - Soutenance PING</option>
              </select>
            </div>

            <div class="form-group">
              <label for="debut" id="date">Début</label>
              <div class="form-row">
                <div class="col-7">
                  <input
                    class="form-control"
                    type="Date"
                    name="dayDebut"
                    placeholder="Début"
                  />
                </div>
                <div class="col-5">
                  <input class="form-control" type="time" name="appt" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="fin" id="date">Fin</label>
              <div class="form-row">
                <div class="col-7">
                  <input
                    class="form-control"
                    type="Date"
                    Name="dayFin"
                    placeholder="Fin"
                  />
                </div>
                <div class="col-5">
                  <input
                    class="form-control"
                    type="time"
                    id="appt"
                    name="appt"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="p-1 p-md-3 col-12 col-md-6 mb-3">
            <div class="form-group">
              <label for="jury1" Name="jury1">Jury n°1</label>
              <select class="form-control" Name="jury1">
                <option value="">Sébastien Ménigot</option>
                <option value="">Sébastien Scotto</option>
                <option value="">Slimane Hammoudi</option>
              </select>
            </div>

            <div class="form-group">
              <label for="jury2" Name="jury2">Jury n°2</label>
              <select class="form-control" Name="jury2">
                <option value="">Sébastien Ménigot</option>
                <option value="">Sébastien Scotto</option>
                <option value="">Slimane Hammoudi</option>
              </select>
            </div>
          </div>

          <button
            class="btn btn-outline-primary btn-block"
            v-on:click="enregistrer()"
          >
            Ajouter le jury
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";

export default {
  el: "#app",
  name: "InfosApprenti",

  beforeMount() {
    this.getTP();
    this.getMA();
    this.getPromo();
  },
  data() {
    return {
      promoList: [],
      maList: [],
      tpList: [],

      id_ma: "",
      id_tp: "",
      id_promo: "",
      mission: "",
      missionValide: "",
    };
  },
  methods: {
    getTP() {
      API_Helper.get("/enseignant/all")
        .then((response) => {
          response.data.forEach((obj) => {
            this.tpList.push({
              id: obj.id,
              prenom: obj.idUser.prenom,
              nom: obj.idUser.nom,
            });
          });
        })
        .catch((error) => console.log("Error : " + error));
    },
    getMA() {
      API_Helper.get("/maitre-apprentissage/all")
        .then((response) => {
          response.data.forEach((obj) => {
            this.maList.push({
              id: obj.id,
              prenom: obj.idUser.prenom,
              nom: obj.idUser.nom,
            });
          });
        })
        .catch((error) => console.log("Error : " + error));
    },
    getPromo() {
      API_Helper.get("/promotion/index")
        .then((response) => {
          response.data.forEach((obj) => {
            this.promoList.push({
              id: obj.id,
              nom: obj.nom,
              annee: obj.annee,
            });
          });
        })
        .catch((error) => console.log("Error : " + error));
    },
    enregistrer() {
      let data_apprenti = {
        id_promotion_id: parseInt(this.id_promo),
        id_ma_id: parseInt(this.id_ma),
        id_enseignant_id: parseInt(this.id_tp),
        mission: this.mission,
        missionValide: this.missionValide,
      };

      let toSend = true;
      if (isNaN(data_apprenti.id_ma_id)) {
        toSend = false;
        document.querySelector("#elma").style.borderColor = "red";
        createToast("Veuillez sélectionner un maître d'apprentissage", {
          type: "danger",
        });
      }
      if (isNaN(data_apprenti.id_enseignant_id)) {
        toSend = false;
        document.querySelector("#eltp").style.borderColor = "red";
        createToast("Veuillez sélectionner un tuteur pédagogique", {
          type: "danger",
        });
      }
      if (isNaN(data_apprenti.id_promotion_id)) {
        toSend = false;
        document.querySelector("#elpromo").style.borderColor = "red";
        createToast("Veuillez sélectionner une promotion", { type: "danger" });
      }

      if (toSend) {
        API_Helper.post("/apprenti/update/4", data_apprenti)
          .then((response) => {
            createToast("Les informations de l'apprenti sont enregistrées", {
              type: "success",
            });
          })
          .catch((error) => {
            console.log("Error : " + error);
            createToast("Impossible d'enregistrer les informations", {
              type: "danger",
            });
          });
      }
    },
  },
};
</script>

<style Scoped></style>
