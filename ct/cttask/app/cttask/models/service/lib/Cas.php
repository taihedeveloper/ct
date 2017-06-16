<?php

/**
 * CAS
 */
class Service_Lib_Cas
{

	function __construct()
	{
		require_once dirname(dirname(__FILE__)).'/library/newSongSpider/phpcas/CAS.php';

		$cas_host = Bd_Conf::getAppConf('cas_host/cas_host');
		$cas_context = Bd_Conf::getAppConf('cas_context/cas_context');
		$cas_port = Bd_Conf::getAppConf('cas_port/cas_port');

		phpCAS::setDebug();
		phpCAS::client(CAS_VERSION_2_0,$cas_host,$cas_port,$cas_context);
		phpCAS::setNoCasServerValidation();
		phpCAS::forceAuthentication();
	}

	public  function checkLogin(){
		return phpCAS::getUser();;
	}
}
?>
