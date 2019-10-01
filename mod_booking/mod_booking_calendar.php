<?php
    /**
     * Booking Calendar Module Entry Point
     *
     * @package    Joomla.Tutorials
     * @subpackage Modules
     * @license    GNU/GPL, see LICENSE.php
     * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
     * mod_booking calendar is free software. This version may have been modified pursuant
     * to the GNU General Public License, and as distributed it includes or
     * is derivative of works licensed under the GNU General Public License or
     * other free or open source software licenses.
     */
    
    // No direct access
    defined('_JEXEC') or die('Restricted Access');
    
    // Include the syndicate functions only once
    require_once dirname(__FILE__) . '/helper.php';
    
    // Get the module parameters
    $startYear = trim($params->get('startYear'));
    $startMonth = trim($params->get('startMonth'));
    $cssfile = $params->get('cssfile', 'v5-blue.css');
    $links = $params->get('links', 1);
    
    // load the required CSS, if it exists
    $document = JFactory::getDocument();
    if (file_exists(JPATH_ROOT . '/media/mod_booking_calendar/css/' . $cssfile))
        $document->addStyleSheet(JURI::root(true) . '/media/mod_booking_calendar/css/' . $cssfile . '?' . filemtime(JPATH_SITE . '/media/mod_booking_calendar/css/' . $cssfile));
    
    // if links are configured, load the Javascript
    if ($links == 1)
        $document->addScript(JURI::root(true) . '/media/mod_booking_calendar/js/mod_bookingcal.js');
    
    // Set the month and year, defaulting to the current month
    // if month is negative, it's a negative offset from the current month
    // if month is positive, it's an actual month
    
    if (empty($startYear))
        $startYear = date('Y');
    
    if (empty($startMonth))
        $startMonth = date('m');
    
    if ($startMonth < 0) {
        $startDate = mktime(0, 0, 0, date('m') + $startMonth, 1, $startYear);
        $startMonth = date('m', $startDate);
        $startYear = date('Y', $startDate);
    }
    $modulePosition = $module->position;
    
    $html = "\n" . '<div class="mod_booking_calendar_outer">';
    $html .= ModBookingCalendarHelper::getCalendarsForAsset($params, $modulePosition, $startYear, $startMonth, $links);
    $html .= '</div>';
    
    require JModuleHelper::getLayoutPath('mod_booking_calendar');
?>