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

//JLoader::import('com_tz_portfolio_plus.addons.attachment.models.attachments', JPATH_SITE);

class PlgTZ_Portfolio_PlusContentAttachment extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;
    protected $data_manager     = true;

//    public function onContentDisplayArticleView($context, &$article, $params, $page = 0, $layout = null){
//        $this -> article        = $article;
//        $this -> trigger_params = $params;
//        return parent::onContentDisplayArticleView($context, $article, $params, $page, $layout);
////        if($this -> _type != 'mediatype'){
////            list($extension, $vName)   = explode('.', $context);
////
////            $item   = $article;
////
////            if($extension == 'module' || $extension == 'modules'){
////                if($path = $this -> getModuleLayout($this -> _type, $this -> _name, $extension, $vName, $layout)){
////                    // Display html
////                    ob_start();
////                    include $path;
////                    $html = ob_get_contents();
////                    ob_end_clean();
////                    $html = trim($html);
////                    return $html;
////                }
////            }else {
////                tzportfolioplusimport('plugin.modelitem');
////
////                if($html = $this -> getViewHtml($context,$article, $params, $layout)){
////                    return $html;
////                }
////            }
////        }
//    }
    public function onContentBeforeDisplay($context, &$article, $params, $page = 0, $layout = 'default') {

        list($extension, $vName)   = explode('.', $context);

        if ($extension != 'com_tz_portfolio_plus' && $extension != 'module' && $extension != 'modules') return '';
        $item   = $article;

        if(isset($item -> attribs) && $item -> attribs) {
            $paramArt = new JRegistry;
            $paramArt->loadString($item->attribs);
        }

        $list   = null;

        if($model  = JModelLegacy::getInstance('Attachments','PlgTZ_Portfolio_PlusContentAttachmentModel'
            , array('ignore_request' => true))){
            $model -> setState('article', $article);
            $model -> setState('addon', $this);
            $list   = $model -> getItems();
        }

        if($extension == 'module' || $extension == 'modules') {

            if($path = $this -> getModuleLayout($this -> _type, $this -> _name, $extension, $vName, $layout)){
                // Display html
                ob_start();
                include $path;
                $html = ob_get_contents();
                ob_end_clean();
                $html = trim($html);

                return $html;

            }
        }

//        elseif(in_array($context, array('com_tz_portfolio_plus.portfolio',
//            'com_tz_portfolio_plus.date', 'com_tz_portfolio_plus.featured'
//            , 'com_tz_portfolio_plus.tags', 'com_tz_portfolio_plus.users'))) {
//            if($html = $this -> _getViewHtml($context,$item, $params, $layout)) {
//                return $html;
//            }
//        }

    }


    public function onRenderAddonView(){

        tzportfolioplusimport('plugin.modelitem');

        $input      = JFactory::getApplication() -> input;

        if($controller = TZ_Portfolio_PlusPluginHelper::getAddonController($input -> get('addon_id'))){
            $task       = $input->get('addon_task');
            $controller -> execute($task);
            $controller -> redirect();
        }
    }

//    protected function getViewHtml($context, &$article, $params, $layout = null){
//        list($extension, $vName)   = explode('.', $context);
//
//        $input      = JFactory::getApplication()->input;
//        $addon_id   = $input -> getInt('addon_id');
//        $addon      = TZ_Portfolio_PlusPluginHelper::getPlugin($this -> _type, $this -> _name);
//
//        if(!$addon_id || ($addon_id && $addon_id == $addon -> id)){
//            tzportfolioplusimport('controller.legacy');
//            $result = true;
//            // Check task with format: addon_name.addon_view.addon_task (example image.default.display);
//            if($result && $controller = TZ_Portfolio_Plus_AddOnControllerLegacy::getInstance('PlgTZ_Portfolio_Plus'
//                    .ucfirst($this -> _type).ucfirst($this -> _name)
//                    , array('base_path' => COM_TZ_PORTFOLIO_PLUS_ADDON_PATH
//                        .DIRECTORY_SEPARATOR.$this -> _type
//                        .DIRECTORY_SEPARATOR.$this -> _name))) {
//                tzportfolioplusimport('plugin.modelitem');
//
//                $controller -> set('addon', $addon);
//                $controller -> set('article', $article);
//                $controller -> set('trigger_params', $params);
//
//                $task   = $input->get('addon_task');
//
//                if(!$task && !$addon_id) {
//                    $input->set('addon_view', $vName);
//                    $input->set('addon_layout', 'default');
//                    if($layout) {
//                        $input->set('addon_layout', $layout);
//                    }
//                }
//
//                $html   = null;
//                try {
//                    ob_start();
//                    $controller->execute($task);
//                    $controller->redirect();
//                    $html = ob_get_contents();
//                    ob_end_clean();
//                }catch (Exception $e){
//                    if($e -> getMessage()) {
//                        JFactory::getApplication() ->enqueueMessage('Addon '.$this -> _name.': '.$e -> getMessage(), 'warning');
//                    }
//                }
//
//                if($html){
//                    $html   = trim($html);
//                }
//                $input -> set('addon_task', null);
//                return $html;
//            }
//        }
//    }

    public function onContentAfterSave($context, $data, $isnew){}
    public function onContentAfterDelete($context, $table){}
}