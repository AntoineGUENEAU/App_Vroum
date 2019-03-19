<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 */
class Serie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="serie", orphanRemoval=true)
     */
    private $questions;

    /**
     * @ORM\Column(type="string")
     */
    private $libelle;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Result", mappedBy="serie_id", cascade={"persist", "remove"})
     */
    private $result;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setSerie($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getSerie() === $this) {
                $question->setSerie(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        // Pour affiche rle nom dans le select
        return $this->libelle;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        // set (or unset) the owning side of the relation if necessary
        $newSerie_id = $result === null ? null : $this;
        if ($newSerie_id !== $result->getSerieId()) {
            $result->setSerieId($newSerie_id);
        }

        return $this;
    }
}
