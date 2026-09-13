/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html


    config.extraPlugins = ['katex', 'eqneditor'];


    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        { name: 'clipboard', groups: ['clipboard', 'undo', 'EqnEditor'] },
        { name: 'editing', groups: ['find', 'selection', 'spellchecker'] },
        { name: 'links' },
        { name: 'insert' },
        // { name: 'forms' },
        { name: 'tools' },
        //{ name: 'document', groups: ['mode', 'document', 'doctools'] },
        { name: 'others' },
        '/',
        { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'about' },
        //  {'name': 'mathjax', groups: ['MathJax']},
    ];

    //  config.allowedContent = true;
    //  config.extraAllowedContent = '*(*)';
    //  config.forcePasteAsPlainText = true;
    // CKEDITOR.replace('editor', {
    //     fullPage: true,
    //     extraPlugins: 'font,panelbutton,colorbutton,colordialog,justify,indentblock,aparat,buyLink',
    //     // You may want to disable content filtering because if you use full page mode, you probably
    //     // want to  freely enter any HTML content in source mode without any limitations.
    //     allowedContent: true,
    //     autoGrow_onStartup: true,
    //     enterMode: CKEDITOR.ENTER_BR
    // });

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    // config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    // config.format_tags = 'p;h1;h2;h3;pre';
    config.katexLibCss = '//cdn.jsdelivr.net/npm/katex@0.13.11/dist/katex.min.css';
    config.katexLibJs = '//cdn.jsdelivr.net/npm/katex@0.13.11/dist/katex.min.js';

    http: //example.com/ckeditor/plugins/eqneditor

        var url = $("#_url").val();
    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.filebrowserUploadMethod = 'form';
    config.filebrowserUploadUrl = url + "/uploadckimg.php";



};