<?php


namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LandingPageController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="page_landing")
     * @return Response
     */
    public function landingPage()
    {
//        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
//            return $this->redirectToRoute('page_login');
//        }

        return $this->render('main.html.twig');
    }
}
