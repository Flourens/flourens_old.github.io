window.sr = ScrollReveal();
sr.reveal('.reveal', {
    duration: 2000
});

 $('.reservebox li').click(function () {
     $(this).addClass('highlight').siblings().removeClass('highlight');
 }); 
$('.link-1').click(function () {
     $('.link-1').addClass('selected').siblings().removeClass('selected');
     $('.displayb').removeClass('displayb');
     $('.checks-1').addClass('displayb');
 }); 
$('.link-2').click(function () {
     $('.link-2').addClass('selected').siblings().removeClass('selected');
     $('.displayb').removeClass('displayb');
     $('.checks-2').addClass('displayb');
 }); 
$('.link-3').click(function () {
     $('.link-3').addClass('selected').siblings().removeClass('selected');
     $('.displayb').removeClass('displayb');
     $('.checks-3').addClass('displayb');
 });
