/* Author:

*/

//slider 1
				var position = 1;
				function showNext(){
					$('div.slider'+position).animate(
							{marginLeft:-602}, {duration: 'slow', easing: 'swing'}, 500
						);
				};
				
				function showPrev(){
					$('div.slider'+position).animate(
							{marginLeft:0}, {duration: 'slow', easing: 'swing'}, 500
						);
				};
				
				//play function
				$('a.next2').click(function(){
					if (position != 22){
						showNext();
					    position++;
					}
					
				});
				
				//play function
				$('a.prev2').click(function(){
					if (position !=1){
						position--;
						showPrev();
					}
					
				});



