<?php
namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	//private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

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
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn, $message) {
		$name = "";
		if ($this->userNameExist()) {
			$name = $this->getUserName();
		}

		if ($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML($message);
		}
		else {
			$response = $this->generateLoginFormHTML($message, $name);
		}

		return $response;
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

	public function isUserLoggingIn() {
		return isset($_POST[self::$login]);
	}

	public function isUserLoggingOut() {
		return isset($_POST[self::$logout]);
	}

	public function userNameExist() {
		return isset($_POST[self::$name]);
	}
	public function getUserName() {
		return $_POST[self::$name];
	}

	public function passwordExist() {
		return isset($_POST[self::$password]);
	}
	public function getPassword() {
		return $_POST[self::$password];
	}

	public function keepStatusExist() {
		return isset($_POST[self::$keep]);
	}
	public function keepIsActivated() {
		return $_POST[self::$keep];
	}

	public function setCookiePasswordCookie() {
		setcookie(self::$cookiePasswordCookieName, $this->cookiePassword, $this->cookieLifeTime, "/");
	}

	public function cookiePassWordCookieExist() {
		return isset($_COOKIE[self::$cookiePasswordCookieName]);
	}
	public function getCookiePasswordCookie() {
		return $_COOKIE[self::$cookiePasswordCookieName];
	}

	public function isLoggedInCookieExist() {
		return isset($_COOKIE[self::$isLoggedInCookieName]);
	}
	public function getIsLoggedInCookie() {
		return $_COOKIE[self::$isLoggedInCookieName];
	}
	public function setIsLoggedInCookie($bool) {
		//setcookie("isLoggedIn", true, time() + (86400 * 30), "/");
		//setcookie("flashMessage", "Welcome back with cookie" , time() + (86400 * 30), "/");
		if ($bool) {
			setcookie(self::$isLoggedInCookieName, $bool, $this->cookieLifeTime, "/");
			//only set flashmessage cookie when logging in
			setcookie(self::$flashMessageCookieName, "Welcome back with cookie" , $this->cookieLifeTime, "/");
		}
		else {
			//loggin out, set the isLoggedInCookie to false and -1 time
			setcookie(self::$isLoggedInCookieName, $bool, time()-1, "/");
		}
	}

	public function isLoggedInSessionExist() {
		return isset($_SESSION[self::$isLoggedInCookieName]);
	}
	public function getIsLoggedInSession() {
		//TODO: find a better solution
		if ($this->isLoggedInSessionExist()) {
			return $_SESSION[self::$isLoggedInCookieName];
		}
		else {
			return null;
		}
	}
	public function setIsLoggedInSession($bool) {
		$_SESSION[self::$isLoggedInCookieName] = $bool;
	}
	public function unsetIsLoggedInSession() {
		unset($_SESSION[self::$isLoggedInCookieName]);
	}

	public function flashMessageCookieExist() {
		return isset($_COOKIE[self::$flashMessageCookieName]);
	}
	public function getFlashMessageCookie() {
		return $_COOKIE[self::$flashMessageCookieName];
	}
	public function clearFlashMessageCookie() {
		setcookie(self::$flashMessageCookieName, false , time()-1);
	}

}
