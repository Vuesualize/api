<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 *
 * @ORM\Entity
 */
class Transition
{
    /**
     * @var int
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
     * @Groups({"write"})
     *
     */
    private $fromStep;

    /**
     * @var Step
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Step")
     *
     * @Groups({"write"})
     *
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

    public function getToStep(): Step
    {
        return $this->toStep;
    }

    public function setToStep(Step $toStep): void
    {
        $this->toStep = $toStep;
    }

    /**
     * @return string[]
     *
     * @Groups({"read"})
     */
    public function getBetweenSteps(): array
    {
        return [
            '/steps/'.$this->fromStep->getId(),
            '/steps/'.$this->toStep->getId(),
        ];
    }
}
