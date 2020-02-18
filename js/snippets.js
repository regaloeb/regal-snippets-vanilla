(function () {
	//forEach NodeList & HTMLCollection polyfill
	NodeList.prototype.forEach||(NodeList.prototype.forEach=Array.prototype.forEach),
	HTMLCollection.prototype.forEach||(HTMLCollection.prototype.forEach=Array.prototype.forEach);

	//animation and transion end event
	function whichAnimationEvent(){var n,i=document.createElement("fakeelement"),t={animation:"animationend",OAnimation:"oAnimationEnd",MozAnimation:"animationend",WebkitAnimation:"webkitAnimationEnd"};for(n in t)if(void 0!==i.style[n])return t[n]}function whichTransitionEvent(){var n,i=document.createElement("fakeelement"),t={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(n in t)if(void 0!==i.style[n])return t[n]}var animationEvent=whichAnimationEvent(),transitionEvent=whichTransitionEvent();

	//closest
	Element.prototype.matches||(Element.prototype.matches=Element.prototype.msMatchesSelector||Element.prototype.webkitMatchesSelector),Element.prototype.closest||(Element.prototype.closest=function(e){var t=this;if(!document.documentElement.contains(t))return null;do{if(t.matches(e))return t;t=t.parentElement||t.parentNode}while(null!==t&&1==t.nodeType);return null});

	//classList polyfill /for IE9-)
	!function(){function t(t){this.el=t;for(var n=t.className.replace(/^\s+|\s+$/g,"").split(/\s+/),i=0;i<n.length;i++)e.call(this,n[i])}function n(t,n,i){Object.defineProperty?Object.defineProperty(t,n,{get:i}):t.__defineGetter__(n,i)}if(!("undefined"==typeof window.Element||"classList"in document.documentElement)){var i=Array.prototype,e=i.push,s=i.splice,o=i.join;t.prototype={add:function(t){this.contains(t)||(e.call(this,t),this.el.className=this.toString())},contains:function(t){return-1!=this.el.className.indexOf(t)},item:function(t){return this[t]||null},remove:function(t){if(this.contains(t)){for(var n=0;n<this.length&&this[n]!=t;n++);s.call(this,n,1),this.el.className=this.toString()}},toString:function(){return o.call(this," ")},toggle:function(t){return this.contains(t)?this.remove(t):this.add(t),this.contains(t)}},window.DOMTokenList=t,n(Element.prototype,"classList",function(){return new t(this)})}}();

	//RequestAnimationFrame Polyfill
	!function(){for(var n=0,i=["ms","moz","webkit","o"],e=0;e<i.length&&!window.requestAnimationFrame;++e)window.requestAnimationFrame=window[i[e]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[i[e]+"CancelAnimationFrame"]||window[i[e]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(i){var e=(new Date).getTime(),a=Math.max(0,16-(e-n)),o=window.setTimeout(function(){i(e+a)},a);return n=e+a,o}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(n){clearTimeout(n)})}();

	function slideDown(elem) {
		var h = 0;
		elem.children.forEach(function(elt){
			h = h + elt.getBoundingClientRect().height;
		});
		elem.style.maxHeight = h + 'px';
	}
	function slideUp(elem) {
		elem.style.maxHeight = '0';
	}

	//triggerEvent
	function triggerEvent(el, type){
		if ('createEvent' in document) {
			var e = document.createEvent('HTMLEvents');
			e.initEvent(type, false, true);
			el.dispatchEvent(e);
		}
		else {
			var e = document.createEventObject();
			e.eventType = type;
			el.fireEvent('on' + e.eventType, e);
		}
	};

	//wait until scroll is done
	window.addEventListener('scroll', function() {
		if(this.scrollTO) clearTimeout(this.scrollTO);
		this.scrollTO = setTimeout(function() {
			triggerEvent(this, 'scrollEnd');
		}, 500);
	}, false);
	window.addEventListener('scrollEnd', function() {
		console.log("scrollEnd");
	});	

	//wait until resize is done
	window.addEventListener('resize', function() {
		if(this.resizeTO) clearTimeout(this.resizeTO);
		this.resizeTO = setTimeout(function() { 
			triggerEvent(this, 'resizeEnd');
		}, 500);
	});
	window.addEventListener('resizeEnd', function() {
		console.log("resizeEnd");
	});

	//object-fit
	function objectFit(){
		if('objectFit' in document.documentElement.style === false) {
			var liste = document.querySelectorAll('.js-object-fit');
			liste.forEach(function(elt){
				var imgUrl = elt.querySelector('img').getAttribute('src');
				if (imgUrl) {
					elt.style.backgroundImage = 'url(' + imgUrl + ')';
					elt.style.backgroundPosition = (elt.getAttribute('data-bg-pos')) ? elt.getAttribute('data-bg-pos') : '50% 50%';
					elt.style.backgroundRepeat = 'no-repeat';
					elt.style.backgroundSize = 'cover';
					elt.classList.add('compat-object-fit');
				}
			});
		}
	};
	objectFit();

	//ajaxCall
	function ajaxCall(url, params, method, callback){
		var xhr = new XMLHttpRequest();
		var hurle = (method.toLowerCase() === 'get') ? url + '?' + params : url;
		xhr.open(method, hurle);
		var postparams = (method.toLowerCase() === 'post') ? params : null;
		xhr.send(postparams);
		xhr.onreadystatechange = function () {
			var DONE = 4; // readyState 4 means the request is done.
			var OK = 200; // status 200 is a successful return.
			if (xhr.readyState === DONE) {
				if (xhr.status === OK) {
					//console.log(xhr.responseText); // 'This is the returned text.'
					callback(xhr.responseText);
				}
				else {
					console.log('Error: ' + xhr.status); // An error occurred during the request.
				}
			}
		};
	};

	var w = window,
		d = document,
		e = d.documentElement,
		g = d.getElementsByTagName('body')[0];

	var windowHeight = w.innerHeight||e.clientHeight||g.clientHeight;
	var windowWidth = w.innerWidth||e.clientWidth||g.clientWidth;
	var mobileLimit = 650;
	var desktopLimit = 1024;
	var isMobileContext = (windowWidth > mobileLimit) ? false : true;
	window.addEventListener('resizeEnd', function() {
		windowHeight = w.innerHeight||e.clientHeight||g.clientHeight;
		windowWidth = w.innerWidth||e.clientWidth||g.clientWidth;
		isMobileContext = (windowWidth > mobileLimit) ? false : true;
	});

	//browsers detection
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
	var isMac = navigator.platform.indexOf('Mac') > -1;
	var isIEorEDGE = navigator.appName == 'Microsoft Internet Explorer' || (navigator.appName == "Netscape" && navigator.appVersion.indexOf('Edge') > -1);
})();
