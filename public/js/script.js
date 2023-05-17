
window.addEventListener('load', function() {

    // Library pour pouvoir desactiver l'autoPlay des gifs (paramètres User)
    Gifffer();


    // ScrollReveal
    ScrollReveal().reveal('.mediaCard');



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






    

    // Asynch toggle Game Favori
    if (document.getElementById('gameId') != null) {

        const gameId = document.getElementById('gameId').textContent;

        document.getElementById('favGameBtn').addEventListener("click", function() {
    
            fetch('/game/toggleGameFav/' + gameId, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
    
                    if (data.newState == "favorited") {
                        document.getElementById('favGameBtn').lastChild.className="fa-solid fa-heart";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid " + document.getElementById('gameColor').textContent;
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid " + document.getElementById('gameColor').textContent;
    
                        document.getElementById('favGameBtn').lastChild.style.color = document.getElementById('gameColor').textContent;
    
                        document.querySelector('#ajaxFlash').textContent = "Favorisé";
    
                    }
                    else if (data.newState == "notFavorited") {
                        document.getElementById('favGameBtn').lastChild.className="fa-regular fa-heart";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid rgb(255 255 255 / 20%)";
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid rgb(255 255 255 / 20%)";
    
                        document.getElementById('favGameBtn').lastChild.style.color = "white";
    
                        document.querySelector('#ajaxFlash').textContent = "Défavorisé";
                    }
    
                    document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");
    
                } else {
                    document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour ajouter un jeu à vos favoris";
                    document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
                }
    
            })
        })
    

    }








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





    // Asynch upvote MediaPost (et recalcul score)
    var btns4 = document.getElementsByClassName("upMediaPostBtn");
        Array.prototype.forEach.call(btns4, function(btn) {
            
            const idMediaPost = btn.getAttribute('mediaPostId');

            btn.addEventListener("click", function() {
                
                fetch('/upvoteMediaPost/' + idMediaPost, {
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
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                            document.getElementById("mdown" + idMediaPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notUpvoted") {
                            btn.firstChild.style.color = "rgb(165, 165, 165)";
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                        }

                        if (data.newScore < 0) {
                            document.getElementById("mediaPostScore" + idMediaPost).style.color = "red";
                        }
                        else {
                            document.getElementById("mediaPostScore" + idMediaPost).style.color = "white";
                        }

                    } else {
                        document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour upvoter un post";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
                    }

                })
            })
        })




    // Asynch downvote MediaPost (et recalcul score)
    var btns4 = document.getElementsByClassName("downMediaPostBtn");
        Array.prototype.forEach.call(btns4, function(btn) {
            
            const idMediaPost = btn.getAttribute('mediaPostId');

            btn.addEventListener("click", function() {
                
                fetch('/downvoteMediaPost/' + idMediaPost, {
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
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                            document.getElementById("mup" + idMediaPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notDownvoted") {
                            btn.firstChild.style.color = "rgb(165, 165, 165)";
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                        }

                        if (data.newScore < 0) {
                            document.getElementById("mediaPostScore" + idMediaPost).style.color = "red";
                        }
                        else {
                            document.getElementById("mediaPostScore" + idMediaPost).style.color = "white";
                        }

                    } else {
                        document.querySelector('#ajaxFlash').textContent = "Vous devez être connecté pour upvoter un post";
                        document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
                    }

                })
            })
        })

            
                    



});

