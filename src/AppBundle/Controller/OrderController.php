<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;
use AppBundle\Entity\Orderline;
use AppBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class OrderController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(UserInterface $user = null, $id)
    {
        $title = 'Listado de pedidos para restaurante ' . $id;
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para ver pedidos');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes ver pedidos de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository("AppBundle:Order")->findBy(array('restaurantid' => $id));

        return $this->render("orders.html.twig", array(
            'orders' => $orders,
            'title' => $title,
            'id' => $id
        ));
    }

    public function addAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXMLHttpRequest()) {
            $orderlines_set = $request->request->get('orderlines');
            $tableid = $request->request->get('tableid');
            $table = $em->getRepository("AppBundle:Table")->find($tableid);
            $restaurant = $em->getRepository("AppBundle:Restaurant")->find($id);
            $order = new Order();
            $order->setRestaurantid($restaurant);
            $order->setTableid($table);
            $order->setCreated(new \DateTime());
            $em->persist($order);
            $em->flush();
            $orderid = $order->getOrderid();
            $total = 0;
            foreach ($orderlines_set as $orderline_set) {
                $price = $orderline_set['price'];
                $quantity = $orderline_set['quantity'];
                $productid = $orderline_set['productid'];
                $product = $em->getRepository('AppBundle:Product')->find($productid);
                $orderline = new Orderline();
                $orderline->setProductid($product);
                $orderline->setQuantity($quantity);
                $orderline->setSubtotal($quantity * $price);
                $orderline->setOrderid($order);
                $em->persist($orderline);
                $total += $quantity * $price;
            }
            $order->setTotal($total);
            $em->persist($order);
            $flush = $em->flush();
            if ($flush == null) {
                $data['orderid'] = $orderid;
                return new JsonResponse(array('data' => $data));
            } else {
                return new Response('El pedido no se ha creado correctamente', 400);
            }
        } else {
            return new Response('This is not ajax!', 400);
        }
    }

    public function payAction(UserInterface $user, $id, $orderid)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para ver pedidos');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes ver pedidos de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:Order')->find($orderid);
        $order->setPaid(true);
        $em->persist($order);
        $flush = $em->flush();
        if ($flush == null) {
            $status = "Pedido cobrado";
            $this->session->getFlashBag()->add("success", $status);
        } else {
            $status = "Pedido NO cobrado";
            $this->session->getFlashBag()->add("danger", $status);
        }
        return $this->redirect($this->generateUrl('order_index', array('id' => $id)));
    }

    public function assignAction(Request $request, UserInterface $user, $id, $orderid, $employeeid)
    {
        if ($request->isXMLHttpRequest()) {
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para asignar pedidos');
            if ($user->getRestaurantid() != $id) {
                $status = 'No puedes asignar pedidos de este restaurante';
                $this->session->getFlashBag()->add('danger', $status);
                return $this->redirectToRoute('homepage');
            }
            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository('AppBundle:Order')->find($orderid);
            $employee = $em->getRepository('AppBundle:Employee')->find($employeeid);
            $order->setEmployeeid($employee);
            $em->persist($order);
            $flush = $em->flush();
            if ($flush == null) {
                $assigned['orderid'] = $orderid;
                $assigned['employee'] = $employee->getName();
                 return new JsonResponse(array('assigned' => $assigned));
            } else {
                return new Response('El pedido no se ha creado correctamente', 400);
            }
        } else {
            return new Response('This is not ajax!', 400);
        }
    }

    public function removeAction($id)
    {
        if (is_numeric($id) && $id > 0) {
            $em = $this->getDoctrine()->getManager();
            $order_repo = $em->getRepository("AppBundle:Order");
            $order = $order_repo->find($id);

            if ($order) {
                $em->remove($order);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "El pedido se ha borrado correctamente";
                    $this->session->getFlashBag()->add("success", $status);
                } else {
                    $status = "El pedido NO se ha borrado correctamente";
                    $this->session->getFlashBag()->add("danger", $status);
                }
            } else {
                $status = "No se ha encontrado el pedido con id: " . $id;
                $this->session->getFlashBag()->add("danger", $status);
            }
            return $this->redirect($this->generateUrl('order_index', array('id' => $id)));
        }
    }

    public function orderAction(UserInterface  $user= null, $orderid){

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para ver pedidos');
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository("AppBundle:Order")->find($orderid);
        if (isset($order)){
            if ($user->getRestaurantid() != $order->getRestaurantid()->getRestaurantid()) {
                $status = 'No puedes ver pedidos de este restaurante';
                $this->session->getFlashBag()->add('danger', $status);
                return $this->redirectToRoute('homepage');
            }
            $title = 'Pedido número ' . $order->getOrderid();
        }else{
            $title = 'Pedido no encontrado con id: ' . $orderid;
        }
        return $this->render("order.html.twig", array(
            'order' => $order,
            'title' => $title,
            'id' => $order->getRestaurantid()->getRestaurantid()
        ));
    }
}
