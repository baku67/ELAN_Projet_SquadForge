
window.addEventListener('load', function() {

    // Library pour pouvoir desactiver l'autoPlay des gifs (paramètres User)
    Gifffer();


    // ScrollReveal
    ScrollReveal().reveal('.mediaCard');


    // Asynch des Likes de média
    var btns = document.getElementsByClassName("likeMedia");
    Array.prototype.forEach.call(btns, function(btn) {
        
        // console.log(btn.getAttribute('mediaId')); 
        const id = btn.getAttribute('mediaId');

        btn.addEventListener("click", function() {
            
            fetch('/likeMedia/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('#ajaxFlash').textContent = "Votre note a bien été pris en compte";
                    document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");
                } else {
                    document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour liker un média";
                    document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
                }

                if (data.newState == "liked") {
                    console.log("liked");
                    btn.style.color = "var(--primary-color)";
                    document.getElementById("countLikesMedia" + id).innerHTML = data.newCountLikes;
                }
                else if (data.newState == "unliked") {
                    console.log("unliked");
                    btn.style.color = "var(--white)";
                    document.getElementById("countLikesMedia" + id).innerHTML = data.newCountLikes;
                }
            })
        })
    })


    // Anim fadeIn 5 Topics Game
    var topicCards = document.querySelectorAll('.topicCard');

    // Fonction pour ajouter une classe supplémentaire à chaque élément après un délai
    function addClassWithDelay(element, className, delay) {
        setTimeout(function() {
            element.classList.add(className);
        }, delay);
    }

    // Parcours de chaque élément et ajout de la classe avec un délai croissant
    var delay = 0;
    for (var i = 0; i < topicCards.length; i++) {
        var card = topicCards[i];
        addClassWithDelay(card, 'topicCardFadeInAnim', delay);
        delay += 250; // Délai en millisecondes (ajuste le délai si nécessaire)
    }
                    



});

