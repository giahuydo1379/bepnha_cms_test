/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.toolbar = 'videoOnly';
    config.toolbar_videoOnly = [{
        name: 'insert',
        items: ['Youtube']
    }, ];
    config.toolbar = 'imageOnly';
    config.toolbar_imageOnly = [{
        name: 'insert',
        items: ['Image']
    }, ];


    //đúng nhưng phải xem lại path
    //k tin thay route nay
    config.filebrowserBrowseUrl = '/assets/inside/plugins/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/assets/inside/plugins/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '/assets/inside/plugins/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '/assets/inside/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '/assets/inside/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/assets/inside/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
