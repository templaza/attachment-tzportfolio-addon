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

JLoader::import('com_tz_portfolio_plus.controllers.addon_data',JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components');

class TZ_Portfolio_Plus_Addon_AttachmentControllerAttach extends TZ_Portfolio_PlusControllerAddon_Data{

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => false))
    {
        if (empty($name))
        {
            $name = $this->context;
        }

        return parent::getModel($name, $prefix, $config);
    }

    public function download(){
        $app    = JFactory::getApplication();
        $cid    = $this->input->post->get('id', array(), 'array');
        $model  = $this->getModel();
        $table  = $model->getTable();

        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar))
        {
            $urlVar = $key;
        }

        // Get the previous record id (if any) and the current record id.
        $recordId = (int) (count($cid) ? $cid[0] : $this->input->getInt($urlVar));

        if(!$model -> download($recordId)) {
            $addonIdURL		= ($addon_id = $this->input -> getInt('addon_id'))?'&addon_id='.$addon_id:'';
            $view = $this->input->getCmd('view');
            $this->setRedirect(
                JRoute::_('index.php?option=' . $this->option . '&view='.$view
                    .$addonIdURL.'&addon_view=' . $this->view_list. $this->getRedirectToListAppend()
                    , false
                ), $model -> getError(), 'warning'
            );
            return false;
        }
        $app -> close();
        return true;
    }
}