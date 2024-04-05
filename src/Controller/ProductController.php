<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
        public function new(Request $request, ProductRepository $productRepository , ImageManager $imageManager): Response
        {
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //ici debut code image
                $imageManager->EnregistreImage($form, 'image' , $product , 'defaultImage.jpg');
                //fin code image

                $productRepository->save($product, true);

                return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('product/new.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
        }

        #[Route('/voir-product/{id}', name: 'app_product_show')]
        public function show(Product $product , Request $request , PictursRepository $pictursRepository , ImageManager $imageManager): Response
        {

            //ici code ajout image
            $pictur = new Picturs();
            $form = $this->createForm(PictursType::class, $pictur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //code image
                $imageManager->EnregistreImage($form, 'name' , $pictur , 'defaultImage.jpg');
                //fin image
                $pictur -> setProduct($product);
                $pictursRepository->save($pictur, true);

                return $this->redirectToRoute('app_product_show', ['id' => $product -> getId()], Response::HTTP_SEE_OTHER);
            }
            //ici fin code ajout image

            return $this->render('product/show.html.twig', [
                'product' => $product,
                'form' => $form -> createView()
            ]);
        }




        #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Product $product, ProductRepository $productRepository , ImageManager $imageManager): Response
        {
            $old_name_image = $product->getImage();
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //ici debut code image
                $imageManager->EnregistreImage($form, 'image' , $product , $old_name_image);
                //fin code image

                $productRepository->save($product, true);

                return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('product/edit.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
        }

        #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
        public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
        {
            if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
                $productRepository->remove($product, true);
            }

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }
}

