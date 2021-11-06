<script language="javascript">
//==================================================================================================
// Configuration properties
//==================================================================================================
mtDropDown.spacerGif = "";                     // path to a transparent spacer gif
mtDropDown.dingbatOn = "";          // path to the active sub menu dingbat
mtDropDown.dingbatOff = "";         // path to the inactive sub menu dingbat
mtDropDown.dingbatSize = 200;                            // size of the dingbat (square shape assumed)
mtDropDown.menuPadding = 5;                             // padding between menu border and items grid
mtDropDown.itemPadding = 4;                             // additional padding around each item
mtDropDown.shadowSize = 2;                              // size of shadow under menu
mtDropDown.shadowOffset = 3;                            // distance shadow should be offset from leading edge
mtDropDown.shadowColor = "#888";                        // color of shadow (transparency is set in CSS)
mtDropDown.shadowPng = "";               // a PNG graphic to serve as the shadow for mac IE5
mtDropDown.backgroundColor = "#eeeeee";                   // color of the background (transparency set in CSS)
mtDropDown.backgroundPng = "";          // a PNG graphic to server as the background for mac IE5
mtDropDown.hideDelay = 1000;                            // number of milliseconds to wait before hiding a menu
mtDropDown.slideTime = 200;                             // number of milliseconds it takes to open and close a menu


//==================================================================================================
// Internal use properties
//==================================================================================================
mtDropDown.reference = {topLeft:1,topRight:2,bottomLeft:3,bottomRight:4};
mtDropDown.direction = {down:1,right:2};
mtDropDown.registry = [];
mtDropDown._maxZ = 100;



//==================================================================================================
// Static methods
//==================================================================================================
// supporting win ie5+, mac ie5.1+ and gecko >= mozilla 1.0
mtDropDown.isSupported = function() {
        var ua = navigator.userAgent.toLowerCase();
		var pf = navigator.platform.toLowerCase();
        var an = navigator.appName;
        var r = false;

        if (ua.indexOf("gecko") > -1 && navigator.productSub >= 20020605) r = true; // gecko >= moz 1.0
        else if (an == "Microsoft Internet Explorer") {
                if (document.getElementById) { // ie5.1+ mac,win
                        if (pf.indexOf("mac") == 0) {
							r = /msie (\d(.\d*)?)/.test(ua) && Number(RegExp.$1) >= 5.1;
						}
						else r = true;
                }
        }

        return true;
}

// call this in onload once menus have been created
mtDropDown.initialize = function() {
        for (var i = 0, menu = null; menu = this.registry[i]; i++) {
                menu.initialize();
        }
}

// call this in document body to write out menu html
mtDropDown.renderAll = function() {
        var aMenuHtml = [];
        for (var i = 0, menu = null; menu = this.registry[i]; i++) {
                aMenuHtml[i] = menu.toString();
        }
        document.write(aMenuHtml.join(""));
}

