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

JHtml::addIncludePath(COM_TZ_PORTFOLIO_PLUS_ADMIN_HELPERS_PATH.DIRECTORY_SEPARATOR.'html');

JHtml::_('bootstrap.tooltip');

$user		= TZ_Portfolio_PlusUser::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'ordering';
$addonId    = $this -> state -> get($this -> getName().'.addon_id');

if ($saveOrder)
{
    $saveOrderingUrl = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
        .'&addon_task=attaches.saveOrderAjax&tmpl=component';
    JHtml::_('tzsortablelist.sortable', 'addonDataList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)); ?>"
      method="post" name="adminForm" id="adminForm">
    <?php
    if(!empty($this -> sidebar)){
    ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this -> sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php }else{?>
    <div id="j-main-container">
    <?php }?>
        <div class="tpContainer">

            <?php
            // Search tools bar
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>

            <table class="table table-striped"  id="addonDataList">
                <thead>
                <tr>
                    <th width="1%" class="nowrap center">
                        <?php echo JHtml::_('searchtools.sort', '', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                    </th>
                    <th width="1%" class="hidden-phone">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="1%" style="min-width:55px" class="nowrap center">
                        <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('searchtools.sort','PLG_CONTENT_ATTACHMENT_FILE_NAME','value.file_name',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('searchtools.sort','JGLOBAL_TITLE','value.title',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('searchtools.sort','PLG_CONTENT_ATTACHMENT_TITLE_ATTRIBUTE_LABEL','value.title_attrib',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('searchtools.sort','PLG_CONTENT_ATTACHMENT_FILE_TYPE','value.file_type',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap" style="width: 7%; text-align: right;">
                        <?php echo JHtml::_('searchtools.sort','PLG_CONTENT_ATTACHMENT_FILE_SIZE_KB','value.file_size',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap" width="5%">
                        <?php echo JHtml::_('searchtools.sort','PLG_CONTENT_ATTACHMENT_DOWNLOADS','value.hits',$listDirn,$listOrder);?>
                    </th>
                    <th class="nowrap" width="1%">
                        <?php echo JHtml::_('searchtools.sort','JGRID_HEADING_ID','id',$listDirn,$listOrder);?>
                    </th>
                </tr>
                </thead>
                <?php if($items = $this -> items):?>
                    <tbody>
                    <?php foreach($items as $i => $data):
                        $canEdit    = $user->authorise('tzportfolioplus.edit',      'com_tz_portfolio_plus.addon.'.$addonId);
                        $canCheckin = $user->authorise('core.manage',     'com_checkin')
                            || (isset($data->checked_out) && ($data->checked_out == $user -> id || $data -> checked_out == 0));
                        $canChange  = $user->authorise('tzportfolioplus.edit.state',
                                'com_tz_portfolio_plus.addon.'.$addonId) && $canCheckin;
                        $item   = $data -> value;
                        ?>
                        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $data -> content_id;?>">
                            <td class="order nowrap center hidden-phone">
                                <?php
                                $iconClass = '';
                                if (!$canChange)
                                {
                                    $iconClass = ' inactive';
                                }
                                elseif (!$saveOrder)
                                {
                                    $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                                }
                                ?>
                                <span class="sortable-handler<?php echo $iconClass ?>">
                                    <span class="icon-menu"></span>
                                </span>
                                <?php if ($canChange && $saveOrder) : ?>
                                    <input type="text" style="display:none" name="order[]" size="5"
                                           value="<?php echo $data->ordering;?>" class="width-20 text-area-order " />
                                <?php endif; ?>
                            </td>
                            <td class="center">
                                <?php echo JHtml::_('grid.id', $i, $data->id, false, 'cid'); ?>
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <?php echo JHtml::_('jgrid.published', $data->published, $i, 'attaches.', $canChange, 'cb'); ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($canEdit) : ?>
                                    <a href="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
                                        .'&addon_task=attach.edit&id=' . (int) $data->id); ?>">
                                        <?php echo $this->escape($item->file_name); ?></a>
                                <?php else : ?>
                                    <?php echo $this->escape($item->file_name); ?>
                                <?php endif; ?>
                                <a class="btn btn-info btn-small hasTooltip" data-toggle="tooltip"
                                   title="<?php echo JText::_('PLG_CONTENT_ATTACHMENT_DOWNLOAD');?>"
                                    href="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
                                    .'&addon_task=attach.download&id=' . ((int) $data->id)
                                        .'_'.JApplicationHelper::getHash($data -> id)); ?>"><span class="icon-download"></span></a>
                                <?php if($data -> content_id){ ?>
                                <div class="small">
                                    <?php echo JText::sprintf('PLG_CONTENT_ATTACHMENT_ARTICLE', '');?>
                                    <a href="index.php?option=com_tz_portfolio_plus&task=article.edit&id=<?php
                                    echo $data -> content_id;?>"><?php echo $data -> content_title;?></a>
                                </div>
                                <?php } ?>
                            </td>
                            <td><?php echo $this->escape($item->title); ?></td>
                            <td><?php echo $this->escape($item->title_attrib); ?></td>
                            <td><?php echo isset($item -> file_type)?$this->escape($item->file_type):''; ?></td>
                            <td style="text-align: right;"><?php echo isset($item -> file_size)?$this->escape($item->file_size):''; ?></td>
                            <td style="text-align: center;"><?php echo (isset($item->hits))?$item->hits:0; ?></td>
                            <td align="center hidden-phone"><?php echo $data -> id;?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                <?php endif;?>

                <tfoot>
                <tr>
                    <td colspan="11">
                        <?php echo $this -> pagination -> getListFooter();?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="addon_task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>