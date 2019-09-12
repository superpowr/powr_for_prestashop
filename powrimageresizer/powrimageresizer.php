<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Powrimageresizer extends Module
{

    public function __construct()
    {
        $this->name = 'powrimageresizer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'powr.io';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('POWr Image Resizer');
        $this->description = $this->l('Adjust the size, shape, and filter for a single image.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        $this->installTab();
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayBackOfficeTop');
    }

    public function uninstall()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            if (!Module::isEnabled('powrsocialfeed')) {
                if (file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js')) {
                    $file_to_delete = _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js';
                    unlink($file_to_delete);
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js',
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                    );
                    $file_to_deletes = _PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js';
                    unlink($file_to_deletes);
                }
            }
        } else {
            if (!Module::isEnabled('powrsocialfeed')) {
                if (file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js')) {
                    $file_to_delete = _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js';
                    unlink($file_to_delete);
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js',
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                    );
                    $file_to_deletes = _PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js';
                    unlink($file_to_deletes);
                }
            }
        }

        return parent::uninstall();
    }


    public function enable($force_all = false)
    {
        $this->installTab();
        return parent::enable($force_all);
    }

    public function installTab()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $id_Tab = Tab::getIdFromClassName('Adminimageresizer');
            if (!$id_Tab) {
                $name ='POWR Image Resizer';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='Adminimageresizer';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminPowrimageresizer');
            if (!$id_Tab) {
                $name ='POWR Image Resizer';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminPowrimageresizer';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerCreate');
            if (!$id_Tab) {
                $name ='Create New Image Resizer';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('AdminPowrimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerCreate';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerManage');
            if (!$id_Tab) {
                $name ='Manage POWr Plugins';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('AdminPowrimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerManage';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerHelp');
            if (!$id_Tab) {
                $name ='POWr Help';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('AdminPowrimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerHelp';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            if (!Module::isEnabled('powrsocialfeed')) {
                if (!file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js')) {
                    copy(
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js',
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js'
                    );
                    rename(
                        _PS_ROOT_DIR_."/js/admin/tinymce.inc.js",
                        _PS_ROOT_DIR_."/js/admin/tinymce.inc-powrimageresizer-backup.js"
                    );

                    if (file_exists(_PS_ROOT_DIR_.'/js/admin/tinymce.inc-powrimageresizer-backup.js')) {
                        copy(
                            _PS_MODULE_DIR_ .'powrimageresizer/views/js/admin/1.7/tinymce.inc.js',
                            _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                        );
                    } else {
                        return false;
                    }

                    $directoryName = _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr';
                    if (!is_dir($directoryName)) {
                        mkdir($directoryName, 0755, true);
                    }

                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/views/js/tiny_mce/plugins/powr/plugin.min.js',
                        _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr/plugin.min.js'
                    );
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/views/img/powr-icon.png',
                        _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr/powr-icon.png'
                    );
                }
            }
        } else {
            $id_Tab = Tab::getIdFromClassName('Adminimageresizer');
            if (!$id_Tab) {
                $name ='POWR Image Resizer';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='Adminimageresizer';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerCreate');
            if (!$id_Tab) {
                $name ='Create New Image Resizer';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerCreate';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerManage');
            if (!$id_Tab) {
                $name ='Manage POWr Plugins';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerManage';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }

            $id_Tab = Tab::getIdFromClassName('AdminimageresizerHelp');
            if (!$id_Tab) {
                $name = 'POWr Help';
                $tab = new Tab();
                $tab->id_parent=(int)Tab::getIdFromClassName('Adminimageresizer');
                $tab->name=array();
                foreach (Language::getLanguages(true) as $lang) {
                    $tab->name[$lang['id_lang']]=$name;
                }
                $tab->class_name='AdminimageresizerHelp';
                $tab->module=$this->name;
                $tab->active=1;
                $tab->add();
            }
            if (!Module::isEnabled('powrsocialfeed')) {
                if (!file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js')) {
                    copy(
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js',
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js'
                    );
                    rename(
                        _PS_ROOT_DIR_."/js/admin/tinymce.inc.js",
                        _PS_ROOT_DIR_."/js/admin/tinymce.inc-powrimageresizer-backup.js"
                    );

                    if (file_exists(_PS_ROOT_DIR_.'/js/admin/tinymce.inc-powrimageresizer-backup.js')) {
                        copy(
                            _PS_MODULE_DIR_ .'powrimageresizer/views/js/admin/1.6/tinymce.inc.js',
                            _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                        );
                    } else {
                        return false;
                    }

                    $directoryName = _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr';
                    if (!is_dir($directoryName)) {
                        mkdir($directoryName, 0755, true);
                    }

                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/views/js/tiny_mce/plugins/powr/plugin.min.js',
                        _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr/plugin.min.js'
                    );
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/views/img/powr-icon.png',
                        _PS_ROOT_DIR_.'/js/tiny_mce/plugins/powr/powr-icon.png'
                    );
                }
            }
        }
    }
    public function disable($force_all = false)
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            if (!Module::isEnabled('powrsocialfeed')) {
                if (file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js')) {
                    $file_to_delete = _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js';
                    unlink($file_to_delete);
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js',
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                    );
                    $file_to_deletes = _PS_MODULE_DIR_ .'powrimageresizer/backup/1.7/tinymce.inc.js';
                    unlink($file_to_deletes);
                }
            }
        } else {
            if (!Module::isEnabled('powrsocialfeed')) {
                if (file_exists(_PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js')) {
                    $file_to_delete = _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js';
                    unlink($file_to_delete);
                    copy(
                        _PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js',
                        _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js'
                    );
                    $file_to_deletes = _PS_MODULE_DIR_ .'powrimageresizer/backup/1.6/tinymce.inc.js';
                    unlink($file_to_deletes);
                }
            }
        }

        return parent::disable($force_all);
    }

    public function hookBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/editor-style.css');
        $this->context->controller->addJS($this->_path.'/views/js/admin/back-nav-links.js');
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $this->context->controller->addCSS($this->_path.'/views/css/back-older-ps.css');
        }
        if (version_compare(_PS_VERSION_, '1.7.6.0', '>=') &&
            $this->context->controller->php_self == 'AdminCmsContent'
            ) {
            $this->context->controller->addJS(Media::getJqueryPath());
            $this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
            $this->context->controller->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js?v='.$this->id);
            $this->context->controller->addJS($this->_path.'/views/js/admin/cms-tinymce.js?v='.$this->id);
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front/powr.js');
    }
}