//==================================================================================================
// mtDropDown constructor (only called internally)
//==================================================================================================
// oActuator            : The thing that causes the menu to be shown when it is mousedover. Either a
//                        reference to an HTML element, or a mtDropDownItem from an existing menu.
// iDirection           : The direction to slide out. One of mtDropDown.direction.
// iLeft                : Left pixel offset of menu from actuator
// iTop                 : Top pixel offset of menu from actuator
// iReferencePoint      : Corner of actuator to measure from. One of mtDropDown.referencePoint.
// parentMenuSet        : Menuset this menu will be added to.
//==================================================================================================
function mtDropDown(oActuator, iDirection, iLeft, iTop, iReferencePoint, parentMenuSet) {
        // public methods
        this.addItem = addItem;
        this.addMenu = addMenu;
        this.toString = toString;
        this.initialize = initialize;
        this.isOpen = false;
        this.show = show;
        this.hide = hide;
        this.items = [];

        // events
        this.onactivate = new Function();       // when the menu starts to slide open
        this.ondeactivate = new Function();     // when the menu finishes sliding closed
        this.onmouseover = new Function();      // when the menu has been moused over
        this.onqueue = new Function();          // hack .. when the menu sets a timer to be closed a little while in the future
        
        // initialization
        this.index = mtDropDown.registry.length;
        mtDropDown.registry[this.index] = this;

        var id = "mtDropDown" + this.index;
        var contentHeight = null;
        var contentWidth = null;
        var childMenuSet = null;
        var animating = false;
        var childMenus = [];
        var slideAccel = -1;
        var elmCache = null;
        var ready = false;
        var _this = this;
        var a = null;

        var pos = iDirection == mtDropDown.direction.down ? "top" : "left";
        var dim = null;

        // private and public method implimentations
        function addItem(sText, sUrl,sTarget,sSiteUrl) {
                var item = new mtDropDownItem(sText, sUrl, sTarget,sSiteUrl, this);
                item._index = this.items.length;
                this.items[item._index] = item;
        }

        function addMenu(oMenuItem) {
                if (!oMenuItem.parentMenu == this) throw new Error("Cannot add a menu here");

                if (childMenuSet == null) childMenuSet = new mtDropDownSet(mtDropDown.direction.right, 230, 0, mtDropDown.reference.bottomLeft);

                var m = childMenuSet.addMenu(oMenuItem);

                childMenus[oMenuItem._index] = m;
                m.onmouseover = child_mouseover;
                m.ondeactivate = child_deactivate;
                m.onqueue = child_queue;

                return m;
        }

        function initialize() {
                initCache();
                initEvents();
                initSize();
                ready = true;
        }

        function show() {
                //dbg_dump("show");
                if (ready) {
                        _this.isOpen = true;
                        animating = true;
                        setContainerPos();
                        elmCache["clip"].style.visibility = "visible";
                        elmCache["clip"].style.zIndex = mtDropDown._maxZ++;
                        //dbg_dump("maxZ: " + mtDropDown._maxZ);
                        slideStart();
                        _this.onactivate();
                }
        }

        function hide() {
                if (ready) {
                        _this.isOpen = false;
                        animating = true;

                        for (var i = 0, item = null; item = elmCache.item[i]; i++) 
                                dehighlight(item);

                        if (childMenuSet) childMenuSet.hide();

                        slideStart();
                        _this.ondeactivate();
                }
        }

        function setContainerPos() {
                var sub = oActuator.constructor == mtDropDownItem; 
                var act = sub ? oActuator.parentMenu.elmCache["item"][oActuator._index] : oActuator; 
                var el = act;
                
                var x = 0;
                var y = 0;

                
                var minX = 0;
                var maxX = (window.innerWidth ? window.innerWidth : document.body.clientWidth) - parseInt(elmCache["clip"].style.width);
                var minY = 0;
                var maxY = (window.innerHeight ? window.innerHeight : document.body.clientHeight) - parseInt(elmCache["clip"].style.height);

                // add up all offsets... subtract any scroll offset
                while (sub ? el.parentNode.className.indexOf("mtDropdownMenu") == -1 : el.offsetParent) {
                        x += el.offsetLeft;
                        y += el.offsetTop;

                        if (el.scrollLeft) x -= el.scrollLeft;
                        if (el.scrollTop) y -= el.scrollTop;
                        
                        el = el.offsetParent;
                }

                if (oActuator.constructor == mtDropDownItem) {
                        x += parseInt(el.parentNode.style.left);
                        y += parseInt(el.parentNode.style.top);
                }

                switch (iReferencePoint) {
                        case mtDropDown.reference.topLeft:
                                break;
                        case mtDropDown.reference.topRight:
                                x += act.offsetWidth;
                                break;
                        case mtDropDown.reference.bottomLeft:
                                y += act.offsetHeight;
                                break;
                        case mtDropDown.reference.bottomRight:
                                x += act.offsetWidth;
                                y += act.offsetHeight;
                                break;
                }

                x += iLeft;
//                y += iTop;
//				x+=3;
				//y+=3;

                x = Math.max(Math.min(x, maxX), minX);
                //y = Math.max(Math.min(y, maxY), minY);

                elmCache["clip"].style.left = x + "px";
                elmCache["clip"].style.top = y + "px";
        }

        function slideStart() {
                var x0 = parseInt(elmCache["content"].style[pos]);
                var x1 = _this.isOpen ? 0 : -dim;

                if (a != null) a.stop();
                a = new Accelimation(x0, x1, mtDropDown.slideTime, slideAccel);

                a.onframe = slideFrame;
                a.onend = slideEnd;

                a.start();
        }

        function slideFrame(x) {
                elmCache["content"].style[pos] = x + "px";
        }

        function slideEnd() {
                if (!_this.isOpen) elmCache["clip"].style.visibility = "hidden";
                animating = false;
        }

        function initSize() {
                // everything is based off the size of the items table...
				
                var ow = elmCache["items"].offsetWidth;
                var oh = elmCache["items"].offsetHeight;
                var ua = navigator.userAgent.toLowerCase();

                // clipping container should be ow/oh + the size of the shadow
                elmCache["clip"].style.width = ow + mtDropDown.shadowSize +  2 + "px";
                elmCache["clip"].style.height = oh + mtDropDown.shadowSize + 2 + "px";

                // same with content...
                elmCache["content"].style.width = ow + mtDropDown.shadowSize + "px";
                elmCache["content"].style.height = oh + mtDropDown.shadowSize + "px";

                contentHeight = oh + mtDropDown.shadowSize;
                contentWidth = ow + mtDropDown.shadowSize;
                
                dim = iDirection == mtDropDown.direction.down ? contentHeight : contentWidth;

                // set initially closed
                elmCache["content"].style[pos] = -dim - mtDropDown.shadowSize + "px";
                elmCache["clip"].style.visibility = "hidden";

                // if *not* mac/ie 5
                if (ua.indexOf("mac") == -1 || ua.indexOf("gecko") > -1) {
                        // set background div to offset size
                        elmCache["background"].style.width = ow + "px";
                        elmCache["background"].style.height = oh + "px";
                        elmCache["background"].style.backgroundColor = mtDropDown.backgroundColor;

/* mereva                        // shadow left starts at offset left and is offsetHeight pixels high
                        elmCache["shadowRight"].style.left = ow + "px";
                        elmCache["shadowRight"].style.height = oh - (mtDropDown.shadowOffset - mtDropDown.shadowSize) + "px";
                        elmCache["shadowRight"].style.backgroundColor = mtDropDown.shadowColor;

                        // shadow bottom starts at offset height and is offsetWidth - shadowOffset 
                        // pixels wide (we don't want the bottom and right shadows to overlap or we 
                        // get an extra bright bottom-right corner)
                        elmCache["shadowBottom"].style.top = oh + "px";
                        elmCache["shadowBottom"].style.width = ow - mtDropDown.shadowOffset + "px";
                        elmCache["shadowBottom"].style.backgroundColor = mtDropDown.shadowColor; mereva */
                }
                // mac ie is a little different because we use a PNG for the transparency
                else {
                        // set background div to offset size
                        elmCache["background"].firstChild.src = mtDropDown.backgroundPng;
                        elmCache["background"].firstChild.width = ow;
                        elmCache["background"].firstChild.height = oh;

/* mereva                           // shadow left starts at offset left and is offsetHeight pixels high
                        elmCache["shadowRight"].firstChild.src = mtDropDown.shadowPng;
                        elmCache["shadowRight"].style.left = ow + "px";
                        elmCache["shadowRight"].firstChild.width = mtDropDown.shadowSize;
                        elmCache["shadowRight"].firstChild.height = oh - (mtDropDown.shadowOffset - mtDropDown.shadowSize);

                        // shadow bottom starts at offset height and is offsetWidth - shadowOffset 
                        // pixels wide (we don't want the bottom and right shadows to overlap or we 
                        // get an extra bright bottom-right corner)
                        elmCache["shadowBottom"].firstChild.src = mtDropDown.shadowPng;
                        elmCache["shadowBottom"].style.top = oh + "px";
                        elmCache["shadowBottom"].firstChild.height = mtDropDown.shadowSize;
                        elmCache["shadowBottom"].firstChild.width = ow - mtDropDown.shadowOffset; mereva */
                }
        }
        
        function initCache() {
                var menu = document.getElementById(id);
                var all = menu.all ? menu.all : menu.getElementsByTagName("*"); // IE/win doesn't support * syntax, but does have the document.all thing

                elmCache = {};
                elmCache["clip"] = menu;
                elmCache["item"] = [];
                
                for (var i = 0, elm = null; elm = all[i]; i++) {
                        switch (elm.className) {
                                case "items":
                                case "content":
                                case "background":
                               /* mereva case "shadowRight":
                                case "shadowBottom": mereva */
                                        elmCache[elm.className] = elm;
                                        break;
                                case "item":
                                        elm._index = elmCache["item"].length;
                                        elmCache["item"][elm._index] = elm;
                                        break;
                        }
                }

                // hack!
                _this.elmCache = elmCache;
        }

        function initEvents() {
                // hook item mouseover
                for (var i = 0, item = null; item = elmCache.item[i]; i++) {
                        item.onmouseover = item_mouseover;
                        item.onmouseout = item_mouseout;
                        item.onclick = item_click;
                }

                // hook actuation
                if (typeof oActuator.tagName != "undefined") {
                        oActuator.onmouseover = actuator_mouseover;
                        oActuator.onmouseout = actuator_mouseout;
                }

                // hook menu mouseover
                elmCache["content"].onmouseover = content_mouseover;
                elmCache["content"].onmouseout = content_mouseout;
        }

        function highlight(oRow) {
                oRow.className = "item hover";
                if (childMenus[oRow._index]) 
                        oRow.lastChild.firstChild.src = mtDropDown.dingbatOn;
        }

        function dehighlight(oRow) {
                oRow.className = "item";
                if (childMenus[oRow._index]) 
                        oRow.lastChild.firstChild.src = mtDropDown.dingbatOff;
        }

        function item_mouseover() {
                if (!animating) {
                        highlight(this);

                        if (childMenus[this._index]) 
                                childMenuSet.showMenu(childMenus[this._index]);
                        else if (childMenuSet) childMenuSet.hide();
                }
        }

        function item_mouseout() {
                if (!animating) {
                        if (childMenus[this._index])
                                childMenuSet.hideMenu(childMenus[this._index]);
                        else    // otherwise child_deactivate will do this
                                dehighlight(this);
                }
        }

        function item_click() {
                if (!animating) {
                        if (_this.items[this._index].url) 
							if (_this.items[this._index].target == 0) {
		                        if (_this.items[this._index].siteUrls) {
									var posHttp;
									posHttp = _this.items[this._index].url.indexOf('http://');
									if (posHttp<0)
		                                location.href = _this.items[this._index].siteUrls + '/' +  _this.items[this._index].url;
									else
										location.href =  _this.items[this._index].url;
								}
							}
							else
								window.open(_this.items[this._index].url);

                }
        }

        function actuator_mouseover() {
                parentMenuSet.showMenu(_this);
        }

        function actuator_mouseout() {
                parentMenuSet.hideMenu(_this);
        }

        function content_mouseover() {
                if (!animating) {
                        parentMenuSet.showMenu(_this);
                        _this.onmouseover();
                }
        }

        function content_mouseout() {
                if (!animating) {
                        parentMenuSet.hideMenu(_this);
                }
        }

        function child_mouseover() {
                if (!animating) {
                        parentMenuSet.showMenu(_this);
                }
        }

        function child_deactivate() {
                for (var i = 0; i < childMenus.length; i++) {
                        if (childMenus[i] == this) {
                                dehighlight(elmCache["item"][i]);
                                break;
                        }
                }
        }

        function child_queue() {
                parentMenuSet.hideMenu(_this);
        }

        function toString() {
                var aHtml = [];
                var sClassName = "mtDropdownMenu" + (oActuator.constructor != mtDropDownItem ? " top" : "");

                for (var i = 0, item = null; item = this.items[i]; i++) {
                        aHtml[i] = item.toString(childMenus[i]);
                }

                return '<div id="' + id + '" class="' + sClassName + '">' + 
                        '<div class="content"><table class="items" cellpadding="0" cellspacing="0" border="0">' + 
                        '<tr><td colspan="2"><img src="' + mtDropDown.spacerGif + '" width="1" height="' + mtDropDown.menuPadding + '"></td></tr>' + 
                        aHtml.join('') + 
                        '<tr><td colspan="2"><img src="' + mtDropDown.spacerGif + '" width="1" height="' + mtDropDown.menuPadding + '"></td></tr></table>' + 
                        '<div class="shadowBottom"><img src="' + mtDropDown.spacerGif + '" width="1" height="1"></div>' + 
                        '<div class="shadowRight"><img src="' + mtDropDown.spacerGif + '" width="1" height="1"></div>' + 
		        '<div class="background"><img src="' + mtDropDown.spacerGif + '" width="1" height="1"></div>' + 
	                '</div></div>';
        }
}


