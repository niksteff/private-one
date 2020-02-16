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
     * @return Task|NULL
     * @throws Exception
     */
    public function checkUserForValidTask(UserInterface $user): ?Task
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

                if ($this->isTaskActive($task)) {
                    return $task;
                }

                // Check again because maybe the task was completed just now
                if (NULL !== $task->getDateCompleted()) {
                    $this->em->persist($task);
                }
            }

            $this->em->flush();
        }

        return NULL;
    }

    /**
     * Computes the endDate for a task by adding the given or default daySpan onto the task.
     *
     * @param Task $task
     * @param int $daySpan
     * @return DateTime
     * @throws Exception
     */
    public function computeTaskEndDate(Task $task, int $daySpan = 30): DateTime
    {
        try {
            // Get the startDate from the task object
            $startDate = $task->getDateCreated();

            // Because ->add modifies the object it is called up we need to first clone the object
            $endDate = clone $startDate;
            $endDate->add(new DateInterval('P' . $daySpan . 'D'));

            return $endDate;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Computes whether a task is still active. A task is active if its creation date is not older than x days.
     * Where x days is a specified amount of days and defaults to 30.
     *
     * @param Task $task
     * @param int $daySpan
     * @return bool
     * @throws Exception
     */
    private
    function isTaskActive(Task &$task, int $daySpan = 30)
    {
        try {
            // Get the startDate from the task object
            $startDate = $task->getDateCreated();

            // Because ->add modifies the object it is called up we need to first clone the object
            $endDate = $this->computeTaskEndDate($task);

            $now = new DateTime('now', new DateTimeZone('utc'));

            // endDate > currentDate = still valid task
            if ($endDate > $now) {
                return true;
            }

            // TODO: Set task as invalid
            $task->setDateCompleted($endDate);

            return false;
        } catch (Exception $e) {
            // TODO: Proper handling
            throw $e;
        }
    }
}
