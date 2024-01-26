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

class TZ_Portfolio_Plus_Addon_AttachmentViewAttaches extends JViewLegacy
{

    protected $state        = null;
    protected $items        = null;
    protected $pagination   = null;

    public function display($tpl = null)
    {
        $this->state        = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('pagination');
        $this->filterForm    = $this->get('FilterForm');

        $this -> addToolbar();

        return parent::display($tpl);
    }
    protected function addToolbar(){

        $user       = TZ_Portfolio_PlusUser::getUser();
        $addonId    = $this -> state -> get($this -> getName().'.addon_id');
        $canDo	    = TZ_Portfolio_PlusHelperAddon_Datas::getActions( $addonId, 'addon','addon');

        if ($canDo -> get('tzportfolioplus.create') ) {
            JToolBarHelper::addNew('attach.add');
        }

        if($canDo -> get('tzportfolioplus.edit')) {
            JToolBarHelper::editList('attach.edit');
        }

        if($canDo -> get('tzportfolioplus.edit.state')) {
            JToolBarHelper::publish('attaches.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('attaches.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('tzportfolioplus.delete')) {
            JToolBarHelper::deleteList('', 'attaches.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('tzportfolioplus.edit.state')) {
            JToolBarHelper::trash('attaches.trash');
        }

        if ($user->authorise('core.admin', 'com_tz_portfolio_plus.addon.'.$addonId)
            || $user->authorise('core.options', 'com_tz_portfolio_plus.addon.'.$addonId))
        {
            TZ_Portfolio_PlusToolbarHelper::preferencesAddon($addonId);
        }
    }
}