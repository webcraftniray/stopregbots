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
     * //take the params and seperate using the comma and set the values into the array vars
      //        $names = explode(",", $this->params->get('name'));
      //        $emails = explode(",", $this->params->get('email'));
      //        $usernames = explode(",", $this->params->get('username'));
      //
      //        //now check if the value being registraed against the params list
      //        if (in_array($data['name'], $names) ||
      //                in_array($data['email'], $emails) ||
      //                in_array($data['username'], $usernames)) {
      //            JError::raiseWarning(1000, JText::_($this->params->get('message')));
      //            $result = FALSE;
      //        }
     * @since   3.1
     * @throws    InvalidArgumentException on invalid date.
     */
    public function onUserBeforeSave($user, $isnew, $data) {
        
        
        // Set RESULT as 'ture' and allow the code below to set it to 'false' if needs be.
        $result = TRUE;

        //take the params and seperate using the comma and set the values into the array vars
        $names = explode(",", $this->params->get('name'));
        $emails = explode(",", $this->params->get('email'));
        $usernames = explode(",", $this->params->get('username'));

        //now check if the value being registraed against the params list
        if (in_array($data['name'], $names) ||
                in_array($data['email'], $emails) ||
                in_array($data['username'], $usernames)) {
            JError::raiseWarning(1000, JText::_($this->params->get('message')));
            $result = FALSE;
        }


        /*
         * 
         * Check the submitted values against the current user database and see if the value is already there. 
         * Use the entered value in the params to count the rows. If the rows are greater than or equal to param value then return false.
         */

        //set vars
        $name = $data['name'];
        $paramRows = $this->params->get('attempts');
        
        
        //check if theis params is set to '0'
        if ($paramRows > 0) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__users');
            $query->where('name=' . $db->Quote($name));
            $db->setQuery($query);
            $db->query();
            $count = $db->getNumRows();

            //check if the number of rows returned is greater than the set limit from params
            if ($count >= $paramRows) {
                JError::raiseWarning(1000, JText::_($this->params->get('message')));
                $result = FALSE;
            }
        }

        return $result;
    }

}
