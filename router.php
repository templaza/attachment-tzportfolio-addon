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

class PlgTZ_Portfolio_PlusContentAttachmentRouter extends TZ_Portfolio_PlusRouter{

    public function build(&$query)
    {
        $segments   = array();
        if(isset($query['addon_id'])){
            if(isset($query['addon_task'])){
                $task   = $query['addon_task'];
                $task   = str_replace('.','_',$task);
                $segments[] = $task;
                unset($query['addon_task']);
            }
            if(isset($query['addon_data_id'])){
                $segments[] = $query['addon_data_id'];
                unset($query['addon_data_id']);
            }
        }
        return $segments;
    }
    public function parse(&$segments)
    {
        $vars                   = array();
        $vars['addon_id']       = $segments[0];
        $s1                     = $segments[1];
        $vars['addon_task']     = str_replace('_','.',$s1);
        $vars['addon_data_id']  = $segments[count($segments) - 1];

        return $vars;
    }
}