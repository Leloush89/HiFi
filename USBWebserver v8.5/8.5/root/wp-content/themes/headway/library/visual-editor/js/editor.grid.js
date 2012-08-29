/*
 * Headway Grid 0.0.1
 *
 * Copyright 2011-2012, Headway Themes, LLC
 *
 * http://headwaythemes.com
 */
 (function(a,b){a.widget("ui.grid",a.ui.mouse,{options:{columns:null,columnWidth:null,gutterWidth:null,yGridInterval:10,minBlockHeight:40,selectedBlocksContainerClass:"selected-blocks-container",defaultBlockClass:"block",defaultBlockContentClass:"block-content"},_create:function(){grid=this;if(!this.options.columns||!this.options.columnWidth||this.options.gutterWidth===null){return console.error("The grid widget was not supplied all of the required arguments.",this.element,this.options)}this.container=a(this.element).contents().find(this.options.container);this.contents=a(this.element).contents();this.focused=false;this.dragged=false;this.helper=a("<div class='ui-grid-helper block'></div>");this.offset=this.container.offset();this.container.addClass("ui-grid");this.container.disableSelection();this._initResizable(this.container.children("."+this.options.defaultBlockClass.replace(".","")));this._initDraggable(this.container.children("."+this.options.defaultBlockClass.replace(".","")));this._bindDoubleClick();this._bindIFrameMouse()},destroy:function(){this.element.removeClass("ui-grid ui-grid-disabled").removeData("grid").unbind(".grid");this._mouseDestroy();this.contents.unbind("mousedown",this._iFrameMouseDown);this.contents.unbind("mouseup",this._iFrameMouseUp);this.contents.unbind("mousemove",this._iFrameMouseMove);this.element.unbind("mouseleave",this._iFrameMouseUp);a.Widget.prototype.destroy.apply(this,arguments);return this},iframeElement:function(c){return a(this.element).contents().find(c)},resetDraggableResizable:function(){this._initResizable(this.container.children("."+this.options.defaultBlockClass.replace(".","")));this._initDraggable(this.container.children("."+this.options.defaultBlockClass.replace(".","")))},_bindIFrameMouse:function(){this.contents.bind("mousedown",this._iFrameMouseDown);this.contents.bind("mouseup",this._iFrameMouseUp);this.element.bind("mouseleave",this._iFrameMouseUp)},_iFrameMouseDown:function(c){if(c.which!==1){return false}grid=Headway.iframe.data("grid");grid.element.focus();grid.mouseEventDown=c;grid.mouseEventElement=a(grid.mouseEventDown.originalEvent.target);if(typeof grid.bindMouseMove==="undefined"){grid.contents.mousemove(grid._iFrameMouseMove);grid.bindMouseMove=true}if(grid.mouseEventElement.hasClass("ui-resizable-handle")){getBlock(grid.mouseEventElement).data("resizable")._mouseDown(c)}else{if(getBlock(grid.mouseEventElement)&&getBlock(grid.mouseEventElement).hasClass(grid.options.defaultBlockClass.replace(".",""))){if(getBlock(grid.mouseEventElement).data("draggable")){getBlock(grid.mouseEventElement).data("draggable")._mouseDown(c)}}else{if(grid.element.data("grid")&&(grid.mouseEventElement[0]==grid.container[0]||grid.mouseEventElement[0]==grid.container.parents("div.wrapper")[0])){grid.element.data("grid")._mouseDown(c)}}}},_iFrameMouseMove:function(c){if(typeof grid.mouseEventDown!=="undefined"){if(grid.mouseEventElement.hasClass("ui-resizable-handle")){getBlock(grid.mouseEventElement).data("resizable")._mouseMove(c)}else{if(getBlock(grid.mouseEventElement)&&getBlock(grid.mouseEventElement).hasClass(grid.options.defaultBlockClass.replace(".",""))){if(getBlock(grid.mouseEventElement).data("draggable")){getBlock(grid.mouseEventElement).data("draggable")._mouseMove(c)}}else{if(grid.element.data("grid")&&(grid.mouseEventElement[0]==grid.container[0]||grid.mouseEventElement[0]==grid.container.parents("div.wrapper")[0])){grid.element.data("grid")._mouseMove(c)}}}}else{if(typeof doingHoverBlockToTop=="undefined"){doingHoverBlockToTop=true;setTimeout(function(){var f=[];var e=c.pageX;var d=c.pageY;$i(".block").each(function(){var h=a(this).offset().left;var j=a(this).offset().top;var g=h+a(this).width();var i=j+a(this).height();if(e<h||e>g){return}if(d<j||d>i){return}f.push(a(this))});f.sort(function(h,g){if(g.width()*g.height()>h.width()*h.height()){return 1}return 0});grid.sendBlockToTop(a(f.pop()));delete doingHoverBlockToTop},50)}}},_iFrameMouseUp:function(d){if(typeof grid.mouseEventDown!=="undefined"){var e=getBlock(grid.mouseEventElement);var c=grid.element;if(e&&typeof e.data("resizable")!="undefined"){e.data("resizable")._mouseUp(d)}if(e&&typeof e.data("draggable")!="undefined"){e.data("draggable")._mouseUp(d)}if(typeof c!="undefined"&&typeof c.data("grid")!="undefined"){c.data("grid")._mouseUp(d)}delete grid.mouseEventDown}},_mouseStart:function(c){if(!c||grid.container.hasClass("grouping-active")){return}this.mouseStartPosition=[c.pageX-this.container.offset().left,c.pageY-this.container.offset().top];this._trigger("start",c);a(this.container).append(this.helper);this.helper.css({width:this.options.columnWidth,height:0,top:0,left:0,display:"none"});return true},_mouseDrag:function(c){if(!c||grid.container.hasClass("grouping-active")){return}this.dragged=true;var f=this.mouseStartPosition[0];var n=this.mouseStartPosition[1];var d=c.pageX-a(this.container).offset().left;var l=c.pageY-a(this.container).offset().top;if(f>d){var j=d;d=f;f=j}if(n>l){var j=l;l=n;n=j}var h=a(this.container).offset().left;var i=a(this.container).offset().top;var p=a(this.container).height();var m=a(this.container).width();if(d>=a(this.container).width()&&f>=a(this.container).width()){return}if(l>=a(this.container).height()&&n>=a(this.container).height()){return}if(f<0){f=0}if(n<0){n=0}if(l>p){l=p}var o=f.toNearest(this.options.columnWidth+this.options.gutterWidth);var g=n.toNearest(this.options.yGridInterval);var k=d.toNearest(this.options.columnWidth+this.options.gutterWidth)-o-this.options.gutterWidth;var e=l.toNearest(this.options.yGridInterval)-n.toNearest(this.options.yGridInterval);Headway.blankBlockOptions={display:"block",left:o,top:g,width:k,height:e};if(o+k>(this.options.columns*(this.options.columnWidth+this.options.gutterWidth))){Headway.blankBlockOptions.width=m-Headway.blankBlockOptions.left}if(c.pageY>(i+p)){Headway.blankBlockOptions.height=p-g}this.helper.css(Headway.blankBlockOptions);if(Headway.blankBlockOptions.height<this.options.minBlockHeight){this.helper.addClass("block-error")}else{if(this.helper.hasClass("block-error")){this.helper.removeClass("block-error")}}this._trigger("drag",c);return false},_mouseStop:function(d){if(!d||grid.container.hasClass("grouping-active")){return}this.dragged=false;this._trigger("stop",d);Headway.blankBlockOptions={width:this.helper.width(),height:this.helper.height(),top:this.helper.position().top,left:this.helper.position().left};this.helper.remove();if(Headway.blankBlockOptions.width<this.options.columnWidth||Headway.blankBlockOptions.height<this.options.minBlockHeight){return false}if(Headway.blankBlockOptions.left+Headway.blankBlockOptions.width>this.options.columns*(this.options.columnWidth+this.options.gutterWidth)+20){var c=(Headway.blankBlockOptions.left+Headway.blankBlockOptions.width)-(this.options.columns*(this.options.columnWidth+this.options.gutterWidth)-20);Headway.blankBlockOptions.width=Headway.blankBlockOptions.width-c}if(Headway.blankBlockOptions.width<this.options.columnWidth){Headway.blankBlockOptions.width=this.options.columnWidth}this.addBlankBlock(Headway.blankBlockOptions);this.mouseStartPosition=false;return false},_mouseUp:function(c){if(!c||grid.container.hasClass("grouping-active")){return}grid=this;a(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=false;if(c.target==this._mouseDownEvent.target){a.data(c.target,this.widgetName+".preventClickEvent",true)}this._mouseStop(c)}return false},_initResizable:function(c){grid=this;if(typeof c=="string"){c=a(c)}if(typeof c.resizable==="function"){c.resizable("destroy")}c.resizable({handles:"n, e, s, w, ne, se, sw, nw",grid:[this.options.columnWidth+this.options.gutterWidth,this.options.yGridInterval],containment:this.container,minHeight:this.options.minBlockHeight,maxWidth:this.options.columns*(this.options.columnWidth+this.options.gutterWidth),start:this._resizableStart,resize:this._resizableResize,stop:this._resizableStop})},_resizableStart:function(d,e){var g=getBlock(e.element);var f=parseInt(g.css("minHeight").replace("px",""));var c=g.height();if(f<=c){g.css("minHeight",0)}g.addClass("block-hover");g.qtip("option","hide.delay",10000);g.qtip("show");g.qtip("reposition")},_resizableResize:function(c,d){var e=getBlock(d.element);e.qtip("show");e.qtip("reposition")},_resizableStop:function(e,f){var i=getBlock(f.element);var c=Math.ceil(i.width()/(grid.options.columnWidth+grid.options.gutterWidth));var h=Math.ceil(i.position().left/(grid.options.columnWidth+grid.options.gutterWidth));var d=getBlockGridWidth(i);var g=getBlockGridLeft(i);i.removeClass("grid-width-"+d);i.addClass("grid-width-"+c);i.removeClass("grid-left-"+g);i.addClass("grid-left-"+h);i.attr({"data-grid-left":h,"data-grid-top":getBlockPositionPixels(i).top,"data-width":c,"data-height":getBlockDimensionsPixels(i).height});i.css("width","");i.css("left","");updateBlockDimensionsHidden(getBlockID(i),getBlockDimensions(i));updateBlockPositionHidden(getBlockID(i),getBlockPosition(i));blockIntersectCheck(i)?allowSaving():disallowSaving();i.qtip("option","hide.delay",25);i.qtip("show");i.qtip("reposition");i.removeClass("block-hover")},_initDraggable:function(c){if(typeof c=="string"){c=a(c)}if(typeof c.draggable==="function"){c.draggable("destroy")}grid=this;c.css("cursor","move").draggable({grid:[this.options.columnWidth+this.options.gutterWidth,this.options.yGridInterval],containment:this.iframeElement(this.options.container),scrollSpeed:40,start:this._draggableStart,stop:this._draggableStop,drag:this._draggableDrag})},_draggableStart:function(c,d){if(a(c.originalEvent.target).parents(".block-controls").length===1||a(c.originalEvent.target).parents(".block-info").length===1){a(this).draggable("stop");return false}a(this).data("dragging",true);posTopArray=[];posLeftArray=[];if(a(this).hasClass("grouped-block")){grid.container.find(".grouped-block").each(function(e){if(c.srcElement==this){return}posTopArray[e]=parseInt(a(this).css("top").replace("px",""))||0;posLeftArray[e]=parseInt(a(this).css("left").replace("px",""))||0});grid.sendBlockToTop(grid.container.find(".grouped-block"))}else{grid.container.removeClass("grouping-active");grid.container.find(".grouped-block").removeClass("grouped-block");hideTaskNotification()}beginTop=a(this).offset().top;beginLeft=a(this).offset().left;a(getBlock(d.helper)).qtip("hide")},_draggableDrag:function(d,e){var c=a(this).offset().top-beginTop;var f=a(this).offset().left-beginLeft;if(a(this).hasClass("grouped-block")){grid.container.find(".grouped-block").each(function(g){if(d.srcElement==this){return}a(this).css("top",posTopArray[g]+c);a(this).css("left",posLeftArray[g]+f)})}else{grid.container.find(".grouped-block").removeClass("grouped-block")}a(getBlock(e.helper)).qtip("hide")},_draggableStop:function(c,d){a(this).data("dragging",false);if(grid.container.find(".grouped-block").length){var e=grid.container.find(".grouped-block")}else{var e=getBlock(d.helper)}e.each(function(){var h=a(this);var g=Math.ceil(h.position().left/(grid.options.columnWidth+grid.options.gutterWidth));var f=getBlockGridLeft(h);h.removeClass("grid-left-"+f);h.addClass("grid-left-"+g);h.attr({"data-grid-left":g,"data-grid-top":getBlockPositionPixels(h).top});h.css("left","");updateBlockPositionHidden(getBlockID(h),getBlockPosition(h));if(blockIntersectCheck(h)){allowSaving()}else{disallowSaving()}});a(document).focus();a(this).data("hoverWaitTimeout",setTimeout(function(){a(getBlock(d.helper)).qtip("reposition");a(getBlock(d.helper)).qtip("show")},300))},_bindDoubleClick:function(){grid=this;this.container.delegate("."+this.options.defaultBlockClass.replace(".",""),"dblclick",function(c){if(a(c.target).parents(".block-info").length==1||a(c.target).parents(".block-controls").length==1){return false}if(a(this).hasClass("grouped-block")&&grid.container.find(".grouped-block").length===1){a(this).removeClass("grouped-block");grid.container.removeClass("grouping-active");hideTaskNotification()}else{if(a(this).hasClass("grouped-block")){a(this).removeClass("grouped-block")}else{a(this).addClass("grouped-block");grid.container.addClass("grouping-active");showTaskNotification("Mass Block Selection Mode",function(){$i(".grouped-block").removeClass("grouped-block");Headway.iframe.data("grid").container.removeClass("grouping-active")})}}})},addBlankBlock:function(o,f,g){var l={top:0,left:0,width:140,height:this.options.minBlockHeight,id:null};var o=a.extend({},l,o);if(typeof f=="undefined"){var f=true}if(typeof g=="undefined"){var g=false}var n=(o.id==false||o.id==null)?getAvailableBlockID():o.id;if(typeof n==="undefined"||!n){return false}Headway.blankBlock=a('<div><div class="block-content-fade block-content"></div><h3 class="block-type" style="display: none;"><span></span></h3></div>').attr("data-id",n).data("id",n).attr("id","block-"+n).addClass(this.options.defaultBlockClass.replace(".",""));var c="This is the ID for the block.  The ID of the block is displayed in the WordPress admin panel if it is a widget area or navigation block.  Also, this can be used with advanced developer functions.";var m="Click to change the block type.";var i="Show the options for this block.";var p="Delete this block.";Headway.blankBlock.addClass("blank-block");Headway.blankBlock.append('			<div class="block-info">				<span class="id tooltip" title="'+c+'">'+n+'</span>				<span class="type type-unknown tooltip" title="'+m+'">Unknown</span>			</div>');Headway.blankBlock.append('			<div class="block-controls">				<span class="options tooltip" title="'+i+'">Options</span>				<span class="delete tooltip" title="'+p+'">Delete</span>			</div>');var k=Headway.blankBlock;k.css({width:parseInt(o.width),height:parseInt(o.height),top:parseInt(o.top),left:parseInt(o.left),position:"absolute",visibility:"hidden"});k.appendTo(this.container);if(f){var d=String(k.width()).replace("px","");var e=Math.ceil(d/(grid.options.columnWidth+grid.options.gutterWidth));var h=String(k.position().left).replace("px","");var j=Math.ceil(h/(grid.options.columnWidth+grid.options.gutterWidth))}else{e=parseInt(o.width);j=parseInt(o.left)}k.attr({"data-width":e,"data-height":parseInt(o.height),"data-grid-top":parseInt(o.top),"data-grid-left":j});k.css("width","").addClass("grid-width-"+e);k.css("left","").addClass("grid-left-"+j);k.css("visibility","visible");this._initResizable(k);this._initDraggable(k);blockIntersectCheck(k);if(g==false){setupTooltips("iframe");showBlockTypePopup(a(Headway.blankBlock))}return k},setupBlankBlock:function(d,c){if(typeof c=="undefined"){var c=false}var f=getBlockTypeIcon(d,true);Headway.blankBlock.removeClass("blank-block");Headway.blankBlock.addClass("block-type-"+d);Headway.blankBlock.find(".block-info span.type").attr("class","").addClass("type").addClass("type-"+d).html(getBlockTypeNice(d)).css("backgroundImage","url("+f+")");loadBlockContent({blockElement:Headway.blankBlock,blockOrigin:{type:d,id:0,layout:Headway.currentLayout},blockSettings:{dimensions:getBlockDimensions(Headway.blankBlock),position:getBlockPosition(Headway.blankBlock)},});if(getBlockTypeObject(d)["fixed-height"]===true){Headway.blankBlock.addClass("block-fixed-height")}else{Headway.blankBlock.addClass("block-fluid-height")}if(!getBlockTypeObject(d)["show-content-in-grid"]){Headway.blankBlock.addClass("hide-content-in-grid")}Headway.blankBlock.find("h3.block-type span").text(getBlockTypeNice(d));Headway.blankBlock.find("h3.block-type").show();if(c==false){hideBlockTypePopup()}addNewBlockHidden(getBlockID(Headway.blankBlock),getBlockType(Headway.blankBlock));updateBlockPositionHidden(getBlockID(Headway.blankBlock),getBlockPosition(Headway.blankBlock));updateBlockDimensionsHidden(getBlockID(Headway.blankBlock),getBlockDimensions(Headway.blankBlock));if(blockIntersectCheck(Headway.blankBlock)){allowSaving()}else{disallowSaving()}var e=Headway.blankBlock;delete Headway.blankBlock;delete Headway.blankBlockOptions;if(c==false){setupTooltips("iframe")}return e},addBlock:function(d){var e={top:0,left:0,width:1,height:this.options.minBlockHeight,type:null,id:null,settings:[]};var d=a.extend({},e,d);if(this.addBlankBlock(d,false,true)){var f=this.setupBlankBlock(d.type,true);var c=getBlockID(f);a.each(d.settings,function(g,h){updatePanelInputHidden({id:g,value:h,group:"general",isBlock:"true",blockID:c});if(g=="mirror-block"){updateBlockMirrorStatus(false,f,h,false)}})}else{return false}},sendBlockToTop:function(c){if(typeof c=="string"){var c=getBlock(c)}if(!c||!c.length){return}$i(".block").css("zIndex",1);c.css("zIndex",2)}});a.extend(a.ui.grid,{version:"0.7"})})(jQuery);