<?php
//##copyright##

$iaDb->setTable("blocked_domains");

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	$iaGrid = $iaCore->factory('grid', iaCore::ADMIN);
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:
			$output = $iaGrid ->gridRead($_GET,
				array('domain', 'note', 'date'),
				array('domain' => 'like')
			);

			break;

		case iaCore::ACTION_EDIT:
			$output = $iaGrid->gridUpdate($_POST);

			break;

		case iaCore::ACTION_DELETE:
			$output = $iaGrid->gridDelete($_POST);
	}

	$iaView->assign($output);
}

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$iaUtil = $iaCore->factory('util');

	if (iaCore::ACTION_ADD == $pageAction)
	{
		if (isset($_POST['save']))
		{
			$error = false;
			$messages = array();

			$blocked_domain['domain'] = str_replace('/', '', str_replace('www.', '', str_replace('http:', '', $_POST['domain'])));
			$blocked_domain['member_id'] = iaUsers::getIdentity()->id;
			$blocked_domain['note'] = $iaUtil->safeHTML($_POST['note']);

			if (empty($blocked_domain['domain']))
			{
				$error = true;
				$messages[] = iaLanguage::getf('field_is_empty', array('field' => iaLanguage::get('domain')));
			}
			else
			{
				$blocked_domain['domain'] = 'http://' .  $blocked_domain['domain'];
				if($iaDb->exists('domain = :domain', array('domain' => $blocked_domain['domain'])))
				{
					$error = true;
					$messages[] = iaLanguage::get('domain_exists');
				}
			}

			if (!$error)
			{
				$iaDb->insert($blocked_domain, array('date' => iaDb::FUNCTION_NOW));

				$messages[] = iaLanguage::get('entry_added');
			}

			$iaView->setMessages($messages, ($error ? 'error' : 'success'));

			if (!$error)
			{
				iaUtil::go_to(IA_ADMIN_URL . 'blacklist/domains/');
			}
		}

		$iaView->display('domains');
	}
	else
	{
		if (iaCore::ACTION_READ == $pageAction)
		{
			$iaView->grid('_IA_URL_plugins/blacklist/js/admin/domains');
		}
	}
}
