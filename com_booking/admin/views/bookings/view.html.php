<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_booking
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Bookings View
 *
 * @since  0.0.1
 */
class BookingViewBookings extends JViewLegacy
{
    /**
     * Display the Bookings view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null)
    {
        // Get data from the model
        $this->bookings		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        
        // Get the order state
        $state = $this->get('State');
        
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');
        
        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        // add the toolbar
        $this->addToolBar();
        
        // Display the template
        parent::display($tpl);
    }
    /**
     * 
     */private function addToolBar()
    {
        JToolBarHelper::title( 'Asset Booking Component', 'generic.png' );
        JToolBarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'bookings.delete');
        JToolBarHelper::editList('booking.edit');
        JToolBarHelper::addNew('booking.add');
    }

    
}