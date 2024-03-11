import * as VueRouter from "vue-router";

// Pages générales
import Accueil from "@/components/Accueil";
import Connexion from "@/components/Connexion";

// Pages apprenti
import Calendrier from "@/components/apprenti/Calendrier";
import DocsPedagos from "@/components/coordinateur/DocsPedagos";
import Livrables from "@/components/apprenti/Livrables";
import NotesPeriodes from "@/components/apprenti/NotesPeriodes";

// Pages coordinatrice alternance
import GestionEvenements from "@/components/coordinateur/GestionEvenements";
import Evenements from "@/components/coordinateur/Evenements";
import GestionGrille from "@/components/coordinateur/GestionGrille";
import GestionEquipeTutorale from "@/components/coordinateur/GestionTuteur";
import GestionJurys from "@/components/coordinateur/GestionJury";

// Pages professeur

// Pages profil
import GestionNotifs from "@/components/profil/GestionNotifs";
import InfoPerso from "@/components/profil/InfoPerso";
import Profil from "@/components/profil/Profil";
import Deconnexion from "@/components/profil/Deconnexion";

// Pages réglages
import GestionEntreprises from "@/components/reglages/GestionEntreprises";
import GestionUsers from "@/components/reglages/GestionUsers";
import CreerUtilisateur from "@/components/CreerUtilisateur";

// Pages responsable cursus
import Missions from "@/components/respcursus/Missions";

// Pages tuteur et maître d'apprentissage
import MesApprentis from "@/components/tuteur_ma/MesApprentis";
import InfosApprenti from "@/components/tuteur_ma/InfosApprenti";

// Pages errors
import PageNotFound from "../components/error/PageNotFound";

import { createToast } from "mosha-vue-toastify";
import JournalApprenti from "@/components/tuteur_ma/JournalApprenti";
import Jurys from "@/components/coordinateur/Jurys";

const routes = [
  { path: "/connexion", component: Connexion, name: "Connexion" },
  { path: "/accueil", component: Accueil },

  // Composants globaux
  { path: "/calendrier", component: Calendrier },

  //Composants Apprentis
  { path: "/documents-pedagogiques", component: DocsPedagos },
  { path: "/notes-periodiques", component: NotesPeriodes },
  { path: "/livrables", component: Livrables },

  //Composant TP / MA
  { path: "/mes-apprentis", component: MesApprentis },
  { path: "/mes-apprentis/:id/journal", component: JournalApprenti },

  // Composant coordinateur
  { path: "/gestion-utilisateurs", component: GestionUsers },
  { path: "/gestion-utilisateurs/creation", component: CreerUtilisateur },
  { path: "/gestion-entreprises", component: GestionEntreprises },
  { path: "/evenements", component: Evenements },
  {
    path: "/evenements/:id/edit",
    name: "event.edit",
    component: GestionEvenements,
  },
  { path: "/evenements/creation", component: GestionEvenements },
  { path: "/jurys", component: Jurys },
  { path: "/jurys/creation", component: GestionJurys },
  { path: "/jurys/:id/edit", name: "jury.edit", component: GestionJurys },
  { path: "/grille-evaluation", component: GestionGrille },
  { path: "/missions-apprentis", component: Missions },
  { path: "/gestion-tutorale", component: GestionEquipeTutorale },

  {
    path: "/profil",
    component: Profil,
    children: [
      { path: "/profil/notifications", component: GestionNotifs },
      { path: "/profil/informations", component: InfoPerso },
      { path: "/profil/infos-apprenti", component: InfosApprenti },
      {
        path: "/profil/deconnexion",
        component: Deconnexion,
        name: "Deconnexion",
      },
    ],
  },

  { path: "/", component: Accueil },
  { path: "/:pathMatch(.*)*", component: PageNotFound },
];

const router = VueRouter.createRouter({
  history: VueRouter.createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.name != "Connexion" && to.name != "Deconnexion") {
    if (
      router.app.$store.state.perm == "UNIDENTIFIED" ||
      router.app.$store.state.perm == undefined
    ) {
      router.push({ path: "/connexion" });
      createToast(
        "Vous devez vous connecter pour accéder à ces fonctionnalités.",
        { position: "bottom-right", type: "danger" }
      );
    }
  }

  if (to.name == "Connexion") {
    if (
      router.app.$store.state.perm !== "UNIDENTIFIED" &&
      router.app.$store.state.perm !== undefined
    ) {
      router.push("/");
    }
  }
  next();
});

export default router;
