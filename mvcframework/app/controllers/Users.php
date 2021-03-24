<?php
/**
 * 
 */
class Users extends Controller
{
	
	public function __construct()
	{
		# code...
		$this->userModel = $this->model('User');
	}
	public function register(){
		$data = [
			'name' => '',
			'email' => '',
			'password' => '',
			'confirmPassword' => '',
			'nameError' => '',
			'emailError' => '',
			'passwordError' => '',
			'confirmPasswordError' => ''
		];


		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//Sanitize post data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$data = [
				'name' => trim($_POST['name']),
				'email' => trim($_POST['email']),
				'password' => trim($_POST['password']),
				'confirmPassword' => trim($_POST['confirmPassword']),
				'nameError' => '',
				'emailError' => '',
				'passwordError' => '',
				'confirmPasswordError' => ''
			];

			$nameValidation = "/^[a-zA-Z]*$/";
			$passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";

			//Validate name on letters
			if (empty($data['name'])) {
				$data['nameError'] = 'Please enter name.';
			} elseif (!preg_match($nameValidation, $data['name'])) {
				$data['nameError'] = 'Name can only contain  letters';
			}

			//Validate email
			if (empty($data['email'])) {
				$data['emailError'] = 'Please enter email address.';
			} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
				$data['emailError'] = 'Please enter the correct format';
			} else {
				//check if email exists
				if ($this->userModel->findUserByEmail($data['email'])) {
					$data['emailError'] = 'Email is already taken.';
					
				}
			}
			//Validate password on length and numeric values
			if (empty($data['password'])) {
				$data['passwordError'] = 'Please enter password.';
			} elseif (strlen($data['password']) < 6) {
				$data['passwordError'] = 'Password must be at least 8 characters.';
			} 
			//elseif (!preg_match($passwordValidation, $data['password'])) {
			//	$data['passwordError'] = 'Password must have at least one numeric value.';
			//}

			//var_dump($data);

			//Validate confirm password
			if (empty($data['confirmPassword'])) {
				$data['confirmPasswordError'] = 'Please enter password.';
			} else {
				if ($data['password'] != $data['confirmPassword']) {
					$data['confirmPasswordError'] = 'Passwords do not match, please try again.';
				}
			}

			//Make sure that errors are empty
			if (empty($data['nameError']) && empty($data['emailError']) && empty($data['passwordError']) && empty($data['confirmPasswordError'])) {
					
					//hash password
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
				

				if ($this->userModel->register($data)) {
					//redirect to the login page
					header('location: ' . URLROOT . '/users/login');
				} else {
					die('Somting went wrong.');
				}
			}
			
		}
		
		$this->view('users/register', $data);
	}
			
	
	public function login(){

		$data = [
			'email'=> '',
			'password'=>'',
			'emailError' => '',
			'passwordError' => ''
		];


		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//Sanitize post data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$data = [
				'email' => trim($_POST['email']),
				'password' => trim($_POST['password']),
				'emailError' => '',
				'passwordError' => ''				
			];

			
			if (empty($data['email'])) {
				$data['emailError'] = 'Please enter a email.';
			}

			if (empty($data['password'])) {
				$data['passwordError'] = 'Please enter a password.';
			}

			if (empty($data['email']) && empty($data['password'])) {
	
				$loggedInUser = $this->userModel->login($data['email'], $data['password']);
	
				if ($loggedInUser) {
					$this->createUserSession($loggedInUser);
				} else {
					$data['passwordError'] = 'Password or email is incorect. Please try again.';

					$this->view('users/login', $data);
				}
			} else {

				$data = [
					'email'=> '',
					'password'=>'',
					'emailError' => '',
					'passwordError' => ''
				];
		}
		
	} $this->view('users/login', $data);
}

	public function createUserSession($user){
		
		$_SESSION['user_id'] = $user->id;
		$_SESSION['name'] = $user->name;
		$_SESSION['email'] = $user->email;
		
	}

	public function logout() {

		unset($_SESSION['user_id']);
		unset($_SESSION['name']);
		unset($_SESSION['email']);

		header('location:' . URLROOT . '/users/login');
	
	}
	public function results(){
		$data = [
			'search'=> ''
			
		];
		$this->view('users/results', $data);
	}
}
