<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
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
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCompleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * Task constructor.
     * @param AppUser $user
     * @throws Exception
     */
    public function __construct(AppUser $user)
    {
        $this->setUser($user);
        $this->setDateCreated(new DateTime('now', new DateTimeZone('utc')));
    }

    /**
     * @return AppUser
     */
    public function getUser(): AppUser
    {
        return $this->user;
    }

    /**
     * @param AppUser $user
     */
    public function setUser(AppUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated(): DateTime
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
     * @return DateTime|null
     */
    public function getDateCompleted(): ?DateTime
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
     * @return DateTime|null
     */
    public function getDateDeleted(): ?DateTime
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
}
