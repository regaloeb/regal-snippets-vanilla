<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>RegalSnippets</title>
		<meta name="description" content="Quelques Polyfills, fonctions et variables indispensables pour Javascript Vanilla.">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="favicon.png">
		<link href="css/styles.css" rel="stylesheet" type="text/css">
		
		<meta property="og:url" content="http://www.regaloeb.com/pages/regal-snippets-vanilla">
		<meta property="og:title" content="RegalSnippets">
		<meta property="og:description" content="Quelques Polyfills, fonctions et variables indispensables pour Javascript Vanilla.">
		<meta property="og:image" content="http://www.regaloeb.com/pages/regal-snippets-vanilla/fb-thumb.png">
		<meta property="og:site_name" content="regaloeb.com">
		<meta property="og:type" content="website">
		<meta property="fb:admins" content="595624305">
		<meta property="fb:app_id" content="217293022051519">
		
		<link rel="canonical" href="http://www.regaloeb.com/pages/regal-snippets-vanilla">
	</head>
	<body>
		<main class="page-content">
			
			<div class="section-inner">
				<h1>RegalSnippets</h1>
				<p>
					Pour faciliter les développements <strong>Javascript Vanilla</strong>, j'utilise un certain nombre de polyfills, fonctions et variables qui me sont aujourd'hui indispensables, alors je partage&nbsp;!<br><br>
					<a href="https://github.com/regaloeb/regal-snippets-vanilla" target="_blank" class="picto-link"><span class="picto"> <svg class="octicon octicon-mark-github v-align-middle" height="32" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/></svg></span><span>Github repository</span></a>
				</p>
				<h2>forEach NodeList et HTMLCollection</h2>
				<p>
					Pour de nombreux navigateurs, les éléments <strong>NodeList</strong> et <strong>HTMLCollection</strong> retournés par un <strong>document.querySelectorAll()</strong> n'héritent pas de la méthode <strong>forEach</strong>, c'est bien dommage.<br>
					Pour boucler dans des <strong>NodeList</strong> et <strong>HTMLCollection</strong>, j'utilise un petit polyfill très simple&nbsp;:<br>
					<div class="code">
//forEach NodeList & HTMLCollection polyfill
NodeList.prototype.forEach||(NodeList.prototype.forEach=Array.prototype.forEach),
HTMLCollection.prototype.forEach||(HTMLCollection.prototype.forEach=Array.prototype.forEach);
					</div>
					C'est grace à ce polyfill que je peux facilement appliquer des Listeners et c'est la base&nbsp;:<br>
					<div class="code">
