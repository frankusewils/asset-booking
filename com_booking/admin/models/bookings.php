<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_booking_calendar
 *
 * @copyright   Copyright (C) 2019 - 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * BookingsList Model
 *
 * @since  0.0.1
 */
class BookingModelBookings extends JModelList
{
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Initialize variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        
        // Create the order statement.
        $query->order($db->escape($this->getState('list.ordering', 'start_date')).' '.
            $db->escape($this->getState('list.direction', 'DESC')));
       
        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__asset_booking'));
        return $query;
    }
    
    protected function populateState($ordering = null, $direction = null) {
        parent::populateState('start_date', 'DESC');
    }
    
    public function __construct($config = array())
    {
        $config['filter_fields'] = array('start_date', 'end_date', 'price');
        parent::__construct($config);
    }
}