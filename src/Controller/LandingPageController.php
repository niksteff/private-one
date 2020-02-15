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

            // Check if a task for the user is currently existing
            if ($this->taskService->checkUserForValidTask($user)) {
                dump('task exists');
                die();
            }
            // TODO: If a task exists and the task is still valid (in the timeframe) the user will not see the form

            // TODO: If no task exists we can move on


            /** @var AppUser $user */
            $appTask = new Task($user);
            $form = $this->createForm(AppTaskType::class, $appTask);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Task $appTask */
                dump($existingTasksForUser);
                if ($existingTasksForUser === NULL) {
                    $this->em->persist($appTask);
                    $this->em->flush();
                }
            }

            return $this->render('main.html.twig', [
                'form' => $form->createView(),
                'existingTask' => $existingTasksForUser
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error creating form: ' . $e->getMessage());
            $this->logger->error($e->getTraceAsString());
            throw $e;
        }

        return $this->render('main.html.twig');
    }
}
