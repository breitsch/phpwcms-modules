PHPWCMS_MODULE.createNamespace("SP");
(function( SP, jQuery, undefined ) {
//Private Property
    var isHot = true;

//Public Property
    SP.myproperty = "Social Plugins Module";

//Private Method
    function br_create_alias(str,encoding,ucfirst) {
    	var str = str.toUpperCase();
    	str = str.toLowerCase();

    	str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E5]/g,'a');
    	str = str.replace(/[\u00E7]/g,'c');
    	str = str.replace(/[\u00E8\u00E9\u00EA\u00EB]/g,'e');
    	str = str.replace(/[\u00EC\u00ED\u00EE\u00EF]/g,'i');
    	str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F8]/g,'o');
    	str = str.replace(/[\u00F9\u00FA\u00FB]/g,'u');
    	str = str.replace(/[\u00FD\u00FF]/g,'y');
    	str = str.replace(/[\u00F1]/g,'n');
    	str = str.replace(/[\u0153\u00F6]/g,'oe');
    	str = str.replace(/[\u00E6\u00E4]/g,'ae');
    	str = str.replace(/[\u00DF]/g,'ss');
    	str = str.replace(/[\u00FC]/g,'ue');

    	str = str.replace(/\s+/g,'-');
    	str = str.replace(/-+\/+-+/g,'/');
    	if(aliasAllowSlashes) {
    		str = str.replace(/[^a-z0-9_\-\/]+/g,'');
    	} else {
    		str = str.replace('/', '-');
    		str = str.replace(/[^a-z0-9_\-]+/g,'');
    	}
    	str = str.replace(/\-+/g,'-');
    	str = str.replace(/\/+/g,'/');
    	str = str.replace(/_+/g,'_');
    	str = str.replace(/^-+|-+$/g, '');
    	str = str.replace(/^\/+|\/+$/g, '');
    	str = str.replace(/^-+|-+$/g, '');

    	if (ucfirst == 1) {
    		c = str.charAt(0);
    		str = c.toUpperCase()+str.slice(1);
    	}

    	return str;
    }

    function sp_is_valid(val) {
        var result = false;
        if( val==0 ) result = false;
        if( val==1 ) result = true;
        return result;
    }

