window.onload = function() {

    // Regex validation Email (critère front)
    document.getElementById('registration_form_email').addEventListener('input', function() {

        var inputEmail = document.getElementById('registration_form_email').value;

        if(inputEmail !== "") {

            const emailValid = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(inputEmail);

            if(emailValid) {
                document.getElementById('registerEmailCriteria').style.color = 'green';
            }
            else {
                document.getElementById('registerEmailCriteria').style.color = 'red';
            }
        }
        else {
            
            document.getElementById('registerEmailCriteria').style.color = 'red';

        }

    })



    // Ajax check pseudo available (TODO: delay: voir 'Debouncing' landingPage)
    document.getElementById('registration_form_pseudo').addEventListener('input', function() {

        var inputPseudo = document.getElementById('registration_form_pseudo').value;

        if(inputPseudo !== "") {

            if(inputPseudo.length >= 5) {

                document.getElementById('registerPseudoCriteria').style.display = "block";

                let searchTimer;

                function delayedSearch() {
                    // Clear any previously set timer
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
                                    document.getElementById('registerPseudoCriteria').style.color = "green";
                                    document.getElementById('registerPseudoCriteria').textContent = "- Le pseudo est disponible";
                                }
                                else {
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

                document.getElementById('registerPseudoCriteria').style.color = "red";
                document.getElementById('registerPseudoCriteria').textContent = "- Le pseudo doit faire au moins 5 caractères";
            }

        }
        else {
            document.getElementById('registerPseudoCriteria').style.display = "none";
        }

    });


            
    // Regex validation mot de passse (critères front)
    document.getElementById('registration_form_plainPassword_first').addEventListener('input', function() {

        var inputPassword = document.getElementById('registration_form_plainPassword_first').value;

        var passwordsMatches = false;



        if(inputPassword !== "") {

            // Display des infos mdp:
        
            // Check 12 char:
            const nbrCharResult = /.{10,}/.test(inputPassword);
            if(nbrCharResult) {
                document.getElementById('registerRestriction-nbrChar').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrChar').style.color = 'red';
            }

            // Check 1 Minuscule:
            const nbrMinResult = /[a-z]/.test(inputPassword);
            if(nbrMinResult) {
                document.getElementById('registerRestriction-nbrMin').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMin').style.color = 'red';
            }

            // Check 1 Majuscule:
            const nbrMajResult = /[A-Z]/.test(inputPassword);
            if(nbrMajResult) {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'red';
            }

            // Check 1 chiffre:
            const nbrNumResult = /[0-9]/.test(inputPassword);
            if(nbrNumResult) {
                document.getElementById('registerRestriction-nbrNum').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrNum').style.color = 'red';
            }

            // Check 1 Special char:
            const nbrSpecialResult = /[$?*@!#%&()^~{}]/.test(inputPassword);
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

            // (désactivé: Toggle btn submit disabled si critères non remplis):
            // if(passwordsMatches && nbrCharResult && nbrMinResult && nbrMajResult && nbrNumResult && nbrSpecialResult) {
            //     console.log("bouton clickjable");
            //     document.getElementById('registration_form_submit').disabled = false;
            // }
            // else {
            //     console.log("bouton non clickjable");
            //     document.getElementById('registration_form_submit').disabled = true;
            // }

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

        const nbrCharResult = /.{10,}/.test(inputPassword);
        const nbrMinResult = /[a-z]/.test(inputPassword);
        const nbrMajResult = /[A-Z]/.test(inputPassword);
        const nbrNumResult = /[0-9]/.test(inputPassword);
        const nbrSpecialResult = /[$?*@!#%&()^~{}]/.test(inputPassword);

        // (désactivé: Toggle btn submit disabled si critères non remplis):
        // if(passwordsMatches && nbrCharResult && nbrMinResult && nbrMajResult && nbrNumResult && nbrSpecialResult) {
        //     console.log("bouton clickjable");
        //     document.getElementById('registration_form_submit').disabled = false;
        // }
        // else {
        //     console.log("bouton non clickjable");
        //     document.getElementById('registration_form_submit').disabled = true;
        // }

    })

}