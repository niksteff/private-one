<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser")
     */
    private $user;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateCompleted;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDeleted;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * Task constructor.
     * @param AppUser $user
     */
    public function __construct(AppUser $user)
    {
        $this->user = $user->getId();
    }

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * @param mixed $dateCompleted
     */
    public function setDateCompleted($dateCompleted): void
    {
        $this->dateCompleted = $dateCompleted;
    }

    /**
     * @return mixed
     */
    public function getDateDeleted()
    {
        return $this->dateDeleted;
    }

    /**
     * @param mixed $dateDeleted
     */
    public function setDateDeleted($dateDeleted): void
    {
        $this->dateDeleted = $dateDeleted;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
