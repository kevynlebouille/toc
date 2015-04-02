<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Project;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('currency', array($this, 'currency')),
            new \Twig_SimpleFilter('gravatar', array($this, 'gravatar')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('count_down', array($this, 'countDown')),
        );
    }

    public function gravatar($email, $size = 100)
    {
        $hash = md5($email);

        return "http://www.gravatar.com/avatar/$hash.jpg?s=$size";
    }

    public function currency($number)
    {
        return number_format($number, 0, ',', ' ') . ' €';
    }

    public function countDown(Project $project, $verbose = false)
    {
        $days = $project->getCountDown();

        if ($days > 0)
        {
            if ($verbose) {
                return $days . ' jour(s) restant(s)';
            }

          return 'J-' . $days;
        }

        if ($verbose) {
            return 'Objectif atteint !';
        }

        return 'Terminé';
    }

    public function getName()
    {
        return 'app_extension';
    }
}
