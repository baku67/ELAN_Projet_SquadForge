window.onload = function() {
            
    document.getElementById('registration_form_plainPassword_first').addEventListener('input', function() {

        var input = document.getElementById('registration_form_plainPassword_first').value;

        var passwordsMatches = false;


        if(input !== "") {

            // Display des infos mdp:
        
            // Check 12 char:
            const nbrCharResult = /.{12,}/.test(input);
            if(nbrCharResult) {
                document.getElementById('registerRestriction-nbrChar').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrChar').style.color = 'red';
            }

            // Check 1 Minuscule:
            const nbrMinResult = /[a-z]/.test(input);
            if(nbrMinResult) {
                document.getElementById('registerRestriction-nbrMin').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMin').style.color = 'red';
            }

            // Check 1 Majuscule:
            const nbrMajResult = /[A-Z]/.test(input);
            if(nbrMajResult) {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrMaj').style.color = 'red';
            }

            // Check 1 chiffre:
            const nbrNumResult = /[0-9]/.test(input);
            if(nbrNumResult) {
                document.getElementById('registerRestriction-nbrNum').style.color = 'green';
            }
            else {
                document.getElementById('registerRestriction-nbrNum').style.color = 'red';
            }

            // Check 1 Special char:
            const nbrSpecialResult = /[$?*@!#%&()^~{}]/.test(input);
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

            // Activation du bouton si critères remplis:
            if(passwordsMatches && nbrCharResult && nbrMinResult && nbrMajResult && nbrNumResult && nbrSpecialResult) {
                document.getElementById('registration_form_submit').disabled = false;
            }
            else {
                document.getElementById('registration_form_submit').disabled = true;
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

    })

}