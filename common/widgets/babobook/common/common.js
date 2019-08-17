(function (cjs, an) {

var p; // shortcut to reference prototypes
var lib={};var ss={};var img={};
lib.ssMetadata = [
		{name:"common_atlas_", frames: [[0,0,1067,649],[1069,530,56,153],[1069,258,131,150],[0,651,1067,20],[0,673,1067,20],[1069,410,107,118],[1069,0,256,256]]}
];


// symbols:



(lib.CachedTexturedBitmap_56 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(0);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_58 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(1);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_59 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(2);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_60 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(3);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_61 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(4);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_62 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(5);
}).prototype = p = new cjs.Sprite();



(lib.CachedTexturedBitmap_63 = function() {
	this.initialize(ss["common_atlas_"]);
	this.gotoAndStop(6);
}).prototype = p = new cjs.Sprite();
// helper functions:

function mc_symbol_clone() {
	var clone = this._cloneProps(new this.constructor(this.mode, this.startPosition, this.loop));
	clone.gotoAndStop(this.currentFrame);
	clone.paused = this.paused;
	clone.framerate = this.framerate;
	return clone;
}

function getMCSymbolPrototype(symbol, nominalBounds, frameBounds) {
	var prototype = cjs.extend(symbol, cjs.MovieClip);
	prototype.clone = mc_symbol_clone;
	prototype.nominalBounds = nominalBounds;
	prototype.frameBounds = frameBounds;
	return prototype;
	}


(lib.loading = function(mode,startPosition,loop) {
if (loop == null) { loop = false; }	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(1));

	// 图层_1
	this.txt = new cjs.Text("0%", "28px 'Arial'", "#FFFFFF");
	this.txt.name = "txt";
	this.txt.textAlign = "center";
	this.txt.lineHeight = 33;
	this.txt.lineWidth = 121;
	this.txt.parent = this;
	this.txt.setTransform(0,-15.65);

	this.instance = new lib.CachedTexturedBitmap_63();
	this.instance.parent = this;
	this.instance.setTransform(-64,-64,0.5,0.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance},{t:this.txt}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.loading, new cjs.Rectangle(-64,-64,128,128), null);


(lib.jt = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_62();
	this.instance.parent = this;
	this.instance.setTransform(-16.75,-18.55,0.3148,0.3148);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.jt, new cjs.Rectangle(-16.7,-18.5,33.7,37.1), null);


(lib.play_icon = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_59();
	this.instance.parent = this;
	this.instance.setTransform(-32.65,-37.55,0.5,0.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.play_icon, new cjs.Rectangle(-32.6,-37.5,65.5,75), null);


(lib.pause_icon = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_58();
	this.instance.parent = this;
	this.instance.setTransform(4,-38.35,0.5,0.5);

	this.instance_1 = new lib.CachedTexturedBitmap_58();
	this.instance_1.parent = this;
	this.instance_1.setTransform(-32,-38.35,0.5,0.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance_1},{t:this.instance}]}).wait(1));

}).prototype = getMCSymbolPrototype(lib.pause_icon, new cjs.Rectangle(-32,-38.3,64,76.5), null);


(lib.video_content = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,0,0);


(lib.video_bar_done = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_60();
	this.instance.parent = this;
	this.instance.setTransform(0,0,0.5,0.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.video_bar_done, new cjs.Rectangle(0,0,533.5,10), null);


(lib.video_bar_bg = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_61();
	this.instance.parent = this;
	this.instance.setTransform(0,0,0.5,0.5);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.video_bar_bg, new cjs.Rectangle(0,0,533.5,10), null);


(lib.bg = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.CachedTexturedBitmap_56();
	this.instance.parent = this;
	this.instance.setTransform(0,0,0.4285,0.4285);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.bg, new cjs.Rectangle(0,0,457.2,278.1), null);


(lib.箭头 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.jt();
	this.instance.parent = this;
	this.instance.setTransform(-0.05,0.05,0.7143,0.7907);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({regY:0.1,x:18.3,y:0.5},14,cjs.Ease.get(1)).to({regY:0,x:-0.05,y:0.05},25).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12,-14.6,42.4,29.7);


(lib.video_bar = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// done
	this.done = new lib.video_bar_done();
	this.done.name = "done";
	this.done.parent = this;
	this.done.setTransform(0.1,4.7,1,1,0,0,0,0.1,4.7);

	this.timeline.addTween(cjs.Tween.get(this.done).wait(1));

	// bg
	this.rail = new lib.video_bar_bg();
	this.rail.name = "rail";
	this.rail.parent = this;
	this.rail.setTransform(0,4.95,1,1,0,0,0,0,5);

	this.timeline.addTween(cjs.Tween.get(this.rail).wait(1));

}).prototype = getMCSymbolPrototype(lib.video_bar, new cjs.Rectangle(0,0,533.5,10), null);


