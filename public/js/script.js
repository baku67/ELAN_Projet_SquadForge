
window.addEventListener('load', function() {

    // Exécuté partout (landingPage y compris:)
    // console.log('TESTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT');



    // *************************  Scroll Header fixed + reduced ************************

        // Vérif pas landingPage
        if (document.getElementById('landingPageBool') == null) {

            window.addEventListener('scroll', () => {
                // Vérifiez la position verticale de défilement
                if (window.scrollY > 0) {
                    document.getElementsByTagName('header')[0].classList.add('headerFixed');
                    document.getElementById('logoPng').style.width = "49px";
                    document.getElementById('logoPng').style.padding = "0px";
                    document.getElementsByClassName('navLine')[0].style.bottom = "0px";

                    // Le header passe de relative à Fixed (compensation du mainContent)
                    document.getElementsByClassName('main')[0].style.paddingTop = "100px";
                } else {
                    document.getElementsByTagName('header')[0].classList.remove('headerFixed');
                    document.getElementById('logoPng').style.width = "169px";
                    document.getElementById('logoPng').style.padding = "6px";
                    document.getElementsByClassName('navLine')[0].style.bottom = "-3px";

                    // Le header passe de relative à Fixed (compensation du mainContent)
                    document.getElementsByClassName('main')[0].style.paddingTop = "0px";
                }
            });

        }

    // **********************************************************************************



    // Library pour pouvoir desactiver l'autoPlay des gifs (paramètres User)
    Gifffer();


    // ScrollReveal
    ScrollReveal().reveal('.mediaCard');



    // Anim fadeIn 5 Topics Game
    var topicCards = document.querySelectorAll('.topicCard');
    var topicCardsGlobal = document.querySelectorAll('.topicCardGlobal');

    // Fonction pour ajouter une classe supplémentaire à chaque élément après un délai
    function addClassWithDelay(element, className, delay) {
        setTimeout(function() {
            element.classList.add(className);
        }, delay);
    }

    // FadeIn des topicCards
    var delay = 0;
    for (var i = 0; i < topicCards.length; i++) {
        var card = topicCards[i];
        addClassWithDelay(card, 'topicCardFadeInAnim', delay);
        delay += 200; // Délai en millisecondes (ajuste le délai si nécessaire)
    }

    // FadeIn des topicCards (liste globale)
    var delay2 = 0;
    for (var i = 0; i < topicCardsGlobal.length; i++) {
        var card2 = topicCardsGlobal[i];
        addClassWithDelay(card2, 'topicCardFadeInAnim', delay2);
        delay2 += 50; // Délai en millisecondes (ajuste le délai si nécessaire)
    }




    // TODO: Asynch fetch périodique newNotifs non clicked (user/modo)
    // setInterval() ajax app_checkNewNotifs 


    

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
                        document.getElementById('favGameBtn').lastChild.className="fa-solid fa-heart favIcon";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid " + document.getElementById('gameColor').textContent;
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid " + document.getElementById('gameColor').textContent;
    
                        document.getElementById('favGameBtn').lastChild.style.color = document.getElementById('gameColor').textContent;
    
                        window.FlashMessage.success('Ajouté aux favoris');
    
                    }
                    else if (data.newState == "notFavorited") {
                        document.getElementById('favGameBtn').lastChild.className="fa-regular fa-heart favIcon";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid rgb(255 255 255 / 20%)";
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid rgb(255 255 255 / 20%)";
    
                        document.getElementById('favGameBtn').lastChild.style.color = "white";
    
                        window.FlashMessage.success('Retiré des favoris');
                    }
        
                } else {
                    window.FlashMessage.error('Vous devez être connecté pour ajouter un jeu à vos favoris');
                }
    
            })
        })
    

    }








    // Asynch des Likes de média (+ recalcule du compte)
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
                    if (data.newState == "liked") {
                        window.FlashMessage.success('Upvoté');
                    }
                    else {
                        window.FlashMessage.success('Upvote retiré');
                    }
                } else {
                    window.FlashMessage.error('Vous devez être connecté pour upvoter un média');
                }

                if (data.newState == "liked") {
                    btn.style.color = "var(--primary-color)";
                    btn.style.borderColor = "var(--primary-color)";
                    document.getElementById("countLikesMedia" + id).innerHTML = data.newCountLikes;
                }
                else if (data.newState == "unliked") {
                    btn.style.color = "var(--white)";
                    btn.style.borderColor = "rgba(255, 255, 255, 0.3)";
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

                        if (data.newState == "upvoted") {
                            window.FlashMessage.success('Commentaire liké');
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                            document.getElementById("down" + idTopicPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notUpvoted") {
                            window.FlashMessage.success('Like retiré');
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
                        window.FlashMessage.error('Vous devez être connecté pour liker un commentaire');
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

                        if (data.newState == "downvoted") {
                            window.FlashMessage.success('Commentaire disliké');
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("topicPostScore" + idTopicPost).innerHTML = data.newScore;
                            document.getElementById("up" + idTopicPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notDownvoted") {
                            window.FlashMessage.success('Dislike retiré');
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
                        window.FlashMessage.error('Vous devez être connecté pour disliker un commentaire');
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

                        if (data.newState == "upvoted") {
                            window.FlashMessage.success('Commentaire liké');
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                            document.getElementById("mdown" + idMediaPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notUpvoted") {
                            window.FlashMessage.success('Like retiré');
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
                        window.FlashMessage.error('Vous devez être connecté pour liker un commentaire');
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

                        if (data.newState == "downvoted") {
                            window.FlashMessage.success('Commentaire disliké');
                            btn.firstChild.style.color = data.gameColor;
                            document.getElementById("mediaPostScore" + idMediaPost).innerHTML = data.newScore;
                            document.getElementById("mup" + idMediaPost).firstChild.style.color = "grey";
                        }
                        else if (data.newState == "notDownvoted") {
                            window.FlashMessage.success('Dislike retiré');
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
                        window.FlashMessage.error('Vous devez être connecté pour disliker un commentaire');
                    }

                })
            })
        })

            
                    

});

