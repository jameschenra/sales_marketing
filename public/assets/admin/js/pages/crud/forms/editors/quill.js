!function(e){var n={};function t(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=n,t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:o})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)t.d(o,r,function(n){return e[n]}.bind(null,r));return o},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="/",t(t.s=502)}({502:function(e,n,t){e.exports=t(503)},503:function(e,n){var t={init:function(){var e,n,t;new Quill("#kt_quil_1",{modules:{toolbar:[[{header:[1,2,!1]}],["bold","italic","underline"],["image","code-block"]]},placeholder:"Type your text here...",theme:"snow"}),e=Quill.import("delta"),n=new Quill("#kt_quil_2",{modules:{toolbar:!0},placeholder:"Type your text here...",theme:"snow"}),t=new e,n.on("text-change",(function(e){t=t.compose(e)})),setInterval((function(){t.length()>0&&(console.log("Saving changes",t),t=new e)}),5e3),window.onbeforeunload=function(){if(t.length()>0)return"There are unsaved changes. Are you sure you want to leave?"}}};jQuery(document).ready((function(){t.init()}))}});