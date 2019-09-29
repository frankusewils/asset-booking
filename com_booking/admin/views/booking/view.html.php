<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_booking
 *
 * @copyright   Copyright (C) 2019 - 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Booking View
 *
 */
class BookingViewBooking extends JViewLegacy
{

     /**
     * Display the Booking view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    
    public function display($tpl = null)
    {
        // Get the Data
        $this->form = $this->get('Form');
        $this->booking = $this->get('Item');
        
        // Check for errors.
        if (!empty($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        // Set the toolbar
        $this->addToolBar();
        
        // Display the template
        parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     */
    protected function addToolBar()
    {
        // Hide Joomla Administrator Main menu
        JFactory::getApplication()->input->set('hidemainmenu', true);
        
        $isNew = ($this->booking->id == 0);
        
        if ($isNew)
        {
            $title = JText::_('COM_BOOKING_MANAGER_BOOKING_NEW');
        }
        else
        {
            $title = JText::_('COM_BOOKING_MANAGER_BOOKING_EDIT');
        }
        
        JToolbarHelper::title($title, 'booking');
        JToolbarHelper::save('booking.save');
        JToolbarHelper::cancel('booking.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}