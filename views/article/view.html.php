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

class PlgTZ_Portfolio_PlusContentAttachmentViewArticle extends JViewLegacy{

    protected $state        = null;
    protected $attachments  = null;
    protected $params       = null;

    public function display($tpl = null)
    {
        $state                  = $this -> get('State');
        $this -> state          = $state;
        $this -> params         = $state -> get('params');
        $this -> attachments    = $this -> get('Attachments');
        if($addon  = $state -> get('article.addon')){
            if(isset($addon -> params) && $addon -> params && !is_string($addon -> params)){
                $addonParams    = $addon -> params;
                if($addonParams -> get('attachment_enable_awesome_font', 1)){
                    $this -> document -> addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/attachment/css/font-awesome.min.css');
                }
            }
        }
        parent::display($tpl);
    }
}