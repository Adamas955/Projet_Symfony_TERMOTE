<?php

namespace App\Controller;

use App\Entity\ProductEntity;
use App\Form\ProductsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(ProductEntity::class)->findAll();

        return $this->render('products.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/products/edit/{id}', name: 'edit_product')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('edit_product');

        $product = $entityManager->getRepository(ProductEntity::class)->find($id);

        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé!');
            return $this->redirectToRoute('products');
        }

        $form = $this->createForm(ProductsFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Produit modifié avec succès!');
            return $this->redirectToRoute('products');
        }

        return $this->render('edit_product.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    #[Route('/products/delete/{id}', name: 'delete_product')]
    public function delete(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        //$this->denyAccessUnlessGranted('delete_product');

        $product = $entityManager->getRepository(ProductEntity::class)->find($id);

        if ($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Produit non trouvé!');
        }

        return $this->redirectToRoute('products');
    }

    #[Route('/products/add', name: 'add_product')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('add_product');

        $product = new ProductEntity();
        $form = $this->createForm(ProductsFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit ajouté avec succès!');
            return $this->redirectToRoute('products');
        }

        return $this->render('add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/products/sort/desc', name: 'products_sorted_desc')]
public function sortByPriceDesc(EntityManagerInterface $entityManager): Response
{
    //$this->denyAccessUnlessGranted('add_product');

    $products = $entityManager->getRepository(ProductEntity::class)->findBy([], ['price' => 'DESC']);

    return $this->render('products.html.twig', [
        'products' => $products,
        'sorted' => true 
    ]);
}

}
