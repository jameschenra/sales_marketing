!function(t){var n={};function s(e){if(n[e])return n[e].exports;var l=n[e]={i:e,l:!1,exports:{}};return t[e].call(l.exports,l,l.exports,s),l.l=!0,l.exports}s.m=t,s.c=n,s.d=function(t,n,e){s.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:e})},s.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},s.t=function(t,n){if(1&n&&(t=s(t)),8&n)return t;if(4&n&&"object"==typeof t&&t&&t.__esModule)return t;var e=Object.create(null);if(s.r(e),Object.defineProperty(e,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var l in t)s.d(e,l,function(n){return t[n]}.bind(null,l));return e},s.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return s.d(n,"a",n),n},s.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},s.p="/",s(s.s=684)}({684:function(t,n,s){t.exports=s(685)},685:function(t,n,s){"use strict";var e={init:function(){var t;new jKanban({element:"#kt_kanban_1",gutter:"0",widthBoard:"250px",boards:[{id:"_inprocess",title:"In Process",item:[{title:'<span class="font-weight-bold">You can drag me too</span>'},{title:'<span class="font-weight-bold">Buy Milk</span>'}]},{id:"_working",title:"Working",item:[{title:'<span class="font-weight-bold">Do Something!</span>'},{title:'<span class="font-weight-bold">Run?</span>'}]},{id:"_done",title:"Done",item:[{title:'<span class="font-weight-bold">All right</span>'},{title:'<span class="font-weight-bold">Ok!</span>'}]}]}),new jKanban({element:"#kt_kanban_2",gutter:"0",widthBoard:"250px",boards:[{id:"_inprocess",title:"In Process",class:"primary",item:[{title:'<span class="font-weight-bold">You can drag me too</span>',class:"light-primary"},{title:'<span class="font-weight-bold">Buy Milk</span>',class:"light-primary"}]},{id:"_working",title:"Working",class:"success",item:[{title:'<span class="font-weight-bold">Do Something!</span>',class:"light-success"},{title:'<span class="font-weight-bold">Run?</span>',class:"light-success"}]},{id:"_done",title:"Done",class:"danger",item:[{title:'<span class="font-weight-bold">All right</span>',class:"light-danger"},{title:'<span class="font-weight-bold">Ok!</span>',class:"light-danger"}]}]}),new jKanban({element:"#kt_kanban_3",gutter:"0",widthBoard:"250px",click:function(t){alert(t.innerHTML)},boards:[{id:"_todo",title:"To Do",class:"light-primary",dragTo:["_working"],item:[{title:"My Task Test",class:"primary"},{title:"Buy Milk",class:"primary"}]},{id:"_working",title:"Working",class:"light-warning",item:[{title:"Do Something!",class:"warning"},{title:"Run?",class:"warning"}]},{id:"_done",title:"Done",class:"light-success",dragTo:["_working"],item:[{title:"All right",class:"success"},{title:"Ok!",class:"success"}]},{id:"_notes",title:"Notes",class:"light-danger",item:[{title:"Warning Task",class:"danger"},{title:"Do not enter",class:"danger"}]}]}),t=new jKanban({element:"#kt_kanban_4",gutter:"0",click:function(t){alert(t.innerHTML)},boards:[{id:"_backlog",title:"Backlog",class:"light-dark",item:[{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <img alt="Pic" src="assets/media/users/300_24.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">SEO Optimization</span>\n                        \t            <span class="label label-inline label-light-success font-weight-bold">In progress</span>\n                        \t        </div>\n                        \t    </div>\n                            '},{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <span class="symbol-label font-size-h4">A.D</span>\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Finance</span>\n                        \t            <span class="label label-inline label-light-danger font-weight-bold">Pending</span>\n                        \t        </div>\n                        \t    </div>\n                            '}]},{id:"_todo",title:"To Do",class:"light-danger",item:[{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <img alt="Pic" src="assets/media/users/300_16.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Server Setup</span>\n                        \t            <span class="label label-inline label-light-dark font-weight-bold">Completed</span>\n                        \t        </div>\n                        \t    </div>\n                            '},{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <img alt="Pic" src="assets/media/users/300_15.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Report Generation</span>\n                        \t            <span class="label label-inline label-light-warning font-weight-bold">Due</span>\n                        \t        </div>\n                        \t    </div>\n                            '}]},{id:"_working",title:"Working",class:"light-primary",item:[{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                            \t         <img alt="Pic" src="assets/media/users/300_24.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Marketing</span>\n                        \t            <span class="label label-inline label-light-danger font-weight-bold">Planning</span>\n                        \t        </div>\n                        \t    </div>\n                            '},{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-light-info mr-3">\n                        \t            <span class="symbol-label font-size-h4">A.P</span>\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Finance</span>\n                        \t            <span class="label label-inline label-light-primary font-weight-bold">Done</span>\n                        \t        </div>\n                        \t    </div>\n                            '}]},{id:"_done",title:"Done",class:"light-success",item:[{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <img alt="Pic" src="assets/media/users/300_11.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">SEO Optimization</span>\n                        \t            <span class="label label-inline label-light-success font-weight-bold">In progress</span>\n                        \t        </div>\n                        \t    </div>\n                            '},{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-success mr-3">\n                        \t            <img alt="Pic" src="assets/media/users/300_20.jpg" />\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Product Team</span>\n                        \t            <span class="label label-inline label-light-danger font-weight-bold">In progress</span>\n                        \t        </div>\n                        \t    </div>\n                            '}]},{id:"_deploy",title:"Deploy",class:"light-primary",item:[{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-light-warning mr-3">\n                        \t            <span class="symbol-label font-size-h4">D.L</span>\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">SEO Optimization</span>\n                        \t            <span class="label label-inline label-light-success font-weight-bold">In progress</span>\n                        \t        </div>\n                        \t    </div>\n                            '},{title:'\n                                <div class="d-flex align-items-center">\n                        \t        <div class="symbol symbol-light-danger mr-3">\n                        \t            <span class="symbol-label font-size-h4">E.K</span>\n                        \t        </div>\n                        \t        <div class="d-flex flex-column align-items-start">\n                        \t            <span class="text-dark-50 font-weight-bold mb-1">Requirement Study</span>\n                        \t            <span class="label label-inline label-light-warning font-weight-bold">Scheduled</span>\n                        \t        </div>\n                        \t    </div>\n                            '}]}]}),document.getElementById("addToDo").addEventListener("click",(function(){t.addElement("_todo",{title:'\n                        <div class="d-flex align-items-center">\n                            <div class="symbol symbol-light-primary mr-3">\n                                <img alt="Pic" src="assets/media/users/300_14.jpg" />\n                            </div>\n                            <div class="d-flex flex-column align-items-start">\n                                <span class="text-dark-50 font-weight-bold mb-1">Requirement Study</span>\n                                <span class="label label-inline label-light-success font-weight-bold">Scheduled</span>\n                            </div>\n                        </div>\n                    '})})),document.getElementById("addDefault").addEventListener("click",(function(){t.addBoards([{id:"_default",title:"New Board",class:"primary-light",item:[{title:'\n                                <div class="d-flex align-items-center">\n                                    <div class="symbol symbol-success mr-3">\n                                        <img alt="Pic" src="assets/media/users/300_13.jpg" />\n                                    </div>\n                                    <div class="d-flex flex-column align-items-start">\n                                        <span class="text-dark-50 font-weight-bold mb-1">Payment Modules</span>\n                                        <span class="label label-inline label-light-primary font-weight-bold">In development</span>\n                                    </div>\n                                </div>\n                        '},{title:'\n                                <div class="d-flex align-items-center">\n                                    <div class="symbol symbol-success mr-3">\n                                        <img alt="Pic" src="assets/media/users/300_12.jpg" />\n                                    </div>\n                                    <div class="d-flex flex-column align-items-start">\n                                    <span class="text-dark-50 font-weight-bold mb-1">New Project</span>\n                                    <span class="label label-inline label-light-danger font-weight-bold">Pending</span>\n                                </div>\n                            </div>\n                        '}]}])})),document.getElementById("removeBoard").addEventListener("click",(function(){t.removeBoard("_done")}))}};jQuery(document).ready((function(){e.init()}))}});