<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bookong
 *
 * @copyright   Copyright (C) 2019 - 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<form action="index.php?option=com_booking&view=bookings" method="post" id="adminForm" name="adminForm">
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="5%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="15%">
				<?php echo JText::_('COM_BOOKING_BOOKINGS_NAME') ;?>
			</th>
			<th width="15%">
				<?php echo JHTML::_( 'grid.sort', 'COM_BOOKING_START_DATE', 'start_date', $this->sortDirection, $this->sortColumn); ?>
			</th>
			<th width="15%">
				<?php echo JHTML::_( 'grid.sort', 'COM_BOOKING_END_DATE', 'end_date', $this->sortDirection, $this->sortColumn); ?>
			</th>
			<th width="30%">
				<?php echo JText::_('COM_BOOKING_REMARKS'); ?>
			</th>
			<th width="10%">
				<?php echo JHTML::_( 'grid.sort', 'COM_BOOKING_PRICE', 'price', $this->sortDirection, $this->sortColumn); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->bookings)) : ?>
				<?php foreach ($this->bookings as $i => $booking) : ?>
					<tr>
						<td>
							<?php echo JHtml::_('grid.id', $i, $booking->id); ?>
						</td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_booking&view=booking&layout=edit&id=' . $booking->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
							<?php echo $this->escape($booking->name); ?></a>
						</td>
						<td>
							<?php echo JHTML::_('date', $booking->start_date, JTEXT::_('Y/m/d')); ?>
						</td>
						<td>
							<?php echo JHTML::_('date', $booking->end_date, JTEXT::_('Y/m/d')); ?>
						</td>
						<td>
							<?php echo $booking->remarks; ?>
						</td>
						<td>
							<?php echo $booking->price; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
	<input type="hidden" name="task" value=""/>
 	<input type="hidden" name="boxchecked" value="0"/>
 	<?php echo JHtml::_('form.token'); ?>
</form>