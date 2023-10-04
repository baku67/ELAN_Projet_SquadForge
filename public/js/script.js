
window.addEventListener('load', function() {

    //**** */ Exécuté partout (landingPage y compris:)


    // Toggle close Mobile Burger Nav
    document.getElementById('closeBurgerMobile').addEventListener("click", function() {
        document.getElementById('showBurgerMenu').classList.toggle('notDisplayed');
        document.getElementById('navLine').classList.toggle('showNavMobile');
    })

    // Toggle show Mobile Burger Nav
    document.getElementById('showBurgerMenu').addEventListener("click", function() {
        document.getElementById('showBurgerMenu').classList.toggle('notDisplayed');
        document.getElementById('navLine').classList.toggle('showNavMobile');
    })


    // scrollToTop pop/depop on scroll
    window.addEventListener('scroll', () => {
        const scrollToTopBtn = document.getElementById('scrollToTop');
        if (window.scrollY > 0) {
            scrollToTopBtn.style.opacity = "1";
            scrollToTopBtn.style.pointerEvents = "all";
            // scrollToTopBtn.style.display = "block";
            
        } else {
            scrollToTopBtn.style.opacity = "0";
            scrollToTopBtn.style.pointerEvents = "none";
            // setTimeout(() => {
            //     scrollToTopBtn.style.display = "none";
            // }, 305);
        }
    })
    // scrollToTop click
    function scrollToTop() {
        // Scroll to the top over a specified duration (e.g., 500 milliseconds)
        const duration = 300;
        const start = window.pageYOffset;
        const startTime = performance.now();
      
        function step(timestamp) {
          const currentTime = timestamp - startTime;
          window.scrollTo(0, easeInOutCubic(currentTime, start, -start, duration));
          if (currentTime < duration) {
            requestAnimationFrame(step);
          }
        }
      
        function easeInOutCubic(t, b, c, d) {
          if ((t /= d / 2) < 1) return (c / 2) * t * t * t + b;
          return (c / 2) * ((t -= 2) * t * t + 2) + b;
        }
      
        requestAnimationFrame(step);
    }

    document.getElementById('scrollToTop').addEventListener('click', scrollToTop);



    // *************************  Scroll Header fixed + reduced ************************

        // Vérif pas landingPage
        if (document.getElementById('landingPageBool') == null) {

            window.addEventListener('scroll', () => {
                // Vérifiez la position verticale de défilement
                if (window.scrollY > 0) {
                    document.getElementsByTagName('header')[0].classList.add('headerFixed');
                    document.getElementById('logoPng').style.width = "49px";
                    document.getElementById('logoPng').style.padding = "0px";

                    document.getElementById('logoShrinkTxt').style.display = "block";
                    document.getElementById('logoShrinkTxt').style.opacity = "1";

                    if(document.getElementsByClassName('navLine')[0] !== null) {
                        document.getElementsByClassName('navLine')[0].style.bottom = "0px";
                    }

                    if(document.getElementById('headerGameTitle') !== null) {
                        document.getElementById('headerGameTitle').style.borderBottomWidth = "0px";
                        document.getElementById('headerGameTitle').style.backgroundColor = "#000000b5";
                    }

                    // Le header passe de relative à Fixed (compensation du mainContent)
                    document.getElementsByClassName('main')[0].style.paddingTop = "100px";

                } else {
                    document.getElementsByTagName('header')[0].classList.remove('headerFixed');
                    document.getElementById('logoPng').style.width = "169px";
                    document.getElementById('logoPng').style.padding = "6px";

                    document.getElementById('logoShrinkTxt').style.opacity = "0";
                    setTimeout(() => {
                        document.getElementById('logoShrinkTxt').style.display = "none";
                    }, 305);
                    

                    if(document.getElementsByClassName('navLine')[0] !== null) {
                        document.getElementsByClassName('navLine')[0].style.bottom = "-4px";
                    }

                    if(document.getElementById('headerGameTitle') !== null) {
                        document.getElementById('headerGameTitle').style.borderBottomWidth = "2px";
                        document.getElementById('headerGameTitle').style.backgroundColor = "#000000b5";
                    }

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

            // Spinning loader pendant requete
            document.getElementById('favHeartIcon').style.opacity = "0";
            document.getElementById('spinningLoader').classList.toggle('spinningLoader');

    
            fetch('/game/toggleGameFav/' + gameId, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {

                document.getElementById('spinningLoader').classList.toggle('spinningLoader'); 
                document.getElementById('favHeartIcon').style.opacity = "1";

                if (data.success) {
    
                    if (data.newState == "favorited") {
                        document.getElementById('favHeartIcon').className="fa-solid fa-heart favIcon";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid " + document.getElementById('gameColor').textContent;
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid " + document.getElementById('gameColor').textContent;
    
                        document.getElementById('favHeartIcon').style.color = document.getElementById('gameColor').textContent;
    
                        window.FlashMessage.success('Ajouté aux favoris');
    
                    }
                    else if (data.newState == "notFavorited") {
                        document.getElementById('favHeartIcon').className="fa-regular fa-heart favIcon";
                        document.getElementById('favGameBtn').style.borderTop = "1px solid rgb(255 255 255 / 20%)";
                        document.getElementById('favGameBtn').style.borderBottom = "1px solid rgb(255 255 255 / 20%)";
    
                        document.getElementById('favHeartIcon').style.color = "white";
    
                        window.FlashMessage.success('Retiré des favoris');
                    }
        
                } else {
                    window.FlashMessage.error('Vous devez être connecté pour ajouter un jeu à vos favoris');
                }
    
            })
        })
    

    }



    // HOME section collapse (mobile)
    if(document.getElementById('homeFavSectionCollapseBtn') !== null) {
        document.getElementById('homeFavSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('homeFavSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('homeFavGamesTitle').classList.toggle('sectionTitleBorderRadius');
            // if(document.getElementById('homeFavSectionCollapse').classList.contains('collapseSection')) {
            //     document.getElementById('homeFavSectionCollapseBtn').innerHTML = "<i class='fa-solid fa-chevron-up'></i>";
            // }
            // else {
            //     document.getElementById('homeFavSectionCollapseBtn').innerHTML = "<i class='fa-solid fa-chevron-down'></i>";
            // }
            document.getElementById('homeFavSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('homeTeamsSectionCollapseBtn') !== null) {
        document.getElementById('homeTeamsSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('homeTeamsSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('homeUserTeamsTitle').classList.toggle('sectionTitleBorderRadius');
            document.getElementById('homeTeamsSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('homeTopicsSectionCollapseBtn') !== null) {
        document.getElementById('homeTopicsSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('homeTopicsSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('homeTopicsTitle').classList.toggle('sectionTitleBorderRadius');
            document.getElementById('homeTopicsSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('homeMediasSectionCollapseBtn') !== null) {
        document.getElementById('homeMediasSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('homeMediasSectionCollapse').classList.toggle('collapseSection3');
            document.getElementById('homeMediasTitle').classList.toggle('sectionTitleBorderRadius');
            // TODO: Fix éléments cachés mais présents et clickables
            document.getElementById('homeMediasSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    // GROUP section collapse (mobile)

    if(document.getElementById('groupHeaderSectionsCollapseBtn') !== null) {
        document.getElementById('groupHeaderSectionsCollapseBtn').addEventListener("click", function() {
            document.getElementById('groupHeaderSectionsCollapse').classList.toggle('collapseSection2');
            document.getElementById('teamImgContainer').classList.toggle('collapseSection2');
            document.getElementById('groupHeaderSectionsCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('groupMemberSectionCollapseBtn') !== null) {
        document.getElementById('groupMemberSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('groupMemberSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('groupMemberSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('groupPlanningSectionCollapseBtn') !== null) {
        document.getElementById('groupPlanningSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('groupPlanningSectionCollapse').classList.toggle('collapseSection3');
            // TODO: Fix éléments cachés mais présents et clickables
            document.getElementById('groupPlanningSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('groupParamSectionCollapseBtn') !== null) {
        document.getElementById('groupParamSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('groupParamSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('groupParamSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }

    if(document.getElementById('groupCandidatureSectionCollapseBtn') !== null) {
        document.getElementById('groupCandidatureSectionCollapseBtn').addEventListener("click", function() {
            document.getElementById('groupCandidatureSectionCollapse').classList.toggle('collapseSection2');
            document.getElementById('groupCandidatureSectionCollapseBtn').classList.toggle('chevronRotate');
        })
    }




    // Asynch des Likes de média (+ recalcule du compte)
    var btns = document.getElementsByClassName("mediaSubLikeDiv");
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

