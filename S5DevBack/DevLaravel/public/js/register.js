document.addEventListener("DOMContentLoaded", () => {
    // console.log("Test");
    // console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    const responses = {};
    const retourButton = document.querySelector("#retour");
    const navButtons = document.querySelectorAll(".btn-nav");
    const submitButtons = document.querySelectorAll(".btn-submit");
    const steps = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');

    function updateProgressBar() {
        const totalSteps = steps.length;
        let activeStepNumber = 0;

        steps.forEach(step => {
            const stepNumber = parseInt(step.id.replace('step', ''), 10);
            if (!step.classList.contains('d-none')) {
                activeStepNumber = stepNumber; 
            }
        });

        const progress = (activeStepNumber / totalSteps) * 100;

        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress.toFixed(0));
    }

    retourButton.addEventListener("click", () => {
        const currentStep = document.querySelector(".step:not(.d-none)");
    
        if (currentStep) {
            let previousStep = currentStep.previousElementSibling;
    
            while (previousStep && !previousStep.classList.contains("step")) {
                previousStep = previousStep.previousElementSibling;
            }
    
            if (!previousStep) {
                window.location.href = retourButton.getAttribute('redirectUrl');
                return;
            }
    
            const stepsToKeep = [];
            let tempStep = previousStep;
    
            while (tempStep) {
                if (tempStep.classList.contains("step")) {
                    stepsToKeep.push(tempStep.id);
                }
                tempStep = tempStep.previousElementSibling;
            }
    
            for (const stepId in responses) {
                if (!stepsToKeep.includes(stepId)) {
                    // console.log("Réponse supprimée pour ", stepId);
                    delete responses[stepId];
                }
            }
    
            // console.log(responses);
    
            // Navigation entre les étapes
            currentStep.classList.add("d-none");
            previousStep.classList.remove("d-none");
            updateProgressBar();
        }
    });
    

    navButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;
            // console.log("Parent trouvé :", parentStep);

            const answer = button.getAttribute("answer");
            if(answer) {
                responses[stepId] = answer;
                // console.log("Réponse enregistrée pour ", stepId, " : ", answer);
            }
            // console.log(responses);

            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none"); 
                nextStep.classList.remove("d-none");   
                updateProgressBar();            
            } else {
                // console.log("Aucune étape suivante trouvée.");
            }

        });
    });

    submitButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault(); 
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;
            // console.log("Parent trouvé :", parentStep);

            const answer = button.getAttribute("answer");
            if (answer) {
                responses[stepId] = answer;
                // console.log("Réponse enregistrée pour ", stepId, " : ", answer);
            }
            // console.log(responses);

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
                        throw new Error("Erreur HTTP ! status: " + response.status);
                    }
                    return response.json(); 
                })
                .then((data) => {
                    // console.log("Réponses envoyées avec succès :", data);
                    window.location.href = button.href;
                })
                .catch((error) => {
                    // console.error("Erreur lors de l'envoi des réponses :", error);
                    alert("Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.");
                });
        })
    })
});
