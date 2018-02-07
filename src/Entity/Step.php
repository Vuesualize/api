<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 *
 * @ORM\Entity
 */
class Step
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"step-read", "read", "write"})
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
     */
    private $journey;

    /**
     * @var Transition[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transition", mappedBy="fromStep")
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $outgoingTransitions;


    /**
     * @var Transition[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transition", mappedBy="toStep")
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $incomingTransitions;

    public function __construct()
    {
        $this->incomingTransitions = new ArrayCollection();
        $this->outgoingTransitions = new ArrayCollection();
    }

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
    public function getOutgoingTransitions(): Collection
    {
        return $this->outgoingTransitions;
    }

    /**
     * @return Transition[]|Collection
     */
    public function getIncomingTransitions(): Collection
    {
        return $this->incomingTransitions;
    }


    /**
     * @return string[]
     *
     * @Groups({"read"})
     */
    public function getInTransitions(): array
    {
        $ids = [];

        foreach ($this->incomingTransitions as $transition) {
            $ids[] = '/transitions/'.$transition->getId();
        }

        return $ids;
    }

    /**
     * @return string[]
     *
     * @Groups({"read"})
     */
    public function getOutTransitions(): array
    {
        $ids = [];

        foreach ($this->outgoingTransitions as $transition) {
            $ids[] = '/transitions/'.$transition->getId();
        }

        return $ids;
    }
}
