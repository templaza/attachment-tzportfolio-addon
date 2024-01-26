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

use Joomla\Registry\Registry;

class PlgTZ_Portfolio_PlusContentAttachmentControllerAttachment extends TZ_Portfolio_PlusControllerLegacy
{
    public function getModel($name = 'attachment', $prefix = 'PlgTZ_Portfolio_PlusContentAttachmentModel',
                             $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
    public function download(){
        $app = JFactory::getApplication();

        $addon_id       = $this -> input -> getInt('addon_id');
        $token          = $this -> input -> getString('addon_data_id');
        $addon_data_id  = $this -> input -> getInt('addon_data_id');
        $article_id     = $this -> input -> getInt('article_id');

        JLoader::import('com_tz_portfolio_plus.helpers.article', JPATH_ROOT.'/components');
        if($article   = TZ_Portfolio_PlusContentHelper::getArticleById($article_id)){
            $article -> link    = TZ_Portfolio_PlusHelperRoute::getArticleRoute($article_id, $article -> catid);
        }

        // Check addon_data_id token
        $check = substr($token, strpos($token, '_') + 1);
        $hash = JApplicationHelper::getHash($addon_data_id);

        if ($check != $hash)
        {
            $this -> setRedirect($article -> link, JText::_('PLG_CONTENT_ATTACHMENT_NOT_FOUND'), 'warning');
            return false;
        }

        if($model  = $this -> getModel()){

            if(isset($article) && $article) {
                $model->setState('filter.contentid', $article->id);
                $model->setState('filter.article', $article);
            }
            $model -> setState('filter.addon_id', $addon_id);
            $model -> setState('filter.addon_data_id', $addon_data_id);

            if($item = $model -> getItem()){
                $plugin     = TZ_Portfolio_PlusPluginHelper::getPlugin('content','attachment');
                $folder     = 'media'.DIRECTORY_SEPARATOR.'tz_portfolio_plus'.DIRECTORY_SEPARATOR.'attachment';
                if($_plgparams  = $plugin -> params){
                    if(is_string($_plgparams)){
                        $plgparams = new Registry;
                        $plgparams -> loadString($_plgparams);
                        if($fpath = $plgparams -> get('attachment_folder')){
                            $folder = $fpath;
                        }
                    }
                }

                $folder = JPATH_SITE.DIRECTORY_SEPARATOR.$folder;
                $file   = $folder.DIRECTORY_SEPARATOR.$item -> value -> file_name;

                if(JFile::exists($file)) {
                    // Add hit
                    $model -> hit($addon_data_id);
                    ob_end_clean();

                    $app -> clearHeaders();
                    $app->setHeader('Pragma', 'public', true);
                    $app->setHeader('Expires', '0', true);
                    $app->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
                    $app->setHeader('Content-Type', $item->value->file_type, true);
                    $app->setHeader('Content-Disposition', 'attachment; filename=' . basename($item->value->file_name) . ';', true);
                    $app->setHeader('Content-Transfer-Encoding', 'binary', true);
                    $app->setHeader('Content-Length', filesize($file), true);
                    $app->sendHeaders();

                    echo @file_get_contents($file);

                    $app -> close();
                }
            }
        }
    }
}