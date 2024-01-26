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

jimport('joomla.filesystem.file');

class PlgTZ_Portfolio_PlusContentAttachmentHelper{
    protected static $attachments_icon_from_mime_type =
        Array( 'application/bzip2' => 'fa fa-file-archive-o',
            'application/excel' => 'fa fa-file-excel-o',
            'application/msword' => 'fa fa-file-word-o',
            'application/pdf' => 'fa  fa-file-pdf-o',
            'application/postscript' => 'fa fa-file-o',
            'application/powerpoint' => 'fa fa-file-powerpoint-o',
            'application/vnd.ms-cab-compressed' => 'fa fa-file-zip-o',
            'application/vnd.ms-excel' => 'fa fa-file-excel-o',
            'application/vnd.ms-powerpoint' => 'fa fa-file-powerpoint-o',
            'application/vnd.ms-pps' => 'fa fa-file-powerpoint-o',
            'application/vnd.ms-word' => 'fa fa-file-word-o',
            'application/vnd.oasis.opendocument.graphics' => 'fa fa-file-o',
            'application/vnd.oasis.opendocument.presentation' => 'fa fa-file-o',
            'application/vnd.oasis.opendocument.spreadsheet' => 'fa fa-file-o',
            'application/vnd.oasis.opendocument.text' => 'fa fa-file-o',
            'application/vnd.openxmlformats' => 'fa fa-file-code-o',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fa fa-file-powerpoint-o',
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow' => 'fa fa-file-powerpoint-o',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa fa-file-excel-o',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa fa-file-word-o',
            'application/x-bz2' => 'fa fa-file-archive-o',
            'application/x-gzip' => 'fa fa-file-archive-o',
            'application/x-javascript' => 'fa fa-file-o',
            'application/x-midi' => 'fa fa-file-o',
            'application/x-shockwave-flash' => 'fa fa-file-o',
            'application/x-rar-compressed' => 'fa fa-file-archive-o',
            'application/x-tar' => 'fa fa-file-archive-o',
            'application/x-vrml' => 'fa fa-file-o',
            'application/zip' => 'fa fa-file-zip-o',
            'application/octet-stream' => 'fa fa-file-zip-o',
            'application/x-zip' => 'fa fa-file-zip-o',
            'application/xml' => 'fa fa-file-code-o',
            'audio/mpeg' => 'fa fa-music',
            'audio/x-aiff' => 'fa fa-music',
            'audio/x-ms-wma' => 'fa fa-music',
            'audio/x-pn-realaudio' => 'fa fa-file-audio-o',
            'audio/x-wav' => 'fa fa-file-audio-o',
            'image/bmp' => 'fa fa-file-image-o',
            'image/gif' => 'fa fa-file-image-o',
            'image/jpeg' => 'fa fa-file-image-o',
            'image/png' => 'fa fa-file-image-o',
            'model/vrml' => 'fa fa-file-o',
            'text/css' => 'fa fa-text-o',
            'text/html' => 'fa fa-text-o',
            'text/plain' => 'fa fa-text-o',
            'text/rtf' => 'fa fa-file-o',
            'text/x-vcard' => 'fa fa-id-card-o',
            'video/mpeg' => 'fa fa-file-video-o',
            'video/quicktime' => 'fa fa-film',
            'video/x-ms-wmv' => 'fa fa-file-video-o',
            'video/x-msvideo' => 'fa fa-file-video-o',

            // Artificial
            'link/generic' => 'fa fa-file-o',
            'link/unknown' => 'fa fa-link'
        );

    public static function getIconSetByMimeType($mimetype, $title = null){
        $mimetype   = strtolower($mimetype);
        $class      = 'fa fa-file-o';
        $attachIcons    = self::$attachments_icon_from_mime_type;
        if(isset($attachIcons[$mimetype]) ){
            $class  = $attachIcons[$mimetype];
        }
        return '<i class="'.$class.'"'.($title?' title="'.$title.'"':'').'></i>';
    }
}