<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_booking
 *
 * @copyright   Copyright (C) 2019 - 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

?>
<form action="<?php echo JRoute::_('index.php?option=com_booking&view=booking&layout=edit&id=' . (int) $this->booking->id); ?>"
    method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_BOOKING_BOOKING_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php 
                        foreach($this->form->getFieldset() as $field) {
                            echo $field->renderField();        
                        }
                    ?>
                </div>
            </div>
        </fieldset>
    </div>
    
    <input type="hidden" name="task" value="booking.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>