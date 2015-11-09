<?php
//##copyright##

if ($iaDb->exists('username = :username', array('username' => iaUsers::getIdentity()->username), 'blocked_usernames'))
{
	$error = true;
	$messages[] = iaLanguage::get('username_is_blocked');
}

if ($iaDb->exists('email = :email', array('email' => iaUsers::getIdentity()->email), 'blocked_emails'))
{
	$error = true;
	$messages[] = iaLanguage::get('email_is_blocked');
}

if ($_POST['url'] || $_POST['website'])
{
	if (is_array($_POST['url']))
	{
		$url = iaSanitize::sql($_POST['url']['url']);
	}
	else
	{
		$url = !empty($_POST['website']) ? iaSanitize::sql($_POST['website']) : iaSanitize::sql($_POST['url']);
	}

	if ($iaDb->exists('domain = :domain', array('domain' => $url), 'blocked_domains'))
	{
		$error = true;
		$messages[] = iaLanguage::get('domain_is_blocked');
	}
}

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) || $_SERVER['REMOTE_ADDR'])
{
	$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

	if ($iaDb->exists('ip = :ip', array('ip' => $ip), 'blocked_ipadresses'))
	{
		$error = true;
		$messages[] = iaLanguage::get('ip_is_blocked');
	}
}

$iaView->setMessages($messages);