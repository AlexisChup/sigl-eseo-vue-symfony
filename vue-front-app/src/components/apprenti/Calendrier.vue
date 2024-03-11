<template>
  <div>
    <div>
      <Calendar
        ref="calendar"
        is-expanded
        show-weeknumbers
        transition="slide-v"
        :attributes="attributes"
        :from-page="{
          month: new Date().getMonth() + 1,
          year: new Date().getFullYear(),
        }"
      >
      </Calendar>
    </div>
    <div v-if="role === 'APPRENTI'">
      <div class="p-1 p-md-3">
        <div class="row justify-content-center text-center p-1">
          <h1>Entretien</h1>
        </div>

        <div class="row m-2">
          <h2>{{ this.pageTitle }}</h2>
          <div class="col-12 row m-0 p-0 justify-content-between">
            <div class="case rounded shadow border col-12 col-md-6 p-3">
              <div class="form-group">
                <label for="inputSemestre">Type</label>
                <select
                  class="custom-select"
                  name="inputSemestre"
                  v-model="eventChoose"
                >
                  <option
                    v-for="(ev, index) in this.events"
                    :key="index"
                    :value="ev"
                  >
                    {{ ev.nom }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label for="inputNom">Titre</label>
                <input
                  type="text"
                  class="form-control"
                  name="inputNom"
                  placeholder="Titre de l'évènement"
                  v-model="this.data.title"
                />
              </div>

              <div class="form-group">
                <label for="inputNom">Emplacement</label>
                <input
                  type="text"
                  class="form-control"
                  name="inputNom"
                  placeholder="Emplacement de l'évenement"
                  v-model="this.data.location"
                />
              </div>
            </div>

            <div
              class="case rounded shadow border col-12 col-md-5 d-flex flex-column p-3 mt-md-0"
              id="secondDiv"
            >
              <div class="form-group mb-0">
                <label for="inputFin">Date de l'entretien</label>
                <input
                  class="form-control"
                  type="date"
                  v-model="this.data.date"
                />
              </div>

              <div class="form-group">
                <label for="inputHoraire">Horaire</label>
                <select
                  type="date"
                  class="form-control"
                  id="inputHoraire"
                  name="inputHoraire"
                  placeholder="Heure de l'évenement"
                  v-model="this.data.time"
                >
                  <option value="8:00">8:00</option>
                  <option value="9:00">9:00</option>
                  <option value="10:00">10:00</option>
                  <option value="11:00">11:00</option>
                  <option value="12:00">12:00</option>
                  <option value="13:00">13:00</option>
                  <option value="14:00">14:00</option>
                  <option value="15:00">15:00</option>
                  <option value="16:00">16:00</option>
                  <option value="17:00">17:00</option>
                  <option value="18:00">18:00</option>
                  <option value="19:00">19:00</option>
                  <option value="20:00">
                    20:00 (pour ceux encore au boulot)
                  </option>
                </select>
              </div>

              <div class="form-group d-flex flex-column flex-grow-1 mt-2">
                <label for="inputComplementaire"
                  >Informations comlémentaires</label
                >
                <textarea
                  type="textfield"
                  class="form-control flex-grow-1"
                  name="inputConcerne"
                  placeholder="Entrer des informations complémentaires"
                  v-model="this.data.information"
                />
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-primary" v-on:click="add_entretien()">
          Ajouter un entretien ou soutenance
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";

export default {
  name: "Calendrier",

  props: {
    apprenti: Object,
  },

  data() {
    return {
      role: this.$store.state.perm,
      pageTitle: "Création entretien semestriel",
      events: [],
      event1: [],
      event2: [],
      isFormEmpty: false,
      eventChoose: {},
      data: {
        event: {},
        title: "",
        location: "",
        date: "",
        time: "",
        information: "",
      },

      // La variable attributes devra être changée dynamiquement lors des calls API avec les nouvelles données à afficher si besoin
      attributes: [],

      sample_data: [],

      attributes_sample: [],
      data_entretiens: [],
      data_livrables: [],
      data_soutenances: [],
    };
  },

  methods: {
    resetData() {
      this.data_entretiens = [];
      this.data_livrables = [];
      this.data_soutenances = [];
      this.attributes = [];
    },

    async add_entretien() {
      if (this.data.title.length === 0) {
        createToast("Le titre de l'évènement est vide", { type: "danger" });
      } else if (this.data.location.length === 0) {
        createToast("La location de l'évènement est vide", { type: "danger" });
      } else if (this.data.date.length === 0) {
        createToast("La date de l'évènement est vide", { type: "danger" });
      } else if (this.data.time.length === 0) {
        createToast("L'horaire de l'évènement est vide", { type: "danger" });
      } else if (this.data.information.length === 0) {
        createToast("Les information complémentaire de l'évènement est vide", {
          type: "danger",
        });
      } else {
        this.data.event = this.eventChoose.id;
        if (
          this.data.date > this.eventChoose.dateDebut &&
          this.data.date < this.eventChoose.dateFin
        ) {
          switch (this.eventChoose.type) {
            case 1:
              API_Helper.post("/soutenance/create", this.data)
                .then((response) => {
                  createToast("La soutenance à bien été pris en compte", {
                    type: "success",
                  });
                  return response.data;
                })
                .catch((e) => {
                  console.log("erreur : ", e);
                });
              break;
            case 2:
              API_Helper.post("/entretien/create", this.data)
                .then((response) => {
                  createToast("L'entretien à bien été pris en compte", {
                    type: "success",
                  });
                  return response.data;
                })
                .catch((e) => {
                  console.log("erreur : ", e);
                });
              break;
          }
        } else {
          createToast(
            "Pour cette evenement, date de début : " +
              this.eventChoose.dateDebut +
              " / date de fin : " +
              this.eventChoose.dateFin,
            { type: "danger" }
          );
        }
      }
    },
  },

  async created() {
    let sample_data_entretiens = [
      // Entretiens semestriels : highlights avec des périodes de date
      {
        description: "Entretien semestriel n°1",
        isComplete: false,
        dates: { start: new Date(), end: new Date().addDays(4) },
        highlight: {
          start: { fillMode: "outline", color: "red" },
          base: { fillMode: "light", color: "red" },
          end: { fillMode: "outline", color: "red" },
        },
      },
      {
        description: "Entretien semestriel n°2",
        isComplete: false,
        dates: { start: new Date().addDays(7), end: new Date().addDays(11) },
        highlight: {
          start: { fillMode: "outline", color: "red" },
          base: { fillMode: "light", color: "red" },
          end: { fillMode: "outline", color: "red" },
        },
      },
    ];

    let sample_data_livrables = [
      // Livrables : points de couleur faits avec highlight: color
      {
        description: "Livrable n°1",
        isComplete: false,
        dates: new Date().addDays(3),
        highlight: {
          color: "green",
          fillMode: "solid",
        },
      },
      {
        description: "Livrable n°2",
        isComplete: false,
        dates: new Date().addDays(10),
        highlight: {
          color: "green",
          fillMode: "solid",
        },
      },
    ];

    let sample_data_soutenances = [
      // Soutenances : barres de couleur sous les dates : bars
      {
        description: "Soutenance n°1",
        isComplete: false,
        dates: new Date().addDays(5),
        bar: {
          color: "blue",
        },
      },
      {
        description: "Soutenance n°2",
        isComplete: false,
        dates: new Date().addDays(12),
        bar: {
          color: "blue",
        },
      },
    ];

    this.attributes_sample = [
      ...sample_data_entretiens.map((data) => ({
        popover: {
          label: data.description,
          visibility: "hover",
        },
        dates: data.dates,
        highlight: data.highlight,
        order: 1,
      })),
      ...sample_data_livrables.map((data) => ({
        popover: {
          label: data.description,
          visibility: "hover",
        },
        dates: data.dates,
        highlight: data.highlight,
        order: 2,
      })),
      ...sample_data_soutenances.map((data) => ({
        popover: {
          label: data.description,
          visibility: "hover",
        },
        dates: data.dates,
        bar: data.bar,
        order: 0,
      })),
    ];

    this.event2 = await API_Helper.get("/event/type/" + 2)
      .then((response) => {
        return response.data;
      })
      .catch((e) => {
        console.log("erreur : ", e);
      });

    this.event1 = await API_Helper.get("/event/type/" + 1)
      .then((response) => {
        return response.data;
      })
      .catch((e) => {
        console.log("erreur : ", e);
      });

    this.events.push(...this.event2);
    this.events.push(...this.event1);
  },

  beforeCreate() {
    Date.prototype.addDays = function (days) {
      let date = new Date(this.valueOf());
      date.setDate(date.getDate() + days);
      return date;
    };
  },

  async mounted() {
    this.resetData();

    let promo_id;

    if (this.apprenti) {
      // L'apprenti est récupéré car consultation par maitre d'apprentissage
      promo_id = this.apprenti.idPromotion.id;
    } else {
      // Récupération de l'apprenti
      if (this.$store.state.perm === "APPRENTI") {
        await API_Helper.get("/apprenti/user/" + this.$store.state.id)
          .then((response) => {
            promo_id = response.data.idPromotion.id;
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        promo_id = null;
      }
    }

    if (promo_id) {
      // Récupération des données des livrables
      // TYPE 0
      API_Helper.get("/event/livrables/" + promo_id)
        .then((response) => {
          response.data.forEach((data) => {
            this.data_livrables.push({
              popover: { label: data.nom, visibility: "hover" },
              dates: new Date(data.dateDebut),
              highlight: { color: "green", fillMode: "solid" },
              order: 2,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des livrables.", {
            position: "bottom-right",
            type: "danger",
          });
        });

      // Récupération des données des entretiens
      // TYPE 1
      API_Helper.get("/event/entretiens/" + promo_id)
        .then((response) => {
          response.data.forEach((data) => {
            this.data_entretiens.push({
              popover: { label: data.nom, visibility: "hover" },
              dates: {
                start: new Date(data.dateDebut),
                end: new Date(data.dateFin),
              },
              highlight: {
                start: { fillMode: "outline", color: "red" },
                base: { fillMode: "light", color: "red" },
                end: { fillMode: "outline", color: "red" },
              },
              order: 1,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des entretiens.", {
            position: "bottom-right",
            type: "danger",
          });
        });

      // Récupération des données des soutenances
      // TYPE 2
      API_Helper.get("/event/soutenances/" + promo_id)
        .then((response) => {
          response.data.forEach((data) => {
            this.data_soutenances.push({
              popover: { label: data.nom, visibility: "hover" },
              dates: new Date(data.dateDebut),
              bar: { color: "blue" },
              order: 0,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des soutenances.", {
            position: "bottom-right",
            type: "danger",
          });
        });
    } else if (promo_id === null) {
      // Récupération des données des livrables
      // TYPE 0
      API_Helper.get("/event/type/0")
        .then((response) => {
          response.data.forEach((data) => {
            this.data_livrables.push({
              popover: {
                label: data.nom + " - Promotion " + data.promotion.nom,
                visibility: "hover",
              },
              dates: new Date(data.dateDebut),
              highlight: { color: "green", fillMode: "solid" },
              order: 2,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des livrables.", {
            position: "bottom-right",
            type: "danger",
          });
        });

      // Récupération des données des entretiens
      // TYPE 1
      API_Helper.get("/event/type/1")
        .then((response) => {
          response.data.forEach((data) => {
            this.data_entretiens.push({
              popover: {
                label: data.nom + " - Promotion " + data.promotion.nom,
                visibility: "hover",
              },
              dates: {
                start: new Date(data.dateDebut),
                end: new Date(data.dateFin),
              },
              highlight: {
                start: { fillMode: "outline", color: "red" },
                base: { fillMode: "light", color: "red" },
                end: { fillMode: "outline", color: "red" },
              },
              order: 1,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des entretiens.", {
            position: "bottom-right",
            type: "danger",
          });
        });

      // Récupération des données des soutenances
      // TYPE 2
      API_Helper.get("/event/type/2")
        .then((response) => {
          response.data.forEach((data) => {
            this.data_soutenances.push({
              popover: {
                label: data.nom + " - Promotion " + data.promotion.nom,
                visibility: "hover",
              },
              dates: new Date(data.dateDebut),
              bar: { color: "blue" },
              order: 0,
            });
          });
        })
        .catch(() => {
          createToast("Erreur lors de la récupération des soutenances.", {
            position: "bottom-right",
            type: "danger",
          });
        });
    }
  },

  computed: {
    attributes() {
      return [
        ...this.data_entretiens,
        ...this.data_livrables,
        ...this.data_soutenances,
      ];
    },
  },
};
</script>

<style scoped></style>
