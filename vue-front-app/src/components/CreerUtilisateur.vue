<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Inscription utilisateur</h1>
    </div>

    <div class="row justify-content-around">
      <div class="col-12 row justify-content-between">
        <div class="case rounded shadow border p-1 p-md-3 mb-3 row m-0">
          <div class="col-12 col-md-6 mb-3">
            <label for="role" name="role">Rôle</label>
            <select name="role" class="form-control" @change="onChange($event)">
              <option value="APP">Apprenti</option>
              <option value="MA">Maître d'apprentissage</option>
              <option value="TP">Enseignant</option>
              <option value="CA">Coordinateur d'apprentissage</option>
            </select>
          </div>
          <div class="col-12 col-md-6 mb-3" v-if="role === 'APP'">
            <label for="promotion">Promotion</label>
            <select class="form-control" v-model="promotion">
              <option
                v-for="(promo, index) in liste_promo"
                v-bind:value="promo.id"
                v-bind:key="index"
              >
                {{ promo.nom }} - {{ promo.annee }}
              </option>
            </select>
          </div>

          <div class="col-12 col-md-6 mb-3" v-if="role === 'MA'">
            <label for="entreprise" name="entreprise">Entreprise</label>
            <select class="form-control" name="Entreprise" v-model="entreprise">
              <option
                v-for="(entreprise, index) in liste_entreprise"
                v-bind:value="entreprise.id"
                v-bind:key="index"
              >
                {{ entreprise.nom }}
              </option>
            </select>
          </div>

          <div class="col-12 col-md-6 mb-3" v-if="role === 'TP'">
            <label for="site" name="site">Site</label>
            <select class="form-control" name="site" v-model="site">
              <option
                v-for="(site, index) in liste_site"
                v-bind:value="site.id"
                v-bind:key="index"
              >
                {{ site.ville }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-12 row justify-content-between">
        <div class="case rounded shadow border p-1 p-md-3 col-12 mb-3">
          <div class="form-row">
            <div class="form-group col-4">
              <label for="prenom">Prénom</label>
              <input
                type="Text"
                class="form-control"
                name="prenom"
                v-model="prenom"
                placeholder="Prénom"
                required
              />
            </div>
            <div class="form-group col-4">
              <label for="nom">Nom</label>
              <input
                class="form-control"
                type="Text"
                name="nom"
                v-model="nom"
                placeholder="Nom"
                required
              />
            </div>
            <div class="form-group col-4">
              <label for="tel">Téléphone</label>
              <input
                type="Text"
                class="form-control"
                name="tel"
                v-model="tel"
                placeholder="Téléphone"
              />
            </div>
          </div>
          <div class="form-group">
            <label for="nom">Adresse</label>
            <input
              class="form-control"
              type="Text"
              name="adresse"
              v-model="adresse"
              placeholder="Adresse"
              required
            />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              class="form-control"
              name="email"
              v-model="email"
              placeholder="email@domaine.com"
              required
            />
          </div>
        </div>
      </div>

      <div class="col-12 row">
        <button
          type="submit"
          class="btn btn-primary btn-block"
          v-on:click="inscription()"
        >
          Inscription
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";

export default {
  name: "CreerUtilisateur",

  data() {
    return {
      prenom: "",
      nom: "",
      email: "",
      promotion: "",
      site: "",
      tel: "",
      adresse: "",
      entreprise: "",
      role: "APP",
      liste_promo: [],
      liste_entreprise: [],
      liste_site: [],
    };
  },

  methods: {
    inscription() {
      let data = {
        prenom: this.prenom,
        nom: this.nom,
        email: this.email,
        promotion: this.promotion,
        site: this.site,
        tel: this.tel,
        adresse: this.adresse,
        entreprise: this.entreprise,
      };

      if (
        data.prenom != null &&
        data.nom != null &&
        data.email != null &&
        data.tel != null &&
        data.adresse != null
      ) {
        switch (this.role) {
          case "APP":
            this.createApprentis(data);
            break;
          case "MA":
            this.createMA(data);
            break;
          case "TP":
            this.createTP(data);
            break;
          case "CA":
            this.createCA(data);
            break;
        }
      } else {
        createToast("Inscription impossible", { type: "danger" });
      }
    },

    onChange(event) {
      this.role = event.target.value;
    },

    async createApprentis(form) {
      await API_Helper.post("/apprenti/create", form)
        .then((response) => {
          this.data = {};
          createToast("L'apprenti " + form.nom + " a été créé", {
            type: "success",
          });
          this.$router.push("/gestion-utilisateurs");
          this.resetFormValues();
        })
        .catch((error) => {
          createToast("Impossible de créer l'apprenti", { type: "danger" });
        });
    },

    async createMA(form) {
      await API_Helper.post("/maitre-aprentissage/create", form)
        .then((response) => {
          this.data = {};
          createToast("Le maitre d'apprentissage " + form.nom + " a été créé", {
            type: "success",
          });
          this.$router.push("/gestion-utilisateurs");
          this.resetFormValues();
        })
        .catch((error) => {
          createToast("Impossible de créer le maitre d'apprentissage", {
            type: "danger",
          });
        });
    },

    async createTP(form) {
      await API_Helper.post("/enseignant/create", form)
        .then((response) => {
          this.data = {};
          createToast("L'enseignant " + form.nom + " a été créé", {
            type: "success",
          });
          this.$router.push("/gestion-utilisateurs");
          this.resetFormValues();
        })
        .catch((error) => {
          createToast("Impossible de créer l'enseignant", { type: "danger" });
        });
    },

    async createCA(form) {
      await API_Helper.post("/coordinateur/create", form)
        .then((response) => {
          this.data = {};
          createToast("Le coordinateur " + form.nom + " a été créé", {
            type: "success",
          });
          this.$router.push("/gestion-utilisateurs");
          this.resetFormValues();
        })
        .catch((error) => {
          createToast("Impossible de créer le coordinateur", {
            type: "danger",
          });
        });
    },

    resetFormValues() {
      this.prenom = "";
      this.nom = "";
      this.email = "";
      this.promotion = "";
      this.site = "";
      this.tel = "";
      this.adresse = "";
      this.entreprise = "";
      this.role = "APP";
    },
  },
  async mounted() {
    if (this.auto == "true" || this.auto == 1) {
      this.generate();
    }

    this.liste_promo = await API_Helper.get("/promotion/promotions").then(
      (response) => {
        return response.data;
      }
    );

    this.liste_entreprise = await API_Helper.get(
      "/entreprise/entreprises"
    ).then((response) => {
      return response.data;
    });

    this.liste_site = await API_Helper.get("/centre-formation/centres").then(
      (response) => {
        return response.data;
      }
    );
  },
};
</script>

<style Scoped></style>
