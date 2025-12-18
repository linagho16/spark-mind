<?php
class produit
{
    private ?int $id;
    private string $title;
    private string $description;
    private int $category; // Foreign key vers categorie.idc
    private string $condition; // neuf - bon etat - usage
    private string $statut;    // disponible - reserve
    private string $photo;

    // Constructor
    public function __construct(
        ?int $id = null,
        string $title,
        string $description,
        int $category,
        string $condition,
        string $statut,
        string $photo
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->condition = $condition;
        $this->statut = $statut;
        $this->photo = $photo;
    }

    // Getters & Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }
}
?>
