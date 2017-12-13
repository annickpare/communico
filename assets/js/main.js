/**
 * Created by francoismoreau on 2017-04-18.
 */
jQuery(document).foundation();

(function($) {

  $(function() {
    // Handler for .ready() called.
    buttonBack();
    transformToOption();
    initAccordeons();
    callPopup();

  });

  function buttonBack(){
    $('.goBack').on('click',function(e){
      e.preventDefault();
      window.history.back();
    });
  }

  function transformToOption(){
    // bind change event to select
    $('#dynamic_select').on('change', function () {
        var url = $(this).val(); // get selected value
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });


    $('#dynamic_select option').each(function(index, element) {
      $(this).removeAttr("selected","");

      if($(this).hasClass("current")){
        $(this).attr("selected","selected");
      }
    });
  }

  /* ==================================================
	 * Fonction initAccordeons()
	 * Gère les accordéons dans le contenu des pages
	 * de façon accessible
	 * ================================================== */
	function initAccordeons(){
		// Cacher le bloc de contenu de l'accordéon au chargement
		$(".accordeon").attr('aria-multiselectable', 'true'); // ARIA
		$(".accordeon > div").addClass('visuallyhidden');

		// Désactiver les liens des accordéons pour le TAB
		$(".accordeon > div a").attr('tabindex', '-1');

		var togglers = $('.accordeon > .toggler');

		// Pour styler avec les icones de l'accordéon (plus/moins), on ajoute le <span>
		togglers.append('<span class="icone"></span>');

		// Switch de l'ouverture à la fermeture au click
		togglers.on('click keypress', function(e){
			// Exécute seulement si cliqué ou keypress avec la touche ENTER (#13)
			if(!e) {e=window.event;}
			var keynum = e.keyCode||e.which;
			if (e.type == 'keypress' && keynum != 13) {
				return true;
			}

			var me = $(this);
			var div = me.next();
			var vitesse = (me.parent().hasClass("long") ? 'slow' : 'fast');

			if (me.hasClass("active")) {
				// Fermeture, mais laisser accessible
				me.removeClass("active");
				//me.find('span.icone').text('Ouvrir');

				div.find('a').attr('tabindex', '-1'); // Désactiver le TAB
				div.attr('aria-hidden', 'true').slideUp(vitesse, 'linear', function(){
					$(this).addClass("visuallyhidden").show();
				});
			}
			else {
				// Ouverture
				me.addClass("active");
				//me.find('span.icone').text('Fermer');

				div.find('a').attr('tabindex', '0'); // Permet le TAB
				div.hide().removeClass("visuallyhidden").attr('aria-hidden', 'false').slideDown(vitesse, 'linear');
			}
			e.preventDefault(); // Prévient le navigateur d'activer la cible du lien
		});

		// Init l'état de tous les accordéons
		togglers.each(function(index, element) {
			var me = $(this);
			var div = me.next();

			var id = div.attr("id");
			if (id == undefined) {
				id = 'AccPanel'+'-'+index;
				div.attr('id', id);
			}
			if (!me.closest('.onglets .contenu .visuallyhidden, .onglets_v .contenu .visuallyhidden').length)
				me.attr('tabindex', '0'); // Permet le focus, si pas dans un onglet fermé

			me.attr('aria-controls', id);
			me.attr('role', 'tab');
			div.attr('aria-hidden', 'true');
			div.attr('role', 'tabpanel');

			// On ouvre un accordeon qu'on veut ouvert par défaut (classe 'active' sur l'élément)
			if (me.hasClass('active'))
			{
				// "Fake" l'ouverture (pas d'animation)
				me.addClass("active");

				div.find('a').attr('tabindex', '0'); // Permet le TAB
				div.hide().removeClass("visuallyhidden").show().attr('aria-hidden', 'false');
			}
		});

		// On ouvre le bloc accordéon si son ancre est présent dans l'URL
		var ancre = $.urlHash();
		if (ancre != "") {
			$('.accordeon #'+ancre+'.toggler').on("click");
		}
	}

  /* ==================================================
	 * Fonction callPopup()
	 * Gère le dévoilement un à un des éléments d'une
	 * liste non ordonnées.
	 * ================================================== */
	function callPopup(){
		$('.popup a').on("click",function(e) {
      console.log("blabla");
			e.preventDefault();
			var NWin = window.open($(this).attr('href'), '', 'scrollbars=1,height=600,width=800');

			if (window.focus){
				NWin.focus();
			}

			return false;
		});
	}

})(jQuery);
