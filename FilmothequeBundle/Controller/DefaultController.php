<?php
namespace DemoApp\FilmothequeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware,
Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DemoApp\FilmothequeBundle\Entity\Categorie;

//ContainerAware  plutot que controller pour mieux comprendre l'utilisation du container 
//et ne pas inclure de classes superflues.
class DefaultController extends ContainerAware 
{
    public function indexAction()
    {
		$em = $this->container->get('doctrine')->getManager();
		$categories = $em->getRepository('DemoAppFilmothequeBundle:Categorie')->findAll();
		$existing_cat = array();
		/**foreach($categories as $categorie)
			(array_key_exists($categorie->getNom(),$existing_cat))?$em->remove($categorie):$existing_cat[$categorie->getNom()]=$categorie->getNom();

		$em->flush();**/
		
		$userManager = $this->container->get('fos_user.user_manager');
		$users = $userManager->findUsers();
		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Default:index.html.twig',array(
			'categories' => $categories,'users'=>$users)
		);
    }

    public function initialiseCategories()
    {
    	$em = $this->container->get('doctrine')->getManager();
		$categorie1 = new Categorie();
		$categorie1->setNom('Comédie');
		$em->persist($categorie1);

		$categorie2 = new Categorie();
		$categorie2->setNom('Science-fiction');
		$em->persist($categorie2);

		$em->flush();

		$message = 'Catégories créées avec succès';

		return $this->container->get('templating')->renderResponse('DemoAppFilmothequeBundle:Default:index.html.twig',
		    array('message' => $message)
		);
    }
	
	
	public function choisirLangueAction($langue = null)
	{
	
		if($langue != null)
		{
			// On enregistre la langue en session
			$this->container->get('session')->set('_locale',$langue);
			//$this->container->get('request')->setLocale($langue);
		}

		// on tente de rediriger vers la page d'origine
		$url = $this->container->get('request')->headers->get('referer');
		if(empty($url)) {
			$url = $this->container->get('router')->generate('demoapp_accueil');
		}
		return new RedirectResponse($url);
	}

}
