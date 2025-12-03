<?php
class Categorie
{
    private ?int $idc;
    private string $nomC;
    private string $descriptionC;
    private string $dateC;
    private string $nom_Createur;

    // Constructor
    public function __construct(
        ?int $idc = null,
        string $nomC,
        string $descriptionC,
        string $dateC,
        string $nom_Createur
    ) {
        $this->idc = $idc;
        $this->nomC = $nomC;
        $this->descriptionC = $descriptionC;
        $this->dateC = $dateC;
        $this->nom_Createur = $nom_Createur;
    }

    // Getters & Setters
    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function setIdc(int $idc): self
    {
        $this->idc = $idc;
        return $this;
    }

    public function getNomC(): string
    {
        return $this->nomC;
    }

    public function setNomC(string $nomC): self
    {
        $this->nomC = $nomC;
        return $this;
    }

    public function getDescriptionC(): string
    {
        return $this->descriptionC;
    }

    public function setDescriptionC(string $descriptionC): self
    {
        $this->descriptionC = $descriptionC;
        return $this;
    }

    public function getDateC(): string
    {
        return $this->dateC;
    }

    public function setDateC(string $dateC): self
    {
        $this->dateC = $dateC;
        return $this;
    }

    public function getNom_Createur(): string
    {
        return $this->nom_Createur;
    }

    public function setNom_Createur(string $nom_Createur): self
    {
        $this->nom_Createur = $nom_Createur;
        return $this;
    }
}
?>
