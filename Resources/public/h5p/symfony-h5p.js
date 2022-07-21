(function ($) {
    ns.init = function () {
        ns.$ = H5P.jQuery;

        if (H5PIntegration !== undefined && H5PIntegration.editor !== undefined) {
            ns.basePath = H5PIntegration.editor.libraryPath;
            ns.fileIcon = H5PIntegration.editor.fileIcon;
            ns.ajaxPath = H5PIntegration.editor.ajaxPath;
            ns.filesPath = H5PIntegration.editor.filesPath + '/editor';
            ns.apiVersion = H5PIntegration.editor.apiVersion;
            // Semantics describing what copyright information can be stored for media.
            ns.copyrightSemantics = H5PIntegration.editor.copyrightSemantics;
            // Required styles and scripts for the editor
            ns.assets = H5PIntegration.editor.assets;
            // Required for assets
            ns.baseUrl = H5PIntegration.baseUrl;

            if (H5PIntegration.editor.contentId !== undefined) {
                ns.contentId = H5PIntegration.editor.contentId;
            }

            var h5peditor;
            var $editor = $('#h5p-editor');
            var $parameters = $('#h5p_parameters');
            var $library = $('#h5p_library');
            var library = $library.val();

            if (h5peditor === undefined) {
                h5peditor = new ns.Editor(library, $parameters.val(), $editor[0]);
            }

            /*
            let formIsUpdated = false;
            const $form = $('form[name="h5p"]').submit(function (event) {
              if ($type.length && $type.filter(':checked').val() === 'upload') {
                return; // Old file upload
              }

              if (h5peditor !== undefined && !formIsUpdated) {

                // Get content from editor
                h5peditor.getContent(function (content) {

                  // Set main library
                  $library.val(content.library);

                  // Set params
                  $params.val(content.params);

                  // Submit form data
                  formIsUpdated = true;
                  $form.submit();
                });

                // Stop default submit
                event.preventDefault();
              }
            });
            */

            let formIsUpdated = false;
            const $form = $("form[name='h5p']").submit(function () {

                if (h5peditor !== undefined && !formIsUpdated) {
                    // Get content from editor
                    h5peditor.getContent(function (content) {
                      // Set main library
                      $library.val(content.library);
                      // Set params
                      $params.val(content.params);
                      // Submit form data
                      formIsUpdated = true;
                      $form.submit();
                    });
                    // Stop default submit
                    event.preventDefault();
                }
            });
        }
    };

    ns.getAjaxUrl = function (action, parameters) {
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

    $(document).ready(ns.init);


})(H5P.jQuery);
