<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('time_diff', [$this, 'calculateTimeDiff']),
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

    public function getName(): string
    {
        return 'app_extension';
    }
}