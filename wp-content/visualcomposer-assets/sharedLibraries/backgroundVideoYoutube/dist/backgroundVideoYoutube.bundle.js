!function(e){function t(r){if(i[r])return i[r].exports;var s=i[r]={exports:{},id:r,loaded:!1};return e[r].call(s.exports,s,s.exports,t),s.loaded=!0,s.exports}var i={};return t.m=e,t.c=i,t.p=".",t(0)}({"./src/backgroundVideoYoutube.js":function(e,t){"use strict";window.vcv.on("ready",function(e,t){if("merge"!==e){var i="[data-vce-assets-video-yt]";i=t?'[data-vcv-element="'+t+'"] '+i:i,window.vceAssetsBackgroundVideoYoutube(i)}})},"./src/plugin.js":function(e,t){"use strict";!function(e,t){function i(t){var i={element:null,player:null,ytPlayer:null,videoId:null,resizer:null,ratio:null,setup:function(t){return t.getVceYoutubeVideo?this.updatePlayer():(t.getVceYoutubeVideo=this,this.element=t,this.resizer=t.querySelector("svg"),this.checkYT(),this.checkOrientation=this.checkOrientation.bind(this),e.addEventListener("resize",this.checkOrientation)),t.getVceYoutubeVideo},updatePlayerData:function(){this.player=t.querySelector(t.dataset.vceAssetsVideoReplacer),this.videoId=t.dataset.vceAssetsVideoYt||null},checkYT:function(){var e=arguments.length<=0||void 0===arguments[0]?0:arguments[0];if("undefined"==typeof YT||!YT.loaded){if(e>100)return void console.log("Too many attempts to load YouTube IFrame API");var t=this;return void setTimeout(function(){e++,t.checkYT(e)},100)}this.createPlayer()},createPlayer:function(){var e=this;this.updatePlayerData(),this.ytPlayer=new YT.Player(this.player,{videoId:this.videoId,playerVars:{autoplay:1,start:0,modestbranding:1,controls:0,disablekb:1,fs:0,iv_load_policy:3,loop:1,playlist:this.videoId,rel:0,showinfo:0},events:{onReady:function(t){var i=t.target.a.getAttribute("height"),r=t.target.a.getAttribute("width");e.resizer.setAttribute("height",i),e.resizer.setAttribute("width",r),e.resizer.setAttribute("data-vce-assets-video-state","visible"),e.ratio=parseInt(r)/parseInt(i),e.checkOrientation(),t.target.mute()}}})},updatePlayer:function(){this.ytPlayer.destroy(),this.createPlayer()},checkOrientation:function(){var t=this.element.dataset.vceAssetsVideoOrientationClass||null,i=e.getComputedStyle(this.element.parentNode);t&&(parseInt(i.width)/parseInt(i.height)>this.ratio?this.element.classList.add(t):this.element.classList.remove(t))}};return i.setup(t)}var r={init:function(e){var r=t.querySelectorAll(e);return r=[].slice.call(r),r.forEach(function(e){e.getVceYoutubeVideo?e.getVceYoutubeVideo.updatePlayer():i(e)}),1===r.length?r.pop():r}};e.vceAssetsBackgroundVideoYoutube=r.init}(window,document)},"./src/youtubeIframeApi.js":function(e,t){"use strict";!function(e,t){var i=t.getElementById("vcv-asset-youtube-iframe-api");if(!i){var r=t.createElement("script");r.id="vcv-asset-youtube-iframe-api",r.src="https://www.youtube.com/iframe_api",t.head.appendChild(r)}}(window,document)},"./src/backgroundVideoYoutube.css":function(e,t){},0:function(e,t,i){i("./src/youtubeIframeApi.js"),i("./src/plugin.js"),i("./src/backgroundVideoYoutube.js"),e.exports=i("./src/backgroundVideoYoutube.css")}});