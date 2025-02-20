/* document.addEventListener("DOMContentLoaded", () => {
    const responses = {};
    const retourButton = document.querySelector("#retour");
    const navButtons = document.querySelectorAll(".btn-nav");
    const submitButtons = document.querySelectorAll(".btn-submit");
    const steps = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');

    const manyClientsButton = document.getElementById("manyClients");
    const capacityModal = new bootstrap.Modal(document.getElementById("capacityModal"));
    const confirmCapacityButton = document.getElementById("confirmCapacity");
    const capacityInput = document.getElementById("capacityInput");

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
                    delete responses[stepId];
                }
            }
            currentStep.classList.add("d-none");
            previousStep.classList.remove("d-none");
            updateProgressBar();
        }
    });
    
    navButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;

            const answer = button.getAttribute("answer");
            if(answer) {
                responses[stepId] = answer;
            }

            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none"); 
                nextStep.classList.remove("d-none");   
                updateProgressBar();            
            } 
        });
    });

    submitButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault(); 
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;

            const answer = button.getAttribute("answer");
            if (answer) {
                responses[stepId] = answer;
            }
 
            fetch("/entreprise/submit-appointments", {
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
                    window.location.href = button.href;
                })
                .catch((error) => {
                    alert("Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.");
                });
        })
    });

    let pendingStepId = null;
    let capacityValue = null;

    if (manyClientsButton) {
        manyClientsButton.addEventListener("click", (event) => {
            event.preventDefault();
            pendingStepId = manyClientsButton.closest(".step").id; // Sauvegarde du stepId
            capacityModal.show(); // Afficher la pop-up
        });
    }

    confirmCapacityButton.addEventListener("click", () => {
        const capacity = capacityInput.value.trim();
        
        if (capacity && !isNaN(capacity) && parseInt(capacity) > 0) {
            capacityValue = parseInt(capacity); // Stocker dans la nouvelle variable
            console.log("Capacité enregistrée :", capacityValue); // Vérifier la valeur dans la console

            capacityModal.hide();

            // Trouver le step actuel et passer au suivant
            const parentStep = document.getElementById(pendingStepId);
            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none");
                nextStep.classList.remove("d-none");
                updateProgressBar();
            }
        } else {
            alert("Veuillez entrer une capacité valide.");
        }
    });
});


 */

document.addEventListener("DOMContentLoaded", () => {
    const responses = {};
    const retourButton = document.querySelector("#retour");
    const navButtons = document.querySelectorAll(".btn-nav");
    const submitButtons = document.querySelectorAll(".btn-submit");
    const steps = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');

    const manyClientsButton = document.getElementById("manyClients");
    const capacityModalElement = document.getElementById("capacityModal");
    const capacityModal = new bootstrap.Modal(capacityModalElement, { keyboard: false, backdrop: 'static' });
    const confirmCapacityButton = document.getElementById("confirmCapacity");
    const capacityInput = document.getElementById("capacityInput");

    let pendingStepId = null;
    let capacityValue = null; // ✅ Stockage de la capacité max

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
                    delete responses[stepId];
                }
            }
            currentStep.classList.add("d-none");
            previousStep.classList.remove("d-none");
            updateProgressBar();
        }
    });

    navButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;

            const answer = button.getAttribute("answer");
            if(answer) {
                responses[stepId] = answer;
            }

            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none"); 
                nextStep.classList.remove("d-none");   
                updateProgressBar();            
            } 
        });
    });

    submitButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault(); 
            const parentStep = button.closest(".step");
            const stepId = parentStep.id;

            const answer = button.getAttribute("answer");
            if (answer) {
                responses[stepId] = answer;
            }

            // ✅ Création d’un objet qui inclut `responses` et `capacityValue` sans modifier `responses`
            const finalData = {
                ...responses, 
                capacity: capacityValue // ✅ Ajout de la capacité ici
            };

            console.log("📤 Données envoyées :", finalData); // Vérifier la requête

            fetch("/entreprise/submit-appointments", {
                method: "POST", 
                headers: {
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"), 
                },
                body: JSON.stringify(finalData), 
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Erreur HTTP ! status: " + response.status);
                }
                return response.json();
            })
            .then((data) => {
                window.location.href = button.href;
            })
            .catch((error) => {
                alert("Une erreur s'est produite lors de l'envoi des données. Veuillez réessayer.");
            });
        });
    });

    if (manyClientsButton) {
        manyClientsButton.addEventListener("click", (event) => {
            event.preventDefault();
            pendingStepId = manyClientsButton.closest(".step").id; // Sauvegarde du stepId
            capacityModal.show(); // Afficher la pop-up
        });
    }

    confirmCapacityButton.addEventListener("click", () => {
        const capacity = capacityInput.value.trim();
        
        if (capacity && !isNaN(capacity) && parseInt(capacity) > 0) {
            capacityValue = parseInt(capacity); // ✅ Stocker dans une variable séparée
            console.log("✅ Capacité enregistrée :", capacityValue); // Vérification

            capacityModal.hide();
            capacityModalElement.setAttribute('aria-hidden', 'true');

            // Passer à l'étape suivante
            const parentStep = document.getElementById(pendingStepId);
            let nextStep = parentStep.nextElementSibling;
            while (nextStep && !nextStep.classList.contains("step")) {
                nextStep = nextStep.nextElementSibling;
            }

            if (nextStep) {
                parentStep.classList.add("d-none");
                nextStep.classList.remove("d-none");
                updateProgressBar();
            }
        } else {
            alert("❌ Veuillez entrer une capacité valide.");
        }
    });
});
