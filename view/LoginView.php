<?php
namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $_PASSWORD = "Password";
	private $_USERNAME = "Admin";

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn, $message) {
		$name = "";
		if (isset($_POST[self::$name])) {
			$name = $_POST[self::$name];
		}

		if(isset($_POST[self::$logout])){
			$this->logout();
			$isLoggedIn = false;
		}

		if ($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML($message);
		}
		else {
			$response = $this->generateLoginFormHTML($message, $name);
		}

		return $response;
	}

	public function checkInput() {
		$message = "";
		if (isset($_POST[self::$name]) && isset($_POST[self::$password])) {
				if ($_POST[self::$name] == "") {
					$message = "Username is missing";
				}

				else if ($_POST[self::$password] == "") {
					$message = "Password is missing";
				}

				else {
					if($this->checkAuthentication($_POST[self::$name], $_POST[self::$password])) {
						//LOGIN SUCCESSFUL
						$message = "Welcome";
					}
					else {
						//LOGIN FAILED
						$message = "Wrong name or password";
					}
				}
		}

		return $message;
	}

	private function logout(){
		session_destroy();
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message, $name) {
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $name . '" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
		return $_POST[self::$name];
	}

	private function getRequestUserPassword() {
		//RETURN REQUEST VARIABLE: PASSWORD
		return $_POST[self::$password];
	}

	private function getRequestUserKeep() {
		//RETURN REQUEST VARIABLE: PASSWORD
		return $_POST[self::$keep];
	}

	private function checkAuthentication($username, $password) {
		$correct = false;
		$_SESSION["isLoggedIn"] = false;

		if ($username == $this->_USERNAME && $password == $this->_PASSWORD) {
			$correct = true;
			$_SESSION["isLoggedIn"] = true;
		}

		return $correct;
	}

}