document.querySelectorAll('.flickity-carousel').forEach(function(elt){
&nbsp;&nbsp;&nbsp;&nbsp;elt.addEventListener('click', FUNCTION);
}
					</div>
				</p>
				
				<h2>onAnimationEnd et onTransitionEnd</h2>
				<p>
					Pour détecter la fin d'une animation ou d'une transition CSS, chaque navigateur a sa propre méthode basée sur leurs préfixes (-webkit-, o, moz).<br>
					Pour simplifier l'appel à ces événements, j'utilise la petite fonction suivante&nbsp;:<br>
					<div class="code">
//animation and transion end event
function whichAnimationEvent(){var n,i=document.createElement("fakeelement"),t={animation:"animationend",OAnimation:"oAnimationEnd",MozAnimation:"animationend",WebkitAnimation:"webkitAnimationEnd"};for(n in t)if(void 0!==i.style[n])return t[n]}function whichTransitionEvent(){var n,i=document.createElement("fakeelement"),t={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(n in t)if(void 0!==i.style[n])return t[n]}var animationEvent=whichAnimationEvent(),transitionEvent=whichTransitionEvent();
					</div>
					Pour détecter la fin d'une transition, une seule syntaxe commune&nbsp;:<br>
					<div class="code">
ELT.addEventListener(transitionEvent, step2);
function step2(event) {
&nbsp;&nbsp;&nbsp;&nbsp;if(event.propertyName == 'transform'){//<strong>Il vaut toujours mieux tester la propriété</strong>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ELT.removeEventListener(transitionEvent, step2);//<strong>Et supprimer l'événement</strong>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...ACTIONS...
&nbsp;&nbsp;&nbsp;&nbsp;}
};
					</div>
				</p>
				
				<h2>closest</h2>
				<p>
					Très pratique pour vérifier si un élément a bien un parent particulier.<br>
					Mais IE11- et Edge 12 à 14 ne comprennent pas la syntaxe.<br>
					Ce polyfill règle le problème&nbsp;:<br>
					<div class="code">
Element.prototype.matches||(Element.prototype.matches=Element.prototype.msMatchesSelector||Element.prototype.webkitMatchesSelector),Element.prototype.closest||(Element.prototype.closest=function(e){var t=this;if(!document.documentElement.contains(t))return null;do{if(t.matches(e))return t;t=t.parentElement||t.parentNode}while(null!==t&&1==t.nodeType);return null});
					</div>
					On peut ainsi par exemple appliquer un onclick sur le document et cibler uniquement un élément et ses enfants (ici .push-video)&nbsp;:<br>
					<div class="code">
document.addEventListener('click', function (e) {
&nbsp;&nbsp;&nbsp;&nbsp;if (e.target.closest('.push-video')) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...ACTIONS...
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return;//<strong>return pour arrêter le if</strong>
&nbsp;&nbsp;&nbsp;&nbsp;}
});
					</div>
					Ou encore retrouver un élément présent au-dessus de la target d'une action (ici la target est pushVideo et l'élément à retrouver un .accordion-desc&nbsp;:<br>
					<div class="code">
var elem = pushVideo.closest('.accordion-desc');
setTimeout(function(){
&nbsp;&nbsp;&nbsp;&nbsp;acc.slideDown(elem);
}, 500);
					</div>
				</p>
				
				<h2>SlideUp et slideDown comme jQuery</h2>
				<p>
					Cette fonctionnalité jQuery très pratique pour les accordéons n'existe pas de base en Javascript Vanilla mais avec cette petite fonction, c'est un jeu d'enfant de l'implémenter&nbsp;:<br>
					<div class="code">
function slideDown(elem) {
&nbsp;&nbsp;&nbsp;&nbsp;var h = 0;
&nbsp;&nbsp;&nbsp;&nbsp;elem.children.forEach(function(elt){
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;h = h + elt.getBoundingClientRect().height;
&nbsp;&nbsp;&nbsp;&nbsp;});
&nbsp;&nbsp;&nbsp;&nbsp;elem.style.maxHeight = h + 'px';
}
function slideUp(elem) {
&nbsp;&nbsp;&nbsp;&nbsp;elem.style.maxHeight = '0';
}
					</div>
					Il faut juste ajouter une transition à la propriété max-height des éléments à cibler.<br>
					<div class="code">
.element{
&nbsp;&nbsp;&nbsp;&nbsp;transition: max-height $speed $ease;
}	
					</div>
				</p>
				
				<h2>triggerevent</h2>
				<p>
					Pour déclencher l'action d'un élément. Cette fonction est fondamentale car elle est utilisée dans plusieurs autres snippets, dont les indispensables <strong>onResizeEnd</strong> et <strong>onScrollEnd</strong>&nbsp;:<br>
					<div class="code">
//triggerEvent
function triggerEvent(el, type){
&nbsp;&nbsp;&nbsp;&nbsp;if ('createEvent' in document) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var e = document.createEvent('HTMLEvents');
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e.initEvent(type, false, true);
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;el.dispatchEvent(e);
&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;else {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var e = document.createEventObject();
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e.eventType = type;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;el.fireEvent('on' + e.eventType, e);
&nbsp;&nbsp;&nbsp;&nbsp;}
}
					</div>
					Syntaxe d'appel au onclick d'un élément&nbsp;:<br>
					<div class="code">
triggerEvent(ELEMENT, 'click');	
					</div>
				</p>
				
				<h2>scrollEnd</h2>
				<p>
					Dans certains cas, il n'est pas utile de surcharger la machine avec des appels en boucle tout au long du scroll de la page.<br>
					L'événement onScrollEnd est là pour nous&nbsp;:<br>
					<div class="code">
//wait until scroll is done
window.addEventListener('scroll', function() {
&nbsp;&nbsp;&nbsp;&nbsp;if(this.scrollTO) clearTimeout(this.scrollTO);
&nbsp;&nbsp;&nbsp;&nbsp;this.scrollTO = setTimeout(function() {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;triggerEvent(this, 'scrollEnd');
&nbsp;&nbsp;&nbsp;&nbsp;}, 500);
}, false);

					</div>
					Syntaxe d'appel au scrollEnd&nbsp;:<br>
					<div class="code">
window.addEventListener('scrollEnd', function() {
&nbsp;&nbsp;&nbsp;&nbsp;console.log("scrollEnd");
});	
					</div>
				</p>
				
				<h2>resizeEnd</h2>
				<p>
					Un des éléments indispensable au responsive design, c'est le onResize de la fenêtre.<br>
					Il est rarement nécessaire de déclencher des événements tout au long du resize, il vaut mieux attendre la fin du resize&nbsp;:
					<div class="code">
//wait until resize is done
window.addEventListener('resize', function() {
&nbsp;&nbsp;&nbsp;&nbsp;if(this.resizeTO) clearTimeout(this.resizeTO);
&nbsp;&nbsp;&nbsp;&nbsp;this.resizeTO = setTimeout(function() { 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;triggerEvent(this, 'resizeEnd');
&nbsp;&nbsp;&nbsp;&nbsp;}, 500);
});
					</div>
					Syntaxe d'appel au resizeEnd&nbsp;:<br>
					<div class="code">
window.addEventListener('resizeEnd', function() {
&nbsp;&nbsp;&nbsp;&nbsp;//console.log("resizeEnd");
});
					</div>
				</p>
				
				<h2>objectfit</h2>
				<p>
					La propriété CSS object-fit permet de forcer une image à remplir son conteneur, comme une image de background. C'est très pratique pour le responsive mais IE11&lt; et Edge 12-15 ne le comprennent pas.<br>
					Pas de panique! avec cette petite fonction, tout rentre dans l'ordre pour ces récalcitrants&nbsp;:
					<div class="code">
function objectFit(){
&nbsp;&nbsp;&nbsp;&nbsp;if('objectFit' in document.documentElement.style === false) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var liste = document.querySelectorAll('.js-object-fit');
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;liste.forEach(function(elt){
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var imgUrl = elt.querySelector('img').getAttribute('src');
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (imgUrl) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;elt.style.backgroundImage = 'url(' + imgUrl + ')';
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;elt.style.backgroundPosition = (elt.getAttribute('data-bg-pos')) ? elt.getAttribute('data-bg-pos') : '50% 50%';
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;elt.style.backgroundRepeat = 'no-repeat';
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;elt.style.backgroundSize = 'cover';
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;elt.classList.add('compat-object-fit');
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;});
&nbsp;&nbsp;&nbsp;&nbsp;}
};
objectFit();
					</div>
					Il faut juste appliquer la classe <strong>js-object-fit</strong> aux blocs et rajouter la classe <strong>.compat-object-fit</strong> à sa CSS&nbsp;:<br>
					<div class="code">
