CKEDITOR.plugins.add('uploadimage', {
    icons: 'uploadimage',
    beforeInit: function (editor) {
        window.KCFinder = {
            callBackMultiple: function (files) {
                //window.KCFinder = null;
                var html = '';
                for (var i = 0; i < files.length; i++) {
                    var id = Math.floor(Math.random() * 1000000);
                    html += '<div class="image_block" style="width: 100%;display: inline-block;text-align: center"><figure id="attachment_' + id + '">' +
                        '<img alt="" sizes="(max-width: 900px) 100vw, 800px" src="' + files[i] + '" width="800" />' +
                        '<figcaption id="attachment_' + id + '">Chú thích ảnh.</figcaption>' +
                        '</figure></div>';
                }

                editor.insertHtml(html);
            }
        };
    },
    onLoad: function (editor) {

    },
    init: function (editor) {
        editor.addCommand('insertImage', {
            exec: function (editor) {

                var w = 800, h = 600;

                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                var systemZoom = width / window.screen.availWidth;
                var left = (width - w) / 2 / systemZoom + dualScreenLeft;
                var top = (height - h) / 2 / systemZoom + dualScreenTop;

                var url = STATIC_URL + '/backend/assets/plugins/ckeditor/kcfinder/browse.php?type=images';
                var title = 'Chèn ảnh';

                window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);
            }
        });
        editor.ui.addButton('uploadimage', {
            label: 'Insert Image',
            command: 'insertImage',
            toolbar: 'insert'
        });
    }
});
