<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator {

	private $formFactory;
	private $em;
	private $router;
	private $passwordEncoder;
	
	public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder) {
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->router = $router;
		$this->passwordEncoder = $passwordEncoder;
	}
	
	public function getCredentials(Request $request) {
		$isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
		if(!$isLoginSubmit) {
			//skip authentication
			return;
		}
		
		$form = $this->formFactory->create(LoginForm::class);
		$form->handleRequest($request);
		
		$data = $form->getData();
		try {
		$request->getSession()->set(
			Security::LAST_USERNAME,
			$data['_username']
		);
		}
		catch (\Exception $e) {
			error_log($e->getMessage());
			$this->addFlash('notice', 'Something went wrong!');
		}
		
		return $data;
	}
	
	public function getUser($credentials, UserProviderInterface $userProvider) {
		$username = $credentials['_username'];
		
		return $this->em->getRepository('AppBundle:User')
			->findOneBy(['email' => $username]);
	}
	

	public function checkCredentials($credentials, UserInterface $user) {
		$password = $credentials['_password'];
		
		if ($this->passwordEncoder->isPasswordValid($user, $password)) {
			return true;
		}
		
		return false;
	}
	
	protected function getLoginUrl() {
		return $this->router->generate('security_login');
	}
	
	/*
	 * Login success redireect
	 * To-Do: REdirect to last page
	 */
	protected  function getDefaultSuccessRedirectUrl() {
		return $this->router->generate('homepage');
	}
}