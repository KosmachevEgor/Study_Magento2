define(['jquery'], function ($){
   var widgetMixin = {
       changeBorderColor: function () {
           $(".studyid-index-index").find('.form-timer').css({'border': 'solid 2px #00edff'});
           this._super();
       }
   };

   return function (targetWidget) {
       $.widget('studynamespace.borderWidget', targetWidget, widgetMixin);
       return $.studynamespace.borderWidget;
   }
})
