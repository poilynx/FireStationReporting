<?php
final class UsersController extends ControllerBase {
	protected function onBefore($action = '') {
		parent::checkIfAdmin();
	}

	private function getListOfUsernames(User $exclude = NULL) {
		$list = array();
		
		$users = User::find('*');
		
		if(is_array($users)) {
			foreach($users as $user) {
				if($exclude == NULL || $exclude->uid != $user->uid) {
					$list[] = $user->username;	
				}
			}
		} else if($users != NULL && ($exclude == NULL || $exclude->uid != $users->uid)) {
			$list[] = $users->username;	
		}
		
		return $list;
	}
	
	private function getListOfMailAdresses(User $exclude = NULL) {
		$list = array();
		
		$users = User::find('*');
		
		if(is_array($users)) {
			foreach($users as $user) {
				if($exclude == NULL || $exclude->uid != $user->uid) {
					$list[] = $user->email;	
				}
			}
		} else if($users != NULL && ($exclude == NULL || $exclude->uid != $users->uid)) {
			$list[] = $users->email;	
		}
		
		return $list;
	}	
	
	public function index() {
		$users = User::find();
		
		if(!is_array($users)) {
			$users = array($users);	
		}
		
		$smarty = new Template();
		$smarty->assign('title', System::getLanguage()->_('Users'));
		$smarty->assign('heading', System::getLanguage()->_('Users'));
		
		$smarty->assign('users', $users);
		
		$smarty->display('users/index.tpl');
	}
	
	public function add() {
		$user = new User();
		
		$form	= new Form('form-user', Router::getInstance()->build('UsersController', 'add'));
		$form->binding = $user;
		
		$fieldset	= new Fieldset(System::getLanguage()->_('General'));
		
		$username = new Text('username', System::getLanguage()->_('Username'), true);
		$username->binding = new Databinding('username');
		$username->blacklist = $this->getListOfUsernames();
		$username->error_msg[4] = System::getLanguage()->_('ErrorUsernameAlreayExists');
		$username->minlength = USERNAME_MIN_LENGTH;
		
		$nickname = new Text('nickname', System::getLanguage()->_('Nickname'));
		$nickname->binding = new Databinding('nickname');
		
		$email = new Text('email', System::getLanguage()->_('EMail'), true);
		$email->binding = new Databinding('email');
		$email->blacklist = $this->getListOfMailAdresses();
		$email->error_msg[4] = System::getLanguage()->_('ErrorMailAdressAlreadyExists');
		
		$language = new Radiobox('lang', System::getLanguage()->_('Language'), L10N::getLanguages(), LANGUAGE);
		$language->binding = new Databinding('lang');

		$usertype = new Select('usertype', 
			System::getLanguage()->_('UserType'), 
			array(
				'0' => System::getLanguage()->_('Administrator'),
				'1' => System::getLanguage()->_('Station'), 
				'2' => System::getLanguage()->_('SmallStation'), 
			)
		);
		$usertype->binding = new Databinding('type');

		
		$fieldset->addElements($username, $nickname, $email, $usertype, $language);
		$form->addElements($fieldset);
		
		$fieldset = new Fieldset(System::getLanguage()->_('Password'));
		$password = new Password('password', System::getLanguage()->_('Password'), true);
		$password->minlength = PASSWORD_MIN_LENGTH;
		
		$password->binding = new Databinding('password');
		$password2 = new Password('password2', System::getLanguage()->_('ReenterPassword'), true);
		
		$fieldset->addElements($password, $password2);
		$form->addElements($fieldset);
		
		$fieldset = new Fieldset(System::getLanguage()->_('Settings'));
		
				
		$form->setSubmit(new Button(
			System::getLanguage()->_('Save'),
			'floppy-disk'
		));
		
		$form->addButton(new Button(
			System::getLanguage()->_('Cancel'),
			'remove',
			Router::getInstance()->build('UsersController', 'index')
		));

		if(Utils::getPOST('submit', false) !== false) {
				
				if($form->validate()) {
					$form->save();
					$user->save();
					System::getSession()->setData('successMsg', System::getLanguage()->_('SuccessToAdd'));
					System::forwardToRoute(Router::getInstance()->build('UsersController', 'index'));
					exit;
				}
		} else {
			$form->fill();	
		}
		$smarty = new Template();
		$smarty->assign('title', System::getLanguage()->_('AddUser'));
		$smarty->assign('heading', System::getLanguage()->_('AddUser'));
		
		$smarty->assign('form', $form);
		$smarty->display('form.tpl');
	}
	
