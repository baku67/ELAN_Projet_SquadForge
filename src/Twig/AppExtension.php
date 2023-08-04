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
    public function __construct(
        private RequestStack $requestStack,
    ){

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('time_diff', [$this, 'calculateTimeDiff']),
            new TwigFunction('time_diff_future', [$this, 'calculateTimeDiffFuture']),
            new TwigFunction('twitchOAuth', [$this, 'twitchOAuth']),
            new TwigFunction('findChannel', [$this, 'findChannel']),
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


    public function twitchOAuth()
    {
        // session_start();
        $session = $this->requestStack->getSession();
        $session->start();
        
        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:broadcast');

        $link = $oauth->get_link_connect();

        if(!empty($_GET['code'])) {
            $code = htmlspecialchars(($_GET['code']));
            $token = $oauth->get_token($code);

            // $_SESSION['token'] = $token;
            $session->set('token', $token);
        }
        else {
            // HS car le code passe par ici (pourtant code bien présent dans l'url callback)
            $session->set('token', 'testEmptyGetCode2');
        }

        return $link;
        // Pour tester le token en session:
        // return $session->get('token');
    }

    public function findChannel(string $input)
    {
        $session = $this->requestStack->getSession();

        $oauth = new OAuthTwitch('9xmxl9h3npck0tvgcdejwzeczhbl0w', 'l0qj5m6wmay7k28z20a48s7f74xs3x', 'http://localhost:8000/oauthCallback', 'user:read:broadcast');

        // Comme HS:
        // $oauth->set_headers($session->get('token'));
        if(!empty($_GET['code'])) {
            $code = htmlspecialchars(($_GET['code']));
            $token = $oauth->get_token($code);
            $session->set('token', $token);
        }
        $oauth->set_headers($session->get('token'));

        $channel = $oauth->search_channel($input);


        return $channel;
    }
    

    public function getName(): string
    {
        return 'app_extension';
    }
}