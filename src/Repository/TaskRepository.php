<?php


namespace App\Repository;


use App\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @return array|object[]
     */
    public function getTasksForUser(UserInterface $user)
    {
        return $this->getEntityManager()->getRepository(Task::class)->findBy(['user' => $user]);
    }

    /**
     * @param UserInterface $user
     * @param int $nrOfTasks
     * @return array
     */
    public function getTasksCompletedForUser(UserInterface $user, int $nrOfTasks = 99): array
    {
        /** @var array<Task> $tasks */
        $tasks = $this->getTasksForUser($user);

        $result = [];
        /** @var Task $task */
        foreach ($tasks as $task) {
            if (NULL !== $task->getDateCompleted()) {
                $result[] = $task;
            }
        }

        return $result;
    }

    /**
     * @param UserInterface $user
     * @param int $nrOfTasks
     * @return array
     */
    public function getTasksDeletedForUser(UserInterface $user, int $nrOfTasks = 99): array
    {
        /** @var array<Task> $tasks */
        $tasks = $this->getTasksForUser($user);

        $result = [];
        /** @var Task $task */
        foreach ($tasks as $task) {
            if (NULL !== $task->getDateDeleted()) {
                $result[] = $task;
            }
        }

        return $result;
    }
}
