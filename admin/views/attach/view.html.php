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

use Joomla\CMS\Factory;

class TZ_Portfolio_Plus_Addon_AttachmentViewAttach extends JViewLegacy
{

    protected $state    = null;
    protected $item     = null;
    protected $form     = null;
    protected $file    = null;

    public function display($tpl = null)
    {
        $this->state    = $this->get('State');
        $this->item     = $this->get('Item');
        $this->form     = $this->get('Form');

        if($item = $this -> item){
            if(!empty($item -> value) && isset($item -> value -> file_name)){
                $this -> file  = $item -> value -> file_name;
            }
        }

        $this -> addToolbar();

        return parent::display($tpl);
    }

    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);

        $user		= TZ_Portfolio_PlusUser::getUser();
        $userId		= $user->get('id');
        $isNew		= ($this->item->id == 0);
        $canDo	    = JHelperContent::getActions('com_tz_portfolio_plus');

        // For new records, check the create permission.
        if ($isNew && (count($user->getAuthorisedCategories('com_tz_portfolio_plus', 'core.create')) > 0)) {
            JToolBarHelper::apply('attach.apply');
            JToolBarHelper::save('attach.save');
            JToolBarHelper::save2new('attach.save2new');
            JToolBarHelper::cancel('attach.cancel');
        }else{
            // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
            if ($canDo->get('core.edit') || ($canDo->get('core.edit.own'))) {
                JToolBarHelper::apply('attach.apply');
                JToolBarHelper::save('attach.save');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ($canDo->get('core.create')) {
                    JToolBarHelper::save2new('attach.save2new');
                }
            }

            // If checked out, we can still save
            if ($canDo->get('core.create')) {
                JToolBarHelper::save2copy('attach.save2copy');
            }

            JToolBarHelper::cancel('attach.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}