<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {
	public function testRegisterAction() {
		$client = static::createClient();
		
		$crawler = $client->request('GET', '/register');
		
		$form = $crawler->selectButton('Register')->form();
		
		$form['user_registration_form[name]'] = 'Test User';
		$form['user_registration_form[email]'] = 'test@example.com';
		$form['user_registration_form[plainPassword][first]'] = 'abcde';
		$form['user_registration_form[plainPassword][second]'] = 'abcde';
		$form['user_registration_form[user_organisation]']->select(1);
		
		$crawler = $client->submit($form);
		
	}
	
// 	public function testHomePage() {
// 		$client = static::createClient();
		
// 		$crawler = $client->request('GET', '/');
// 	}
}