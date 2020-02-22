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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return RedirectResponse
     * @throws Exception
     */
    public function indexAction(Request $request): Response
    {
        try {
            $user = $this->getUser();

            $existingTask = $this->taskService->checkUserForValidTask($user);
            if (NULL !== $existingTask) {
                return $this->redirectToRoute('page_taskViewer');
            }

            return $this->redirectToRoute('page_taskCreator');
        } catch (Exception $exception) {
            $this->logger->error('Exception while deciding for user redirection on start page.' . $exception->getMessage());
            throw $exception;
        }
    }

    /**
     * @Route("/task/viewer", name="page_taskViewer")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function viewTaskAction(Request $request)
    {
        try {
            $user = $this->getUser();

            $existingTask = $this->taskService->checkUserForValidTask($user);
            if ($existingTask !== NULL) {
                return $this->render('task/task_viewer.html.twig', [
                    'activeTask' => $existingTask,
                    'taskEndDate' => $this->taskService->computeTaskEndDate($existingTask),
                    'completedTasks' => $this->taskService->getUserCompletedTasks($user),
                    'deletedTasks' => $this->taskService->getUserDeletedTasks($user),
                ]);
            }

            // Throw a logic exception. There is no task existing but we are here nonetheless
            $this->logger->error('Non logic behaviour found in ' . __METHOD__ . ' by assuming a task is existing for the given user but it is not.');
            return $this->redirectToRoute('page_taskCreator');
        } catch (Exception $exception) {
            $this->logger->error('Exception while trying to display task viewer. ' . $exception->getMessage());
            throw $exception;
        }
    }

    /**
     * @Route("/task/creator", name="page_taskCreator")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function createTaskAction(Request $request)
    {
        try {
            $user = $this->getUser();

            // Prepare the form
            /** @var AppUser $user */
            $appTask = new Task($user);
            $form = $this->createForm(AppTaskType::class, $appTask);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $newTask = $form->getData();

                $this->em->persist($newTask);
                $this->em->flush();

                return $this->redirectToRoute('page_taskViewer');
            }

            /** @var Task $newTask */
            return $this->render('task/task_creator.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error creating form: ' . $e->getMessage());
            $this->logger->error($e->getTraceAsString());
            throw $e;
        }
    }
}
