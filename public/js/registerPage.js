window.onload = function() {

    
    var emailValid = false;
    var termsAccepted = false;
    var pseudoValid = false;

    var passwordsMatches = false;
    var nbrCharResult = false;
    var nbrMinResult = false;
    var nbrMajResult = false;
    var nbrNumResult = false;
    var nbrSpecialResult = false;



    // Regex validation Email (critère front)
    document.getElementById('registration_form_email').addEventListener('input', function() {

        var inputEmail = document.getElementById('registration_form_email').value;

        if(inputEmail !== "") {

            emailValid = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(inputEmail);

            if(emailValid) {
                document.getElementById('registerEmailCriteria').textContent = "L'email est valide";
                document.getElementById('registerEmailCriteria').style.color = 'green';
            }
            else {
                document.getElementById('registerEmailCriteria').textContent = "L'email n'est pas valide";
                document.getElementById('registerEmailCriteria').style.color = 'red';
            }
        }
        else {
            document.getElementById('registerEmailCriteria').textContent = "L'email n'est pas valide";
            document.getElementById('registerEmailCriteria').style.color = 'red';
        }

        checkIfAllCriterias();

    })



    // validation case "Terms and conditions" cochée:
    document.getElementById('registration_form_agreeTerms').addEventListener('input', function() {

        if (document.getElementById('registration_form_agreeTerms').checked) {
            termsAccepted = true;
            document.getElementById('registerTermsCriteria').style.color = 'green';
        } else {
            termsAccepted = false;
            document.getElementById('registerTermsCriteria').style.color = 'red';
        }

        checkIfAllCriterias();

    })




    // Ajax check pseudo available (TODO: delay: voir 'Debouncing' landingPage)
    document.getElementById('registration_form_pseudo').addEventListener('input', function() {

        var inputPseudo = document.getElementById('registration_form_pseudo').value;

        if(inputPseudo !== "") {

            if(inputPseudo.length >= 5) {

                document.getElementById('registerPseudoCriteria').style.display = "block";

                let searchTimer;

                function delayedSearch() {
                    // Nettoie le timer (debouncing lors de la frappe)
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        fetch('/checkPseudoAvailable/' + inputPseudo, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if(data.pseudoAvailable) {
                                    pseudoValid = true;
                                    document.getElementById('registerPseudoCriteria').style.color = "green";
                                    document.getElementById('registerPseudoCriteria').textContent = "- Le pseudo est disponible";
                                }
                                else {
                                    pseudoValid = false;
                                    document.getElementById('registerPseudoCriteria').style.color = "red";
                                    document.getElementById('registerPseudoCriteria').textContent = "- Le pseudo est déjà utilisé";
                                }
                            } else {
                                window.FlashMessage.error('Il y a eu un problème avec le chargment du contenu');
                            }
                        })
                    }, 250);

                }

                delayedSearch();

            }
            else {
                document.getElementById('registerPseudoCriteria').style.display = "block";

                pseudoValid = false;
                document.getElementById('registerPseudoCriteria').style.color = "red";
                document.getElementById('registerPseudoCriteria').textContent = "- Le pseudo doit faire au moins 5 caractères";
            }

        }
        else {
            document.getElementById('registerPseudoCriteria').style.display = "none";
        }

        checkIfAllCriterias();

    });


       
    
    // Regex validation mot de passse (critères front)
    document.getElementById('registration_form_plainPassword_first').addEventListener('input', function() {

        var inputPassword = document.getElementById('registration_form_plainPassword_first').value;


        if(inputPassword !== "") {

            // Display des infos mdp:
        
            // Check 12 char:
            nbrCharResult = /.{10,}/.test(inputPassword);
            if(nbrCharResult) {
                document.getElementById('registerRestriction-nbrChar').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrChar').style.color = 'red';
            }

            // Check 1 Minuscule:
            nbrMinResult = /[a-z]/.test(inputPassword);
            if(nbrMinResult) {
                document.getElementById('registerRestriction-nbrMin').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMin').style.color = 'red';
            }

            // Check 1 Majuscule:
            nbrMajResult = /[A-Z]/.test(inputPassword);
            if(nbrMajResult) {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'red';
            }

            // Check 1 chiffre:
            nbrNumResult = /[0-9]/.test(inputPassword);
            if(nbrNumResult) {
                document.getElementById('registerRestriction-nbrNum').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrNum').style.color = 'red';
            }

            // Check 1 Special char:
            nbrSpecialResult = /[$?*@!#%&()^~{}]/.test(inputPassword);
            if(nbrSpecialResult) {
                document.getElementById('registerRestriction-nbrSpecial').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrSpecial').style.color = 'red';
            }


            // Check match des password (à déplacer en dehors du eventListener input):
            if(document.getElementById('registration_form_plainPassword_first').value == document.getElementById('registration_form_plainPassword_second').value) {
                document.getElementById('registerRestriction-passwordMatch').style.color = 'green';
                passwordsMatches = true;
            }
            else {
                document.getElementById('registerRestriction-passwordMatch').style.color = 'red';
                passwordsMatches = false;
            }


        } 
        else {

            // Vide => tous les warnings
            document.getElementById('registerRestriction-nbrChar').style.color = 'red';
            document.getElementById('registerRestriction-nbrMin').style.color = 'red';
            document.getElementById('registerRestriction-nbrMaj').style.color = 'red';
            document.getElementById('registerRestriction-nbrNum').style.color = 'red';
            document.getElementById('registerRestriction-nbrSpecial').style.color = 'red';
            document.getElementById('registerRestriction-passwordMatch').style.color = 'red';

        }

        checkIfAllCriterias();
    })




    document.getElementById('registration_form_plainPassword_second').addEventListener('input', function() {

        // Check match des password (à déplacer en dehors du eventListener input):
        if(document.getElementById('registration_form_plainPassword_first').value == document.getElementById('registration_form_plainPassword_second').value) {
            document.getElementById('registerRestriction-passwordMatch').style.color = 'green';
            passwordsMatches = true;
        }
        else {
            document.getElementById('registerRestriction-passwordMatch').style.color = 'red';
            passwordsMatches = false;
        }

        checkIfAllCriterias();
    })



    // Fonction de vérification des critères pour activer le bouton submit
    function checkIfAllCriterias() {
        if(emailValid && pseudoValid && termsAccepted && passwordsMatches && nbrCharResult && nbrMinResult && nbrMajResult && nbrNumResult && nbrSpecialResult) {
            document.getElementById('registration_form_submit').disabled = false;
        }
        else {
            document.getElementById('registration_form_submit').disabled = true;
        }
    }

}