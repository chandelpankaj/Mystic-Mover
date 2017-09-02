const gameMap = function(col, row, imageId, mapLayer, ctx){
	this.firstBoxCol = col;
	this.firstBoxRow = row;
	this.destBoxRow = row;
	this.destBoxCol = col;
	this.blockSize = 70;
	this.clipX = col * 70;
	this.clipY = row * 70;
	this.clipW = ctx.canvas.width;
	this.clipH = ctx.canvas.height;
	this.mapLayer = mapLayer;
	this.x = 0;
	this.y = 0;
	this.movingSpeed = 1;
	this.sprite = document.getElementById(imageId);
	this.ctx = ctx;
	this.isMoving = false;
}
gameMap.prototype.render = function(){

//drawImage(my_pic, clipX, clipY, clipW, clipH, x, y, w, h)
	this.ctx.drawImage(this.sprite, this.clipX, this.clipY, this.clipW, this.clipH, this.x, this.y, this.clipW, this.clipH);
}

gameMap.prototype.update = function(){
	if(this.isMoving){
		if(this.clipX > this.destBoxCol * this.blockSize){
			this.clipX -= this.movingSpeed;
			if(this.clipX < this.destBoxCol * this.blockSize){
				this.clipX = this.destBoxCol * this.blockSize;
			}
		}
		else if(this.clipX < this.destBoxCol * this.blockSize){
			this.clipX += this.movingSpeed;
			if(this.clipX > this.destBoxCol * this.blockSize){
				this.clipX = this.destBoxCol * this.blockSize;
			}
		}
		if(this.clipY < this.destBoxRow * this.blockSize){
			this.clipY += this.movingSpeed;
			if(this.clipY > this.destBoxRow * this.blockSize){
				this.clipY = this.destBoxRow * this.blockSize;
			}
		}
		else if(this.clipY > this.destBoxRow * this.blockSize){
			this.clipY -= this.movingSpeed;
			if(this.clipY < this.destBoxRow * this.blockSize){
				this.clipY = this.destBoxRow * this.blockSize;
			}
		}
	}
}
gameMap.prototype.findDestinationBox = function(fromDirection, toDirection){
	if(toDirection == this.oppositeDirection(fromDirection)){
		switch(fromDirection){
			case 'left':
				this.destBoxCol = this.firstBoxCol + 2; break;
			case 'up':
				this.destBoxRow = this.firstBoxRow + 2; break;
			case 'right':
				this.destBoxCol = this.firstBoxCol - 2; break;
			case 'down':
				this.destBoxRow = this.firstBoxRow - 2; break;
		}
	}
	else{
		switch(toDirection){
			case 'up':
				this.destBoxRow = this.firstBoxRow - 1; break;
			case 'left':
				this.destBoxCol = this.firstBoxCol - 1; break;
			case 'down':
				this.destBoxRow = this.firstBoxRow + 1; break;
			case 'right':
				this.destBoxCol = this.firstBoxCol + 1; break;
		}
		switch(fromDirection){
			case 'up':
				this.destBoxRow = this.firstBoxRow + 1; break;
			case 'left':
				this.destBoxCol = this.firstBoxCol + 1; break;
			case 'down':
				this.destBoxRow = this.firstBoxRow - 1; break;
			case 'right':
				this.destBoxCol = this.firstBoxCol - 1; break;
		}
	}
}
gameMap.prototype.oppositeDirection = function(direction){
	switch(direction){
		case 'left': return 'right';
		case 'right': return 'left';
		case 'up': return 'down';
		case 'down': return 'up';
	}
}
gameMap.prototype.move = function(direction, distance){
	switch(direction){
		case 'left':
			this.clipX += distance;
			break;
		case 'up':
			this.clipY += distance;
			break;
		case 'right':
			this.clipX -= distance;
			break;
		case 'down':
			this.clipY -= distance;
			break;
		default:
			console.log('wrong direction to move map');
			break;
	}
}


gameMap.prototype.correctPosition = function(){

	let correctX = this.destBoxCol * 70;
	let correctY = this.destBoxRow * 70;
	if(this.clipX != correctX)
		this.clipX = correctX;
	if(this.clipY != correctY)
		this.clipY = correctY;


	///just for checking error
	if(this.clipY%70!=0 || this.clipX%70!=0){
		console.log('error: map deviation correction formula not correct');
	}
}

