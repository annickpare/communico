(function($) {
	// Avoid `console` errors in browsers that lack a console.
	if (!(window.console && console.log)) {
		(function() {
			var noop = function() {};
			var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
			var length = methods.length;
			var console = window.console = {};
			while (length--) {
				console[methods[length]] = noop;
			}
		}());
	}


	/***
	 * Fonction JQuery urlParam(name, url)
	 * name : nom du paramÃ¨tre de l'url
	 * url  : facultatif, si on ne veut pas utiliser l'url en cours
	 * Utilisation :   $.urlParam('id');
	***/
	$(function(){
		$.urlParam = function(name, url){
			var check_url = url;
			if (url == null)
				check_url = window.location.href;

			var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(check_url);

			if (results == null)
				return "";
			else
				return results[1] || 0;
		}
	});


	/***
	 * Fonction JQuery urlHash(url)
	 * url : facultatif, si on ne veut pas utiliser l'url en cours
	 * Utilisation :   $.urlHash();
	***/
	$(function(){
		$.urlHash = function(url){
			var check_url = url;
			if (url == null)
				check_url = document.URL;

			// S'il y a bien un hash (ancre)
			if (check_url.indexOf('#') != -1)
				return check_url.substr(check_url.indexOf('#')+1);
			else
				return '';
		}
	});


	/***
	 * Fonction JQuery smartresize(function())
	 * Utilisation :
			$(window).smartresize(function(){
				// Code ici
			});
	***/
	(function($,sr){
		// debouncing function from John Hann
		// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
		var debounce = function (func, threshold, execAsap) {
			var timeout;

			return function debounced () {
				var obj = this, args = arguments;
				function delayed (){
					if (!execAsap)
						func.apply(obj, args);
					timeout = null;
				};

				if (timeout)
					clearTimeout(timeout);
				else if (execAsap)
					func.apply(obj, args);

				timeout = setTimeout(delayed, threshold || 100);
			};
		}

		// smartresize
		jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

	})(jQuery,'smartresize');


	/***
	 * Fonction smoothScroll()
	 * Plugin JS de SmoothScroll
	 * http://www.creativejuiz.fr/blog/doc/demo-smoothscroll.html
	 * Utilisation : smoothScroll('a.smoothscroll','slow');
	***/
	function smoothScroll(selector, speed, v_indent) {
		if (!speed) var speed = 'slow';
		if (!v_indent) var v_indent = 0;

		$(selector).on('click', function() {
			smoothScrollExecute($(this), speed, v_indent);
		});
	};


	/***
	 * Fonction smoothScrollExecute()
	 * ExÃ©cution de l'animation, peut Ãªtre appelÃ© directement
	 * Utilisation : smoothScrollExecute('.btnSoumettre','slow', 0, function() {});
	 *   ou encore : smoothScrollExecute('#ancre','slow', 0, function() {});
	***/
	function smoothScrollExecute(obj, speed, v_indent, callback_fn) {
		if (!speed) var speed = 'slow';
		if (!v_indent) var v_indent = 0;
		var the_hash = null;
		var goscroll = false;

		// On peut fournir l'ancre directement ou l'objet cliquable qui fourni l'ancre : <a href="#ancre">
		if (typeof(obj) == 'string')
			the_hash = obj;
		else {
			// Est un objet Jquery
			if (obj.is('a'))
				the_hash = obj.attr("href"); // Si c'est un lien
			else {
				the_element = obj; // L'objet cible
				goscroll = true;
			}
		}

		if (the_hash != null && the_hash.match("\#")) {
			the_hash = the_hash.split('#');
			the_hash = the_hash[1];

			if ($("#"+the_hash).length>0) {
				the_element = "#" + the_hash;
				goscroll = true;
			}
			else if ($("a[name=" + the_hash + "]").length>0) {
				the_element = "a[name=" + the_hash + "]";
				goscroll = true;
			}
		}

		if (goscroll) {
			/*var container = 'html';
			if ($.browser.webkit || $.browser.chrome)
				container = 'body';
			*/
			//$(container).animate({
			$('html, body').animate({
				scrollTop:$(the_element).offset().top + v_indent
			}, speed,
				function(){
					if (the_hash != null) {
						$(the_element).attr('tabindex','0').focus();//.removeAttr('tabindex');
						window.location = document.location.protocol + '//' + document.location.host + document.location.pathname + document.location.search +'#'+ the_hash;
					}

					if (callback_fn)
						callback_fn();
				});
			return false;
		}
	};


	/***
	 * Fonction JS valider_courriel()
	 * Validation avec un RegExp
	 * http://www.jquery4u.com/snippets/javascript-validate-email-address-regex/
	***/
	function valider_courriel(str)
	{
		var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
		if (str == '' || !re.test(str))
		{
			return false;
		}
		return true;
	}

})(jQuery);
