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

JLoader::import('addons.content.attachment.helpers.attachment', COM_TZ_PORTFOLIO_PLUS_PATH_SITE);

class PlgTZ_Portfolio_PlusContentAttachmentModelAttachments extends JModelList
{

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('article', null);
        $this->setState('addon', null);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $article        = $this -> getState('article');
        $addon          = $this -> getState('addon');
        $addon_params   = $addon -> params;

        $db             = $this->getDbo();
        $query          = $db->getQuery(true);

        $query->select('*');
        $query -> select('substring_index( substring_index( value, '
                    . $db->quote('"title":"') . ', -1 ) , '
                    . $db->quote('","') . ', 1 ) AS title');
        $query -> select('substring_index( substring_index( value, '
                    . $db->quote('"title":"') . ', -1 ) , '
                    . $db->quote('"}') . ', 1 ) AS title2');
        $query -> select('CONVERT(substring_index( substring_index( value, '
                    . $db->quote('"hits":') . ', -1 ) , '
                    . $db->quote('}') . ', 1 ),UNSIGNED INTEGER) AS hits');
        $query -> select('CONVERT(substring_index( substring_index( value, '
                    . $db->quote('"hits":') . ', -1 ) , '
                    . $db->quote(',"') . ', 1 ),UNSIGNED INTEGER) AS hits2');

        $query->from($db->quoteName('#__tz_portfolio_plus_addon_data'));
        $query -> where('element='.$db -> quote('attachment'));
        $query -> where('published = 1');


        if($article && isset($article -> id)) {
            $query->where('content_id =' . $article -> id);
        }

        switch($orderby = $addon_params -> get('attachment_orderby', 'rid')){
            default:
            case 'rid':
                $query -> order('id DESC');
                break;
            case 'id':
                $query -> order('id ASC');
                break;
            case 'alpha':
                $query -> order('title ASC');
                $query -> order('title2 ASC');
                break;
            case 'ralpha':
                $query -> order('title DESC');
                $query -> order('title2 DESC');
                break;
            case 'hits':
                $query -> order('hits DESC');
                $query -> order('hits2 DESC');
                break;
            case 'rhits':
                $query -> order('hits ASC');
                $query -> order('hits2 ASC');
                break;
            case 'ordering':
                $query -> order('ordering ASC');
                break;
        }

        return $query;
    }

    public function getItems()
    {
        if ($items = parent::getItems()) {
            $article        = $this -> getState('article');
            foreach ($items as &$item) {
                if ($item->value && is_string($item->value)) {
                    $value = json_decode($item->value);
                    $item->value = $value;
                    $value -> icon  = null;
                    if(isset($value -> file_type) && $value -> file_type){
                        $iconset        = PlgTZ_Portfolio_PlusContentAttachmentHelper::getIconSetByMimeType($value -> file_type);
                        $value -> icon  = $iconset;
                    }
                    if(!isset($value -> hits) || (isset($value -> hits) && !$value -> hits)){
                        $value -> hits  = 0;
                    }

                    if($article && is_object($article)) {
                        $item->link = JRoute::_('index.php?option=com_tz_portfolio_plus&view=addon'
                            . '&addon_id=' . $item->extension_id . '&addon_task=attachment.download&addon_data_id='
                            . $item->id.'_'.JApplicationHelper::getHash($item -> id).'&article_id='.$item -> content_id);
                    }
                }
            }
            return $items;
        }
        return false;
    }
}