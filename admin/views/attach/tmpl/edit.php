<?php
/*------------------------------------------------------------------------

# Attachment Addon

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2016 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

$addonId    = $this -> state -> get($this -> getName().'.addon_id');
$group      = 'value';

$item   = null;
if($this -> item){
    $item   = $this -> item;
}
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
    .'&addon_view=attach&addon_layout=edit&id='.$this -> item -> id); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="form-horizontal tpArticle">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('JDETAILS', true)); ?>
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('title',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('title',$group);?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('title_attrib',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('title_attrib',$group);?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('file', $group);?></div>
                    <div class="controls"><?php
                        echo $this -> form -> getInput('file', $group);
                        echo $this -> form -> getInput('file_name',$group);
                        echo $this -> loadTemplate('files');?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('published');?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('published');?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('content_id');?></div>
                    <div class="controls"><?php echo $this->form->getInput('content_id');?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('id');?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('id');?></div>
                </div>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="addon_task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
