!function(e){var n={};function o(r){if(n[r])return n[r].exports;var t=n[r]={i:r,l:!1,exports:{}};return e[r].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=e,o.c=n,o.d=function(e,n,r){o.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:r})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,n){if(1&n&&(e=o(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(o.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var t in e)o.d(r,t,function(n){return e[n]}.bind(null,t));return r},o.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(n,"a",n),n},o.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},o.p="/",o(o.s=636)}({636:function(e,n,o){e.exports=o(637)},637:function(e,n,o){"use strict";var r={init:function(){var e,n,o;e=$("#kt_dropdown_api_output"),n=new KTDropdown("kt_dropdown_api_1"),o=new KTDropdown("kt_dropdown_api_2"),n.on("afterShow",(function(n){e.append("<p>Dropdown 1: afterShow event fired</p>")})),n.on("beforeShow",(function(n){e.append("<p>Dropdown 1: beforeShow event fired</p>")})),n.on("afterHide",(function(n){e.append("<p>Dropdown 1: afterHide event fired</p>")})),n.on("beforeHide",(function(n){e.append("<p>Dropdown 1: beforeHide event fired</p>")})),o.on("afterShow",(function(n){e.append("<p>Dropdown 2: afterShow event fired</p>")})),o.on("beforeShow",(function(n){e.append("<p>Dropdown 2: beforeShow event fired</p>")})),o.on("afterHide",(function(n){e.append("<p>Dropdown 2: afterHide event fired</p>")})),o.on("beforeHide",(function(n){e.append("<p>Dropdown 2: beforeHide event fired</p>")}))}};jQuery(document).ready((function(){r.init()}))}});