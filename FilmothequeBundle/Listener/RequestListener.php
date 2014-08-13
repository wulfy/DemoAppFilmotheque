<?php
namespace DemoApp\FilmothequeBundle\Listener;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
 
class RequestListener 
{
	protected $request;
    protected $response;
    protected $allowLocale;
    protected $locale;
    protected $cookie = null;
	
	public function onKernelRequest(GetResponseEvent $event)
    {
		//get datas
        $this->request = $event->getRequest();
		$session = $this->request->getSession();
		
		if($session->has('_locale'))
			$this->request->setLocale($session->get('_locale'));
	}
}
