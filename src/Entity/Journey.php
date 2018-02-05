<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
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
 *
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
     * @Groups("admin")
     * @MaxDepth(1)
     *
     * @ApiProperty(readable=false, readableLink=false)
     */
    private $owner;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", fetch="LAZY")
     *
     * @Groups({"read"})
     */
    private $project;

    /**
     * @var Step[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Step", mappedBy="journey")
     * @ORM\OrderBy({"id"="ASC"})
     *
     * @Groups({"read"})
     *
     * @MaxDepth(2)
     */
    private $steps;

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
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
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

    public function getOwnerId()
    {
        return $this->getOwner()->getId();
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
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
}
