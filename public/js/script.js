
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





    // Asynch upvote TopicPost (et recalcul score)
    var btns2 = document.getElementsByClassName("upTopicPostBtn");
        Array.prototype.forEach.call(btns2, function(btn) {
            
            const idTopicPost = btn.getAttribute('postId');

            btn.addEventListener("click", function() {
                
                fetch('/upvoteTopicPost/' + idTopicPost, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        document.querySelector('#ajaxFlash').textContent = "Upvoté";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");

                        if (data.newState == "upvoted") {
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                            document.getElementById("down" + idTopicPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notUpvoted") {
                            btn.firstChild.style.color = "rgb(165, 165, 165)";
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                        }

                        if (data.newScore < 0) {
                            document.getElementById("topicPostScore" + idTopicPost).style.color = "red";
                        }
                        else {
                            document.getElementById("topicPostScore" + idTopicPost).style.color = "white";
                        }

                    } else {
                        document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour upvoter un post";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
                    }

                })
            })
        })




    // Asynch downvote TopicPost (et recalcul score)
    var btns3 = document.getElementsByClassName("downTopicPostBtn");
        Array.prototype.forEach.call(btns3, function(btn) {
            
            const idTopicPost = btn.getAttribute('postId');

            btn.addEventListener("click", function() {
                
                fetch('/downvoteTopicPost/' + idTopicPost, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        document.querySelector('#ajaxFlash').textContent = "Downvoté";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");

                        if (data.newState == "downvoted") {
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                            document.getElementById("up" + idTopicPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notDownvoted") {
                            btn.firstChild.style.color = "rgb(165, 165, 165)";
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                        }

                        if (data.newScore < 0) {
                            document.getElementById("topicPostScore" + idTopicPost).style.color = "red";
                        }
                        else {
                            document.getElementById("topicPostScore" + idTopicPost).style.color = "white";
                        }

                    } else {
                        document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour downvoté un post";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
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

