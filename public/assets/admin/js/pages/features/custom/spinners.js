!function(t){var e={};function n(i){if(e[i])return e[i].exports;var r=e[i]={i:i,l:!1,exports:{}};return t[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,i){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(i,r,function(e){return t[e]}.bind(null,r));return i},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=664)}({664:function(t,e,n){t.exports=n(665)},665:function(t,e,n){"use strict";var i={init:function(){var t;t=KTUtil.getById("kt_btn_1"),KTUtil.addEvent(t,"click",(function(){KTUtil.btnWait(t,"spinner spinner-right spinner-white pr-15","Please wait"),setTimeout((function(){KTUtil.btnRelease(t)}),1e3)})),function(){var t=KTUtil.getById("kt_btn_2");KTUtil.addEvent(t,"click",(function(){KTUtil.btnWait(t,"spinner spinner-dark spinner-right pr-15","Loading"),setTimeout((function(){KTUtil.btnRelease(t)}),1e3)}))}(),function(){var t=KTUtil.getById("kt_btn_3");KTUtil.addEvent(t,"click",(function(){KTUtil.btnWait(t,"spinner spinner-left spinner-darker-success pl-15","Disabled..."),setTimeout((function(){KTUtil.btnRelease(t)}),1e3)}))}(),function(){var t=KTUtil.getById("kt_btn_4");KTUtil.addEvent(t,"click",(function(){KTUtil.btnWait(t,"spinner spinner-left spinner-darker-danger pl-15","Please wait"),setTimeout((function(){KTUtil.btnRelease(t)}),1e3)}))}()}};jQuery(document).ready((function(){i.init()}))}});