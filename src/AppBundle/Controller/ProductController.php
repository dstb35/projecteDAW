<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class ProductController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(UserInterface $user = null, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product_repo = $em->getRepository('AppBundle:Product');
        if ($user != null && $user->getRestaurantid() == $id) {
            $products = $product_repo->findBy(array('restaurantid' => $id));
        }else{
            $products = $product_repo->findBy(array('restaurantid' => $id, 'published' => 1));
        }

        $categoriesSet = $em->getRepository('AppBundle:Category')->findAll();
        $uncategorized = [];
        $categories = [];
        foreach ($products as $product){
            foreach ($categoriesSet as $category){
                if ($category == $product->getCategory()){
                    $categories[$category->getName()][] = $product;
                    unset($product);
                    break;
                }
            }
            if (isset($product)){
                $uncategorized[] = $product;
            }
        }

        if (isset($uncategorized)){
            $categories['Sin categoría'] = $uncategorized;
        }
        $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
        if (isset($restaurant)) {
            $title = 'Productos para ' . $restaurant->getName();
        } else {
            $title = 'Restaurante no encontrado para id: ' . $id;
        }

        return $this->render('products.html.twig', array(
            'title' => $title,
            'categories' => $categories,
            'id' => $id,
            'restaurant' => $restaurant
        ));

    }

    public function addAction(Request $request, UserInterface $user = null, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para crear productos');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes añadir productos de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('product_index', array('id' => $id));
        }

        $title = 'Añadir producto';
        $em = $this->getDoctrine()->getManager();
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $product = $form->getData();
            if ($user) {
                $product->setRestaurantid($user);
                if ($form->isValid()) {
                    $imageFile = $form->get('image')->getData();
                    if ($imageFile) {
                        //$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        //$newFilename = $user->getRestaurantid() . '-' . $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        $newFilename = $user->getRestaurantid() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        try {
                            $imageFile->move(
                                $this->getParameter('products_images'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $status = 'No se ha podido guardar la imagen' . $e->getMessage();
                            $this->session->getFlashBag()->add('danger', $status);
                        }
                        $product->setImage($newFilename);
                    }

                    $em->persist($product);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = 'El producto se ha creado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('product_index', array('id' => $user->getRestaurantid()));
                    } else {
                        $status = 'El producto NO se ha creado correctamente';
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                } else {
                    $this->session->getFlashBag()->add('danger', 'Form no válido');
                    unset($product);
                }
            } else {
                $status = 'El restaurante no es válido o el usuario no está identificado';
                $this->session->getFlashBag()->add('danger', $status);
            }
        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
            'productAdd' => true,
            'title' => $title
        ));
    }

    public function editAction(Request $request, UserInterface $user = null, $productid, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para editar productos');
        $title = 'Editar producto: ';
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($productid);
        $form = $this->createForm(ProductType::class, $product);

        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes editar productos de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('product_index', array('id' => $id));
        }

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($user) {
                if ($form->isValid()) {
                    $imageFile = $form->get('image')->getData();
                    if ($imageFile) {
                        //$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        //$newFilename = $user->getRestaurantid() . '-' . $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        $newFilename = $user->getRestaurantid() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        try {
                            $fs = new Filesystem();
                            $fs->remove($this->getParameter('products_images') . '/' . $product->getImage());
                            $imageFile->move(
                                $this->getParameter('products_images'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $status = 'No se ha podido renombrar la imagen' . $e->getMessage();
                            $this->session->getFlashBag()->add('danger', $status);
                        }
                        $product->setImage($newFilename);
                    }

                    $em->persist($product);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = 'El producto se ha creado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('product_index', array('id' => $id));
                    } else {
                        $status = 'El producto NO se ha creado correctamente';
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                } else {
                    $this->session->getFlashBag()->add('danger', 'Form no válido');
                    unset($product);
                }
            } else {
                $status = 'El restaurante no es válido o el usuario no está identificado';
                $this->session->getFlashBag()->add('danger', $status);
            }
        }

        return $this->render('productEdit.twig', array(
            'form' => $form->createView(),
            'title' => $title,
            'product' => $product,
            'id' => $id
        ));
    }

    public function removeAction(UserInterface $user = null, $id, $productid)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para borrar productos');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes borrar productos de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('product_index', array('id' => $id));
        }
        if (is_numeric($productid) && $productid > 0) {
            $em = $this->getDoctrine()->getManager();
            $product_repo = $em->getRepository('AppBundle:Product');
            $product = $product_repo->find($productid);

            if ($product) {
                try {
                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('products_images') . '/' . $product->getImage());
                } catch (IOException $e) {
                    $status = 'No se ha podido borrar el archivo de imagen' . $e->getMessage();
                    $this->session->getFlashBag()->add('danger', $status);
                }
                $em->remove($product);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = 'El producto se ha borrado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                } else {
                    $status = 'El producto NO se ha borrado correctamente';
                    $this->session->getFlashBag()->add('danger', $status);
                }
            } else {
                $status = 'No se ha encontrado el producto con id: ' . $productid;
                $this->session->getFlashBag()->add('danger', $status);
            }
            return $this->redirectToRoute('product_index', array('id' => $id));
        }
    }

}
