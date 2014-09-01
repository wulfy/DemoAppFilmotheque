<?php
namespace DemoApp\FilmothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DemoApp\FilmothequeBundle\Entity\Film;
use DemoApp\FilmothequeBundle\Form\FilmForm;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FilmController extends Controller
{
    
	
	/*public function lastAction($max = 5)
	{
		$em = $this->container->get('doctrine')->getEntityManager();

		$qb = $em->createQueryBuilder();
		$qb->select('a')
		  ->from('DemoAppFilmothequeBundle:Film', 'a')
		  ->orderBy('f.nom', 'DESC')
		  ->setMaxResults($max);

		$query = $qb->getQuery();
		$acteurs = $query->getResult();

		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Acteur:liste.html.twig', array(
			'acteurs' => $acteurs,
		));
	}*/
	
	public function listerAction()
    {
		$em = $this->getDoctrine()->getManager();

		$films= $em->getRepository('DemoAppFilmothequeBundle:Film')->findAll();

		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Film:lister.html.twig', 
		array('films' => $films));
    }
    
	public function editerAction($idFilm = null)
	{
	  $message='';
	  $em = $this->container->get('doctrine')->getManager();

	  if (isset($idFilm)) 
		{
			// modification d'un film existant : on recherche ses données
			$film = $em->find('DemoAppFilmothequeBundle:Film', $idFilm);

			if (!$film)
			{
				$message='Aucun Film trouvé';
			}
		}else{
			$film = new film();
		}	

	  
	  //utilisation de la fonction de la classe parent de controller pour les besoin de demo de design pattern FACTORY
	  //Le second paraletre de "create" permet de définir le type de donnees attendues dans le formulaire et gérer la validation
	  $form = $this->container->get('form.factory')->create(new FilmForm(), $film);

	  $request = $this->container->get('request');

	  if ($request->getMethod() == 'POST') 
	  {
	    $form->bind($request);

	    if ($form->isValid()) 
	    {
	      $em = $this->getDoctrine()->getManager();

	     if($em->getRepository('DemoAppFilmothequeBundle:Film')->findOneByTitre($film->getTitre()) != null)
	      	$message='Film déjà existant !';
	      else
	      {
	      	$em->persist($film);
	      	$em->flush();
	      	(isset($idFilm))?$message='Film modifié avec succès !':$message='Film ajouté avec succès !';
	      }
	      
	    }
	  }

	  return $this->render('DemoAppFilmothequeBundle:Film:editer.html.twig',
	  array('form' => $form->createView(),'message' => $message,'id'=>$idFilm));
	  
	}

    public function modifierAction($idFilm)
    {
	return $this->render('DemoAppFilmothequeBundle:Film:modifier.html.twig');
    }

    public function supprimerAction($idFilm)
    {
		$em = $this->getDoctrine()->getManager();
	  	$films = $em->find('DemoAppFilmothequeBundle:Film', $idFilm);
	        
	  	if (!$films) 
	  	{
	   	 throw new NotFoundHttpException("Film non trouvé");
	  	}
	        
	  	$em->remove($films);
	  	$em->flush();        

	  	return new RedirectResponse($this->generateUrl('demoapp_film_lister'));
    }
}
