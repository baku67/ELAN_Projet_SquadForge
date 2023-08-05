<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Entity\OAuthTwitch;

class AppExtension extends AbstractExtension
{
    public function __construct(private RequestStack $requestStack){
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('time_diff', [$this, 'calculateTimeDiff']),
            new TwigFunction('time_diff_future', [$this, 'calculateTimeDiffFuture']),
            new TwigFunction('twitchOAuth', [$this, 'twitchOAuth']),
            new TwigFunction('findChannel', [$this, 'findChannel']),
            new TwigFunction('findGameChannels', [$this, 'findGameChannels']),
            new TwigFunction('findGamesByName', [$this, 'findGamesByName']),
            new TwigFunction('getUser', [$this, 'getUser']),
            new TwigFunction('getUserFromUsername', [$this, 'getUserFromUsername']),
            
        ];
    }

    public function calculateTimeDiff($date): string
    {
        $now = new \DateTime();
        $diff = $now->diff($date);

        $days = $diff->format('%a');
        $hours = $diff->format('%h');
        $minutes = $diff->format('%i');

        if ($days == 0) {
            $days = "";
        }
        if ($hours == 0) {
            $hours = "";
        }
        if ($minutes == 0) {
            $minutes = "";
        }


        if ($days > 0) {
            if ($days > 365) {
                return "il y a > 1an";
            }
            else {
                if($days > 30) {
                    $nbrMonth = intval($days / 30);
                    return "il y a {$nbrMonth}mois";
                }
                else {
                    return "il y a {$days}j";
                }
            }
        }
        else if (($hours > 0) && (empty($days))) {
            return "il y a {$hours}h";
        }
        else if (($minutes >= 0) && (empty($hours)) && (empty($days))) {
            return "il y a {$minutes}m";
        }
        else if ((empty($days)) && (empty($hours)) && (empty($minutes))) {
            return "à l'instant";
        }
    }


    public function calculateTimeDiffFuture($date): string
    {
        $now = new \DateTime();
        $diff = $now->diff($date);

        if ($diff->invert == 1) {
            // Date has already passed (1 = diff négative, 0 sinon)
            return false;
        }

        $days = $diff->format('%a');
        $hours = $diff->format('%h');
        $minutes = $diff->format('%i');

        if ($days == 0) {
            $days = "";
        }
        if ($hours == 0) {
            $hours = "";
        }
        if ($minutes == 0) {
            $minutes = "";
        }


        if ($days > 0) {
            if ($days > 365) {
                return "> 1an";
            }
            else {
                if($days > 30) {
                    $nbrMonth = intval($days / 30);
                    return "{$nbrMonth}mois";
                }
                else {
                    return "{$days}j";
                }
            }
        }
        else if (($hours > 0) && (empty($days))) {
            return "{$hours}h";
        }
        else if (($minutes >= 0) && (empty($hours)) && (empty($days))) {
            return "{$minutes}m";
        }
        else if ((empty($days)) && (empty($hours)) && (empty($minutes))) {
            return "à l'instant";
        }
    }




    // *********************  Twitch OAuth requêtes API

    public function getUser(string $username) 
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');
        $oauth->set_headers($session->get('token'));

        $user = $oauth->get_user($username);
        // Si ni ID ou Username spécifié, mais User Acces Toekn, alors renvoi l'user coonecté: (HS) https://dev.twitch.tv/docs/api/reference/#get-users
        // $channels = $oauth->get_user($session->get('token'));

        return $user;
    }



    public function findChannel(string $input)
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');
        $oauth->set_headers($session->get('token'));

        $channels = $oauth->search_channel($input);

        return $channels;
    }


    // Recherche de jeux par mot
    public function findGamesByName(string $title)
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');
        $oauth->set_headers($session->get('token'));

        $games = $oauth->get_games($title);

        return $games;
    }

    // TODO: get les Streamers qui stream le jeu (id envoyé en paramètre) actuellement
    public function findGameChannels(int $id)
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:email');
        $oauth->set_headers($session->get('token'));

        $channels = $oauth->get_game_streams($id);

        return $channels;
    }

    

    public function getName(): string
    {
        return 'app_extension';
    }
}