//Public Methods
    SP.isValidURL = function (elem) {
        //Private Property
        var newClassName = "";
        var i;
        var remove = "errorInputText";
        var classes = elem.className.split(" ");
        for(i = 0; i < classes.length; i++) {
            if(classes[i] !== remove) {
                newClassName += classes[i] + " ";
            }
        }
        elem.className = newClassName;
        var url = elem.value;
        if (url=="") {
            return (true);
        }
        if (!/^https?:\/\//.test(url)) {
                url = "http://" + url;
                elem.value = url;
        }
        //var urlregex = new RegExp("^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
        var urlregex = new RegExp("^((ht|f)tp(s?))\\:\\/\\/([0-9a-zA-Z\\-]+\\.)+[a-zA-Z]{2,6}(\\:[0-9]+)?(\\/\\S*)?$");
        if (urlregex.test(url)) {
            return (true);
        }

        elem.className = elem.className + " " + remove;
        return (false);


    };

    SP.validTwRecomm = function (elem) {
        if (!/(^[0-9A-Za-z_]+$)|^([0-9A-Za-z_]+)\:+[^\"\<\>\/\\\,]*$/.test(elem.value)) {
            var exploded = elem.value.split(':');
            exploded[0] = exploded[0].replace(/[^0-9A-Za-z_]+/g,"");
            if(exploded[1]){
                exploded[1] = exploded[1].replace(/[\"\<\>\/\\\,]+/g,"");
            }
            elem.value = exploded.join(":");
        }
    };
    SP.validTwVal = function (elem) {
        elem.value = elem.value.replace(/[^0-9A-Za-z_]+/g,"");
    };
    SP.validTwValComma = function (elem) {
        elem.value = elem.value.replace(/[^0-9A-Za-z_,]+/g,"");
    };
    SP.validTwNumber = function (elem) {
        elem.value = elem.value.replace(/[^0-9]+/g,"");
    };
    SP.requiredInput = function (elem) {
        if(jQuery(elem).val().length == 0) {
            jQuery(elem).addClass('errorInputText');
        } else {
            jQuery(elem).removeClass('errorInputText');
        }
    };

    SP.load_preview = function (plugin) {
        var currdomain = jQuery("#param_fb_site_url_fix").is(":checked");
        var exturl = jQuery("#param_fb_site_url").val();
        var usedomain = jQuery("#param_fb_phpwcms_url").val();
        if( currdomain==false && exturl !="" ) {
            usedomain = exturl;
        }
        if(jQuery("#prev_check").is(":checked")) {
            if(plugin == 'activity') {
                jQuery("#fb_plugin_preview_update").css("display","inline-block");
                jQuery("#fb_plugin_preview").css("width","" + jQuery('#fb_width').val() + "px");
                jQuery("#fb_plugin_preview").html("<iframe src=\"https://www.facebook.com/plugins/activity.php?site=" + usedomain + "&amp;width=" + jQuery("#fb_width").val() + "&amp;height=" + jQuery("#fb_height").val() + "&amp;header=" + jQuery("#param_fb_header").is(":checked") + "&amp;font=" + jQuery("#fb_font").val() + "&amp;colorscheme=" + jQuery("#fb_colorscheme").val() + "&amp;recommendations=" + jQuery("#param_fb_show_recom").is(":checked") + "&amp;ref=" + jQuery("#fb_article_alias").val() + "&amp;locale=" + jQuery("#param_fb_locale").val() + "\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:" + jQuery("#fb_width").val() + "px; height:" + jQuery("#fb_height").val() + "px;\" allowTransparency=\"true\"></iframe>");
            } else if (plugin == 'recomm') {
                jQuery("#fb_plugin_preview_update").css("display","inline-block");
                jQuery("#fb_plugin_preview").css("width","" + jQuery('#fb_width').val() + "px");
                jQuery("#fb_plugin_preview").html("<iframe src=\"http://www.facebook.com/plugins/recommendations.php?site=" + usedomain + "&amp;width=" + jQuery("#fb_width").val() + "&amp;height=" + jQuery("#fb_height").val() + "&amp;header=" + jQuery("#param_fb_header").is(":checked") + "&amp;font=" + jQuery("#fb_font").val() + "&amp;colorscheme=" + jQuery("#fb_colorscheme").val() + "&amp;ref=" + jQuery("#fb_article_alias").val() + "\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:" + jQuery("#fb_width").val() + "px; height:" + jQuery("#fb_height").val() + "px;\" allowTransparency=\"true\"></iframe>");
            }
        } else {
            jQuery("#fb_plugin_preview_update").css("display","none");
            jQuery("#fb_plugin_preview").html("");
        }
        return (false);
    };

    SP.set_ref = function (onempty_only, alias_basis, alias_target) {
        var aalias = document.getElementById(alias_target);
      	if(onempty_only && aalias.value != '') return false;
      	var atitle = document.getElementById(alias_basis);
      	aalias.value = br_create_alias(atitle.value);
      	return false;
    };

//self init function
    SP.init = function () {
        jQuery(".toggle").click(function(el) {
            var cont = "#" + this.id + "-content";
            jQuery(cont).slideToggle("slow", function() {
            });
        });
        jQuery(".togglemap").click(function(el) {
            var cont = "#t5map-container";
            if(jQuery(cont).height() <= 100 ){
                jQuery('#map_canvas').height(240);

                jQuery(cont).slideDown("slow", function() {
                   inimap();
                });
            }else{
                jQuery(cont).slideUp("slow", function() {
                    jQuery('#map_canvas').height(0);
                });
            }
        });
    }();

}( window.PHPWCMS_MODULE.SP = window.PHPWCMS_MODULE.SP || {}, window.jQuery = window.jQuery || {} ));