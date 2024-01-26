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

class PlgTZ_Portfolio_PlusContentAttachmentModelAttachment extends JModelItem
{

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();
        $input = $app->input;

        $this->setState('filter.contentid', null);
        $this->setState('filter.article', null);
        $this->setState('filter.addon_data_id', null);

        parent::populateState($ordering, $direction);
    }

    public function getTable($type = 'Addon_Data', $prefix = 'TZ_Portfolio_PlusTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function hit($pk = null){
        $table  = $this -> getTable();
        $key = $table->getKeyName();
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

        try {
            if ($pk > 0) {
                if($table->load($pk)) {

                    // Plus hit with 1
                    if($value = $table -> value){
                        if(is_string($value)){
                            $value  = json_decode($value);
                            if(isset($value -> hits)) {
                                $value->hits += 1;
                            }else{
                                $value -> hits  = 0;
                            }
                            $table -> value = json_encode($value);
                        }
                    }

                    // Store the data.
                    if (!$table->store()) {
                        $this->setError($table->getError());

                        return false;
                    }

                    // Clean the cache.
                    $this->cleanCache();
                }

            }
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }

    }

    public function getItem($pk = null){
        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('*');
        $query->from($db->quoteName('#__tz_portfolio_plus_addon_data'));
        $query -> where('element='.$db -> quote('attachment'));
        if($contentid = $this -> getState('filter.contentid')) {
            $query->where('content_id =' . $this->getState('filter.contentid'));
        }
        if($extension_id = $this -> getState('filter.addon_id')) {
            $query->where('extension_id =' . $this->getState('filter.addon_id'));
        }
        if($addon_data_id = $this -> getState('filter.addon_data_id')) {
            $query->where('id =' . $this->getState('filter.addon_data_id'));
        }
        $query->where('published = 1');

        $db -> setQuery($query);
        if($item = $db -> loadObject()){
            if(isset($item -> value) && is_string($item -> value)){
                $item -> value  = json_decode($item -> value);
            }
            return $item;
        }

        return false;
    }
}