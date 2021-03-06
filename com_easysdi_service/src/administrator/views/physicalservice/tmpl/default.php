<?php
/**
 * @version     4.4.5
 * @package     com_easysdi_service
 * @copyright   Copyright (C) 2013-2017. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      EasySDI Community <contact@easysdi.org> - http://www.easysdi.org
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_easysdi_service/assets/css/easysdi_service.css?v=' . sdiFactory::getSdiFullVersion());
$document->addScript('components/com_easysdi_service/views/physicalservice/tmpl/physicalservice.js?v=' . sdiFactory::getSdiFullVersion());
JText::script('JGLOBAL_VALIDATION_FORM_FAILED');
JText::script('COM_EASYSDI_SERVICE_FORM_DESC_SERVICE_NEGOTIATION_ERROR');
?>

<form action="<?php echo JRoute::_('index.php?option=com_easysdi_service&view=physicalservice&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="physicalservice-form" class="form-validate">
    <div id="progress">
        <img id="progress_image"  src="components/com_easysdi_service/assets/images/loader.gif" alt="">
    </div>
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_EASYSDI_SERVICE_TAB_NEW_SERVICE') : JText::sprintf('COM_EASYSDI_SERVICE_TAB_EDIT_SERVICE', $this->item->id); ?></a></li>
                <?php if (count($this->item->currentserviceauthenticationconnectorlist) > 1): ?>
                    <li><a href="#provider" data-toggle="tab"><?php echo JText::_('COM_EASYSDI_SERVICE_TAB_PROVIDER'); ?></a></li>
                <?php endif ?>
                <li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_EASYSDI_SERVICE_TAB_PUBLISHING'); ?></a></li>
                <?php if ($this->canDo->get('core.admin')): ?>
                    <li><a href="#permissions" data-toggle="tab"><?php echo JText::_('COM_EASYSDI_SERVICE_TAB_RULES'); ?></a></li>
                <?php endif ?>
            </ul>

            <div class="tab-content">
                <!-- Begin Tabs -->
                <div class="tab-pane active" id="details">
                    <?php
                    foreach ($this->form->getFieldset('details') as $field):
                        if ($field->fieldname == 'resourceauthentication_id') {
                            ?>
                            <div class="control-group">
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls"><?php echo JHTML::_("select.genericlist", $this->item->currentresourceauthenticationconnectorlist, 'jform[resourceauthentication_id]', 'size="1 class="inputbox"" ', 'id', 'value', $this->item->resourceauthentication_id); ?></div>
                            </div>
                            <?php
                        } else if ($field->fieldname == 'supportedversions') {
                            ?>
                            <div class="control-group">
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="row controls form-inline"><?php echo $this->form->getInput('negotiation'); ?><?php echo $field->input; ?></div>
                            </div>
                            <?php
                        } else if($field->fieldname == 'server_id' ){
                            if($this->item->serviceconnector == "WMS"){
                            ?>
                            <div class="control-group" id="<?php echo $field->fieldname; ?>">
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls"><?php echo $field->input; ?></div>
                            </div>
                            <?php }
                        }else {
                            ?>
                            <div class="control-group" id="<?php echo $field->fieldname; ?>">
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls"><?php echo $field->input; ?></div>
                            </div>
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
                <?php if (count($this->item->currentserviceauthenticationconnectorlist) > 1): ?>
                    <div class="tab-pane" id="provider">
                        <?php
                        foreach ($this->form->getFieldset('provideroptions') as $field):
                            if ($field->fieldname == 'serviceauthentication_id') {
                                ?>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $field->label; ?></div>
                                    <div class="controls"><?php echo JHTML::_("select.genericlist", $this->item->currentserviceauthenticationconnectorlist, 'jform[serviceauthentication_id]', 'size="1" ', 'id', 'value', $this->item->serviceauthentication_id); ?></div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $field->label; ?></div>
                                    <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            <?php } ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="tab-pane" id="publishing">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('created'); ?></div>
                    </div>
                    <?php if ($this->item->modified_by) : ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($this->canDo->get('core.admin')): ?>
                    <div class="tab-pane" id="permissions">
                        <fieldset>
                            <?php echo $this->form->getInput('rules'); ?>
                        </fieldset>
                    </div>
                <?php endif; ?>
            </div>
            <!-- End Tabs -->
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

        <!-- Begin Sidebar -->
        <div class="span2">
            <h4><?php echo JText::_('JDETAILS'); ?></h4>
            <hr />
            <fieldset class="form-vertical">
                <div class="control-group">
                    <div class="control-group">
                        <div class="controls">
                            <?php echo $this->form->getValue('name'); ?>
                        </div>
                    </div>
                    <?php
                    if ($this->canDo->get('core.edit.state')) {
                        ?>
                        <div class="control-label">
                            <?php echo $this->form->getLabel('state'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('state'); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('access'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('access'); ?>
                    </div>
                </div>
            </fieldset>
        </div>
        <!-- End Sidebar -->
    </div>
</form>