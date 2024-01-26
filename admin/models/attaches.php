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

class TZ_Portfolio_Plus_Addon_AttachmentModelAttaches extends TZ_Portfolio_PlusModelAddon_Datas{
    protected $addon_element   = 'attachment';

    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id','a.id',
                'extension_id','a.extension_id',
                'type','a.type',
                'value','a.value',
                'value.title',
                'value.title_attrib',
                'value.file_name',
                'value.file_type',
                'value.file_size',
                'value.hits',
                'ordering',
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'id', $direction = 'desc'){

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $search = $this->getUserStateFromRequest($this->context.'.filter.article', 'filter_article');
        $this->setState('filter.article', $search);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    public function getListQuery(){
        if($addonId = $this -> getState($this -> getName().'.addon_id')){
            $db     = $this -> getDbo();
            $query  = $db -> getQuery(true)
                -> select('a.*')
                -> from($db -> quoteName('#__tz_portfolio_plus_addon_data').' AS a')
                -> where('a.extension_id ='.$addonId);
            if($element = $this -> addon_element){
                $query -> where('a.element ='.$db -> quote($element));
            }

            // Filter by published state
            $published = $this->getState('filter.published');
            if (is_numeric($published)) {
                $query->where('a.published = ' . (int) $published);
            }
            elseif ($published === '') {
                $query->where('(a.published = 0 OR a.published = 1 OR a.published = -1)');
            }

            // Add the list ordering clause.
            $orderCol = $this->getState('list.ordering','a.id');
            $orderDirn = $this->getState('list.direction','desc');

            if(!empty($orderCol) && !empty($orderDirn)){
                if(strpos($orderCol,'value.') !== false) {
                    $fields     = explode('.',$orderCol);
                    $orderCol   = array_pop($fields);
                    $query->order('substring_index(a.value,' . $db->quote('"'.$orderCol.'":') . ',-1) '. $orderDirn);
                }else{
                    $query->order($db->escape($orderCol . ' ' . $orderDirn));
                }
            }

            // Filter by search in title or id.
            $search = $this->getState('filter.search');
            if (!empty($search)) {
                if (stripos($search, 'id:') === 0) {
                    $query->where('a.id = '.(int) substr($search, 3));
                }
                elseif (stripos($search, 'alias:') === 0) {
                    $search = $db->escape(substr($search, 6));
                    $query -> where('(SUBSTRING(substring_index(a.value,' . $db->quote('"alias":"') . ',-1),'
                        .'1,'.strlen($search).') = '.$db -> quote($search).')');
                }
                else {
                    $query -> where('(SUBSTRING(substring_index(a.value,' . $db->quote('"title":"') . ',-1),'
                        .'1,'.strlen($search).') = '.$db -> quote($search).')');
                }
            }

            // Filter by article.
            $content_id = $this->getState('filter.article');
            if (!empty($content_id)) {
                $query -> where('a.content_id = '.$content_id);
            }

            $query -> select('c.title AS content_title');
            $query -> join('LEFT','#__tz_portfolio_plus_content AS c ON c.id = a.content_id');

            return $query;
        }
        return false;
    }


    public function getFilterForm($data = array(), $loadData = true)
    {
        $form = null;
        // Load addon's form
        if($addonId = JFactory::getApplication()->input->getInt('addon_id')){
            // Get a row instance.
            $table = $this->getTable('Extensions','TZ_Portfolio_PlusTable');

            // Attempt to load the row.
            $return = $table->load($addonId);

            // Check for a table object error.
            if ($return === false && $table->getError())
            {
                $this->setError($table->getError());

                return $return;
            }

            $path   = COM_TZ_PORTFOLIO_PLUS_ADDON_PATH.DIRECTORY_SEPARATOR.$table -> folder
                .DIRECTORY_SEPARATOR.$table -> element;

            JForm::addFormPath($path.DIRECTORY_SEPARATOR.'admin/models/form');
            JForm::addFormPath($path.DIRECTORY_SEPARATOR.'admin/models/forms');

            // Try to locate the filter form automatically. Example: ContentModelArticles => "filter_articles"
            if (empty($this->filterFormName))
            {
                $classNameParts = explode('Model', get_called_class());

                if (count($classNameParts) == 2)
                {
                    $this->filterFormName = 'filter_' . strtolower($classNameParts[1]);
                }
            }

            if (!empty($this->filterFormName))
            {
                // Get the form.
                $form = $this->loadForm($this->context . '.filter', $this->filterFormName, array('control' => '', 'load_data' => $loadData));
            }
        }

        return $form;
    }
}