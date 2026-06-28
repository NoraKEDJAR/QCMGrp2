let secondesRestantes = window.tempsQcmRestant || (10 * 60);
let triche = 0;
const timer = document.getElementById("timer");
const warning = document.getElementById("warning");
const form = document.getElementById("formQcm");
const inputTriche = document.getElementById("triche_detectee");
const btnFullscreen = document.getElementById("btnFullscreen");

function affichageTemps() {
    const minutes = Math.floor(secondesRestantes / 60);
    const secondes = secondesRestantes % 60;
    timer.textContent = String(minutes).padStart(2, "0") + ":" + String(secondes).padStart(2, "0");
}

function signalerTriche(type) {
    triche++;
    inputTriche.value = triche;
    warning.textContent = "Avertissement anti-triche : " + type;

    fetch("qcm_triche.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "type=" + encodeURIComponent(type)
    });

    if (triche >= 2) {
        alert("Tentative annulée à cause des avertissements anti-triche.");
        form.submit();
    }
}

btnFullscreen.addEventListener("click", () => {
    const element = document.documentElement;
    if (element.requestFullscreen) {
        element.requestFullscreen();
    }
});

document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) {
        signalerTriche("sortie du plein écran");
    }
});

document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        signalerTriche("changement d'onglet ou fenêtre minimisée");
    }
});

document.addEventListener("contextmenu", (event) => {
    event.preventDefault();
});

document.addEventListener("copy", (event) => event.preventDefault());
document.addEventListener("paste", (event) => event.preventDefault());

setInterval(() => {
    secondesRestantes--;
    affichageTemps();
    if (secondesRestantes <= 0) {
        alert("Temps terminé. Le QCM va être envoyé automatiquement.");
        form.submit();
    }
}, 1000);

affichageTemps();
