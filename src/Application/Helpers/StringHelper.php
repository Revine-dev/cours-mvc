<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class StringHelper
{
    /**
     * Génère un slug à partir d'une chaîne de caractères.
     *
     * @param string $text Texte à transformer
     * @param string $divider Séparateur (par défaut '-')
     * @return string Le slug généré
     */
    public function slugify(string $text, string $divider = '-'): string
    {
        // Remplacer les caractères non-alphanumériques par le séparateur
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // Translittération (ex: é -> e)
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', (string) $text);

        // Supprimer les caractères indésirables restants
        $text = preg_replace('~[^-\w]+~', '', (string) $text);

        // Supprimer les séparateurs redondants
        $text = preg_replace('~-+~', $divider, (string) $text);

        // Retirer les séparateurs aux extrémités
        $text = trim((string) $text, $divider);

        // Convertir en minuscules
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function substr(string $text, int $offset, ?int $length = null): string
    {
        return mb_substr($text, $offset, $length);
    }
}
