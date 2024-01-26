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

class PlgTZ_Portfolio_PlusContentAttachmentModelArticle extends TZ_Portfolio_PlusPluginModelItem
{
    public function getAttachments(){
        if($model  = JModelLegacy::getInstance('Attachments','PlgTZ_Portfolio_PlusContentAttachmentModel',
            array('ignore_request' => true))) {

            $model->setState('article', $this->article);
            $model->setState('addon', $this->addon);

            return $model -> getItems();
        }
        return false;
    }
}