	public function edit() {
		$user = User::find('_id', $this->getParam('uid', 0));
		
		if($user == NULL) {
			System::displayError(System::getLanguage()->_('ErrorUserNotFound'), '404 Not Found');	
		}
		
		$form	= new Form('form-user', Router::getInstance()->build('UsersController', 'edit', $user));
		$form->binding = $user;
		
		$fieldset	= new Fieldset(System::getLanguage()->_('General'));
		
		$username = new Text('username', System::getLanguage()->_('Username'), true);
		$username->binding = new Databinding('username');
		$username->blacklist = $this->getListOfUsernames($user);
		$username->error_msg[4] = System::getLanguage()->_('ErrorUsernameAlreayExists');
		
		$nickname = new Text('nickname', System::getLanguage()->_('Nickname'));
		$nickname->binding = new Databinding('nickname');
		
		
		$email = new Text('email', System::getLanguage()->_('EMail'), true);
		$email->binding = new Databinding('email');
		$email->blacklist = $this->getListOfMailAdresses($user);
		$email->error_msg[4] = System::getLanguage()->_('ErrorMailAdressAlreadyExists');
		
		$language = new Radiobox('lang', System::getLanguage()->_('Language'), L10N::getLanguages(), LANGUAGE);
		$language->binding = new Databinding('lang');
		
		$fieldset->addElements($username, $nickname, $email, $language);
		$form->addElements($fieldset);
		
		$fieldset = new Fieldset(System::getLanguage()->_('Password'));
		$password = new Password('password', System::getLanguage()->_('Password'));
		$password->binding = new Databinding('password');
		$password2 = new Password('password2', System::getLanguage()->_('ReenterPassword'));
		
		$fieldset->addElements($password, $password2);
		$form->addElements($fieldset);
		
		if($user->uid != System::getUser()->uid) {
			$fieldset = new Fieldset(System::getLanguage()->_('Settings'));
			

			$usertype = new Select('usertype', 
				System::getLanguage()->_('UserType'), 
				array(
					'0' => System::getLanguage()->_('Administrator'),
					'1' => System::getLanguage()->_('Squadron'), 
					'2' => System::getLanguage()->_('Platoon'), 
				)
			);
			$usertype->binding = new Databinding('type');

			
			$fieldset->addElements($usertype);		
			$form->addElements($fieldset);
		}
		
		$form->setSubmit(new Button(
			System::getLanguage()->_('Save'),
			'floppy-disk'
		));
		
		if($user->uid != System::getUser()->uid) {
			$form->addButton(new Button(
				System::getLanguage()->_('DeleteUser'),
				'trash',
				Router::getInstance()->build('UsersController', 'delete', $user)
			));
		}
		
		$form->addButton(new Button(
			System::getLanguage()->_('Cancel'),
			'remove',
			Router::getInstance()->build('UsersController', 'index')
		));

		
		if(Utils::getPOST('submit', false) !== false) {
			if($form->validate()) {
					$form->save();
					$user->save();
					System::getSession()->setData('successMsg', System::getLanguage()->_('SuccessToEdit'));
					System::forwardToRoute(Router::getInstance()->build('UsersController', 'index'));
					exit;
			}
		} else {
			$form->fill();	
		}

		$smarty = new Template();
		$smarty->assign('title', System::getLanguage()->_('EditUser'));
		$smarty->assign('heading', System::getLanguage()->_('EditUser'));
		
		$smarty->assign('form', $form);
		$smarty->display('form.tpl');
	}
	
	public function delete() {
		$user = User::find('_id', $this->getParam('uid', 0));
		
		if($user == NULL) {
			System::displayError(System::getLanguage()->_('ErrorUserNotFound'), '404 Not Found');	
		} else if($user->uid == System::getUser()->uid) {
			System::displayError(System::getLanguage()->_('ErrorCannotDeleteYourself'), '403 Forbidden');		
		}
		
		$form	= new Form('form-user', Router::getInstance()->build('UsersController', 'delete', $user));
		$fieldset = new Fieldset(System::getLanguage()->_('Confirm'));
		
		$checkbox = new Checkbox('confirm', System::getLanguage()->_('ConfirmDeleteUser'), true);
		$p = new Paragraph(System::getLanguage()->_('ConfirmDeleteUserInfo'));
		
		$fieldset->addElements($checkbox, $p);
		$form->addElements($fieldset);
		
		$form->setSubmit(new Button(
			System::getLanguage()->_('Confirm'),
			'icon icon-delete'
		));
		
		$form->addButton(new Button(
			System::getLanguage()->_('Cancel'),
			'icon icon-cancel',
			Router::getInstance()->build('UsersController', 'index')
		));
		
		if(Utils::getPOST('submit', false) !== false) {
			if($form->validate()) {
				$user->delete();
				
				System::forwardToRoute(Router::getInstance()->build('UsersController', 'index'));
				exit;	
			}
		}
		
		$smarty = new Template();
		$smarty->assign('title', System::getLanguage()->_('DeleteUser'));
		$smarty->assign('heading', System::getLanguage()->_('DeleteUser'));
		
		$smarty->assign('form', $form);
		$smarty->display('form.tpl');
	}
}
?>
