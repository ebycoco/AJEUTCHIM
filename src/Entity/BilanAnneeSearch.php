<?php

namespace App\Entity;

class BilanAnneeSearch
{
    /**
     * annee
     *
     * @var string|null
     */
    private $annee;

    /**
     * Get $annee
     * @return string
     */
    public function getAnnee(): string
    {
        return $this->annee;
    }

    /**
     * Set $annee
     * @param string $annee
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    }
}