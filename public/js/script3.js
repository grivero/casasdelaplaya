var slide; 
				function showNext(){
					clearInterval(slide);
					$('#img-somos2').animate(
							{marginLeft:591}, {duration: 'slow', easing: 'swing'}, 500
						);
					slide = window.setTimeout(function(){showPrev()}, 5000);
				};
				
				
				function showPrev(){
					clearInterval(slide);
					$('#img-somos2').animate(
							{marginLeft:0}, {duration: 'slow', easing: 'swing'}, 500
						);
					slide = window.setTimeout(function(){showNext()}, 5000);
				};
					
				
				$(document).ready(function () {
					window.setTimeout(function(){showNext()}, 5000);  
				});
				