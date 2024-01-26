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

if($controller = TZ_Portfolio_Plus_AddOnControllerLegacy::getInstance('TZ_Portfolio_Plus_AddOn_Attachment'
    , array('base_path' => COM_TZ_PORTFOLIO_PLUS_ADDON_PATH
        .DIRECTORY_SEPARATOR.'content'
        .DIRECTORY_SEPARATOR.'attachment'.DIRECTORY_SEPARATOR.'admin'))) {
    $task   = JFactory::getApplication()->input->get('addon_task');
    $controller->execute($task);
    $controller->redirect();
}