//==================================================================================================
// mtDropDownSet
//==================================================================================================
// iDirection           : The direction to slide out. One of mtDropDown.direction.
// iLeft                : Left pixel offset of menus from actuator
// iTop                 : Top pixel offset of menus from actuator
// iReferencePoint      : Corner of actuator to measure from. One of mtDropDown.referencePoint.
//==================================================================================================
mtDropDownSet.registry = [];

function mtDropDownSet(iDirection, iLeft, iTop, iReferencePoint) {
        // public methods
        this.addMenu = addMenu;
        this.showMenu = showMenu;
        this.hideMenu = hideMenu;
        this.hide = hide;

        // initialization
        var menus = [];
        var _this = this;
        var current = null;

        this.index = mtDropDownSet.registry.length;
        mtDropDownSet.registry[this.index] = this;

        // method implimentations...
        function addMenu(oActuator) {
                var m = new mtDropDown(oActuator, iDirection, iLeft, iTop, iReferencePoint, this);
                menus[menus.length] = m;
                return m;
        }

        function showMenu(oMenu) {
                if (oMenu != current) {
                        // close currently open menu
                        if (current != null) hide(current);        

                        // set current menu to this one
                        current = oMenu;

                        // if this menu is closed, open it
                        oMenu.show();
                }
                else {
                        // hide pending calls to close this menu
                        cancelHide(oMenu);
                }
        }

        function hideMenu(oMenu) {
                //dbg_dump("hideMenu a " + oMenu.index);
                if (current == oMenu && oMenu.isOpen) {
                        //dbg_dump("hideMenu b " + oMenu.index);
                        if (!oMenu.hideTimer) scheduleHide(oMenu);
                }
        }

        function scheduleHide(oMenu) {
                //dbg_dump("scheduleHide " + oMenu.index);
                oMenu.onqueue();
                oMenu.hideTimer = window.setTimeout("mtDropDownSet.registry[" + _this.index + "].hide(mtDropDown.registry[" + oMenu.index + "])", mtDropDown.hideDelay);
        }

        function cancelHide(oMenu) {
                //dbg_dump("cancelHide " + oMenu.index);
                if (oMenu.hideTimer) {
                        window.clearTimeout(oMenu.hideTimer);
                        oMenu.hideTimer = null;
                }
        }

        function hide(oMenu) {   
                if (!oMenu && current) oMenu = current;

                if (oMenu && current == oMenu && oMenu.isOpen) {
                        //dbg_dump("hideA " + oMenu.index);
                        cancelHide(oMenu);
                        current = null;
                        oMenu.hideTimer = null;
                        oMenu.hide();
                }
        }

}

