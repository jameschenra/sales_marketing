!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=676)}({676:function(t,e,n){t.exports=n(677)},677:function(t,e,n){"use strict";var o={init:function(){$("[data-switch=true]").bootstrapSwitch(),$("#kt_notify_btn").click((function(){var t={message:"New order has been placed"};$("#kt_notify_title").prop("checked")&&(t.title="Notification Title"),""!=$("#kt_notify_icon").val()&&(t.icon="icon "+$("#kt_notify_icon").val()),$("#kt_notify_url").prop("checked")&&(t.url="www.keenthemes.com",t.target="_blank");var e=$.notify(t,{type:$("#kt_notify_state").val(),allow_dismiss:$("#kt_notify_dismiss").prop("checked"),newest_on_top:$("#kt_notify_top").prop("checked"),mouse_over:$("#kt_notify_pause").prop("checked"),showProgressbar:$("#kt_notify_progress").prop("checked"),spacing:$("#kt_notify_spacing").val(),timer:$("#kt_notify_timer").val(),placement:{from:$("#kt_notify_placement_from").val(),align:$("#kt_notify_placement_align").val()},offset:{x:$("#kt_notify_offset_x").val(),y:$("#kt_notify_offset_y").val()},delay:$("#kt_notify_delay").val(),z_index:$("#kt_notify_zindex").val(),animate:{enter:"animate__animated animate__"+$("#kt_notify_animate_enter").val(),exit:"animate__animated animate__"+$("#kt_notify_animate_exit").val()}});$("#kt_notify_progress").prop("checked")&&(setTimeout((function(){e.update("message","<strong>Saving</strong> Page Data."),e.update("type","primary"),e.update("progress",20)}),1e3),setTimeout((function(){e.update("message","<strong>Saving</strong> User Data."),e.update("type","warning"),e.update("progress",40)}),2e3),setTimeout((function(){e.update("message","<strong>Saving</strong> Profile Data."),e.update("type","danger"),e.update("progress",65)}),3e3),setTimeout((function(){e.update("message","<strong>Checking</strong> for errors."),e.update("type","success"),e.update("progress",100)}),4e3))}))}};jQuery(document).ready((function(){o.init()}))}});