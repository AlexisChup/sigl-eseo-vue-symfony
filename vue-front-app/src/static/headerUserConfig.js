import router from "@/router/router";

const apprenti = [
  { name: "Accueil", url: "/accueil" },
  { name: "Calendrier", url: "/calendrier" },
  { name: "Bloc-Note", url: "/notes-periodiques" },
  { name: "Livrables", url: "/livrables" },
  { name: "Documents Pédagogiques", url: "/documents-pedagogiques" },
];

const tuteurMaitreApp = [
  { name: "Accueil", url: "/accueil" },
  { name: "Calendrier", url: "/calendrier" },
  { name: "Mes apprentis", url: "/mes-apprentis" },
  { name: "Mes jurys", url: "/jurys" },
];

const respCursus = [
  { name: "Accueil", url: "/accueil" },
  { name: "Calendrier", url: "/calendrier" },
  { name: "Missions apprentis", url: "/accueil/missions" },
  { name: "Documents pédagogiques", url: "/documents-pedagogiques" },
];

const coordAlternance = [
  { name: "Accueil", url: "/accueil" },
  { name: "Calendrier", url: "/calendrier" },
  { name: "Évènements", url: "/evenements" },
  { name: "Jurys", url: "/jurys" },
  { name: "Grille", url: "/grille-evaluation" },
  { name: "Gestion tutorale", url: "/gestion-tutorale" },
  { name: "Docs pédagogiques", url: "/documents-pedagogiques" },
  { name: "Missions", url: "/missions-apprentis" },
];

const admin = [
  { name: "Utilisateurs", url: "/gestion-utilisateurs" },
  { name: "Entreprises", url: "/gestion-entreprises" },
  { name: "Gestion tutorale", url: "/gestion-tutorale" },
];

const headerItems = {
  ADMIN: admin,
  APPRENTI: apprenti,
  COORDINATEUR: coordAlternance,
  RESPONSABLE: respCursus,
  ENSEIGNANT: tuteurMaitreApp,
  "MAITRE-APPRENTISSAGE": tuteurMaitreApp,
};

export const getUrlAvailablePerUser = (role, url) => {
  const items = headerItems[role];
  let urlAvailablePerUser = [
    "/",
    "/page-not-found",
    "/accueil",
    "/profil",
    "/profil/notifications",
    "/profil/informations",
    "/profil/infos-apprenti",
    "/profil/deconnexion",
  ];

  for (let index = 0; index < items.length; index++) {
    urlAvailablePerUser.push(items[index].url);
  }

  const isUrlInAvailableUrls = urlAvailablePerUser.includes(url);
  return isUrlInAvailableUrls;
};

export default {
  apprenti,
  tuteurMaitreApp,
  respCursus,
  coordAlternance,
  admin,
  headerItems,
};
