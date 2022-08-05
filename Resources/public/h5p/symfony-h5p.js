var ns = H5PEditor;

(function ($) {
    H5PEditor.init = function () {
        H5PEditor.$ = H5P.jQuery;

        H5PEditor.basePath = H5PIntegration.editor.libraryPath;
        H5PEditor.fileIcon = H5PIntegration.editor.fileIcon;
        H5PEditor.ajaxPath = H5PIntegration.editor.ajaxPath;
        H5PEditor.filesPath = H5PIntegration.editor.filesPath + '/editor';
        H5PEditor.apiVersion = H5PIntegration.editor.apiVersion;
        H5PEditor.contentLanguage = H5PIntegration.editor.language;

        // Semantics describing what copyright information can be stored for media.
        H5PEditor.copyrightSemantics = H5PIntegration.editor.copyrightSemantics;
        H5PEditor.metadataSemantics = H5PIntegration.editor.metadataSemantics;

        // Required styles and scripts for the editor
        H5PEditor.assets = H5PIntegration.editor.assets;

        // Required for assets
        H5PEditor.baseUrl = '';

        if (H5PIntegration.editor.nodeVersionId !== undefined) {
            H5PEditor.contentId = H5PIntegration.editor.nodeVersionId;
        }

        var h5peditor;
        var $editor = $('#h5p-editor');
        //change if bug name of your entity form here it H5_P
        var $parameters = $('#h5p_parameters');
        var $library = $('#h5p_library');
        var $title = $('#h5p_title');
        var library = $library.val();

        if (h5peditor === undefined) {
            h5peditor = new ns.Editor(library, $parameters.val(), $editor[0]);
        }

        let formIsUpdated = false;
        const $form = $('form[name="h5p"]').submit(function (event) {
            if (h5peditor !== undefined && !formIsUpdated){
                event.preventDefault();
                h5peditor.getContent(content => {
                    $library.val(content.library);
                    $parameters.val(content.params);
                    $title.val(content.title);
                    formIsUpdated = true;
                    $form.submit();
                });
                return false;
            }
        });
    };

    H5PEditor.getAjaxUrl = function (action, parameters) {
        var url = H5PIntegration.editor.ajaxPath + action + '/?';
        var request_params = [];

        if (parameters !== undefined) {
            for (var property in parameters) {
                if (parameters.hasOwnProperty(property)) {
                    request_params.push(encodeURIComponent(property) + "=" + encodeURIComponent(parameters[property]));
                }
            }
        }
        return url + request_params.join('&');
    };

    $(document).ready(H5PEditor.init);


})(H5P.jQuery);