//==================================================================================================
// mtDropDownItem (internal)
// represents an item in a dropdown
//==================================================================================================
// sText        : The item display text
// sUrl         : URL to load when the item is clicked
// oParent      : Menu this item is a part of
//==================================================================================================
function mtDropDownItem(sText, sUrl, sTarget, sSiteUrl, oParent) {
        this.toString = toString;
        this.text = sText;
        this.url = sUrl;
		this.target = sTarget;
		this.siteUrls = sSiteUrl;
        this.parentMenu = oParent;


        function toString(bDingbat) {
                var sDingbat = bDingbat ? mtDropDown.dingbatOff : mtDropDown.spacerGif;
                var iEdgePadding = mtDropDown.itemPadding + mtDropDown.menuPadding;
                var sPaddingLeft = "padding:" + mtDropDown.itemPadding + "px; padding-left:" + iEdgePadding + "px;"
                var sPaddingRight = "padding:" + mtDropDown.itemPadding + "px; padding-right:" + iEdgePadding + "px;"
                var dingbatSize = mtDropDown.dingbatSize;
//			    var dingbatSize = 200;
                return '<tr class="item"><td nowrap width="' + dingbatSize + '" style="' + sPaddingLeft + '">' + 
                      sText + '</td><td width="1" style="' + sPaddingRight + '">' + 
                        '<img src="' + sDingbat + '" width="1" height="1"></td></tr>';
	}
}






