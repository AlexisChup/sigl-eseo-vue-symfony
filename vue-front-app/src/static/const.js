const semestres =  [
    { nom: 'Semestre 5', value: 5},
    { nom: 'Semestre 6', value: 6},
    { nom: 'Semestre 7', value: 7},
    { nom: 'Semestre 8', value: 8},
    { nom: 'Semestre 9', value: 9},
    { nom: 'Semestre 10', value: 10},
];

const EVENT_TYPE_LIVRABLE = 0;
const EVENT_TYPE_ENTRETIEN = 1;
const EVENT_TYPE_SOUTENANCE = 2;

const strEvent = ['Livrable', 'Entretien', 'Soutenance'];
const colorEvent = [
    'background-color :#4B80B3; color: white;',
    'background-color :#FF8B85; color: white;',
    'background-color :#59B35C; color: white;'
];

module.exports = {
    semestres,
    EVENT_TYPE_ENTRETIEN,
    EVENT_TYPE_SOUTENANCE,
    EVENT_TYPE_LIVRABLE,
    strEvent,
    colorEvent
}

