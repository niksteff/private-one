<?php


namespace App\Service;


use App\Entity\Task;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Sodium\add;

class TaskService
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;

    /**
     * TaskService constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->em = $entityManager;
    }

    /**
     * Checks if a task for the specified user exists and if the task is in the valid date range.
     * If no task exists or no task is valid true will be returned.
     *
     * @param UserInterface $user
     * @return bool
     */
    public function checkUserForValidTask(UserInterface $user): bool
    {
        /** @var array<Task> $userTasks */
        $userTasks = $this->em->getRepository(Task::class)->findBy(['user' => $user]);

        if (count($userTasks) > 0) {
            /** @var Task $task */
            foreach ($userTasks as $task) {
                if ($task->getDateCompleted() !== NULL || $task->getDateDeleted() !== NULL) {
                    // Break as soon as we see the task is already inactive
                    continue;
                }

                if ($this->isTaskActive($task->getDateCreated(), $task)) {
                    dump('There is a still active task.');
                    dump($task);
                    return true;
                }
            }
        }

        dump('There is NO other task.');
        return false;
    }

    /**
     * Computes whether a task is still active. A task is active if its creation date is not older than x days.
     * Where x days is a specified amount of days and defaults to 30.
     *
     * @param DateTime $taskStartDate
     * @param int $dateSpan
     * @param Task $task
     * @return bool
     * @throws Exception
     */
    private
    function isTaskActive(DateTime $taskStartDate, Task &$task, int $dateSpan = 30)
    {
        try {
            // TODO: Get the startDate plus the dateSpan
            // TODO: Fix this.
            $taskEndDate = $taskStartDate->modify('+' . $dateSpan . 'day');

            // TODO: Check if the current date is bigger than the endDate. If so task is invalid.
            $now = new DateTime('now', new DateTimeZone('utc'));

            return false;
        } catch (Exception $e) {
            // TODO: Proper handling
            throw $e;
        }
    }
}
