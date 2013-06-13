<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Autosize
 *
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Joomla Autosize plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Autosize
 * @since       3.1
 */
class PlgSystemAutosize extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An array that holds the plugin configuration.
	 *
	 * @access  protected
	 * @since   3.1
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		// Get the application.
		$app = JFactory::getApplication();

		// Save the syntax for later use.
		if ($app->isAdmin())
		{
			$app->setUserState('editor.source.syntax', 'css');
		}
	}

	/**
	 * Method to catch the onAfterDispatch event.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.1
	 */
	public function onAfterDispatch()
	{
		// Check that we are in the site application.
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}

		// Get the document object.
		$doc = JFactory::getDocument();

		// Add Stylesheet.
		if ($custom_css = trim($this->params->get('custom_css')))
		{
			$doc->addStyleDeclaration($custom_css);
		}
		else
		{
			JHtml::stylesheet('plg_system_autosize/autosize.css', false, true, false);
		}

		// Add JavaScript Frameworks.
		JHtml::_('jquery.framework');

		// Load JavaScript.
		if ($this->params->get('minified', 1))
		{
			JHtml::script('plg_system_autosize/jquery.autosize.min.js', false, true);
		}
		else
		{
			JHtml::script('plg_system_autosize/jquery.autosize.js', false, true);
		}

		// Build the script.
		$script = array();
		$script[] = 'jQuery(document).ready(function($) {';
		$script[] = '	$(\'' . $this->params->get('selector', 'textarea') . '\').autosize();';
		$script[] = '});';

		// Add the script to the document head.
		$doc->addScriptDeclaration(implode("\n", $script));

		return true;
	}
}
