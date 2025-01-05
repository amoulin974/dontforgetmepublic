/*document.addEventListener("DOMContentLoaded", () => {
    console.log("Test");

    // Sélectionner tous les boutons
    const buttons = document.querySelectorAll("button");

    const boutonRetour = document.querySelector("#retour");

    boutonRetour.addEventListener("click", () => {
        // Récupérer la div qui a pour classe step (et non step d-none)
        // Puis logique de récupération du prcédent, ajout de d-none sur l'actuel et enlever le d-none du précédent 
    })

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            // Accéder au parent ayant la classe 'step'
            const parentStep = button.closest(".step");
            console.log("Parent trouvé :", parentStep);
            console.log("ID du parent :", parentStep.id);

            // Accéder à l'élément suivant ayant la classe 'step'
            let nextStep = parentStep.nextElementSibling;
            let previousStep = parentStep.previousElementSibling;

            // Vérifier si l'élément suivant existe et a la classe 'step'
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }
            while (previousStep && !previousStep.classList.contains("step")) {
                previousStep = previousStep.previousElementSibling;
            }

            if (nextStep) {
                console.log("Étape suivante :", nextStep);
                console.log("ID de l'étape suivante :", nextStep.id);
                parentStep.classList.add("d-none"); // Masquer l'étape actuelle
                nextStep.classList.remove("d-none"); // Afficher l'étape suivante                
            } else {
                console.log("Aucune étape suivante trouvée.");
            }

            if (previousStep) {
                console.log("Étape précedente :", previousStep);
                console.log("ID de l'étape précédente :", previousStep.id);
                // parentStep.classList.add("d-none"); // Masquer l'étape actuelle
                // nextStep.classList.remove("d-none"); // Afficher l'étape suivante                
            } else {
                console.log("Aucune étape précédente trouvée.");
            }
        });
    });
});
*/

document.addEventListener("DOMContentLoaded", () => {
    console.log("Test");
    console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    const responses = {};
    const retourButton = document.querySelector("#retour");
    const navButtons = document.querySelectorAll(".btn-nav");
    const submitButtons = document.querySelectorAll(".btn-submit");

    retourButton.addEventListener("click", () => {
        const currentStep = document.querySelector(".step:not(.d-none)");

        if (currentStep) {
            let previousStep = currentStep.previousElementSibling;

            while (previousStep && !previousStep.classList.contains("step")) {
                previousStep = previousStep.previousElementSibling;
            }

            if (!previousStep) {
                console.log("Aucune étape précédente trouvée.");
                retourButton.disabled = true; 
                return;
            }

            retourButton.disabled = false;
            const stepId = previousStep.id;
            console.log(stepId);
            if (responses[stepId]) {
                console.log("Réponse supprimée pour ", stepId);
                delete responses[stepId];
            }
            console.log(responses); 
            currentStep.classList.add("d-none");
            previousStep.classList.remove("d-none");
        }
    });

    navButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;
            console.log("Parent trouvé :", parentStep);

            const answer = button.getAttribute("answer");
            if(answer) {
                responses[stepId] = answer;
                console.log("Réponse enregistrée pour ", stepId, " : ", answer);
            }
            console.log(responses);

            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none"); 
                nextStep.classList.remove("d-none");               
            } else {
                console.log("Aucune étape suivante trouvée.");
            }

        });
    });

    submitButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault(); 
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;
            console.log("Parent trouvé :", parentStep);

            const answer = button.getAttribute("answer");
            if (answer) {
                responses[stepId] = answer;
                console.log("Réponse enregistrée pour ", stepId, " : ", answer);
            }
            console.log(responses);

            fetch("/register/submit-responses", {
                method: "POST", 
                headers: {
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"), 
                },
                body: JSON.stringify(responses), 
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Erreur HTTP ! status: ", response.status);
                    }
                    return response.json(); 
                })
                .then((data) => {
                    console.log("Réponses envoyées avec succès :", data);
                    window.location.href = button.href; 
                })
                .catch((error) => {
                    console.error("Erreur lors de l'envoi des réponses :", error);
                    alert("Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.");
                });
        })
    })
});
