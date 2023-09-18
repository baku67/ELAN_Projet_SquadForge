<?php 

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

use Doctrine\ORM\EntityManagerInterface;

class LoginListener implements EventSubscriberInterface
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Get the user from the event
        $user = $event->getAuthenticationToken()->getUser();

        // Get the current timestamp as the new last connection timestamp
        $currentTimestamp = new \DateTime();

        // Get the previous last connection timestamp from the user profile
        $previousTimestamp = $user->getLastCo();

        // Set the previous connection timestamp to the value of the current last connection timestamp
        $user->setPreviousCo($previousTimestamp);

        // Update the last connection timestamp with the current timestamp
        $user->setLastCo($currentTimestamp);

        // Save the changes to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }
}