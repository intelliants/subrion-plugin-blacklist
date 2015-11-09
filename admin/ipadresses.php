<?php
//##copyright##

$iaDb->setTable("blocked_ipadresses");

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	$iaGrid = $iaCore->factory('grid', iaCore::ADMIN);
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:
			$output = $iaGrid ->gridRead($_GET,
				array('ip', 'note', 'date'),
				array('ip' => 'like')
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

			$blocked_ip['ip'] = str_replace('/', '', str_replace('www.', '', str_replace('http:', '', $_POST['ip'])));
			$blocked_ip['member_id'] = iaUsers::getIdentity()->id;
			$blocked_ip['note'] = $iaUtil->safeHTML($_POST['note']);

			if (empty($blocked_ip['ip']))
			{
				$error = true;
				$messages[] = iaLanguage::getf('field_is_empty', array('field' => iaLanguage::get('ip')));
			}
			else
			{
				if($iaDb->exists('ip = :ip', array('ip' => $blocked_ip['ip'])))
				{
					$error = true;
					$messages[] = iaLanguage::get('ip_exists');
				}
			}

			if (!$error)
			{
				$iaDb->insert($blocked_ip, array('date' => iaDb::FUNCTION_NOW));
				$messages[] = iaLanguage::get('entry_added');
			}

			$iaView->setMessages($messages, ($error ? 'error' : 'success'));

			if (!$error)
			{
				iaUtil::go_to(IA_ADMIN_URL . 'blacklist/ipadresses/');
			}
		}

		$iaView->display('ipadresses');
	}
	else
	{
		if (iaCore::ACTION_READ == $pageAction)
		{
			$iaView->grid('_IA_URL_plugins/blacklist/js/admin/ipadresses');
		}
	}
}
