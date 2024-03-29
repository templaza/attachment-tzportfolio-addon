<?php
/*------------------------------------------------------------------------

# Custom Addon

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

JLoader::import('com_tz_portfolio_plus.controllers.addon_datas',JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components');

class TZ_Portfolio_Plus_Addon_AttachmentControllerAttaches extends TZ_Portfolio_PlusControllerAddon_Datas{
    protected $text_prefix = 'PLG_CONTENT_ATTACHMENT';

    public function getModel($name = 'attach', $prefix = 'TZ_Portfolio_Plus_Addon_AttachmentModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}