.compat-object-fit{
&nbsp;&nbsp;&nbsp;&nbsp;img{
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;opacity: 0;
&nbsp;&nbsp;&nbsp;&nbsp;}
}
					</div>
					Il faut aussi penser à appeler la fonction <strong>objectFit();</strong> lorsqu'on charge du contenu dynamiquement via AJAX.
				</p>
				
				<h2>AJAX</h2>
				<p>
					Pour éviter d'avoir à ré-écrire à chaque fois les appels AJAX, j'ai fait cette petite fonction facile à utiliser&nbsp;:<br>
					<div class="code">
//ajaxCall
function ajaxCall(url, params, method, callback){
&nbsp;&nbsp;&nbsp;&nbsp;var xhr = new XMLHttpRequest();
&nbsp;&nbsp;&nbsp;&nbsp;var hurle = (method.toLowerCase() === 'get') ? url + '?' + params : url;
&nbsp;&nbsp;&nbsp;&nbsp;xhr.open(method, hurle);
&nbsp;&nbsp;&nbsp;&nbsp;var postparams = (method.toLowerCase() === 'post') ? params : null;
&nbsp;&nbsp;&nbsp;&nbsp;xhr.send(postparams);
&nbsp;&nbsp;&nbsp;&nbsp;xhr.onreadystatechange = function () {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var DONE = 4; // readyState 4 means the request is done.
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var OK = 200; // status 200 is a successful return.
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (xhr.readyState === DONE) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (xhr.status === OK) {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//console.log(xhr.responseText); // 'This is the returned text.'
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;callback(xhr.responseText);
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;console.log('Error: ' + xhr.status); // An error occurred during the request.
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;};
}
					</div>
					Appel à la fonction, ici avec un retour JSON&nbsp;:<br>
					<div class="code">
function callbackWelcomeBanner(data){
&nbsp;&nbsp;&nbsp;&nbsp;var json = JSON.parse(data);
&nbsp;&nbsp;&nbsp;&nbsp;var arr = json.welcome;
&nbsp;&nbsp;&nbsp;&nbsp;for (var i=0; i&lt;arr.length; i++){
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var htmlToWrite = htmlBannerTemplate.replace('XXX_image_XXX', arr[i].image).replace('XXX_title_XXX', arr[i].title).replace('XXX_desc_XXX', arr[i].desc);
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cib.innerHTML += htmlToWrite;
&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;objectFit();
}
ajaxCall('json/welcome-banner.txt', '', 'get', callbackWelcomeBanner);
					</div>
				</p>
				
				<h2>IE9 et inférieurs</h2>
				<p>
					Si on veut cibler ces vieux navigateurs qui ne comprennent ni <strong>classList</strong> ni <strong>RequestAnimationFrame</strong>,
					ces polyfills sont là pour nous aider&nbsp;:<br>
					<div class="code">
//classList polyfill /for IE9-)
!function(){function t(t){this.el=t;for(var n=t.className.replace(/^\s+|\s+$/g,"").split(/\s+/),i=0;i&lt;n.length;i++)e.call(this,n[i])}function n(t,n,i){Object.defineProperty?Object.defineProperty(t,n,{get:i}):t.__defineGetter__(n,i)}if(!("undefined"==typeof window.Element||"classList"in document.documentElement)){var i=Array.prototype,e=i.push,s=i.splice,o=i.join;t.prototype={add:function(t){this.contains(t)||(e.call(this,t),this.el.className=this.toString())},contains:function(t){return-1!=this.el.className.indexOf(t)},item:function(t){return this[t]||null},remove:function(t){if(this.contains(t)){for(var n=0;n&lt;this.length&&this[n]!=t;n++);s.call(this,n,1),this.el.className=this.toString()}},toString:function(){return o.call(this," ")},toggle:function(t){return this.contains(t)?this.remove(t):this.add(t),this.contains(t)}},window.DOMTokenList=t,n(Element.prototype,"classList",function(){return new t(this)})}}();

//RequestAnimationFrame Polyfill
!function(){for(var n=0,i=["ms","moz","webkit","o"],e=0;e&lt;i.length&&!window.requestAnimationFrame;++e)window.requestAnimationFrame=window[i[e]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[i[e]+"CancelAnimationFrame"]||window[i[e]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(i){var e=(new Date).getTime(),a=Math.max(0,16-(e-n)),o=window.setTimeout(function(){i(e+a)},a);return n=e+a,o}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(n){clearTimeout(n)})}();
					</div>
					On peut ainsi utiliser classList et RequestAnimationFrame avec la syntaxe standard.
				</p>
				<h2>Variables utiles pour responsive</h2>
				<p>
					Un des éléments indispensable au responsive design, c'est le onResize de la fenêtre.<br>
					Il est rarement nécessaire de déclencher des événements tout au long du resize, il vaut mieux attendre la fin du resize&nbsp;:<br>
					<div class="code">
var w = window,
&nbsp;&nbsp;&nbsp;&nbsp;d = document,
&nbsp;&nbsp;&nbsp;&nbsp;e = d.documentElement,
&nbsp;&nbsp;&nbsp;&nbsp;g = d.getElementsByTagName('body')[0];

var windowHeight = w.innerHeight||e.clientHeight||g.clientHeight;
var windowWidth = w.innerWidth||e.clientWidth||g.clientWidth;
var mobileLimit = 650;
var desktopLimit = 1024;
var isMobileContext = (windowWidth > mobileLimit) ? false : true;
window.addEventListener('resizeEnd', function() {
&nbsp;&nbsp;&nbsp;&nbsp;windowHeight = w.innerHeight||e.clientHeight||g.clientHeight;
&nbsp;&nbsp;&nbsp;&nbsp;windowWidth = w.innerWidth||e.clientWidth||g.clientWidth;
&nbsp;&nbsp;&nbsp;&nbsp;isMobileContext = (windowWidth > mobileLimit) ? false : true;
});
					</div>
				</p>
				
				<h2>Browsers sniffing</h2>
				<p>
					Oui, je sais, c'est mal... Mais c'est parfois la seule solution pour résoudre un problème spécifique à Safari, MacIntosh ou Edge (et c'est pas comme si c'était si rare&nbsp;!).<br>
					<div class="code">
//browsers detection
var isiPad = navigator.userAgent.match(/iPad/i) != null;
var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
var isMac = navigator.platform.indexOf('Mac') > -1;
var isIEorEDGE = navigator.appName == 'Microsoft Internet Explorer' || (navigator.appName == "Netscape" && navigator.appVersion.indexOf('Edge') > -1);
					</div>
				</p>
				
				<h2>Tout en un</h2>
				<p>
					J'ai regroupé tous ces petits morceaux de code dans un fichier qu'il ne vous reste plus qu'à télécharger&nbsp;!<br>
					<a href="js/snippets.js" target="_blank" download>snippets.js</a>
				</p>
			</div>
		
		</main>
		<script src="js/snippets.js"></script>
	</body>
</html>