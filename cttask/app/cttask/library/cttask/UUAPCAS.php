<?php
// constants
define("UUAP_CAS_PATH", dirname(__FILE__).'/uuap');
define("UUAP_CAS_HOST", 'cas.taihenw.com');
define("UUAP_CAS_PORT", 443);
define("UUAP_CAS_CONTEXT", '/cas');

require_once(UUAP_CAS_PATH.'/CAS.php');

class Cttask_UUAPCAS{
	
	public static $instance = null;
	
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new Cttask_UUAPCAS();
		}
		return self::$instance;
	}
	
	public function __construct(){
		// Uncomment to enable debugging
		// phpCAS::setDebug();
		// Initialize phpCAS
		phpCAS::client(CAS_VERSION_2_0, UUAP_CAS_HOST, UUAP_CAS_PORT, UUAP_CAS_CONTEXT);
		// For production use set the CA certificate that is the issuer of the cert
		// on the CAS server and uncomment the line below
		// phpCAS::setCasServerCACert($cas_server_ca_cert_path);
		// For quick testing you can disable SSL validation of the CAS server.
		// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
		// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
		phpCAS::setNoCasServerValidation();
		// Handle logout requests
		phpCAS::handleLogoutRequests(false);
	}
	
	/**
	 * authenticate
	 */
	public function authenticate(){
		// force CAS authentication
		phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().
	}
	
	/**
	 * get current username
	 */
	public function getUserName(){
		return phpCAS::getUser();
	}
	
	/**
	 * logout
	 */
	public function logout(){
		phpCAS::logout();
	}
	
}