<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ApiResource()
 *
 * @ORM\Entity
 */
class Step
{
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     *
     * @Groups({"read", "write"})
     */
    public $name = '';

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     *
     * @Groups({"read", "write"})
     */
    public $description = '';

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     *
     * @Groups({"read", "write"})
     */
    public $imageUrl = '';

    /**
     * @var Journey
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Journey", inversedBy="steps")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"write"})
     * @MaxDepth(1)
     */
    private $journey;

    /**
     * @var Transition[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transition", mappedBy="fromStep")
     * @ORM\OrderBy({"id"="ASC"})
     *
     * @Groups({"read"})
     *
     * @MaxDepth(1)
     */
    private $outTransitions;


    /**
     * @var Transition[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transition", mappedBy="toStep")
     * @ORM\OrderBy({"id"="ASC"})
     *
     * @Groups({"read"})
     *
     * @MaxDepth(1)
     */
    private $inTransitions;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return Journey
     */
    public function getJourney(): Journey
    {
        return $this->journey;
    }

    /**
     * @param Journey $journey
     */
    public function setJourney(Journey $journey): void
    {
        $this->journey = $journey;
    }

    /**
     * @return Transition[]|Collection
     */
    public function getOutTransitions(): Collection
    {
        return $this->outTransitions;
    }

    /**
     * @param Transition[]|Collection $outTransitions
     */
    public function setOutTransitions(Collection $outTransitions): void
    {
        $this->outTransitions = $outTransitions;
    }

    /**
     * @return Transition[]|Collection
     */
    public function getInTransitions(): Collection
    {
        return $this->inTransitions;
    }

    /**
     * @param Transition[]|Collection $inTransitions
     */
    public function setInTransitions(Collection $inTransitions): void
    {
        $this->inTransitions = $inTransitions;
    }
}