//=====================================================================
// Accel[erated] [an]imation object
// change a property of an object over time in an accelerated fashion
//=====================================================================
// obj  : reference to the object whose property you'd like to animate
// prop : property you would like to change eg: "left"
// to   : final value of prop
// time : time the animation should take to run
// zip	: optional. specify the zippiness of the acceleration. pick a 
//		  number between -1 and 1 where -1 is full decelerated, 1 is 
//		  full accelerated, and 0 is linear (no acceleration). default
//		  is 0.
// unit	: optional. specify the units for use with prop. default is 
//		  "px".
//=====================================================================
// bezier functions lifted from the lib_animation.js file in the 
// 13th Parallel API. www.13thparallel.org
//=====================================================================

function Accelimation(from, to, time, zip) {
	if (typeof zip  == "undefined") zip  = 0;
	if (typeof unit == "undefined") unit = "px";

        this.x0         = from;
        this.x1		= to;
	this.dt		= time;
	this.zip	= -zip;
	this.unit	= unit;
	this.timer	= null;
	this.onend	= new Function();
        this.onframe    = new Function();
}



//=====================================================================
// public methods
//=====================================================================

// after you create an accelimation, you call this to start it-a runnin'
Accelimation.prototype.start = function() {
	this.t0 = new Date().getTime();
	this.t1 = this.t0 + this.dt;
	var dx	= this.x1 - this.x0;
	this.c1 = this.x0 + ((1 + this.zip) * dx / 3);
	this.c2 = this.x0 + ((2 + this.zip) * dx / 3);
	Accelimation._add(this);
}