(lib.video = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(1));

	// controll
	this.bar = new lib.video_bar();
	this.bar.name = "bar";
	this.bar.parent = this;
	this.bar.setTransform(266.6,295,1,1,0,0,0,266.6,5);

	this.play_icon = new lib.play_icon();
	this.play_icon.name = "play_icon";
	this.play_icon.parent = this;
	this.play_icon.setTransform(288,143.95);

	this.pause_icon = new lib.pause_icon();
	this.pause_icon.name = "pause_icon";
	this.pause_icon.parent = this;
	this.pause_icon.setTransform(275.05,145.1,1,1,0,0,0,0.3,0.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.pause_icon},{t:this.play_icon},{t:this.bar}]}).wait(1));

	// video
	this.content = new lib.video_content();
	this.content.name = "content";
	this.content.parent = this;
	this.content.setTransform(0,-1,1,1,0,0,0,0,-1);

	this.timeline.addTween(cjs.Tween.get(this.content).wait(1));

	// 底
	this.bg = new lib.bg();
	this.bg.name = "bg";
	this.bg.parent = this;
	this.bg.setTransform(266.65,150,1.1669,1.0791,0,0,0,228.5,139);

	this.timeline.addTween(cjs.Tween.get(this.bg).wait(1));

}).prototype = getMCSymbolPrototype(lib.video, new cjs.Rectangle(0,0,533.5,300.1), null);


(lib.next_tips = function(mode,startPosition,loop) {
if (loop == null) { loop = false; }	this.initialize(mode,startPosition,loop,{});

	// 图层_1
	this.instance = new lib.箭头();
	this.instance.parent = this;
	this.instance.setTransform(-69.3,-2.1,2.0085,2.0085,0,0,0,-0.1,-0.1);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.next_tips, new cjs.Rectangle(-93.2,-31.2,48.300000000000004,59), null);


// stage content:
(lib.common = function(mode,startPosition,loop) {
if (loop == null) { loop = false; }	this.initialize(mode,startPosition,loop,{});

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,0,0);
// library properties:
lib.properties = {
	id: 'E4019F635D6CBC488C42C0A8B05F0249',
	width: 720,
	height: 1280,
	fps: 24,
	color: "#F2F2F2",
	opacity: 1.00,
	manifest: [
		{src:"images/common_atlas_.png?1563437701657", id:"common_atlas_"}
	],
	preloads: []
};



// bootstrap callback support:

(lib.Stage = function(canvas) {
	createjs.Stage.call(this, canvas);
}).prototype = p = new createjs.Stage();

p.setAutoPlay = function(autoPlay) {
	this.tickEnabled = autoPlay;
}
p.play = function() { this.tickEnabled = true; this.getChildAt(0).gotoAndPlay(this.getTimelinePosition()) }
p.stop = function(ms) { if(ms) this.seek(ms); this.tickEnabled = false; }
p.seek = function(ms) { this.tickEnabled = true; this.getChildAt(0).gotoAndStop(lib.properties.fps * ms / 1000); }
p.getDuration = function() { return this.getChildAt(0).totalFrames / lib.properties.fps * 1000; }

p.getTimelinePosition = function() { return this.getChildAt(0).currentFrame / lib.properties.fps * 1000; }

an.bootcompsLoaded = an.bootcompsLoaded || [];
if(!an.bootstrapListeners) {
	an.bootstrapListeners=[];
}

an.bootstrapCallback=function(fnCallback) {
	an.bootstrapListeners.push(fnCallback);
	if(an.bootcompsLoaded.length > 0) {
		for(var i=0; i<an.bootcompsLoaded.length; ++i) {
			fnCallback(an.bootcompsLoaded[i]);
		}
	}
};

an.compositions = an.compositions || {};
an.compositions['E4019F635D6CBC488C42C0A8B05F0249'] = {
	getStage: function() { return exportRoot.getStage(); },
	getLibrary: function() { return lib; },
	getSpriteSheet: function() { return ss; },
	getImages: function() { return img; }
};

an.compositionLoaded = function(id) {
	an.bootcompsLoaded.push(id);
	for(var j=0; j<an.bootstrapListeners.length; j++) {
		an.bootstrapListeners[j](id);
	}
}

an.getComposition = function(id) {
	return an.compositions[id];
}



})(createjs = createjs||{}, AdobeAn = AdobeAn||{});
var createjs, AdobeAn;