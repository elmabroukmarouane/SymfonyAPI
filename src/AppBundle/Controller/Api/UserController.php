<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * @Route("/api/personnes",name="list_personnes")
     * @Method({"GET"})
     */
    public function listPersonnes()
    {
        $personnes=$this->getDoctrine()->getRepository('AppBundle:Personne')->findAll();
        if (!count($personnes)){
            $response=array(
                'code'=>1,
                'message'=>'No users found!',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 404);
        }
        $data=$this->get('jms_serializer')->serialize($personnes,'json');
        $response=array(
            'code'=>0,
            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)
        );
        return new JsonResponse($response,200);
	}
	
	    /**
	 * @Route("/api/personnes/{id}",name="show_personne")
     * @Method({"GET"})
     */
    public function showPersonne($id)
    {
        $personne=$this->getDoctrine()->getRepository('AppBundle:Personne')->find($id);
        if (empty($personne)){
            $response=array(
                'code'=>1,
                'message'=>'User not found',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 404);
        }
        $data=$this->get('jms_serializer')->serialize($personne,'json');
        $response=array(
            'code'=>0,
            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)
        );
        return new JsonResponse($response,200);
    }
	
	/**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/add-personnes",name="create_personne")
     * @Method({"POST"})
     */
    public function createPersonne(Request $request)
    {
		$data=$request->getContent();
        $post=$this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\Personne','json');

        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'User created!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
	}
	
	    /**
     * @param Request $request
     * @param $id
     * @Route("/api/update-personnes/{id}",name="update_personne")
     * @Method({"PUT"})
     * @return JsonResponse
     */
    public function updatePersonne(Request $request, $id)
    {
        $personne = $this->getDoctrine()->getRepository('AppBundle:Personne')->find($id);
        if (empty($personne))
        {
            $response = array(
                'code'=>1,
                'message'=>'User Not found !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 404);
        }
        $body = $request->getContent();
        $data = $this->get('jms_serializer')->deserialize($body, 'AppBundle\Entity\Personne','json');

        $personne->setName($data->getName());
        $personne->setBirthdate($data->getBirthdate());
		$personne->setAddress($data->getAddress());
		
        $em=$this->getDoctrine()->getManager();
        $em->persist($personne);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'User updated!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
    }
	
    /**
     * @Route("/api/delete-personnes/{id}",name="delete_personne")
     * @Method({"DELETE"})
     */
    public function deletePersonne($id)
    {
        $personne=$this->getDoctrine()->getRepository('AppBundle:Personne')->find($id);
        if (empty($personne)) {
            $response=array(
                'code'=>1,
                'message'=>'User Not found !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 404);
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($personne);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'User deleted !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
}
