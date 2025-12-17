<?php

namespace Entities;

use DateTime;
use JsonSerializable;

/**
 * Entité Event - Représente un événement
 * 
 * Cette classe encapsule toutes les données et comportements d'un événement
 * avec validation, sérialisation JSON et méthodes utilitaires
 */
class Event implements JsonSerializable
{
    private ?int $id = null;
    private string $titre;
    private string $description;
    private string $lieu;
    private float $prix;
    private DateTime $dateEvent;
    private int $duree; // en minutes
    private int $capaciteMax = 100;
    private DateTime $dateCreation;
    private DateTime $dateModification;
    private bool $actif = true;
    private string $image = '';
    private string $categorie = '';
    
    /**
     * Constructeur
     */
    public function __construct(array $data = [])
    {
        $this->dateCreation = new DateTime();
        $this->dateModification = new DateTime();
        
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }
    
    /**
     * Hydrate l'entité avec un tableau de données
     */
    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        
        return $this;
    }
    
    // ============== GETTERS ==============
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitre(): string
    {
        return $this->titre;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function getLieu(): string
    {
        return $this->lieu;
    }
    
    public function getPrix(): float
    {
        return $this->prix;
    }
    
    public function getDateEvent(): DateTime
    {
        return $this->dateEvent;
    }
    
    public function getDuree(): int
    {
        return $this->duree;
    }
    
    public function getCapaciteMax(): int
    {
        return $this->capaciteMax;
    }
    
    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }
    
    public function getDateModification(): DateTime
    {
        return $this->dateModification;
    }
    
    public function isActif(): bool
    {
        return $this->actif;
    }
    
    public function getImage(): string
    {
        return $this->image;
    }
    
    public function getCategorie(): string
    {
        return $this->categorie;
    }
    
    // ============== SETTERS ==============
    
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function setTitre(string $titre): self
    {
        if (empty(trim($titre))) {
            throw new \InvalidArgumentException("Le titre ne peut pas être vide");
        }
        
        if (strlen($titre) > 200) {
            throw new \InvalidArgumentException("Le titre ne peut pas dépasser 200 caractères");
        }
        
        $this->titre = trim($titre);
        $this->updateModificationDate();
        return $this;
    }
    
    public function setDescription(string $description): self
    {
        if (empty(trim($description))) {
            throw new \InvalidArgumentException("La description ne peut pas être vide");
        }
        
        $this->description = trim($description);
        $this->updateModificationDate();
        return $this;
    }
    
    public function setLieu(string $lieu): self
    {
        if (empty(trim($lieu))) {
            throw new \InvalidArgumentException("Le lieu ne peut pas être vide");
        }
        
        $this->lieu = trim($lieu);
        $this->updateModificationDate();
        return $this;
    }
    
    public function setPrix(float $prix): self
    {
        if ($prix < 0) {
            throw new \InvalidArgumentException("Le prix ne peut pas être négatif");
        }
        
        $this->prix = round($prix, 2);
        $this->updateModificationDate();
        return $this;
    }
    
    public function setDateEvent($dateEvent): self
    {
        if (is_string($dateEvent)) {
            $dateEvent = new DateTime($dateEvent);
        }
        
        if (!$dateEvent instanceof DateTime) {
            throw new \InvalidArgumentException("Date invalide");
        }
        
        $this->dateEvent = $dateEvent;
        $this->updateModificationDate();
        return $this;
    }
    
    public function setDuree(int $duree): self
    {
        if ($duree <= 0) {
            throw new \InvalidArgumentException("La durée doit être positive");
        }
        
        $this->duree = $duree;
        $this->updateModificationDate();
        return $this;
    }
    
    public function setCapaciteMax(int $capaciteMax): self
    {
        if ($capaciteMax <= 0) {
            throw new \InvalidArgumentException("La capacité maximale doit être positive");
        }
        
        $this->capaciteMax = $capaciteMax;
        $this->updateModificationDate();
        return $this;
    }
    
    public function setDateCreation($dateCreation): self
    {
        if (is_string($dateCreation)) {
            $dateCreation = new DateTime($dateCreation);
        }
        
        $this->dateCreation = $dateCreation;
        return $this;
    }
    
    public function setDateModification($dateModification): self
    {
        if (is_string($dateModification)) {
            $dateModification = new DateTime($dateModification);
        }
        
        $this->dateModification = $dateModification;
        return $this;
    }
    
    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        $this->updateModificationDate();
        return $this;
    }
    
    public function setImage(string $image): self
    {
        $this->image = trim($image);
        $this->updateModificationDate();
        return $this;
    }
    
    public function setCategorie(string $categorie): self
    {
        $this->categorie = trim($categorie);
        $this->updateModificationDate();
        return $this;
    }
    
    // ============== MÉTHODES UTILITAIRES ==============
    
    /**
     * Met à jour la date de modification
     */
    private function updateModificationDate(): void
    {
        $this->dateModification = new DateTime();
    }
    
    /**
     * Vérifie si l'événement est passé
     */
    public function isPassed(): bool
    {
        return $this->dateEvent < new DateTime();
    }
    
    /**
     * Vérifie si l'événement est à venir
     */
    public function isFuture(): bool
    {
        return $this->dateEvent > new DateTime();
    }
    
    /**
     * Vérifie si l'événement est aujourd'hui
     */
    public function isToday(): bool
    {
        $today = new DateTime();
        return $this->dateEvent->format('Y-m-d') === $today->format('Y-m-d');
    }
    
    /**
     * Retourne le nombre de jours avant l'événement
     */
    public function getDaysUntilEvent(): int
    {
        $now = new DateTime();
        $interval = $now->diff($this->dateEvent);
        return (int) $interval->format('%r%a');
    }
    
    /**
     * Formate la durée en heures et minutes
     */
    public function getFormattedDuree(): string
    {
        $heures = floor($this->duree / 60);
        $minutes = $this->duree % 60;
        
        if ($heures > 0 && $minutes > 0) {
            return "{$heures}h{$minutes}min";
        } elseif ($heures > 0) {
            return "{$heures}h";
        } else {
            return "{$minutes}min";
        }
    }
    
    /**
     * Formate le prix avec le symbole euro
     */
    public function getFormattedPrix(): string
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }
    
    /**
     * Vérifie si l'événement est gratuit
     */
    public function isGratuit(): bool
    {
        return $this->prix == 0;
    }
    
    /**
     * Retourne la date formatée pour l'affichage
     */
    public function getFormattedDate(string $format = 'd/m/Y à H:i'): string
    {
        return $this->dateEvent->format($format);
    }
    
    /**
     * Vérifie si l'événement peut être réservé
     */
    public function isBookable(int $placesReservees = 0): bool
    {
        return $this->actif 
            && $this->isFuture() 
            && $placesReservees < $this->capaciteMax;
    }
    
    /**
     * Calcule le nombre de places disponibles
     */
    public function getPlacesDisponibles(int $placesReservees): int
    {
        return max(0, $this->capaciteMax - $placesReservees);
    }
    
    /**
     * Calcule le taux de remplissage en pourcentage
     */
    public function getTauxRemplissage(int $placesReservees): float
    {
        if ($this->capaciteMax == 0) {
            return 0;
        }
        
        return round(($placesReservees / $this->capaciteMax) * 100, 2);
    }
    
    /**
     * Vérifie si l'événement est presque complet (>80%)
     */
    public function isPresqueComplet(int $placesReservees): bool
    {
        return $this->getTauxRemplissage($placesReservees) >= 80;
    }
    
    /**
     * Vérifie si l'événement est complet
     */
    public function isComplet(int $placesReservees): bool
    {
        return $placesReservees >= $this->capaciteMax;
    }
    
    /**
     * Valide l'entité avant sauvegarde
     */
    public function validate(): array
    {
        $errors = [];
        
        if (empty($this->titre)) {
            $errors[] = "Le titre est obligatoire";
        }
        
        if (empty($this->description)) {
            $errors[] = "La description est obligatoire";
        }
        
        if (empty($this->lieu)) {
            $errors[] = "Le lieu est obligatoire";
        }
        
        if ($this->prix < 0) {
            $errors[] = "Le prix ne peut pas être négatif";
        }
        
        if (!isset($this->dateEvent)) {
            $errors[] = "La date de l'événement est obligatoire";
        }
        
        if ($this->duree <= 0) {
            $errors[] = "La durée doit être positive";
        }
        
        if ($this->capaciteMax <= 0) {
            $errors[] = "La capacité maximale doit être positive";
        }
        
        return $errors;
    }
    
    /**
     * Vérifie si l'entité est valide
     */
    public function isValid(): bool
    {
        return empty($this->validate());
    }
    
    /**
     * Convertit l'entité en tableau pour la base de données
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'lieu' => $this->lieu,
            'prix' => $this->prix,
            'date_event' => $this->dateEvent->format('Y-m-d H:i:s'),
            'duree' => $this->duree,
            'capacite_max' => $this->capaciteMax,
            'date_creation' => $this->dateCreation->format('Y-m-d H:i:s'),
            'date_modification' => $this->dateModification->format('Y-m-d H:i:s'),
            'actif' => $this->actif ? 1 : 0,
            'image' => $this->image,
            'categorie' => $this->categorie
        ];
    }
    
    /**
     * Implémentation de JsonSerializable
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'lieu' => $this->lieu,
            'prix' => $this->prix,
            'prixFormate' => $this->getFormattedPrix(),
            'dateEvent' => $this->dateEvent->format('Y-m-d H:i:s'),
            'dateFormatee' => $this->getFormattedDate(),
            'duree' => $this->duree,
            'dureeFormatee' => $this->getFormattedDuree(),
            'capaciteMax' => $this->capaciteMax,
            'actif' => $this->actif,
            'image' => $this->image,
            'categorie' => $this->categorie,
            'isPassed' => $this->isPassed(),
            'isFuture' => $this->isFuture(),
            'isToday' => $this->isToday(),
            'isGratuit' => $this->isGratuit()
        ];
    }
    
    /**
     * Représentation en chaîne de caractères
     */
    public function __toString(): string
    {
        return sprintf(
            "Event #%d: %s (%s) - %s",
            $this->id ?? 0,
            $this->titre,
            $this->getFormattedDate(),
            $this->getFormattedPrix()
        );
    }
    
    /**
     * Clone l'entité
     */
    public function __clone()
    {
        $this->id = null;
        $this->dateCreation = new DateTime();
        $this->dateModification = new DateTime();
    }
}
