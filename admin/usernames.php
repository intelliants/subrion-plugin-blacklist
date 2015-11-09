<?php
//##copyright##

$iaDb->setTable("blocked_usernames");

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	$iaGrid = $iaCore->factory('grid', iaCore::ADMIN);
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:
			$output = $iaGrid ->gridRead($_GET,
				array('username', 'note', 'date'),
				array('username' => 'like')
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

			$blocked_username['username'] = str_replace('/', '', str_replace('www.', '', str_replace('http:', '', $_POST['username'])));
			$blocked_username['member_id'] = iaUsers::getIdentity()->id;
			$blocked_username['note'] = $iaUtil->safeHTML($_POST['note']);

			if (empty($blocked_username['username']))
			{
				$error = true;
				$messages[] = iaLanguage::getf('field_is_empty', array('field' => iaLanguage::get('username')));
			}
			else
			{
				if($iaDb->exists('username = :username', array('username' => $blocked_username['username'])))
				{
					$error = true;
					$messages[] = iaLanguage::get('username_exists');
				}
			}

			if (!$error)
			{
				$iaDb->insert($blocked_username, array('date' => iaDb::FUNCTION_NOW));
				$messages[] = iaLanguage::get('entry_added');
			}

			$iaView->setMessages($messages, ($error ? 'error' : 'success'));

			if (!$error)
			{
				iaUtil::go_to(IA_ADMIN_URL . 'blacklist/usernames/');
			}
		}

		$iaView->display('usernames');
	}
	else
	{
		if (iaCore::ACTION_READ == $pageAction)
		{
			$iaView->grid('_IA_URL_plugins/blacklist/js/admin/usernames');
		}
	}
}
