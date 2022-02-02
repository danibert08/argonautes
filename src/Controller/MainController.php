<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use App\Repository\ArgonauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ArgonauteRepository $argoRepo): Response
    {
        $argonautes = $argoRepo->findAll();
        $argonaute = new Argonaute();
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($argonaute);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }

        return $this->renderForm('main/index.html.twig', [
            'argonaute' => $argonaute,
            'argonautes' => $argonautes,
            'form' => $form,
        ]);
    }

     /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete( Request $request, Argonaute $argonaute, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$argonaute->getId(), $request->request->get('_token'))) {
            $entityManager->remove($argonaute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main');
    }

     /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Argonaute $argonaute, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }

        return $this->renderForm('main/edit.html.twig', [
            'argonaute' => $argonaute,
            'form' => $form,
        ]);
    }


}
