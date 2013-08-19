<?php

/**
 * @package     StopRegBots.Plugin
 *
 * @copyright   Copyright (C) 2013 Ray Lawlor. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */
// no direct access
defined('JPATH_BASE') or die;

//Is this even needed anymore?
jimport('joomla.plugin.plugin');

class PlgUserStopregbots extends JPlugin {

    /**
     * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
     *
     * @access      protected
     * @param       object  $subject The object to observe
     * @param       array   $config  An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    /**
     * Method is called before user data is stored in the database
     *
     * @param   array    $user   Holds the old user data.
     * @param   boolean  $isnew  True if a new user is stored.
     * @param   array    $data   Holds the new user data.
     *
     * @return    boolean
     *
     * @since   3.1
     * @throws    InvalidArgumentException on invalid date.
     */
    public function onUserBeforeSave($user, $isnew, $data) {

        $names = explode(",", $this->params->get('name'));
        $emails = explode(",", $this->params->get('email'));
        $usernames = explode(",", $this->params->get('username'));

        if (in_array($data['name'], $names) ||
                in_array($data['email'], $emails) ||
                in_array($data['username'], $usernames)) {
            JError::raiseWarning(1000, JText::_('PLG_USER_STOPREGBOTS_MESSAGE'));
            $result = FALSE;
        } else {
            $result = TRUE;
        }
        return $result;
    }

}