// and if you need to stop it early for some reason...
Accelimation.prototype.stop = function() {
	Accelimation._remove(this);
}



//=====================================================================
// private methods
//=====================================================================

// paints one frame. gets called by Accelimation._paintAll.
Accelimation.prototype._paint = function(time) {
	if (time < this.t1) {
		var elapsed = time - this.t0;
	        this.onframe(Accelimation._getBezier(elapsed/this.dt,this.x0,this.x1,this.c1,this.c2));
        }
	else this._end();
}

// ends the animation
Accelimation.prototype._end = function() {
	Accelimation._remove(this);
        this.onframe(this.x1);
	this.onend();
}




//=====================================================================
// static methods (all private)
//=====================================================================

// add a function to the list of ones to call periodically
Accelimation._add = function(o) {
	var index = this.instances.length;
	this.instances[index] = o;
	// if this is the first one, start the engine
	if (this.instances.length == 1) {
		this.timerID = window.setInterval("Accelimation._paintAll()", this.targetRes);
	}
}

// remove a function from the list
Accelimation._remove = function(o) {
	for (var i = 0; i < this.instances.length; i++) {
		if (o == this.instances[i]) {
			this.instances = this.instances.slice(0,i).concat( this.instances.slice(i+1) );
			break;
		}
	}
	// if that was the last one, stop the engine
	if (this.instances.length == 0) {
		window.clearInterval(this.timerID);
		this.timerID = null;
	}
}

// "engine" - call each function in the list every so often
Accelimation._paintAll = function() {
	var now = new Date().getTime();
	for (var i = 0; i < this.instances.length; i++) {
		this.instances[i]._paint(now);
	}
}


// Bezier functions:
Accelimation._B1 = function(t) { return t*t*t }
Accelimation._B2 = function(t) { return 3*t*t*(1-t) }
Accelimation._B3 = function(t) { return 3*t*(1-t)*(1-t) }
Accelimation._B4 = function(t) { return (1-t)*(1-t)*(1-t) }


//Finds the coordinates of a point at a certain stage through a bezier curve
Accelimation._getBezier = function(percent,startPos,endPos,control1,control2) {
	return endPos * this._B1(percent) + control2 * this._B2(percent) + control1 * this._B3(percent) + startPos * this._B4(percent);
}


//=====================================================================
// static properties
//=====================================================================

Accelimation.instances = [];
Accelimation.targetRes = 10;
Accelimation.timerID = null;
</script>