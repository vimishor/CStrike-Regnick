/**
 * nTip - Simple jquery tooltip plugin
 *
 * @version: 1.0 - (19/09/2011)
 * @author Staicu Ionut-Bogdan
 * @link http://www.iamntz.com/184/frontend-developer/tooltips/
 */
jQuery.fn.nTip = function() {
  $(document.createElement('div')).attr('id', 'nTipWrapper').appendTo('body');
  $(this).each(function(){
    var tooltipEl=$(this);
    tooltipEl.data('title', tooltipEl.attr('title')).removeAttr('title');
    tooltipEl.hover(function(e){
      $('#nTipWrapper').empty().html(tooltipEl.data('title')).css({
        left:e.pageX+20,
        top:e.pageY+20
      }).show();
      tooltipEl.bind('mousemove.nTip', function(el){
        if( Math.round($(window).width()/2) > el.pageX ) {
          $('#nTipWrapper').css({
            marginLeft:0
          });
        } else {
          $('#nTipWrapper').css({
            marginLeft:-$('#nTipWrapper').outerWidth()-20
          })
        }
        $('#nTipWrapper').css({
          left:el.pageX+20,
          top:el.pageY+20
        })
      });
    }, function(){
      tooltipEl.unbind('mousemove.nTip');
      $('#nTipWrapper').empty().hide();
    });
  });
};
$('.nTip').nTip();