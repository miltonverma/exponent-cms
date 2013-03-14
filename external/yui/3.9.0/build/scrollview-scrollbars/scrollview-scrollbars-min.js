/* YUI 3.9.0 (build 5827) Copyright 2013 Yahoo! Inc. http://yuilibrary.com/license/ */
YUI.add("scrollview-scrollbars",function(e,t){function L(){L.superclass.constructor.apply(this,arguments)}var n=e.ClassNameManager.getClassName,r,i=e.Transition,s=i.useNative,o="scrollbar",u="scrollview",a="verticalNode",f="horizontalNode",l="childCache",c="top",h="left",p="width",d="height",v="_sbh",m="_sbv",g=e.ScrollView._TRANSITION.PROPERTY,y="transform",b="translateX(",w="translateY(",E="scaleX(",S="scaleY(",x="scrollX",T="scrollY",N="px",C=")",k=N+C;L.CLASS_NAMES={showing:n(u,o,"showing"),scrollbar:n(u,o),scrollbarV:n(u,o,"vert"),scrollbarH:n(u,o,"horiz"),scrollbarVB:n(u,o,"vert","basic"),scrollbarHB:n(u,o,"horiz","basic"),child:n(u,"child"),first:n(u,"first"),middle:n(u,"middle"),last:n(u,"last")},r=L.CLASS_NAMES,L.NAME="pluginScrollViewScrollbars",L.NS="scrollbars",L.SCROLLBAR_TEMPLATE=["<div>",'<span class="'+r.child+" "+r.first+'"></span>','<span class="'+r.child+" "+r.middle+'"></span>','<span class="'+r.child+" "+r.last+'"></span>',"</div>"].join(""),L.ATTRS={verticalNode:{setter:"_setNode",valueFn:"_defaultNode"},horizontalNode:{setter:"_setNode",valueFn:"_defaultNode"}},e.namespace("Plugin").ScrollViewScrollbars=e.extend(L,e.Plugin.Base,{initializer:function(){this._host=this.get("host"),this.afterHostEvent("scrollEnd",this._hostScrollEnd),this.afterHostMethod("scrollTo",this._update),this.afterHostMethod("_uiDimensionsChange",this._hostDimensionsChange)},_hostDimensionsChange:function(){var t=this._host,n=t._cAxis,r=t.get(x),i=t.get(T);this._dims=t._getScrollDims(),n&&n.y&&this._renderBar(this.get(a),!0,"vert"),n&&n.x&&this._renderBar(this.get(f),!0,"horiz"),this._update(r,i),e.later(500,this,"flash",!0)},_hostScrollEnd:function(){var e=this._host,t=e.get(x),n=e.get(T);this.flash(),this._update(t,n)},_renderBar:function(e,t){var n=e.inDoc(),i=this._host._bb,s=e.getData("isHoriz")?r.scrollbarHB:r.scrollbarVB;t&&!n?(i.append(e),e.toggleClass(s,this._basic),this._setChildCache(e)):!t&&n&&(e.remove(),this._clearChildCache(e))},_setChildCache:function(e){var t=e.get("children"),n=t.item(0),r=t.item(1),i=t.item(2),s=e.getData("isHoriz")?"offsetWidth":"offsetHeight";e.setStyle(g,y),r.setStyle(g,y),i.setStyle(g,y),e.setData(l,{fc:n,lc:i,mc:r,fcSize:n&&n.get(s),lcSize:i&&i.get(s)})},_clearChildCache:function(e){e.clearData(l)},_updateBar:function(e,t,n,r){var i=this._host,o=this._basic,u=0,a=1,f=e.getData(l),g=f.lc,L=f.mc,A=f.fcSize,O=f.lcSize,M,_,D,P,H,B,j,F,I,q;r?(B=p,j=h,F=v,I=this._dims.offsetWidth,q=this._dims.scrollWidth,P=b,H=E,t=t!==undefined?t:i.get(x)):(B=d,j=c,F=m,I=this._dims.offsetHeight,q=this._dims.scrollHeight,P=w,H=S,t=t!==undefined?t:i.get(T)),u=Math.floor(I*(I/q)),a=Math.floor(t/(q-I)*(I-u)),u>I&&(u=1),a>I-u?u-=a-(I-u):a<0?(u=a+u,a=0):isNaN(a)&&(a=0),M=u-(A+O),M<0&&(M=0),M===0&&a!==0&&(a=I-(A+O)-1),n!==0?(D={duration:n},s?D.transform=P+a+k:D[j]=a+N,e.transition(D)):s?e.setStyle(y,P+a+k):e.setStyle(j,a+N);if(this[F]!==M){this[F]=M;if(M>0){n!==0?(D={duration:n},s?D.transform=H+M+C:D[B]=M+N,L.transition(D)):s?L.setStyle(y,H+M+C):L.setStyle(B,M+N);if(!r||!o)_=u-O,n!==0?(D={duration:n},s?D.transform=P+_+k:D[j]=_,g.transition(D)):s?g.setStyle(y,P+_+k):g.setStyle(j,_+N)}}},_update:function(e,t,n){var r=this.get(a),i=this.get(f),s=this._host,o=s._cAxis;n=(n||0)/1e3,this._showing||this.show(),o&&o.y&&r&&t!==null&&this._updateBar(r,t,n,!1),o&&o.x&&i&&e!==null&&this._updateBar(i,e,n,!0)},show:function(e){this._show(!0,e)},hide:function(e){this._show(!1,e)},_show:function(e,t){var n=this.get(a),r=this.get(f),i=t?.6:0,s=e?1:0,o;this._showing=e,this._flashTimer&&this._flashTimer.cancel(),o={duration:i,opacity:s},n&&n._node&&n.transition(o),r&&r._node&&r.transition(o)},flash:function(){this.show(!0),this._flashTimer=e.later(800,this,"hide",!0)},_setNode:function(t,n){var i=n===f;return t=e.one(t),t&&(t.addClass(r.scrollbar),t.addClass(i?r.scrollbarH:r.scrollbarV),t.setData("isHoriz",i)),t},_defaultNode:function(){return e.Node.create(L.SCROLLBAR_TEMPLATE)},_basic:e.UA.ie&&e.UA.ie<=8})},"3.9.0",{requires:["classnamemanager","transition","plugin"],skinnable:!0});
