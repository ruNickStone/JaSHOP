function validCheck() 
{
	var login = document.getElementById('login').value;
	var result = document.getElementById('result');
	var letters = /^[0-9a-zA-Zа-яёА-ЯЁ@-_.]+$/;
	var strError = '<div class="alert alert-danger" role="alert"><strong>Error</strong>. Jabber is not valid.<br /><p style="margin-left: 20px;">';

	if (login.length < 10)
	{
		result.innerHTML = strError + 'The length must be at least 10 characters</p></div>';
		return false;
	}
	else if (login.indexOf('@') == -1)
	{
		result.innerHTML = strError + 'Missing the @ symbol</p></div>';
		return false;
	}
	else if (!login.match(letters))
	{
		result.innerHTML = strError + 'I found forbidden characters</p></div>';
		return false;
	}
	else
	{
		domain = login.split('@')[1];
		login  = login.split('@')[0];

		if (domain.split('.')[0].length < 2)
		{
			result.innerHTML = strError + 'The minimum length of a domain name is equal 2</p></div>'
			return false;
		}

		//check if domain exist in BD
		domains = ['jabbim.pl', 'jabbim.sk', 'jabbim.com', 'jabbim.cz', 'jabber.root.cz'];
		if (domains.indexOf(domain) == -1)
		{
			result.innerHTML = '<div class="alert alert-danger" role="alert"><strong>Error</strong>. I don\'t have BD from ' + domain + '</div>';
			return false;
		}
	}
	return true;
}