<?php
header("Content-type: text/html; charset=UTF-8");
include('./xmpp/XMPP.php');
include('config.php');

if (isset($_GET['term']) && !empty($_GET['term']))
{
	$term  = mysql_real_escape_string($_GET['term']); 
	$query = mysql_query("SELECT username FROM `data` WHERE username LIKE '$term%'");
	if(mysql_num_rows($query) > 0)
	{
		while ($tmp = mysql_fetch_array($query)) $result[] = $tmp['username'];
	}
	exit(json_encode($result));
}

$login = $_POST['login'];
$login = addslashes($login);
$login = htmlspecialchars($login);
$login = stripslashes($login);
$strError = '<div class="alert alert-danger" role="alert"><strong>Error</strong>. Jabber is not valid.<br /><p style="margin-left: 20px;">';

if (strlen($login) < 10)
	exit($strError.'The length must be at least 10 characters</p></div>');
elseif (strrpos($login, '@') === false)
	exit($strError.'Missing the @ symbol</p></div>');
elseif (preg_match("/^[\w@.-а-яА-Я]+$/", $login) === false)
	exit($strError.'I found forbidden characterrs</p></div>');
else
{
	$tmp = explode('@', $login);
	$domain = $tmp[1];
	$username = $tmp[0];

	$domainExp = explode('.', $domain);
	if (strlen($domainExp[0]) < 2)
		exit($strError.'The minimum length of a domain name is equal 2</p></div>');

	//check if domain exist in BD
	$domains = array('jabbim.pl', 'jabbim.sk', 'jabbim.com', 'jabbim.cz', 'jabber.root.cz');
	if (array_search($domain, $domains) === false)
		exit('<div class="alert alert-danger" role="alert"><strong>Error</strong>. I don\'t have BD from '.$domain.'</p></div>');
}

$query = mysql_query("SELECT password,available FROM `data` WHERE username='$login'", $db);
if(mysql_num_rows($query) == 1)
{
	$info = mysql_fetch_array($query);
	if ($info['available'] == 1)
	{
		$bitcoin_cost = file_get_contents('https://blockchain.info/tobtc?currency=USD&value=15');
		$html = '
			<div class="panel panel-success">
  				<div class="panel-heading">Success. Buy account '.$login.'</div>
  				<div class="panel-body">
    				<center><img src="./img/jabbim.gif"></center>
    				<p class="text-left">15$/'.$bitcoin_cost.' btc</p>
  				</div>
			</div>
		';
		exit($html);
	}
	else
		exit('<div class="alert alert-warning" role="alert"><strong>Sorry!</strong> This account was bought earlier</p></div>');
} else
	exit('<div class="alert alert-warning" role="alert"><strong>Sorry!</strong> I have not found in my database that person</p></div>');

function jabberCheck($domain, $username, $password)
{
	$connection = new XMPPHP_XMPP($domain, 5222, $username, $password, '');
	if($conn->connect())
		return true;
	else
		return false;
}
?>