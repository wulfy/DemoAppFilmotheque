<?php
namespace DemoApp\FilmothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DemoApp\FilmothequeBundle\Entity\Categorie;
use DemoApp\FilmothequeBundle\Form\CategorieForm;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CategorieController extends Controller
{
    public function listerAction()
    {
		$em = $this->getDoctrine()->getManager();

		$categories= $em->getRepository('DemoAppFilmothequeBundle:Categorie')->findAll();

		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Categorie:lister.html.twig', 
		array('categories' => $categories));
    }
    
	public function editerAction($id = null)
	{
	  $message='';
	  $em = $this->container->get('doctrine')->getManager();

	  if (isset($id)) 
		{
			// modification d'une categorie existante : on recherche ses données
			$categorie = $em->find('DemoAppFilmothequeBundle:Categorie', $id);

			if (!$categorie)
			{
				$message='Aucune categorie trouvée';
			}
		}else{
			$categorie = new Categorie();
		}	

	  
	  //utilisation de la fonction de la classe parent de controller pour les besoin de demo de design pattern FACTORY
	  //Le second paraletre de "create" permet de définir le type de donnees attendues dans le formulaire et gérer la validation
	  $form = $this->container->get('form.factory')->create(new CategorieForm(), $categorie);

	  $request = $this->container->get('request');

	  if ($request->getMethod() == 'POST') 
	  {
	    $form->bind($request);

	    if ($form->isValid()) 
	    {
	      $em = $this->getDoctrine()->getManager();

	     if($em->getRepository('DemoAppFilmothequeBundle:Categorie')->findOneByNom($categorie->getNom()) != null)
	      	$message='Catégorie déjà existante !';
	      else
	      {
	      	$em->persist($categorie);
	      	$em->flush();
	      	(isset($id))?$message='Catégorie modifiée avec succès !':$message='Catégorie ajoutée avec succès !';
	      }
	      
	    }
	  }

	  return $this->render('DemoAppFilmothequeBundle:Categorie:editer.html.twig',
	  array('form' => $form->createView(),'message' => $message,'id'=>$id));
	  
	}

    public function modifierAction($id)
    {
	return $this->render('DemoAppFilmothequeBundle:Categorie:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
		$em = $this->getDoctrine()->getManager();
	  	$categorie = $em->find('DemoAppFilmothequeBundle:Categorie', $id);
	        
	  	if (!$categorie) 
	  	{
	   	 throw new NotFoundHttpException("Catégorie non trouvée");
	  	}
	        
	  	$em->remove($categorie);
	  	$em->flush();        

	  	return new RedirectResponse($this->generateUrl('demoapp_categorie_lister'));
    }
}
