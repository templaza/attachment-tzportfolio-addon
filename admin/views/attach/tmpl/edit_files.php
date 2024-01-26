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

if($files = $this -> file):
    $group  = 'value';
    $item   = null;
    if($this -> item && isset($this -> item -> value) && $this -> item -> value){
        $item   = $this -> item -> value;
    }
?>
    <table class="table table-bordered" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>File</th>
            <?php if(isset($item -> file_type) && $file_type = $item -> file_type){ ?>
                <th style="width: 15%;"><?php echo JText::_('PLG_CONTENT_ATTACHMENT_FILE_TYPE');?></th>
            <?php } ?>
            <?php if(isset($item -> file_size) && $file_size = $item -> file_size){ ?>
                <th style="width: 15%; text-align: center;"><?php echo JText::_('PLG_CONTENT_ATTACHMENT_FILE_SIZE_KB');?></th>
            <?php } ?>
            <th style="width: 5%; text-align: center;">
                <?php echo JText::_('PLG_CONTENT_ATTACHMENT_DOWNLOADS'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <span class="icon-file"></span> <?php echo basename($files);?>
            </td>
            <?php if(isset($item -> file_type) && $file_type = $item -> file_type){ ?>
                <td><?php echo $file_type;?>
                    <?php echo $this -> form -> getInput('file_type', $group);?></td>
            <?php } ?>
            <?php if(isset($item -> file_size) && $file_size = $item -> file_size){ ?>
                <td style="text-align: right;"><?php echo $file_size;?>
                <?php echo $this -> form -> getInput('file_size', $group);?></td>
            <?php } ?>
            <td style="text-align: center;">
            <?php if(isset($item -> hits) && $hits = $item -> hits){
                echo $hits;
            }else{
                echo 0;
            }
            echo $this -> form -> getInput('hits', $group);
            ?>
            </td>
        </tr>
        </tbody>
    </table>
<?php
endif;