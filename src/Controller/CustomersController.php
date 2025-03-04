<?php

namespace App\Controller;

use App\Entity\CustomerEntity;
use App\Form\CustomerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CustomersController extends AbstractController
{
    #[Route('/customers', name: 'customers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_customers');

        $customers = $entityManager->getRepository(CustomerEntity::class)->findAll();

        return $this->render('customers.html.twig', [
            'customers' => $customers
        ]);
    }

    #[Route('/customers/add', name: 'add_customer')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_customers');

        $customer = new CustomerEntity();
        $form = $this->createForm(CustomerFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();
            $this->addFlash('success', 'Client ajouté avec succès!');
            return $this->redirectToRoute('customers');
        }

        return $this->render('add_customer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/customers/edit/{id}', name: 'edit_customer')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_customers');

        $customer = $entityManager->getRepository(CustomerEntity::class)->find($id);

        if (!$customer) {
            $this->addFlash('error', 'Client non trouvé!');
            return $this->redirectToRoute('customers');
        }

        $form = $this->createForm(CustomerFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client modifié avec succès!');
            return $this->redirectToRoute('customers');
        }

        return $this->render('edit_customer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/customers/delete/{id}', name: 'delete_customer')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('view_customers');
        
        $customer = $entityManager->getRepository(CustomerEntity::class)->find($id);

        if ($customer) {
            $entityManager->remove($customer);
            $entityManager->flush();
            $this->addFlash('success', 'Client supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Client non trouvé!');
        }

        return $this->redirectToRoute('customers');
    }
}
