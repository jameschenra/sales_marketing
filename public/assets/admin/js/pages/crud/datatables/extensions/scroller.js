!function(t){var a={};function e(n){if(a[n])return a[n].exports;var l=a[n]={i:n,l:!1,exports:{}};return t[n].call(l.exports,l,l.exports,e),l.l=!0,l.exports}e.m=t,e.c=a,e.d=function(t,a,n){e.o(t,a)||Object.defineProperty(t,a,{enumerable:!0,get:n})},e.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,a){if(1&a&&(t=e(t)),8&a)return t;if(4&a&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(e.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&a&&"string"!=typeof t)for(var l in t)e.d(n,l,function(a){return t[a]}.bind(null,l));return n},e.n=function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,"a",a),a},e.o=function(t,a){return Object.prototype.hasOwnProperty.call(t,a)},e.p="/",e(e.s=476)}({476:function(t,a,e){t.exports=e(477)},477:function(t,a,e){"use strict";var n={init:function(){$("#kt_datatable").DataTable({responsive:!0,ajax:HOST_URL+"/api/datatables/demos/server.php",deferRender:!0,scrollY:"500px",scrollCollapse:!0,scroller:!0,columns:[{data:"RecordID",visible:!1},{data:"OrderID"},{data:"ShipCity"},{data:"ShipAddress"},{data:"CompanyAgent"},{data:"CompanyName"},{data:"ShipDate"},{data:"Status"},{data:"Type"},{data:"Actions",responsivePriority:-1}],columnDefs:[{targets:-1,title:"Actions",orderable:!1,render:function(t,a,e,n){return'\t\t\t\t\t\t\t<div class="dropdown dropdown-inline">\t\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">\t                                <i class="la la-cog"></i>\t                            </a>\t\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\t\t\t\t\t\t\t\t\t<ul class="nav nav-hoverable flex-column">\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-edit"></i><span class="nav-text">Edit Details</span></a></li>\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-leaf"></i><span class="nav-text">Update Status</span></a></li>\t\t\t\t\t\t\t    \t\t<li class="nav-item"><a class="nav-link" href="#"><i class="nav-icon la la-print"></i><span class="nav-text">Print</span></a></li>\t\t\t\t\t\t\t\t\t</ul>\t\t\t\t\t\t\t  \t</div>\t\t\t\t\t\t\t</div>\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Edit details">\t\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete">\t\t\t\t\t\t\t\t<i class="la la-trash"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t'}},{width:"75px",targets:-3,render:function(t,a,e,n){var l={1:{title:"Pending",class:"label-light-primary"},2:{title:"Delivered",class:" label-light-danger"},3:{title:"Canceled",class:" label-light-primary"},4:{title:"Success",class:" label-light-success"},5:{title:"Info",class:" label-light-info"},6:{title:"Danger",class:" label-light-danger"},7:{title:"Warning",class:" label-light-warning"}};return void 0===l[t]?t:'<span class="label label-lg font-weight-bold'+l[t].class+' label-inline">'+l[t].title+"</span>"}},{width:"75px",targets:-2,render:function(t,a,e,n){var l={1:{title:"Online",state:"danger"},2:{title:"Retail",state:"primary"},3:{title:"Direct",state:"success"}};return void 0===l[t]?t:'<span class="label label-'+l[t].state+' label-dot mr-2"></span><span class="font-weight-bold text-'+l[t].state+'">'+l[t].title+"</span>"}}]})}};jQuery(document).ready((function(){n.init()}))}});