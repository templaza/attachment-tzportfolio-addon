<?php
/*------------------------------------------------------------------------

# Music Addon

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

jimport('joomla.filesystem.file');

class TZ_Portfolio_Plus_Addon_AttachmentModelAttach extends TZ_Portfolio_PlusModelAddon_Data{
    protected $addon_element    = 'attachment';
    protected $fpath            = null;
    protected $__state_set     = null;

    public function __construct(array $config)
    {
        $this -> fpath  = 'media'.DIRECTORY_SEPARATOR.'tz_portfolio_plus'.DIRECTORY_SEPARATOR.'attachment';
        parent::__construct($config);
    }

    protected function populateState()
    {
        $plugin     = TZ_Portfolio_PlusPluginHelper::getPlugin('content','attachment');
        $plgParams  = new Registry;
        if(is_string($plugin -> params)){
            $plgParams->loadString($plugin->params);
        }else{
            $plgParams  = $plugin -> params;
        }
        $this -> setState('addonParams', $plgParams);

        if($fpath = $plgParams -> get('attachment_folder')){
            $this -> fpath  = $fpath;
        }

        parent::populateState();
    }

    public function getItem($pk = null){
        if($item = parent::getItem($pk)){
            if(is_string($item -> value)){
                $item -> value  = json_decode($item -> value);
            }
            if(isset($item -> value -> file_name) && !empty($item -> value -> file_name)
                && strpos('|', $item -> value -> file_name)){
                $item -> value -> file_name  = explode('|', $item -> value -> file_name);
            }
            return $item;
        }
        return false;
    }

    public function save($data)
    {
        if(isset($data['value']) && $data['value']){
            $input      = JFactory::getApplication() -> input;

            if($files  = $input -> post ->files) {
                if($files = $files->get('jform')) {
                    $files  = $files['value']['file'];

                    // Upload and return file information
                    if($files && isset($files['name']) && !empty($files['name'])){
                        if($newFiles = $this->upload($files)){

                            // Remove file before upload new file
                            if(isset($files['name']) && !empty($files['name'])
                                && isset($data['value']['file_name']) && !empty($data['value']['file_name'])){
                                $curFile    = $data['value']['file_name'];
                                $curPath    = JPATH_SITE.DIRECTORY_SEPARATOR.$this -> fpath.DIRECTORY_SEPARATOR.$curFile;
                                if(JFile::exists($curPath)){
                                    JFile::delete($curPath);
                                }
                            }

                            $data['value']['file_name']    = $newFiles['name'];
                            $data['value']['file_type']    = $newFiles['type'];
                            $data['value']['file_size']    = $newFiles['size'];
                            if(!isset($data['value']['title_attrib']) ||
                                (isset($data['value']['title_attrib']) && empty($data['value']['title_attrib']))){
                                $data['value']['title_attrib']  = $newFiles['name'];
                            }
                            if(!isset($data['value']['hits']) ||
                                (isset($data['value']['hits']) && empty($data['value']['hits']))){
                                $data['value']['hits']  = 0;
                            }
                        }else{
                            return false;
                        }
                    }
                }
            }
        }

        return parent::save($data);
    }

    protected function upload($files, $newFileName = null){
        if($files){
            $app        = JFactory::getApplication();
            $plgParams  = $this -> getState('addonParams', new Registry());

            if(is_array($files)){
                // Get some params
                $file_types     = $plgParams -> get('file_type','bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,'
                    .'ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS');
                $file_types     = explode(',',$file_types);
                $file_sizes     = $plgParams -> get('file_size',10);
                $file_sizes     = $file_sizes * 1024 * 1024;

                $file_names     = array();

                if(isset($files['name']) && !empty($files['name'])){
                    $file_type = JFile::getExt($files['name']);

                    //-- Check image information --//
                    // Check file type
                    if (!in_array($file_type, $file_types)) {
                        $this -> setError(JText::_('PLG_CONTENT_ATTACHMENT_ERROR_WARNFILETYPE'));
                        return false;
                    }

                    // Check file size
                    if ($files['size'] > $file_sizes) {
                        $this -> setError(JText::_('PLG_CONTENT_ATTACHMENT_ERROR_WARNFILETOOLARGE'));
                        return false;
                    }
                    //-- End check image information --//

                    $folder     = $this -> fpath;
                    if(!JFile::exists(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'index.html')){
                        $html   = htmlspecialchars_decode('<!DOCTYPE html><title></title>');
                        JFile::write(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'index.html', $html);
                    }

                    if(!$newFileName){
                        $newFileName    = $files['name'];
                    }else{
                        $newFileName    .=  '.' . $file_type;
                    }
                    $newFileName    = JFile::stripExt($newFileName);
                    $newFileName    = JApplicationHelper::stringURLSafe($newFileName);
                    $newFileName   .= '.'.$file_type;

                    $newpath    = $folder. DIRECTORY_SEPARATOR . $newFileName;

                    // Create new file if current file exists
                    $bakFileName    = $newFileName;
                    $i              = 1;
                    while(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR .$newpath)){
                        $newFileName    = JFile::stripExt($bakFileName).'_'.$i.'.'.JFile::getExt($bakFileName);
                        $newpath        = $folder. DIRECTORY_SEPARATOR . $newFileName;
                        $i++;
                    }

                    if (!JFile::upload($files['tmp_name'], JPATH_SITE . DIRECTORY_SEPARATOR . $newpath)) {
//                        $app -> enqueueMessage(JText::_('PLG_CONTENT_ATTACHMENT_ERROR_WARNFILETOOLARGE'), 'notice');
                        $this -> setError(JText::_('PLG_CONTENT_ATTACHMENT_ERROR_WARNFILETOOLARGE'));
                        return false;
                    }

                    $file_names['name'] = $newFileName;
                    $file_names['type'] = $files['type'];
                    $file_size          = $files['size'];
                    $file_names['size'] = round($file_size / 1024,2);
                }

                if(count($file_names)) {
                    return $file_names;
                }
            }
        }
        return false;
    }

    public function delete(&$pks)
    {
        // Get files
        $table  = $this -> getTable();
        foreach ($pks as $i => $pk)
        {
            if ($table->load($pk))
            {
                $fInfo  = $table -> value;
                if(is_string($fInfo)){
                    $fInfo  = json_decode($fInfo);
                    if(!empty($fInfo)) {
                        $path = $this -> fpath;
                        $file[] = JPATH_ROOT . DIRECTORY_SEPARATOR.$path . DIRECTORY_SEPARATOR . $fInfo -> file_name;
                    }
                }
            }
        }
        if(parent::delete($pks)){
            // Delete files
            if(!JFile::delete($file)){
                $this -> setError(JText::_('PLG_CONTENT_ATTACHMENT_ERROR_DELETE_FILE'));
            }
            return true;
        }
        return false;
    }

    public function download($key, $hash = null){
        $app    = JFactory::getApplication();
        $check  = JApplicationHelper::getHash($key);
        if ($hash && $check != $hash)
        {
            JError::raiseError(404, JText::_('PLG_CONTENT_ATTACHMENT_NOT_FOUND'));
            return false;
        }
        $table  = $this -> getTable();
        if($table -> load($key)) {
            $fInfo  = $table -> value;
            if(is_string($fInfo)){
                $fInfo  = json_decode($fInfo);
            }

            if(!empty($fInfo)) {
                $path = $this -> fpath;
                $file = JPATH_ROOT . DIRECTORY_SEPARATOR.$path . DIRECTORY_SEPARATOR . $fInfo -> file_name;
                if (JFile::exists($file))
                {
                    $len = filesize($file);
                    ob_end_clean();

                    $app -> clearHeaders();
                    $app -> setHeader('Pragma', 'public', true);
                    $app -> setHeader('Expires', '0', true);
                    $app -> setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
                    $app -> setHeader('Content-Type', $fInfo->file_type, true);
                    $app -> setHeader('Content-Disposition', 'attachment; filename='.$fInfo -> file_name.';', true);
                    $app -> setHeader('Content-Transfer-Encoding', 'binary', true);
                    $app -> setHeader('Content-Length', $len, true);
                    $app -> sendHeaders();

                    echo @file_get_contents($file);

//                    echo JFile::read($file);

                }
                else
                {
                    $this -> setError(JText::_('PLG_CONTENT_ATTACHMENT_FILE_DOES_NOT_EXIST'));
                    return false;
                }
            }
        }
        return true;
    }

}