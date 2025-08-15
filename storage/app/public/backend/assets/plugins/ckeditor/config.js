/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */



CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'about'}
    ];
    //config.height = 400;

    config.allowedContent = true;
    config.extraAllowedContent = '*(*);*[*];*{*}';
    config.extraAllowedContent = 'i';
    CKEDITOR.dtd.$removeEmpty.i = 0;
    config.htmlEncodeOutput = false;
    config.entities = false;
    config.extraPlugins = 'widgetselection';
    config.extraPlugins = 'lineutils';
    config.extraPlugins = 'widget';
    config.extraPlugins = 'codesnippet';
    config.extraPlugins = 'uploadimage';

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.filebrowserBrowseUrl = STATIC_URL + '/backend/plugins/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
    config.filebrowserBrowseUrl = STATIC_URL + '/backend/assets/plugins/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
    config.filebrowserFlashBrowseUrl = STATIC_URL + '/backend/assets/plugins/kcfinder/browse.php?opener=ckeditor&type=flash';
    config.filebrowserUploadUrl = STATIC_URL + '/backend/assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
    config.filebrowserImageUploadUrl = STATIC_URL + '/backend/assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
    config.filebrowserFlashUploadUrl = STATIC_URL + '/backend/assets/plugins/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
};
