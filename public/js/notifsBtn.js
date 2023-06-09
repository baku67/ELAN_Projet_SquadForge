$(document).ready(function() {

    // Ajax bouton: suppr toutes les notifs
    $('#deleteAllLink').click(function(event) {
    event.preventDefault(); 
    
        $.ajax({
            url: '/cleanNotifsUser',
            type: 'GET',
            success: function(response) {

                if(document.getElementById('newNotifBubbleCount') != null) {
                    document.getElementById('newNotifBubbleCount').classList.add("fadeOut");
                }

                if(document.getElementById('allSeenLink') != null) {
                    document.getElementById('allSeenLink').classList.add('fadeOut');
                }
                document.getElementById('deleteAllLink').classList.add('fadeOut');
                setTimeout(() => {
                    if(document.getElementById('allSeenLink') != null) {
                        document.getElementById('allSeenLink').remove();
                    }
                    document.getElementById('deleteAllLink').remove();

                    $msg = document.createElement('p');
                    $msg.className = 'emptyListMsg fadeIn';
                    $msg.textContent = "Aucune notifications";
                    $msg.style.margin = "50px auto";
                    $msg.style.opacity = "0";
                    document.getElementById('notifListButtons').append($msg);
                }, 600);

                var elements = document.querySelectorAll('.notifCardLine');
                var reversedArray = Array.from(elements).reverse(); // Convertit NodeList en array et inverse l'ordre
                
                function removeElementsWithDelay(index) {
                  if (index >= reversedArray.length) {
                    return; // Arrête la fonction quand tous les éléments ont été traités
                  }
                
                  setTimeout(function() {
                    reversedArray[index].classList.add("fadeOutNotifCard");
                    setTimeout(function() {
                      reversedArray[index].remove();
                    }, 550);
                
                    removeElementsWithDelay(index + 1); 
                  }, 250);
                }
                
                removeElementsWithDelay(0);

                window.FlashMessage.success('Les notifications ont été nettoyées');
            },
            error: function(xhr, status, error) {

                window.FlashMessage.error('Une erreur est survenue');
            }
        });
    });



    // Ajax bouton: passer toutes les notifs en "vue" 
    $('#allSeenLink').click(function(event) {
    event.preventDefault(); 
    
        $.ajax({
            url: '/notifsAllSeen',
            type: 'GET',
            success: function(response) {

                if(document.getElementById('newNotifBubbleCount') != null) {
                    document.getElementById('newNotifBubbleCount').classList.add("fadeOut");
                }

                if(document.getElementById('allSeenLink') != null) {
                    document.getElementById('allSeenLink').classList.add('fadeOut');
                    setTimeout(function() {
                        document.getElementById('allSeenLink').remove();
                    }, 550)
                }

                var elements = document.querySelectorAll('.unclickedNotifSpot');
                elements.forEach(function(element) {
                    element.classList.add("fadeOutNotifPeriod");
                });
                window.FlashMessage.success('Notifications marquées en \"vues\"');
            },
            error: function(xhr, status, error) {
                window.FlashMessage.error('Une erreur est survenue');
            }
        });
    });


});

