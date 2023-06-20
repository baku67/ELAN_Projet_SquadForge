$(document).ready(function() {

    // Ajax bouton: suppr toutes les notifs en "vue"
    $('#deleteAllLink').click(function(event) {
    event.preventDefault(); 
    
        $.ajax({
            url: '/cleanNotifsUser',
            type: 'GET',
            success: function(response) {

                document.getElementById('newNotifBubbleCount').classList.add("fadeOut");

                document.getElementById('allSeenLink').classList.add('fadeOut');
                document.getElementById('deleteAllLink').classList.add('fadeOut');
                setTimeout(() => {
                    document.getElementById('allSeenLink').remove();
                    document.getElementById('deleteAllLink').remove();

                    $msg = document.createElement('p');
                    $msg.className = 'emptyListMsg fadeIn';
                    $msg.textContent = "Aucune notifications";
                    $msg.style.margin = "50px auto";
                    $msg.style.opacity = "0";
                    document.getElementById('notifListButtons').append($msg);
                }, 600);

                var elements = document.querySelectorAll('.notifCard');
                function removeElementsWithDelay(index) {
                    if (index >= elements.length) {
                        return; // Stop quand plus d'elem
                    }
                    setTimeout(function() {
                        elements[index].classList.add("fadeOutNotifCard");
                        setTimeout(function() {
                            elements[index].remove();
                        }, 550);
                        // elements[index].remove();
                        removeElementsWithDelay(index + 1); 
                    }, 250); 
                }
                removeElementsWithDelay(0);
            
                document.querySelector('#ajaxFlash').textContent = "Les notifications ont été nettoyées";
                document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");
            },
            error: function(xhr, status, error) {
                document.querySelector('#ajaxFlash').textContent = "Echec";
                document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
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
                document.querySelector('#ajaxFlash').textContent = "Notifications marquées en \"vues\"";
                document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "successAjaxFlash");
            },
            error: function(xhr, status, error) {
                document.querySelector('#ajaxFlash').textContent = "Echec";
                document.querySelector('#ajaxFlash').classList.add("ajaxFlashAnim", "errorAjaxFlash");
            }
        });
    });


});

