/* Author:

*/

//slider 1
				var position = 1;
				function showNext(){
					$('div.slider'+position).animate(
							{marginLeft:-507}, {duration: 'slow', easing: 'swing'}, 500
						);
				};
				
				function showPrev(){
					$('div.slider'+position).animate(
							{marginLeft:0}, {duration: 'slow', easing: 'swing'}, 500
						);
				};
				
				//play function
				$('a.next').click(function(){
					if (position != 5){
						showNext();
					    position++;
					}
					
				});
				
				//play function
				$('a.prev').click(function(){
					if (position !=1){
						position--;
						showPrev();
					}
					
				});



