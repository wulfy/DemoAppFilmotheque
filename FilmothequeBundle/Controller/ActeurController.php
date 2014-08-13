<?php
namespace DemoApp\FilmothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DemoApp\FilmothequeBundle\Entity\Acteur;
use DemoApp\FilmothequeBundle\Form\ActeurForm;
use DemoApp\FilmothequeBundle\Form\ActeurRechercheForm;

class ActeurController extends Controller
{

	public function topAction($max = 5)
	{
		$em = $this->getDoctrine()->getManager();

		$qb = $em->createQueryBuilder();
		$qb->select('a')
		  ->from('DemoAppFilmothequeBundle:Acteur', 'a')
		  ->orderBy('a.dateNaissance', 'DESC')
		  ->setMaxResults($max);

		$query = $qb->getQuery();
		$acteurs = $query->getResult();

		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Acteur:liste.html.twig', array(
			'acteurs' => $acteurs,
		));
	}

    public function listerAction()
    {
		$em = $this->getDoctrine()->getManager();

		$acteurs= $em->getRepository('DemoAppFilmothequeBundle:Acteur')->findAll();

		$form = $this->container->get('form.factory')->create(new ActeurRechercheForm($this->container->get('translator')));
		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Acteur:lister.html.twig', array(
			'acteurs' => $acteurs,
			'rechercheform' => $form->createView()
		));

    }
    
	public function editerAction($id = null)
	{
	  $message='';
	  $em = $this->container->get('doctrine')->getManager();

	  if (isset($id)) 
		{
			// modification d'un acteur existant : on recherche ses données
			$acteur = $em->find('DemoAppFilmothequeBundle:Acteur', $id);

		if (!$acteur)
		{
			$message='Aucun acteur trouvé';
		}
		}else{
			$acteur = new Acteur();
		}	

	  
	  //utilisation de la fonction de la classe parent de controller pour les besoin de demo de design pattern FACTORY
	  //Le second paraletre de "create" permet de définir le type de donnees attendues dans le formulaire et gérer la validation
	  $form = $this->container->get('form.factory')->create(new ActeurForm(), $acteur);

	  $request = $this->container->get('request');

	  if ($request->getMethod() == 'POST') 
	  {
	    $form->bind($request);

	    if ($form->isValid()) 
	    {
	      $em = $this->getDoctrine()->getManager();

	     if($em->getRepository('DemoAppFilmothequeBundle:Acteur')->findOneBy(array("nom" => $acteur->getNom(), 'prenom' => $acteur->getPrenom())) != null)
	      	$message='Acteur déjà existant !';
	      else
	      {
	      	$em->persist($acteur);
	      	$em->flush();
			$modifier_message = $this->get('translator')->trans('acteur.modifier.succes');
			$ajouter_message = $this->get('translator')->trans('acteur.ajouter.succes',array(
					'%nom%' => $acteur->getNom(),
					'%prenom%' => $acteur->getPrenom()
				));
			
	      	(isset($id))?$message = $modifier_message :$message = $ajouter_message;
	      }
	      
	    }
	  }

	  return $this->render('DemoAppFilmothequeBundle:Acteur:editer.html.twig',
	  array('form' => $form->createView(),'message' => $message,'id'=>$id));
	  
	}

	public function rechercherAction()
	{               
		$request = $this->container->get('request');

		if($request->isXmlHttpRequest())
		{
			$motcle = '';
			$motcle = $request->request->get('motcle');

			$em = $this->container->get('doctrine')->getEntityManager();

			if($motcle != '')
			{
				   $qb = $em->createQueryBuilder();

				   $qb->select('a')
					  ->from('DemoAppFilmothequeBundle:Acteur', 'a')
					  ->where("a.nom LIKE :motcle OR a.prenom LIKE :motcle")
					  ->orderBy('a.nom', 'ASC')
					  ->setParameter('motcle', '%'.$motcle.'%');

				   $query = $qb->getQuery();               
				   $acteurs = $query->getResult();
			}
			else {
				$acteurs = $em->getRepository('DemoAppFilmothequeBundle:Acteur')->findAll();
			}

			return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Acteur:liste.html.twig', array(
				'acteurs' => $acteurs
				));
		}
		else {
			return $this->listerAction();
		}
	}

    public function modifierAction($id)
    {
	return $this->render('DemoAppFilmothequeBundle:Acteur:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
		$em = $this->getDoctrine()->getManager();
	  	$acteur = $em->find('DemoAppFilmothequeBundle:Acteur', $id);
	        
	  	if (!$acteur) 
	  	{
	   	 throw new NotFoundHttpException("Acteur non trouvé");
	  	}
	        
	  	$em->remove($acteur);
	  	$em->flush();        

	  	return new RedirectResponse($this->generateUrl('demoapp_acteur_lister'));
    }
}
