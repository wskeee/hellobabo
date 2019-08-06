!function(e){var t={};function n(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(i,o,function(t){return e[t]}.bind(null,o));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=5)}([function(e,t,n){"use strict";function i(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function o(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}Object.defineProperty(t,"__esModule",{value:!0}),t.default=t.DisplayObjectUitl=void 0;var r=function(){function e(){!function(t,n){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this)}return function(e,t,n){n&&i(e,n)}(e,0,[{key:"enabled",value:function(t,n,i,o,r,a){if(null==n&&(n=!0),null==i&&(i=!0),null==o&&(o=!0),null==r&&(r=!1),null==a&&(a=e.disableAlpha),o&&(t.mouseEnabled=n),i){var s=t.filters||[];if(n){var c=s.findIndex(function(t){return t==e.blackAndWhiteFilter});-1!=c&&(s=s.splice(c,1),t.filters=s)}else s.push(e.blackAndWhiteFilter),t.filters=s}r&&(t.alpha=n?1:a)}},{key:"setEnabled",value:function(t,n,i,o,r,a){e.enabled(t,n,i,o,r,a)}},{key:"setPopBlur",value:function(t,n){var i=t.filters||[];if(null==n&&(n=!0),n)i.push(e.popBlurFilter),t.filters=i;else{var o=i.findIndex(function(t){return t==e.popBlurFilter});-1!=o&&(i=i.splice(o,1),t.filters=i)}}},{key:"scale",value:function(e,t,n,i,o){var r=!(5<arguments.length&&void 0!==arguments[5])||arguments[5],a=i/t,s=o/n,c=Math.min(a,s);return e.scaleX=e.scaleY=c,r&&(e.x=i-t*c>>1,e.y=o-n*c>>1),c}}]),e}();o(t.DisplayObjectUitl=r,"colorMatrix",new createjs.ColorMatrix),o(r,"blackAndWhiteFilter",new createjs.ColorMatrixFilter(r.colorMatrix)),o(r,"popBlurFilter",new createjs.BlurFilter(30,30,4)),o(r,"disableAlpha",.5),r.colorMatrix.adjustSaturation(-100);var a={isPC:function(){for(var e=navigator.userAgent,t=new Array("Android","iPhone","SymbianOS","Windows Phone","iPad","iPod"),n=!0,i=0;i<t.length;i++)if(0<e.indexOf(t[i])){n=!1;break}return n}};t.default=a},function(e,t,n){"use strict";function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){return!t||"object"!==i(t)&&"function"!=typeof t?function(e){if(void 0!==e)return e;throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}(e):t}function a(e){return(a=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function s(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&function(e,t){(Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}(e,t)}Object.defineProperty(t,"__esModule",{value:!0}),t.Event=t.default=void 0;var c,u,l,d=function(){function e(){return o(this,e),r(this,a(e).apply(this,arguments))}return s(e,createjs.EventDispatcher),e}();t.default=d,u="instance",l=new(c=d),u in c?Object.defineProperty(c,u,{value:l,enumerable:!0,configurable:!0,writable:!0}):c[u]=l;var h=function(){function e(t,n){var i;return o(this,e),(i=r(this,a(e).call(this,t))).data=n,i}return s(e,createjs.Event),e}();t.Event=h},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=s(n(3)),o=s(n(6)),r=s(n(7)),a=s(n(0));function s(e){return e&&e.__esModule?e:{default:e}}function c(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function u(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var l=function(){function e(){!function(t,n){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this),this.stage,this.canvas,this.anim_container,this.dom_overlay_container,this.stageWidth=0,this.stageHeight=0}return function(e,t,n){t&&c(e.prototype,t)}(e,[{key:"init",value:function(e,t,n){var o=this;window.playSound=this.playSound;var r=this.appData=window.appData;this.libs=[r.common].concat(r.scenes),this.canvas=e,this.anim_container=t,this.dom_overlay_container=n,this.stage=new createjs.Stage(this.canvas),this.loader=i.default.r,this.loader.load(this.libs),this.loader.addEventListener("progress",function(e){document.getElementById("preloader-txt").innerText=Math.round(100*e.progress)+"%"}),this.loader.addEventListener("resload",function(e){if(e.resId==r.common.id){var t=document.getElementsByClassName("preloader-box")[0];t&&(t.style.display="none"),o.initStage(),o.initView()}}),this.loader.addEventListener("complete",function(e){console.log("所有资源加载完成！")})}},{key:"initStage",value:function(){var t=this,n=this.exportRoot=new createjs.Container,i=this.stage=new createjs.StageGL(this.canvas);this.resize(),i.addChild(n),createjs.Ticker.timingMode=createjs.Ticker.RAF_SYNCHED,createjs.Ticker.framerate=e.fps,createjs.Ticker.addEventListener("tick",function(e){return t.handleTick(e)})}},{key:"initView",value:function(){var t=this.exportRoot,n=new o.default(e.w,e.h);t.addChild(n),r.default.init(a.default.isPC()?this.stage:this.canvas,n,e.w,e.h)}},{key:"playSound",value:function(e,t){return createjs.Sound.play(e)}},{key:"handleTick",value:function(e){var t=this.stage,n=this.exportRoot;n&&n.cache(0,0,this.stageWidth,this.stageHeight),t.update()}},{key:"getProjectionMatrix",value:function(t,n){var i=e.w/2,o=e.h/2,r=(n+528.25)/528.25,a=new createjs.Matrix2D;a.a=1/r,a.d=1/r;var s=new createjs.Matrix2D;return s.tx=-i,s.ty=-o,(s=s.prependMatrix(a)).tx+=i,s.ty+=o,s}},{key:"analyzeRootDepth",value:function(){var e=this.exportRoot.___camera___instance;void 0!==e&&void 0!==e.pinToObject&&(e.x=e.pinToObject.x+e.pinToObject.pinOffsetX,e.y=e.pinToObject.y+e.pinToObject.pinOffsetY,void 0!==e.pinToObject.parent&&void 0!==e.pinToObject.parent.depth&&(e.depth=e.pinToObject.parent.depth+e.pinToObject.pinOffsetZ)),this.applyLayerZDepth(this.exportRoot)}},{key:"applyLayerZDepth",value:function(t){var n=t.___camera___instance,i={x:0,y:0};if(t===this.exportRoot){var o={x:e.w/2,y:e.h/2};i.x=o.x,i.y=o.y}for(var r in t.children){var a=t.children[r];if(a!=n&&(this.applyLayerZDepth(a,n),void 0!==a.layerDepth)){a.currentFrame!=a.parent.currentFrame&&a.gotoAndPlay(a.parent.currentFrame);var s=new createjs.Matrix2D,c=new createjs.Matrix2D,u=a.layerDepth?a.layerDepth:0,l=0;if(n&&!a.isAttachedToCamera){var d=n.getMatrix();d.tx-=i.x,d.ty-=i.y,(c=d.invert()).prependTransform(i.x,i.y,1,1,0,0,0,0,0),c.appendTransform(-i.x,-i.y,1,1,0,0,0,0,0),n.depth&&(l=n.depth)}if(a.depth&&(u=a.depth),(u-=l)<-528.25)s.a=0,s.d=0;else{if(a.layerDepth){var h=this.getProjectionMatrix(t,a.layerDepth);h&&(h.invert(),s.prependMatrix(h))}s.prependMatrix(c);var f=this.getProjectionMatrix(t,u);f&&s.prependMatrix(f)}a.transformMatrix=s}}}},{key:"makeResponsive",value:function(e,t,n,i){var o=this;window.addEventListener("resize",function(r){return o.resizeCanvas(e,t,n,i)}),this.resizeCanvas(e,t,n,i)}},{key:"resizeCanvas",value:function(t,n,i,o){var r,a,s=1,c=this.stage,u=this.canvas;function l(){var l=e.w,d=e.h,h=window.innerWidth,f=window.innerHeight,v=window.devicePixelRatio||1,p=h/l,y=f/d,g=1;t&&("width"==n&&r==h||"height"==n&&a==f?g=s:i?1==o?g=Math.min(p,y):2==o&&(g=Math.max(p,y)):(h<l||f<d)&&(g=Math.min(p,y))),u.width=l*v*g,u.height=d*v*g,u.style.width=l*g+"px",u.style.height=d*g+"px",c.scaleX=v*g,c.scaleY=v*g,r=h,a=f,s=g,c.tickOnUpdate=!1,c.updateViewport(u.width,u.height),c.tickOnUpdate=!0}window.addEventListener("resize",l),l()}},{key:"resize",value:function(){var t=1/window.devicePixelRatio,n=this,i=this.canvas,o=this.stage,r=this.exportRoot,a=this.stageWidth,s=this.stageHeight;document.querySelector('meta[name="viewport"]').setAttribute("content","width=device-width,initial-scale="+t+", maximum-scale="+t+", minimum-scale="+t+", user-scalable=no"),function(){if(a!=document.documentElement.clientWidth||s!=document.documentElement.clientHeight){a=n.stageWidth=document.documentElement.clientWidth,s=n.stageHeight=document.documentElement.clientHeight,i.width=a,i.height=s;var t=a/e.w,c=s/e.h;r.scaleX=t,r.scaleY=c}o.updateViewport(i.width,i.height)}()}}]),e}();u(t.default=l,"w",750),u(l,"h",1e3),u(l,"fps",60),u(l,"commonResId","E4019F635D6CBC488C42C0A8B05F0249")},function(e,t,n){"use strict";function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function a(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e}function s(e,t){return!t||"object"!==i(t)&&"function"!=typeof t?function(e){if(void 0!==e)return e;throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}(e):t}function c(e){return(c=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function u(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&function(e,t){(Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}(e,t)}Object.defineProperty(t,"__esModule",{value:!0}),t.Loader=t.ResLoadEvent=t.ProgressEvent=t.default=void 0;var l,d=function(){function e(){var t;return o(this,e),(t=s(this,c(e).call(this))).comps={},t.libs={},t.loaders={},t.queues=[],t.configs=[],t.loaded={},t._curConfig=null,t._loading=!1,t}return u(e,createjs.EventDispatcher),a(e,[{key:"_addQueue",value:function(e){this.queues.push(e),this._nextQueue()}},{key:"_nextQueue",value:function(){0<this.queues.length?this._loading||(this._loading=!0,this._load(this.queues.shift())):this.dispatchEvent("complete")}},{key:"_load",value:function(e){var t=this;this._curConfig=e;var n=new v;n.addEventListener("progress",function(e){return t._handleProgress(e)}),n.addEventListener("complete",function(e){return t._handleComplete(e)}),n.load(e),this.loaders[e.id]=n}},{key:"_handleProgress",value:function(e){var t=this.configs.length,n=t-this.queues.length-1,i=1/t,o=n*i;this.dispatchEvent(new h(n,t,o+e.progress*i,{progress:e.progress,resId:this._curConfig.id}))}},{key:"_handleComplete",value:function(e){this._loading=!1,this.loaded[this._curConfig.id]=!0,this.dispatchEvent(new f(this._curConfig.id)),this._nextQueue()}},{key:"load",value:function(e){var t=this;this.configs=this.configs.concat(e),e.forEach(function(e){var n=AdobeAn.getComposition(e.id),i=n.getLibrary();t.loaded[e.id]=!1,t.comps[e.id]=n,t.libs[e.id]=i,t._addQueue(e)}),this._nextQueue()}},{key:"getLibRes",value:function(e,t){return new(this.getLib(t)[e])}},{key:"getComp",value:function(e){return this.comps[e]}},{key:"getLib",value:function(e){return this.libs[e]}},{key:"getLoader",value:function(e){return this.loaders[e]}},{key:"getLibHasDone",value:function(e){return this.loaded[e]}}],[{key:"r",get:function(){return e._r||(e._r=new e),e._r}}]),e}();t.default=d,"_r"in(l=d)?Object.defineProperty(l,"_r",{value:null,enumerable:!0,configurable:!0,writable:!0}):l._r=null;var h=function(){function e(t,n,i,r){var a;return o(this,e),(a=s(this,c(e).call(this,"progress"))).load=t,a.total=n,a.progress=i,a.data=r,a}return u(e,createjs.Event),e}();t.ProgressEvent=h;var f=function(){function e(t){var n;return o(this,e),(n=s(this,c(e).call(this,"resload"))).resId=t,n}return u(e,createjs.Event),e}();t.ResLoadEvent=f;var v=function(){function e(){var t;return o(this,e),t=s(this,c(e).call(this)),createjs.Sound.alternateExtensions=["mp3"],t}return u(e,createjs.EventDispatcher),a(e,[{key:"_load",value:function(e){var t=this,n=e.id,i=e.path;this.comp=AdobeAn.getComposition(n);var o=this.comp.getLibrary(),r=new createjs.LoadQueue(!0,i);r.installPlugin(createjs.Sound),r.addEventListener("fileload",function(e){return t._handleFileLoad(e)}),r.addEventListener("progress",function(e){return t._handleProgress(e)}),r.addEventListener("complete",function(e){return t._handleComplete(e)}),r.loadManifest(o.properties.manifest),this.sourceLoader=r}},{key:"_handleFileLoad",value:function(e){var t=this.comp.getImages();e&&"image"==e.item.type&&(t[e.item.id]=e.result),this.dispatchEvent(e.clone())}},{key:"_handleProgress",value:function(e){this.dispatchEvent(e.clone())}},{key:"_handleComplete",value:function(e){for(var t=this.comp,n=t.getLibrary(),i=t.getSpriteSheet(),o=e.target,r=n.ssMetadata,a=0;a<r.length;a++)i[r[a].name]=new createjs.SpriteSheet({images:[o.getResult(r[a].name)],frames:r[a].frames});AdobeAn.compositionLoaded(this.config.id),this.dispatchEvent(e.clone())}},{key:"load",value:function(e){this.comp=null,this.config=e,this._load(e)}}]),e}();t.Loader=v},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=n(0),o=n(1);function r(e){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function a(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function s(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function c(e,t,n){return t&&s(e.prototype,t),n&&s(e,n),e}function u(e,t){return!t||"object"!==r(t)&&"function"!=typeof t?function(e){if(void 0!==e)return e;throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}(e):t}function l(e){return(l=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function d(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&function(e,t){(Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}(e,t)}function h(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var f=function(){function e(t,n,i){var o,r=3<arguments.length&&void 0!==arguments[3]?arguments[3]:400,s=4<arguments.length&&void 0!==arguments[4]?arguments[4]:300;return a(this,e),(o=u(this,l(e).call(this))).id="video_"+Math.round(99999999*Math.random()),o.width=r,o.height=s,o.src=t,o.autoPlay=n,o.video=i,o.videoBuffer=new createjs.VideoBuffer(i),o.videoBM=new createjs.Bitmap(o.videoBuffer),o.videoWidth=o.videoHeight=0,o.initVideoEvent(),o.initControl(),o}return d(e,createjs.Container),c(e,[{key:"initControl",value:function(){this.control=new v(this,this.width,this.height),this.addChild(this.control)}},{key:"initVideoEvent",value:function(){var e=this;this.video.addEventListener("play",function(t){e.dispatchEvent(new o.Event("playing"))}),this.video.addEventListener("loadedmetadata",function(t){e.onVideoEvent(t)})}},{key:"onVideoEvent",value:function(e){switch(console.log(e),e.type){case"loadedmetadata":this.videoWidth=this.video.videoWidth,this.videoHeight=this.video.videoHeight}}},{key:"seek",value:function(e){this.video.currentTime=e}},{key:"play",value:function(){try{this.video.play()}catch(e){}}},{key:"pause",value:function(){try{this.video.pause()}catch(e){}}},{key:"toggle",value:function(){this.video.paused?this.play():this.pause()}},{key:"destory",value:function(){this.pause(),this.video.remove(),this.control.destory(),this.parent.removeChild(this)}},{key:"isPlaying",get:function(){return!this.video.paused}}],[{key:"getVideo",value:function(t,n,i,o){var r=2<arguments.length&&void 0!==i?i:400,a=3<arguments.length&&void 0!==o?o:300,s=document.getElementsByClassName("video-box")[0];s||((s=document.createElement("div")).className="video-box",document.body.appendChild(s));var c=window.appData?window.appData.path:"";t=-1==t.indexOf("http://")?c+t:t;var u=document.createElement("video");s.append(u),u.src=t,u.autoPlay=n,u.style.display="none",u.setAttribute("webkit-playsinline",!0),u.setAttribute("playsinline",!0),u.setAttribute("x5-video-player-type","h5");var l=new e(t,n,u,r,a);return e.videos.push(l),l}},{key:"destoryAllVideo",value:function(){e.videos.forEach(function(e){return e.destory()}),e.videos=[]}}]),e}();h(t.default=f,"videos",[]);var v=function(){function e(t){var n,i=1<arguments.length&&void 0!==arguments[1]?arguments[1]:300,o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:200;return a(this,e),(n=u(this,l(e).call(this))).video=t,n.width=i,n.height=o,n.initChild(),n.initVideoEvent(),n}return d(e,createjs.Container),c(e,[{key:"initChild",value:function(){var e=this,t=window.lib;this.play_icon=new t.play_icon,this.pause_icon=new t.pause_icon,this.bar=new p(this.width,10),this.content=new createjs.Container,this.addChild(this.content),this.addChild(this.play_icon),this.addChild(this.pause_icon),this.addChild(this.bar),this.content.addChild(this.video.videoBM),this.bar.addEventListener(y.CHANGED,function(t){var n=t.data.percentage;e.video.seek(e.video.duration*n)}),this.addEventListener("mousedown",function(t){e.video.toggle()}),this.refresh()}},{key:"initVideoEvent",value:function(){var e=this,t=this.video.video;t.addEventListener("play",function(t){e.onVideoEvent(t)}),t.addEventListener("pause",function(t){e.onVideoEvent(t)}),t.addEventListener("timeupdate",function(t){e.onVideoEvent(t)}),t.addEventListener("loadedmetadata",function(t){e.onVideoEvent(t)})}},{key:"onVideoEvent",value:function(e){switch(e.type){case"play":case"pause":this.showControl(),this.refresh();break;case"loadedmetadata":this.refresh();break;case"timeupdate":this.bar.setValue(this.video.currentTime/this.video.duration)}}},{key:"refresh",value:function(){this.video.videoBM&&i.DisplayObjectUitl.scale(this.video.videoBM,this.video.videoWidth,this.video.videoHeight,this.width,this.height),this.play_icon.visible=!this.video.isPlaying,this.pause_icon.visible=this.video.isPlaying,this.play_icon.x=this.pause_icon.x=this.width>>1,this.play_icon.y=this.pause_icon.y=this.height>>1,this.bar.y=this.height-this.bar.height}},{key:"showControl",value:function(e,t){var n=this,i=!(0<arguments.length&&void 0!==e)||e,o=1<arguments.length&&void 0!==t?t:2e3;clearTimeout(this.hideDelay),this._showControl(!0),i&&(this.hideDelay=setTimeout(function(){n._showControl(!1)},o))}},{key:"_showControl",value:function(e){console.log("_showControl",e),this.play_icon.visible=this.pause_icon.visible=this.bar.visible=e}},{key:"destory",value:function(){this.bar.removeAllEventListeners(y.CHANGED),this.removeAllEventListeners("mousedown")}}]),e}(),p=function(){function e(){var t,n=0<arguments.length&&void 0!==arguments[0]?arguments[0]:100,i=1<arguments.length&&void 0!==arguments[1]?arguments[1]:10,o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:"#28b28b",r=3<arguments.length&&void 0!==arguments[3]?arguments[3]:"#ffffff";return a(this,e),(t=u(this,l(e).call(this))).value=0,t.width=n,t.height=i,t.doneColor=o,t.railColor=r,t.initChild(),t}return d(e,createjs.Container),c(e,[{key:"initChild",value:function(){var e=this;this.done=new createjs.Shape,this.rail=new createjs.Shape,this.addEventListener("mousedown",function(t){var n=e.width,i=e.ui.globalToLocal(t.stageX,t.stageY).x/n;t.stopImmediatePropagation(),t.stopPropagation(),e.dispatchEvent(new y(y.CHANGED,{percentage:i}))}),this.setValue(0)}},{key:"setValue",value:function(e){this.value=e,this.draw()}},{key:"draw",value:function(){this.done.graphics.clear(),this.done.graphics.beginFill(this.doneColor),this.done.graphics.drawRect(0,0,this.width*this.value,this.height),this.done.graphics.clear(),this.rail.graphics.beginFill(this.railColor),this.rail.graphics.drawRect(0,0,this.width,this.height)}}]),e}(),y=function(){function e(t,n){var i;return a(this,e),(i=u(this,l(e).call(this,t))).data=n,i}return d(e,createjs.Event),e}();h(y,"CHANGED","changed")},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=a(n(2)),o=a(n(1)),r=n(0);function a(e){return e&&e.__esModule?e:{default:e}}var s={coms:{Video:a(n(4)).default},utils:{DisplayObjectUitl:r.DisplayObjectUitl},manager:{EventManager:o.default},hellobabo:{init:function(){try{document.createElement("canvas").getContext("2d")}catch(e){return}(new i.default).init(document.getElementById("canvas"),document.getElementById("animation_container"),document.getElementById("dom_overlay_container"))}}};Object.assign(s,window.wskeee||{}),window.onload=s.hellobabo.init;var c=window.wskeee=s;t.default=c},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=function(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var n in e)if(Object.prototype.hasOwnProperty.call(e,n)){var i=Object.defineProperty&&Object.getOwnPropertyDescriptor?Object.getOwnPropertyDescriptor(e,n):{};i.get||i.set?Object.defineProperty(t,n,i):t[n]=e[n]}return t.default=e,t}(n(1)),o=s(n(4)),r=s(n(3)),a=s(n(2));function s(e){return e&&e.__esModule?e:{default:e}}function c(e){return(c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function u(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function l(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function d(e,t,n){return t&&l(e.prototype,t),n&&l(e,n),e}function h(e,t){return!t||"object"!==c(t)&&"function"!=typeof t?v(e):t}function f(e){return(f=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function v(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function p(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&function(e,t){(Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}(e,t)}var y,g,b,w=function(){function e(t,n){var i,o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:"h";return u(this,e),i=h(this,f(e).call(this)),e.instance=v(i),i.appData=window.appData,i.index=0,i.isLock=!1,i.isTween=!1,i.viewList=[],i.contentView=i.addChild(new createjs.Container),i.moveView=i.currentView=null,i.viewH=n,i.viewW=t,i.scrollDir=o,i.pageSize="h"==o?t:n,i.initChild(),i.initEvent(),i}return p(e,createjs.Container),d(e,[{key:"initChild",value:function(){var e,t=this;this.appData.scenes.forEach(function(n){e=new m(n,t.viewW,t.viewH),t.viewList.push(e)}),this.currentView=this.viewList[0],this.currentView.active=!0,this.addContent(this.currentView),this.checkLock(0)}},{key:"initEvent",value:function(){var e=this;i.default.instance.addEventListener("sceneFinished",function(t){e.setSceneLock(e.index,!1)}),i.default.instance.addEventListener("nextScene",function(t){e.next()}),i.default.instance.addEventListener("prevScene",function(t){e.prev()}),i.default.instance.addEventListener("changeScene",function(t){e.changeIndex(t.data.value),e.showNav(!1)}),i.default.instance.addEventListener("hideNav",function(t){e.showNav(!1)})}},{key:"changeIndex",value:function(e){this.changeView(this.index>e?"next":"prev",e)}},{key:"changeView",value:function(e,t,n){if(!this.isTween&&this.index!=t){var i="h"==this.scrollDir?"x":"y",o=this.pageSize;this.moveView=this.viewList[t],this.addContent(this.moveView),this.moveView[i]=o,this.changeHandler("next",t,n)}}},{key:"changeHandler",value:function(t,n,i){var r=this;if(!(n<0||n>this.viewList.length)||1==i){var a,s=this.moveView,c=this.currentView,u="h"==this.scrollDir?"x":"y",l=this.pageSize,d={},h={};d[u]=0,this.isTween=!0,this.index=n,this.checkLock(n),createjs.Sound.stop(),o.default.destoryAllVideo(),"next"==t?(a=Math.abs((l-Math.abs(c[u]))/l),h[u]=-l):(h[u]=l,a=Math.abs((l-Math.abs(this.currentView[u]))/l)),createjs.Tween.get(s).to(d,500*a),createjs.Tween.get(c).to(h,500*a).call(function(){return r.delayComplete()}),e.bi=a}}},{key:"delayComplete",value:function(){var e=this.currentView;this.isTween=!1,e.active=!1,e.reset(),this.removeContent(e),(e=this.currentView=this.viewList[this.index]).active=!0,e.play()}},{key:"resumeHandler",value:function(t){var n=this.moveView,i=this.currentView,o="h"==this.scrollDir?"x":"y",r=this.pageSize,a={},s={};a[o]=0,e.bi=Math.abs(i[o]/r),s[o]="next"==t?-r:r,createjs.Tween.get(i).to(a,500*e.bi),createjs.Tween.get(n).to(s,500*e.bi).call(this.resumeComplete)}},{key:"resumeComplete",value:function(){var e=this.moveView;e&&this.removeContent(e)}},{key:"addContent",value:function(e){this.contentView.addChild(e)}},{key:"removeContent",value:function(e){e.parent&&e.parent.removeChild(e)}},{key:"next",value:function(){this.changeView("next",this.index+1)}},{key:"prev",value:function(){this.changeView("prev",this.index-1)}},{key:"lock",value:function(){this.isLock=!0}},{key:"unlock",value:function(){this.isLock=!1}},{key:"setSceneLock",value:function(e,t){this.appData.scenes[e].lock=t,this.checkLock(e)}},{key:"checkLock",value:function(e){var t=this.moveView?this.moveView:this.currentView;this.isLock=this.appData.scenes[e].lock,t.nextTips.visible=!this.isLock&&0==e&&1<this.viewList.length}},{key:"showNav",value:function(e){(this.navView.visible=e)?this.lock():this.checkLock(this.index)}}]),e}();t.default=w,b=void 0,(g="instance")in(y=w)?Object.defineProperty(y,g,{value:b,enumerable:!0,configurable:!0,writable:!0}):y[g]=b;var m=function(){function e(t,n,i){var o;return u(this,e),(o=h(this,f(e).call(this))).width=n,o.height=i,o.libConfig=t,o.active=!1,o.init(),o}return p(e,createjs.Container),d(e,[{key:"init",value:function(){var e=this,t=this.libConfig;this.nextTips=r.default.r.getLibRes("next_tips",a.default.commonResId);var n=this.loading=r.default.r.getLibRes("loading",a.default.commonResId);this.addChild(n),n.setTransform(this.width/2,this.height/2),r.default.r.addEventListener("progress",function(e){e.data.resId==t.id&&(n.txt.text=Math.round(100*e.data.progress)+"%")}),r.default.r.addEventListener("resload",function(n){n.resId==t.id&&e.initChild()})}},{key:"initChild",value:function(){this.removeChild(this.loading),this.content=r.default.r.getLibRes("page",this.libConfig.id),this.addChild(this.content),this.addChild(this.nextTips),this.nextTips.setTransform(this.width-5,this.height/2),this.active?this.content.play():this.content.gotoAndStop(0)}},{key:"play",value:function(){this.content&&this.content.play()}},{key:"reset",value:function(){this.content&&this.content.gotoAndStop(0)}},{key:"stop",value:function(){this.content&&this.content.stop()}}]),e}()},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i,o=(i=n(0))&&i.__esModule?i:{default:i};function r(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function a(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var s=function(){function e(){!function(t,n){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this)}return function(e,t,n){n&&r(e,n)}(e,0,[{key:"init",value:function(t,n,i,r){var a=2<arguments.length&&void 0!==i?i:720,s=3<arguments.length&&void 0!==r?r:1280;e.stage=t,e.view=n,e.w=a,e.h=s,o.default.isPC()?e.mouseInit():e.touchInit()}},{key:"mouseInit",value:function(){var t="h"==e.scrollDir?"x":"y",n="h"==e.scrollDir?"X":"Y",i=0,o=!1,r="h"==e.scrollDir?e.w:e.h,a=e.stage,s=e.view;a.addEventListener("stagemousedown",function(e){s.isTween||s.isLock||(o=!0,i=e["raw".concat(n)],s[t])}),a.addEventListener("stagemousemove",function(e){s.isTween||s.isLock||0!=o&&function(e){if(!s.isTween&&!s.isLock){var n=s.index,o=0,a=s.viewList,c=a.length,u=s.moveView,l=s.currentView;c<n||(0<e&&0<n?(u=s.moveView=a[n-1],o=-r,u.parent||(u[t]=-r,s.addChild(u)),u&&(u[t]=i+e+o,l[t]=i+e)):e<0&&n<c&&(u=s.moveView=a[n+1],o=r,u&&(u.parent||(u[t]=r,s.addChild(u)),u[t]=i+e+o,l[t]=i+e)))}}(e["raw".concat(n)]-i)}),a.addEventListener("stagemouseup",function(e){if(!s.isTween&&!s.isLock){var n=s.currentView,i=s.index;0<n[t]?100<n[t]?s.changeHandler("prev",i-1):s.resumeHandler("next"):n[t]<0&&(n[t]<-100?s.changeHandler("next",i+1):s.resumeHandler("prev")),o=!1}})}},{key:"touchInit",value:function(){var t="h"==e.scrollDir?"x":"y",n="h"==e.scrollDir?"X":"Y",i=0,o=0,r="h"==e.scrollDir?e.w:e.h,a=e.stage,s=e.view;a.addEventListener("touchstart",function(e){if(!s.isTween&&!s.isLock){e.preventDefault();var r=e.targetTouches[0];i=r["page".concat(n)],o=s[t]}},!1),a.addEventListener("touchmove",function(e){s.isTween||s.isLock||1!=e.targetTouches.length||(e.preventDefault(),function(e){if(!s.isTween&&!s.isLock){var n=0,i=s.index,a=s.viewList,c=a.length,u=s.moveView,l=s.currentView;c<i||(0<e&&0<i?(u=s.moveView=a[i-1],n=-r,u.parent||(u[t]=-r,s.addContent(u)),u&&(u[t]=o+e+n,l[t]=o+e)):e<0&&i<c&&(u=s.moveView=a[i+1],n=r,u&&(u.parent||(u[t]=r,s.addContent(u)),u[t]=o+e+n,l[t]=o+e)))}}(e.targetTouches[0]["page".concat(n)]-i))},!1),a.addEventListener("touchend",function(e){if(!s.isTween&&!s.isLock){var n=s.index,i=s.currentView;0<i[t]?100<i[t]?s.changeHandler("prev",n-1):s.resumeHandler("next"):i[t]<0&&(i[t]<-100?s.changeHandler("next",n+1):s.resumeHandler("prev"))}},!1)}}]),e}();a(t.default=s,"w",720),a(s,"h",1280),a(s,"stage",void 0),a(s,"view",void 0),a(s,"scrollDir","h")}]);
//# sourceMappingURL=app.bundle.js.map