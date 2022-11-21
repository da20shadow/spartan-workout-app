<?php

namespace App\Entity;

use App\Repository\ExercisesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExercisesRepository::class)]
class Exercises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $push_ups = 0;

    #[ORM\Column(nullable: true)]
    private ?int $sit_ups = 0;

    #[ORM\Column(nullable: true)]
    private ?int $dips = 0;

    #[ORM\Column(nullable: true)]
    private ?int $squats = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $workout_time = null;

    #[ORM\Column(nullable: true)]
    private ?int $pull_ups = 0;

    #[ORM\Column(nullable: true)]
    private ?int $hammer_curl = 0;

    #[ORM\Column(nullable: true)]
    private ?int $barbel_curl = 0;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPushUps(): ?int
    {
        return $this->push_ups;
    }

    public function setPushUps(?int $push_ups): self
    {
        $this->push_ups = $push_ups;

        return $this;
    }

    public function getSitUps(): ?int
    {
        return $this->sit_ups;
    }

    public function setSitUps(?int $sit_ups): self
    {
        $this->sit_ups = $sit_ups;

        return $this;
    }

    public function getDips(): ?int
    {
        return $this->dips;
    }

    public function setDips(?int $dips): self
    {
        $this->dips = $dips;

        return $this;
    }

    public function getSquats(): ?int
    {
        return $this->squats;
    }

    public function setSquats(?int $squats): self
    {
        $this->squats = $squats;

        return $this;
    }

    public function getWorkoutTime(): ?\DateTimeInterface
    {
        return $this->workout_time;
    }

    public function setWorkoutTime(?\DateTimeInterface $workout_time): self
    {
        $this->workout_time = $workout_time;

        return $this;
    }

    public function getPullUps(): ?int
    {
        return $this->pull_ups;
    }

    public function setPullUps(?int $pull_ups): self
    {
        $this->pull_ups = $pull_ups;

        return $this;
    }

    public function getHammerCurl(): ?int
    {
        return $this->hammer_curl;
    }

    public function setHammerCurl(?int $hammer_curl): self
    {
        $this->hammer_curl = $hammer_curl;

        return $this;
    }

    public function getBarbelCurl(): ?int
    {
        return $this->barbel_curl;
    }

    public function setBarbelCurl(?int $barbel_curl): self
    {
        $this->barbel_curl = $barbel_curl;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
