<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"read"}},
 *          "denormalization_context"={"groups"={"write"}}
 *     },
 *     itemOperations={
 *         "get"={"method"="GET",
 *              "access_control"="is_granted('ROLE_USER') and object.getOwnerId() == user.getId()"
 *          }
 *     }
 * )
 *
 * @ORM\Entity
 */
class Journey
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
     * @var array
     *
     * @ORM\Column(type="simple_array")
     * @Assert\NotBlank
     *
     * @Groups({"read", "write"})
     */
    public $tags = '';

    /**
     * @var ApplicationUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ApplicationUser")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups("auto-generated")
     */
    private $owner;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     *
     * @Groups({"read", "write"})
     */
    private $project;

    /**
     * @var Step[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Step", mappedBy="journey")
     * @ORM\OrderBy({"id"="ASC"})
     *
     * @Groups({"read"})
     */
    private $steps;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    public function getOwner(): ApplicationUser
    {
        return $this->owner;
    }

    public function setOwner(ApplicationUser $owner): void
    {
        $this->owner = $owner;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return Step[]|Collection
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    /**
     * @param Step[]|Collection $steps
     */
    public function setSteps(Collection $steps): void
    {
        $this->steps = $steps;
    }

    public function getOwnerId(): int
    {
        return $this->owner->getId();
    }

    /**
     * @return array
     *
     * @Groups({"read"})
     */
    public function getTransitions()
    {
        $transitions = [];

        foreach ($this->steps as $step) {
            foreach ($step->getIncomingTransitions() as $transition) {
                $transitions[$transition->getId()] = $transition;
            }

            foreach ($step->getOutgoingTransitions() as $transition) {
                $transitions[$transition->getId()] = $transition;
            }
        }

        return array_values($transitions);
    }
}
