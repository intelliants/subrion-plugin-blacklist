<?php
//##copyright##

$iaDb->setTable("blocked_emails");

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	$iaGrid = $iaCore->factory('grid', iaCore::ADMIN);
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:
			$output = $iaGrid ->gridRead($_GET,
				array('email', 'note', 'date'),
				array('email' => 'like')
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

			$blocked_email['email'] = str_replace('/', '', str_replace('www.', '', str_replace('http:', '', $_POST['email'])));
			$blocked_email['member_id'] = iaUsers::getIdentity()->id;
			$blocked_email['note'] = $iaUtil->safeHTML($_POST['note']);

			if (empty($blocked_email['email']))
			{
				$error = true;
				$messages[] = iaLanguage::getf('field_is_empty', array('field' => iaLanguage::get('email')));
			}
			else
			{
				if (!iaValidate::isEmail($blocked_email['email']))
				{
					$error = true;
					$messages[] = iaLanguage::get('error_email_incorrect');
				}

				if($iaDb->exists('email = :email', array('email' => $blocked_email['email'])))
				{
					$error = true;
					$messages[] = iaLanguage::get('email_exists');
				}
			}

			if (!$error)
			{
				$iaDb->insert($blocked_email, array('date' => iaDb::FUNCTION_NOW));
				$messages[] = iaLanguage::get('entry_added');
			}

			$iaView->setMessages($messages, ($error ? 'error' : 'success'));

			if (!$error)
			{
				iaUtil::go_to(IA_ADMIN_URL . 'blacklist/emails/');
			}
		}

		$iaView->display('emails');
	}
	else
	{
		if (iaCore::ACTION_READ == $pageAction)
		{
			$iaView->grid('_IA_URL_plugins/blacklist/js/admin/emails');
		}
	}
}
