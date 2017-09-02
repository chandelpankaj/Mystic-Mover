window.onload = function(){
//drawImage(my_pic, clipX, clipY, clipW, clipH, x, y, w, h)
	const FPS = 50;
	const mapLayer = new mapLayers();
	const ctx = document.getElementById("gameCanvas").getContext("2d");
	const gameCanvasWidth = ctx.canvas.width;
	const gameCanvasHeight = ctx.canvas.height;
	const george = new gameCharacter(3, 2, 48, 48, 'right', 4, 'george', ctx);
	const map = new gameMap(0, 0, 'gameMap', mapLayer, ctx);
	/*
	MAIN GAME LOOP
	*/
	function gameLoop(){
		ctx.clearRect(0,0,ctx.canvas.width, ctx.canvas.height);
		george.update(map);
		map.update();
		map.render();
		george.render();
	}
	var animateInterval = setInterval(gameLoop,1000/FPS);
	
	

	document.addEventListener('keydown', function(event){
		//65 A, 37-left, 83-s 40-down, 68-d, 96-right, 87-w, 38-up

		switch(event.keyCode){
			case 65:
			case 37:
				george.move('left',map);
			break;
			case 83:
			case 40:
				george.move('down',map);
			break;
			case 68:
			case 39:
				george.move('right',map);
			break;
			case 87:
			case 38:
				george.move('up',map);
			break;
		}
	});
}