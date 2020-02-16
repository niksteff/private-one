<?php


namespace App\Controller;


use App\Entity\AppUser;
use App\Entity\Task;
use App\Form\AppTaskType;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $em;
    private TaskService $taskService;

    /**
     * LandingPageController constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param TaskService $taskService
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, TaskService $taskService)
    {
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="page_landing")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        try {
            $user = $this->getUser();

            // Prepare the form
            /** @var AppUser $user */
            $appTask = new Task($user);
            $form = $this->createForm(AppTaskType::class, $appTask);

            // Check if a task for the user is currently existing, if so return the task
            $existingTask = $this->taskService->checkUserForValidTask($user);
            if (NULL !== $existingTask) {
                $form->setData($existingTask);
                // TODO: task exists so we need to display the task edit option
                return $this->render('main.html.twig', [
                    'taskTitle' => $existingTask->getTitle(),
                    'taskStartDate' => $existingTask->getDateCreated()->format('yyyy-mm-dd'),
                    'taskEndDate' => $this->taskService->computeTaskEndDate($existingTask)->format('yyyy-mm-dd'),
                ]);
            }

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // TODO: persist the task
                $newTask = $form->getData();

                $this->em->persist($newTask);
                $this->em->flush();

                /** @var Task $newTask */
                return $this->render('main.html.twig', [
                    'taskTitle' => $newTask->getTitle(),
                    'taskStartDate' => $newTask->getDateCreated()->format('yyyy-mm-dd'),
                    'taskEndDate' => $this->taskService->computeTaskEndDate($newTask)->format('yyyy-mm-dd'),
                ]);
            }

            /** @var Task $newTask */
            return $this->render('main.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error creating form: ' . $e->getMessage());
            $this->logger->error($e->getTraceAsString());
            throw $e;
        }
    }
}
