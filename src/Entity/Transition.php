<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ApiResource()
 *
 * @ORM\Entity
 */
class Transition
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
     * @var Step
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Step")
     *
     * @Groups("admin")
     * @MaxDepth(1)
     */
    private $fromStep;

    /**
     * @var Step
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Step")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups("admin")
     */
    private $toStep;

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
     * @return Step
     */
    public function getFromStep(): Step
    {
        return $this->fromStep;
    }

    /**
     * @param Step $fromStep
     */
    public function setFromStep(Step $fromStep): void
    {
        $this->fromStep = $fromStep;
    }

    /**
     * @return Step
     */
    public function getToStep(): Step
    {
        return $this->toStep;
    }

    /**
     * @param Step $toStep
     */
    public function setToStep(Step $toStep): void
    {
        $this->toStep = $toStep;
    }

    /**
     * @Groups({"read"})
     */
    public function getFromStepId(): int
    {
        return $this->fromStep->getId();
    }

    /**
     * @Groups({"read"})
     */
    public function getToStepId(): int
    {
        return $this->toStep->getId();
    }
}
