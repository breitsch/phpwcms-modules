// JavaScript Document
//Self-Executing Anonymous Func
(function( PHPWCMS_MODULE, jQuery, undefined ) {

    //load JQuery if not loaded jet
    //and ability to bind it to $
		if (typeof jQuery === "undefined" || jQuery == null ) {

        AddScript("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js",
            function() {
					      //$ = jQueryObj;
                //we're not shure what other libraries are loaded so we call noConflict and bind jQuery to 'jQuery' within the global namespace
                window.jQuery.noConflict();
            }
        );

		} else {

      //leave everything as it is
      //jQuery is loaded, but we still can't be sure if its the only one, to address later
		}

    //Private Properties
    //var abc = true;

    //Private Methods

    // url - a string-based url of js-file
    // callback - reference to a user-defined method, which will be called when loading is finished
    function AddScript(url, callback) {
        var script   = document.createElement('script');
        script.src   = url;
        script.type  = 'text/javascript';
        script.defer = false;

        if (typeof callback != "undefined" && callback != null) {

            // IE only, connect to event, which fires when JavaScript is loaded
            script.onreadystatechange = function() {
                if (this.readyState == 'complete' || this.readyState == 'loaded') {
                    this.onreadystatechange = this.onload = null; // prevent duplicate calls
                    callback();
                }
            }

            // FireFox and others, connect to event, which fires when JavaScript is loaded
            script.onload = function() {
                this.onreadystatechange = this.onload = null; // prevent duplicate calls
                callback();
            };
        }

        var head = document.getElementsByTagName('head').item(0);
        head.appendChild(script);
    }

    //Public Properties
    PHPWCMS_MODULE.myproperty = "Module for PHPWCMS";

    //Public Methods
    PHPWCMS_MODULE.createNamespace = function (ns) {
        // First split the namespace string separating each level of the namespace object.
        var splitNs = ns.split(".");
        // Define a string, which will hold the name of the object we are currently working with.  Initialize to the first part.
        var builtNs = splitNs[0];
        var i, base = this;
        for (i = 0; i < splitNs.length; i++) {
          if (typeof(base[ splitNs[i] ]) == 'undefined') base[ splitNs[i] ] = {};
          base = base[ splitNs[i] ];
        }
        return base; // Return the namespace as an object.
    };


}( window.PHPWCMS_MODULE = window.PHPWCMS_MODULE || {}, jQuery = window.jQuery ));

//Create new namespace wherever
//PHPWCMS_MODULE.createNamespace("MyModule");