<template>
  <div>
    <div>
      <h1 class="text-center">Gestion des jurys</h1>
    </div>
    <h2>{{ this.pageTitle }}</h2>
    <div class="case rounded shadow border pt-1 p-md-3">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-8 col-md-10">
          <span>
            <strong><p>Apprenti concerné :</p></strong>
            <p v-if="this.$route.params.id !== undefined && requestDone">
              {{ apprenti.utilisateur.nom }} {{ apprenti.utilisateur.prenom }} -
              {{ apprenti.idPromotion.nom }} {{ apprenti.idPromotion.annee }}
            </p>
          </span>

          <select
            class="form-select custom-select"
            v-model="apprenti"
            v-on:change="update"
            v-if="this.$route.params.id === undefined"
          >
            <option
              v-for="(app, index) in this.apprentis"
              :key="index"
              :value="app"
            >
              {{ app.utilisateur.nom }} {{ app.utilisateur.prenom }} -
              {{ app.idPromotion.nom }} {{ app.idPromotion.annee }}
            </option>
          </select>
          <span>
            <strong><p class="mt-3">Évènement :</p></strong>
            <p v-if="this.$route.params.id !== undefined && requestDone">
              {{ event.nom }} - {{ event.promotion.nom }}
              {{ event.promotion.annee }}
            </p>
          </span>

          <select
            class="form-select custom-select"
            v-model="event"
            v-if="this.$route.params.id === undefined"
          >
            <option
              v-for="(evt, index) in this.events"
              :key="index"
              :value="evt"
            >
              {{ evt.nom }} - {{ evt.promotion.nom }} {{ evt.promotion.annee }}
            </option>
          </select>
          <span>
            <strong><p class="mt-3">Menbres du jury :</p></strong>
          </span>
          <VueMultiselect
            v-model="audience"
            :options="this.auditeurs"
            :multiple="true"
            :close-on-select="false"
            :clear-on-select="false"
            :preserve-search="true"
            placeholder="Selectionner les membres du jury"
            label="name"
            track-by="id"
            :preselect-first="true"
          >
            <!-- eslint-disable -->
            <template slot="selection" slot-scope="{ values, search, isOpen }">
              <span
                class="multiselect__single"
                v-if="values.length &amp;&amp; !isOpen"
              >
                {{ values.length }} options selected
              </span>
            </template>
            <!-- eslint-enable -->
          </VueMultiselect>
          <div class="text-center">
            <button
              type="button"
              class="btn btn-primary mt-3"
              v-if="this.$route.params.id === undefined"
              v-on:click="createJury"
            >
              Valider
            </button>
            <button
              type="button"
              class="btn btn-primary mt-3"
              v-if="this.$route.params.id !== undefined"
              v-on:click="updateJury"
            >
              Valider
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { EVENT_TYPE_SOUTENANCE } from "@/static/const";
import VueMultiselect from "vue-multiselect";
import { createToast } from "mosha-vue-toastify";

export default {
  name: "GestionJury",
  components: {
    VueMultiselect,
  },

  data() {
    return {
      pageTitle: "",
      apprentis: [],
      events: [],
      auditeurs: [],
      requestDone: false,
      apprenti: null,
      event: null,
      audience: null,
    };
  },

  async created() {
    this.apprentis = await API_Helper.get(`/apprenti/apprenti`).then(
      (res) => res.data
    );

    this.events = await API_Helper.get(
      `event/type/${EVENT_TYPE_SOUTENANCE}`
    ).then((res) => res.data);

    this.auditeurs = await API_Helper.get(`/jury/possible-auditeurs`).then(
      (res) =>
        res.data.map((user) => {
          return {
            id: user.utilisateur.id,
            name: `${user.utilisateur.nom} ${user.utilisateur.prenom}`,
          };
        })
    );

    conso;
  },

  async mounted() {
    if (this.$route.params.id !== undefined) {
      this.pageTitle = "Edition d'un jury";

      const juryToEdit = await API_Helper.get(
        `/jury/${this.$route.params.id}`
      ).then((res) => res.data);

      this.event = juryToEdit.evenement;
      this.apprenti = juryToEdit.apprenti;
      this.audience = juryToEdit.auditeur.map((user) => {
        return { id: user.id, name: `${user.nom} ${user.prenom}` };
      });
    } else {
      this.pageTitle = "Création d'un jury";
    }

    this.requestDone = true;
  },

  methods: {
    createJury() {
      if (
        this.apprenti === null ||
        this.event === null ||
        this.audience === null
      ) {
        createToast("Veuillez renseigner tous les champs de saisie.", {
          type: "warning",
        });
      } else if (this.audience.length === 0) {
        createToast("Veuillez renseigner tous les champs de saisie.", {
          type: "warning",
        });
      } else if (this.event.promotion.id !== this.apprenti.idPromotion.id) {
        createToast(
          "L'évènement et l'apprenti ne sont pas rattachés à la même promotion.",
          { type: "warning" }
        );
      } else {
        API_Helper.post(`/jury/create`, {
          appId: this.apprenti.id,
          eventId: this.event.id,
          audience: this.audience.map((auditeur) => auditeur.id),
        })
          .then((response) => {
            createToast("Le jury a été créé.", { type: "success" });
            this.$router.push("/jurys");
          })
          .catch((err) => {
            if (err.response.status === 409) {
              createToast(err.response.data, { type: "warning" });
            } else {
              createToast("Impossible de créer le jury.", { type: "danger" });
            }
          });
      }
    },

    updateJury() {
      if (
        this.apprenti === null ||
        this.event === null ||
        this.audience === null
      ) {
        createToast("Veuillez renseigner tous les champs de saisie.", {
          type: "warning",
        });
      } else if (this.audience.length === 0) {
        createToast("Veuillez renseigner tous les champs de saisie.", {
          type: "warning",
        });
      } else {
        API_Helper.put(`/jury/${this.$route.params.id}`, {
          appId: this.apprenti.id,
          eventId: this.event.id,
          audience: this.audience.map((auditeur) => auditeur.id),
        })
          .then((response) => {
            createToast("Le jury a été mis à jour.", { type: "success" });
            this.$router.push("/jurys");
          })
          .catch((err) => {
            createToast("Impossible de mettre à jour le jury.", {
              type: "danger",
            });
          });
      }
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
