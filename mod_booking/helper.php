<?php

/**
 * Helper class for Booking Calendar module
 *
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_booking_calendar is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModBookingCalendarHelper
{

    static $modulePosition;

    /**
     * Retrieves the html page
     *
     * @param array $params
     *            An object containing the module parameters
     *            
     * @access public
     */

    // get parameter functions
    public static function getNumMonths($params)
    {
        return trim($params->get('numMonths', 1));
    }

    public static function getDayNamelength($params)
    {
        return trim($params->get('dayLength', 2));
    }

    public static function getStartDay($params)
    {
        return trim($params->get('firstDay', 1));
    }

    public static function getFullWidth($params)
    {
        return trim($params->get('fullwidth', 0));
    }

    static function deriveAssetFromModulePosition($position)
    {
        // filter out the asset after the last double underscore
        $match = null;
        preg_match('/(?<=__).*$/', $position, $match);
        if (!isset($match) || count($match) == 0)
            return null;
        return $match[0];
    }

    // ---------------------------------------------------------------------------------------------
    // Get an array of booked days
    //
    public static function getBookings()
    {
        $asset= self::deriveAssetFromModulePosition(self::$modulePosition);
        echo "fetching bookings for " . $asset;
        // just startup
        $mainframe = JFactory::getApplication();
        // Get the database
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Get Booked periods
        $query->select($db->quoteName(array(
            'start_date',
            'end_date',
            'remarks'
        )))
            ->from($db->quoteName('#__asset_booking'));
        if (isset($asset))
            $query->where($db->quoteName('name') . ' = ' . $db->quote($asset));
        $db->setQuery($query);
        // Array for the booked periods
        return $db->loadObjectList();
    }

    // ---------------------------------------------------------------------------------------------
    // Get an array of day names starting with the start day
    //
    static function getDayNames($start_day)
    {
        $j_days = array(
            JText::_('SUNDAY'),
            JText::_('MONDAY'),
            JText::_('TUESDAY'),
            JText::_('WEDNESDAY'),
            JText::_('THURSDAY'),
            JText::_('FRIDAY'),
            JText::_('SATURDAY')
        );
        for ($i = 0; $i < 7; $i ++) {
            $day = ($i + $start_day) % 7;
            $days[] = $j_days[$day];
        }
        return $days;
    }

    // ---------------------------------------------------------------------------------------------
    // Get a month name
    //
    static function getMonthName($month)
    {
        switch ($month) {
            case 1:
                return JText::_('JANUARY');
            case 2:
                return JText::_('FEBRUARY');
            case 3:
                return JText::_('MARCH');
            case 4:
                return JText::_('APRIL');
            case 5:
                return JText::_('MAY');
            case 6:
                return JText::_('JUNE');
            case 7:
                return JText::_('JULY');
            case 8:
                return JText::_('AUGUST');
            case 9:
                return JText::_('SEPTEMBER');
            case 10:
                return JText::_('OCTOBER');
            case 11:
                return JText::_('NOVEMBER');
            case 12:
                return JText::_('DECEMBER');
        }
    }

    static function showPreviousMonth($month, $year, $currentMonth, $currentYear)
    {
        $current = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
        $showing = mktime(0, 0, 0, $month, 1, $year);
        $usr = JFactory::getUser();
        if ($current >= $showing)
            return isset($usr) && $usr->authorise('core.admin');
        return true;
    }

    static function getStartDates($bookings)
    {
        $dates = array();
        foreach ($bookings as $booking) {
            $dates[] = strtotime($booking->start_date);
        }
        return $dates;
    }

    static function getEndDates($bookings)
    {
        $dates = array();
        foreach ($bookings as $booking) {
            $dates[] = strtotime($booking->end_date);
        }
        return $dates;
    }

    // ---------------------------------------------------------------------------------------------
    // Draw a calendar for a single month
    //
    static function getCalendar($params, $year, $month, $dayNameLength, $startDay, $width, $links, $bookings)
    {
        $startDates = self::getStartDates($bookings);
        $endDates = self::getEndDates($bookings);
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');

        $numColumns = 7;

        $html = '<table class="mod_bookingcal_table"' . $width . '>';

        // draw the month and year heading
        $monthString = self::getMonthName($month) . ' ' . $year;
        $html .= '<tr class="mod_bookingcal_month">';
        if ($links == 0)
            $html .= '<th colspan="' . $numColumns . '">' . $monthString . '</th>';
        else {
            $subtractSpan = 1;
            if (self::showPreviousMonth($month, $year, $currentMonth, $currentYear)) {
                $onclick = 'mod_bookingcal_ajax(-1, \'' . self::$modulePosition . '\', ' . $year . ', ' . $month . ');';
                $html .= '<th class="mod_bookingcal_left" onclick="' . $onclick . '"><span class="mod_bookingcal_left" ></span></th>';
                $subtractSpan = 2;
            }
            $html .= '<th colspan="' . ($numColumns - $subtractSpan) . '">' . $monthString . '</th>';
            $onclick = 'mod_bookingcal_ajax(+1, \'' . self::$modulePosition . '\', ' . $year . ', ' . $month . ');';
            $html .= '<th class="mod_bookingcal_right" onclick="' . $onclick . '"><span class="mod_bookingcal_right" ></span></th>';
        }
        $html .= '</tr>';

        // draw the day names heading
        if ($dayNameLength > 0) {
            $html .= '<tr class="mod_bookingcal_day">';
            $days = self::getDayNames($startDay);
            for ($i = 0; $i < 7; $i ++) {
                $day_name = $days[$i];
                if (function_exists('mb_substr'))
                    $dayShortName = mb_substr($day_name, 0, $dayNameLength, 'UTF-8'); // prefer this if available
                else
                    $dayShortName = substr($day_name, 0, $dayNameLength); // use this if no mbstring library
                $html .= "<th>$dayShortName</th>";
            }
            $html .= '</tr>';
        }

        // draw the days
        $dayTime = gmmktime(5, 0, 0, $month, 1, $year); // GMT of first day of month
        $firstWeekDay = gmstrftime("%w", $dayTime); // 0 = Sunday ... 6 = Saturday
        $firstColumn = ($firstWeekDay + 7 - $startDay) % 7; // column for first day

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $rows = 1;
        $html .= '<tr>';
        if ($firstColumn > 0)
            $html .= '<td colspan="' . $firstColumn . '" class="mod_bookingcal_nonday"></td>'; // days before the first of the month
        $columnCount = $firstColumn;

        for ($day = 1; $day <= $daysInMonth; $day ++) {
            $cls = '';
            $date = mktime(0, 0, 0, $month, $day, $year);
            if ($columnCount == 7) {
                $html .= "</tr>\n<tr>";
                $columnCount = 0;
                $rows ++;
            }
            if (($year == $currentYear) and ($month == $currentMonth) and ($day == $currentDay))
                // highlight today's date
                $cls = 'mod_bookingcal_today';
            else 
            // distinguish here if it's a booked or switchover day
            if (in_array($date, $startDates) && in_array($date, $endDates))
                $cls = 'mod_bookingcal_start_end';
            elseif (in_array($date, $endDates))
                $cls = 'mod_bookingcal_end';
            elseif (in_array($date, $startDates))
                $cls = 'mod_bookingcal_start';
            else
                foreach ($bookings as $booking)
                    if (($date < strtotime($booking->end_date)) and ($date > strtotime($booking->start_date))) {
                        $cls = 'mod_bookingcal_booked';
                        break;
                    }
            if ($cls == '')
                $html .= '<td>' . $day . '</td>';
            else
                $html .= '<td class="' . $cls . '">' . $day . '</td>';
            $columnCount ++;
        }
        $endCols = 7 - $columnCount;
        if ($endCols > 0)
            $html .= '<td colspan="' . $endCols . '" class="mod_bookingcal_nonday"></td>'; // days after the last day of the month
        if ($rows < 6)
            $html .= '</tr><tr><td colspan="7" class="mod_bookingcal_nonday">&nbsp;</td>'; // include empty row to make all calendars 6 rows high
        $html .= "</tr></table>\n";
        return $html;
    }

    static function getCalendarsForAsset($params, $modulePosition, $year, $month, $links)
    {
        self::$modulePosition = $modulePosition;
        return self::getCalendars($params, $year, $month, $links);
    }

    // ---------------------------------------------------------------------------------------------
    // Draw the number of calendars requested in the module parameters
    //
    static function getCalendars($params, $year, $month, $links)
    {
        $bookings = self::getBookings();
        $dayNameLength = self::getDayNamelength($params);
        $startDay = self::getStartDay($params);
        $numMonths = self::getNumMonths($params);
        $tableWidth = self::getFullWidth($params);

        if ($tableWidth) {
            $divWidth = ' style="width:calc(100% - 6px)"';
            $tableWidth = ' style="width:100%"';
        } else {
            $divWidth = '';
            $tableWidth = '';
        }
        $html = '';
        for ($i = 1; $i <= $numMonths; $i ++) {
            $html .= '<div class="mod_bookingcal_inner"' . $divWidth . '>';
            $html .= self::getCalendar($params, $year, $month, $dayNameLength, $startDay, $tableWidth, $links, $bookings);
            $links = ''; // only draw links on first calendar
            $html .= '</div>';
            $month ++;
            if ($month > 12) {
                $month = 1;
                $year ++;
            }
        }
        return $html;
    }

    // ---------------------------------------------------------------------------------------------
    // The forward and backward links call here via the Joomla com_ajax component
    //
    static function getAjax()
    {
        $jinput = JFactory::getApplication()->input;
        $offset = $jinput->get('offset', '0', 'STRING'); // -1 for back one month, or +1 for forward one month
        $year = $jinput->get('year', '0', 'STRING');
        $month = $jinput->get('month', '0', 'STRING');
        $position = $jinput->get('position', null, 'STRING');

        // Calculate the new starting month required
        $startDate = mktime(0, 0, 0, $month + $offset, 1, $year);
        $month = date('m', $startDate);
        $year = date('Y', $startDate);

        // get the module parameters
        $module = JModuleHelper::getModule('mod_booking_calendar');
        $params = new JRegistry($module->params);

        // re-make all the calendars and send them back as the Ajax response
        echo self::getCalendarsForAsset($params, $position, $year, $month, 1);
    }
}

?>