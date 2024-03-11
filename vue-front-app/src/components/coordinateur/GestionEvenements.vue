<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Évènements</h1>
    </div>

    <div class="row m-2">
      <h2>{{ this.pageTitle }}</h2>
      <div class="col-12 row m-0 p-0 justify-content-between">
        <div class="case rounded shadow border col-12 col-md-6 p-3">
          <div class="form-group">
            <label for="inputType">Type</label>
            <select class="custom-select" name="inputType" v-model="data.type" v-on:change="typeEvtChanged">
              <option value="0">Livrable</option>
              <option value="1">Entretien semestriel</option>
              <option value="2">Soutenance</option>
            </select>
          </div>

          <div class="form-group">
            <label for="inputNom">Nom</label>
            <input type="text" class="form-control" name="inputNom" v-model="data.nom" v-on:change="checkValidForm">
          </div>

          <div class="form-group">
            <label for="inputConcerne">Promotion concernée</label>
            <select class="col-12 custom-select" name="inputConcerne"
                    v-model="data.promo" required v-on:change="checkValidForm">
              <option v-for="(promo, index) in this.promotions" :value="promo.id" :key="index">
                {{ promo.nom }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="inputInitiateur">Initiateur</label>
            <select class="custom-select" name="inputInitiateur" v-model="data.initiateur">
              <option value="Apprenant">Apprenant</option>
              <option value="Maître d'apprentissage">Maître d'apprentissage</option>
              <option value="ESEO">ESEO</option>
            </select>
          </div>

          <div class="form-group">
            <label for="inputConcerne">Personnes engagées</label>
            <input type="text" class="form-control" name="inputConcerne" v-model="data.concerne"
                   placeholder="Equipe tutorale ...">
          </div>
        </div>

        <div class="case rounded shadow border col-12 col-md-5 d-flex flex-column p-3 mt-md-0" id="secondDiv">

          <div class="form-group mb-0" v-if="data.type == 0">
            <label for="inputFin">Date butoir de restitution</label>
            <input class="form-control" type="date" v-model="data.fin" v-on:change="checkValidForm">
          </div>

          <div class="form-group" v-if="data.type == 1">
            <label for="inputDebut" class="mt-2">Début de la période des entretiens</label>
            <input class="form-control" type="date" v-model="data.debut" v-on:change="checkValidForm">
            <label for="inputFin" class="mt-2">Fin de la période des entretiens</label>
            <input class="form-control" type="date" v-model="data.fin" v-on:change="checkValidForm">
          </div>

          <div class="form-group" v-if="data.type == 2">
            <label for="inputDebut" class="mt-2">Début de la période des soutenances</label>
            <input class="form-control mt-2" type="date" v-model="data.debut" v-on:change="checkValidForm">
            <label for="inputFin" class="mt-2">Fin de la période des soutenances</label>
            <input class="form-control" type="date" v-model="data.fin" v-on:change="checkValidForm">
          </div>

          <div class="form-group d-flex flex-column flex-grow-1 mt-2">
            <label for="inputRemarque">Remarques</label>
            <textarea type="textfield" class="form-control flex-grow-1" name="inputConcerne"
                      v-model="data.remarque"></textarea>
          </div>
          <div class="col-12 mt-3" v-if="isLoading">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <div class="" v-if="!isLoading">
            <input type="submit" class="btn btn-primary btn-lg btn-block"
                   :disabled="!correctForm"
                   v-on:click="controlInputEvent" value="Valider">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {API_Helper} from "@/api/helper_api";
import {createToast} from "mosha-vue-toastify";
import consts from '@/static/const'

export default {
  name: "GestionEvenements",

  data() {
    return {
      isLoading: false,
      correctForm: false,
      eventToEdit: {},
      pageTitle: '',

      data: {
        type: '',
        nom: '',
        promo: '',
        debut: '',
        fin: '',
        initiateur: '',
        concerne: '',
        grille: '',
        remarque: '',
      },

      promotions: {},

      jurys: {
        utilisateur: {
          id: '',
          nom: '',
          prenom: '',
        }
      },

      apprentis: {
        utilisateur: {
          id: '',
          nom: '',
          prenom: '',
        }
      },
    }
  },

  created() {
    this.resetChamps();
    this.promoGet();
    this.apprentiGet();
  },

  async beforeMount() {
    if (this.$route.params.id != undefined) {
      this.pageTitle = 'Edition d\'un évènement';
      this.eventToEdit = await API_Helper.get(`event/${this.$route.params.id}`)
          .then(res => {
            return res.data
          })

      if (this.eventToEdit) {

        //restore data

        this.data.concerne = this.eventToEdit.concerne;
        this.data.nom = this.eventToEdit.nom;
        this.data.type = this.eventToEdit.type
        this.data.remarque = this.eventToEdit.remarque
        this.data.debut = this.eventToEdit.dateDebut.slice(0, 10);
        if(this.eventToEdit.dateFin != null)
          this.data.fin = this.eventToEdit.dateFin.slice(0, 10);
        this.data.promo = this.eventToEdit.promotion.id;

        this.correctForm = true
      }
    } else {
      this.pageTitle = 'Création d\'un nouvel évènement';
    }
  },

  methods: {
    async formSubmit(e) {
      this.isLoading = true;
      e.preventDefault();

      if (this.$route.params.id != undefined) {

        await API_Helper.put(`/event/${this.$route.params.id}`, this.data)
            .then((response) => {
              createToast(
                  "L'évènement a été modifier",
                  {type: "success"}
              );
              this.resetChamps();
              this.$router.push('/evenements');
            })
            .catch((e) => {
              createToast(
                  "Modification impossible",
                  {type: "danger"}
              );
            })

      } else {

        await API_Helper.post("/event/create", this.data)
            .then((response) => {
              createToast(
                  "L'évènement a été créé",
                  {type: "success"}
              );
              this.resetChamps();
              this.$router.push('/evenements');
            })
            .catch((e) => {
              createToast(
                  "Impossible de créer l'évènement",
                  {type: "danger"}
              );
            })
      }
      this.isLoading = false;
    },

    resetChamps() {
      this.data.type = '0';
      this.data.nom = '';
      this.data.promo = '';
      this.data.butoir = '';
      this.data.debut = '';
      this.data.fin = '';
      this.data.initiateur = '';
      this.data.concerne = '';
      this.data.grille = '';
    },

    promoGet() {
      API_Helper.get("/promotion/promotions")
          .then((response) => {
            this.promotions = response.data;
          })
    },

    apprentiGet() {
      API_Helper.get("/apprenti/apprenti")
          .then((response) => {
            this.apprentis = response.data;
          })
    },

    controlInputSoutenance(e) {
      let now = new Date();
      let butoir = new Date(this.data.butoir);

      if (butoir.getTime() < now.getTime()) {
        createToast(
            "L'échéance doit être supérieure à la date du jour",
            {type: "danger"}
        );
      } else if (this.data.fin === '') {
        createToast(
            "Veuillez renseigner la date de fin",
            {type: "danger"}
        );
      }

      if (this.data.nom === "") {
        createToast(
            "Veuillez saisir un nom à l'évènement",
            {type: "danger"}
        );
      } else if (this.data.enseignantsConcernes === "") {
        createToast(
            "Veuillez sélectionner la ou les enseignant(e)(s) concerné(e)(s) par l'évènement",
            {type: "danger"}
        );
      } else if (this.data.apprentiConcerne === "") {
        createToast(
            "Veuillez sélectionner l'apprenti concerné par l'évènement",
            {type: "danger"}
        );
      } else {
        this.formSubmit(e);
      }
    },

    controlInputEvent(e) {
      let now = new Date();
      let butoir = new Date(this.data.butoir);
      let debut = new Date(this.data.debut);
      let fin = new Date(this.data.fin);

      let error = false;
      if (this.data.type === "") {
        error = true;
        createToast(
            "Vous devez sélectionner un type d'évènement",
            {type: "danger"}
        );
      } else {
        if (this.data.type === "Livrable" || this.data.type === "Entretien semestriel") {
          // Gestion date butoir
          if (this.data.fin === '') {
            error = true;
            createToast(
                "Veuillez renseigner la date de fin",
                {type: "danger"}
            );
          } else if (butoir.getTime() < now.getTime()) {
            error = true;
            createToast(
                "L'échéance doit être supérieure à la date du jour",
                {type: "danger"}
            );
          }
        }
        // Gestion plage de dates
        if (this.data.type === 'Entretien semestriel' || this.data.type === "Soutenance") {
          if (this.data.debut === '' || this.data.fin === '') {
            if (this.data.debut === '') {
              error = true;
              createToast(
                  "Veuillez renseigner la date de début de l'évènement",
                  {type: "danger"}
              );
            }
            if (this.data.fin === '') {
              error = true;
              createToast(
                  "Veuillez renseigner la date de fin de l'évènement",
                  {type: "danger"}
              );
            }
          } else {
            if (fin.getTime() < now.getTime()) {
              error = true;
              createToast(
                  "L'échéance doit être supérieure à la date du jour",
                  {type: "danger"}
              );
            } else if (debut.getTime() > fin.getTime()) {
              error = true;
              createToast(
                  "La date de fin doit être supérieure à la date de début",
                  {type: "danger"}
              );
            }
          }
        }
      }

      if (this.data.nom === "") {
        error = true;
        createToast(
            "Veuillez saisir un nom à l'évènement",
            {type: "danger"}
        );
      }

      if (this.data.promo === "") {
        error = true;
        createToast(
            "Veuillez sélectionner la ou les promotions concernées par l'évènement",
            {type: "danger"}
        );
      }
      if (!error) {
        this.formSubmit(e);
      }
    },

    checkValidForm() {

      const today = new Date().toISOString().slice(0, 10);

      if (this.data.type == 2 || this.data.type == 1) {
        this.correctForm = (this.data.debut >= today && this.data.fin >= this.data.debut);
      }

      if (this.data.type == 0) {
        this.correctForm = (this.data.fin >= today);
      }

      this.correctForm &= this.data.promo != '';
      this.correctForm &= this.data.nom != '';
    },

    typeEvtChanged() {
      this.data.fin = '';
      this.data.debut = '';
      this.correctForm = false;
    }
  },

}

</script>

<style scoped>

@media (max-width: 768px) {
  #secondDiv {
    margin-top: 1em;
  }
}

</style>