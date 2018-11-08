<?php

class AuthController extends Zend_Controller_Action

{

	...

	function loginAction()

	{

		$this->view->message = '';

 

		if ($this->_request->isPost()) {

			// collect the data from the user

			Zend_Loader::loadClass('Zend_Filter_StripTags');

			$f = new Zend_Filter_StripTags();

			$username = $f->filter($this->_request->getPost('username'));

			$password = $f->filter($this->_request->getPost('password'));

 

			if (empty($username)) {

				$this->view->message = 'Please provide a username.';

			} else {

				// setup Zend_Auth adapter for a database table

				Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');

				$dbAdapter = Zend_Registry::get('dbAdapter');

				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

				$authAdapter->setTableName('utilisateur');

				$authAdapter->setIdentityColumn('NOM');

				$authAdapter->setCredentialColumn('CODUTIL');

 

				// Set the input credential values to authenticate against

				$authAdapter->setIdentity($username);

				$authAdapter->setCredential($password);

 

				// do the authentication

				$auth = Zend_Auth::getInstance();

				$result = $auth->authenticate($authAdapter);

 

				if ($result->isValid()) {

					// success: store database row to auth's storage

					// system. (Not the password though!)

					$data = $authAdapter->getResultRowObject(null, 'password');

					$auth->getStorage()->write($data);

					die(tapk);

					$this->_redirect('/');

				} else {

					// failure: clear database row from session

					$this->view->message = 'Login failed.';

				}

			}

		}

		$this->view->title = "Log in";

		$this->render();

	}

}						