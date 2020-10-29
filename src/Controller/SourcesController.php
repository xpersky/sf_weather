<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Sources;
use App\Form\SourceType;

use App\Form\CordType;

use App\Repository\SourcesRepository;

class SourcesController extends AbstractController
{
    /**
     * @Route("/sources", name="sources")
     */
    public function index(): Response
    {
        $sources = $this->getDoctrine()->getRepository(Sources::Class)->findAllSources();
        
        $sourceView = [];
        foreach ( $sources as $key => $source ) {
            $sourceView[] = (object)[
                'lp' => $key + 1,
                'name' => $source->getName(),
                'secret' => $source->getApikey(),
                'edit' => $this->generateUrl('sources_edit',['id'=>$source->getId()]),
                'delete' => $this->generateUrl('sources_delete',['id'=>$source->getId()])
            ];
        }

        $shouldSeeAlert = count($sourceView) < 2;

        return $this->render('sources/index.html.twig', [
            'sources' => $sourceView,
            'alert' => $shouldSeeAlert,
        ]);
    }

    /**
     * @Route("/sources/edit/{id}", name="sources_edit")
     */
    public function add(Request $request): Response
    {
        $id = $request->get('id');
        $source = $this->getDoctrine()->getRepository(Sources::class)->find($id);

        if ( !$source ) {
            $source = new Sources();
            $source->setfield('temp');
        }

        $form = $this->createForm(SourceType::class,$source);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $source = $form->getData();
            $source->setIscord(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($source);
            $entityManager->flush();

            return $this->redirectToRoute('sources');
        }

        return $this->render('sources/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sources/editcord", name="sources_editcord")
     */
    public function editcord(Request $request): Response
    {
        $source = $this->getDoctrine()->getRepository(Sources::class)->findCords()[0];

        if ( !$source ) {
            $source = new Sources();
            $source->setfield('cords');
        }

        $form = $this->createForm(CordType::class,$source);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $source = $form->getData();
            $source->setName('coordinates');
            $source->setUrl('http://api.positionstack.com/v1/forward?access_key={apikey}&query={city},{country}');
            $source->setOptions('');
            $source->setIscord(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($source);
            $entityManager->flush();

            return $this->redirectToRoute('sources');
        }

        return $this->render('sources/addcord.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sources/delete/{id}", name="sources_delete")
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $source = $this->getDoctrine()->getRepository(Sources::class)->find($id);

        if ( !$source ) {
            throw $this->createNotFoundException('The source does not exist');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($source);
        $em->flush();
    
        return $this->redirectToRoute('sources');
    }
}
