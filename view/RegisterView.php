<?php
namespace view;

class RegisterView {
  private static $route = 'register';
	private static $register = 'RegisterView::Register';
	private static $name = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $message = 'RegisterView::Message';
  /*
	//Cookie settings
	private static $daysInSeconds = 86400;
	private static $daysInMonth = 30;
	private $cookieLifeTime;
	private $cookiePassword;
	private static $cookiePasswordCookieName = "LoginView::CookiePassword";
	private static $isLoggedInCookieName = "LoginView::isLoggedIn";
	private static $flashMessageCookieName = "LoginView::flashMessage";

	function __construct() {
		$this->cookieLifeTime = time() + (self::$daysInSeconds * self::$daysInMonth);
		$this->cookiePassword = md5("test");
	}*/

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($message) {
		$response = $this->generateRegisterFormHTML($message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateRegisterFormHTML($message) {
		return '

    <a href="/">Back to login</a>
			<form method="post" >
				<fieldset>
					<legend>Register</legend>
					<p id="' . self::$message . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" /><br>
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" /><br>
          <label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />

					<input type="submit" name="' . self::$register . '" value="register" />
				</fieldset>
			</form>
		';
	}

  public function isUserRequestingRegister() {
    return isset($_GET[self::$route]);
  }

	public function isUserSendingNewRegister() {
		return isset($_POST[self::$register]);
	}

	public function userNameExist() {
		return isset($_POST[self::$name]);
	}
	public function getUserName() {
    if ($this->userNameExist()) {
		    return $_POST[self::$name];
    }else {
      return null;
    }
	}

	public function passwordExist() {
		return isset($_POST[self::$password]);
	}
	public function getPassword() {
    if ($this->passwordExist()) {
		    return $_POST[self::$password];
    }else {
      return null;
    }
	}

	public function keepStatusExist() {
		return isset($_POST[self::$keep]);
	}
	public function keepIsActivated() {
		return $_POST[self::$keep];
	}

}
