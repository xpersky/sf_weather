<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Requests;
use App\Entity\Sources;

use App\Form\RequestType;

use App\Service\ApiExecutor;

class HomepageController extends AbstractController
{

    /**
     * @Route("/homepage", name="homepage")
     */
    public function index(Request $request,HttpClientInterface $client, ApiExecutor $executor): Response
    {
        $sources = $this->getDoctrine()->getRepository(Sources::class)->findAllSources();
        $cords = $this->getDoctrine()->getRepository(Sources::class)->findCords()[0];

        if ( count($sources) < 2 ) {
            return $this->redirectToRoute('sources');
        }

        $apiRequest = new Requests();
        $apiRequest->setTimecreated(new \DateTime());

        $form = $this->createForm(RequestType::class,$apiRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $apiRequest = $form->getData();

            $response = $executor->execute(
                $apiRequest->getCountry(),
                $apiRequest->getCity(),
                $sources,
                $client,
                $cords
            );

            $response = round($response,2);

            $apiRequest->setTemp($response);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($apiRequest);
            $entityManager->flush();

            return $this->render('homepage/result.html.twig', [
                'result' => $response,
            ]);
        }

        return $this->render('homepage/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logs", name="logs")
     */
    public function logs(): Response
    {
        $logs = $this->getDoctrine()->getRepository(Requests::class)->findAll();
        
        $logsView = [];
        foreach ( $logs as $key => $log ) {
            $logsView[] = (object)[
                'lp' => $key + 1,
                'city' => $log->getCity(),
                'country' => $log->getCountry(),
                'time' => $log->getTimecreated(),
                'temp' => $log->getTemp()
            ];
        }

        return $this->render('homepage/logs.html.twig', [
            'logs' => $logsView,
        ]);
    }
}
