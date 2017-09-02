const gameCharacter = function(boxRow, boxCol, width, height, direction, maxFrame, imageId, ctx){
	this.boxRow = boxRow;
	this.boxCol = boxCol;
	this.mapPosRow = 3;
	this.mapPosCol = 2;
	this.mapDesRow = 3;
	this.mapDesCol = 2;
	this.destBoxCol = boxCol;
	this.destBoxRow = boxRow;
	this.x = boxCol * 70 + (70 - 48)/2; //the difference box size and character size
	this.y = boxRow * 70 + (70 - 48)/2;
	this.width = width;
	this.height = height;
	this.direction = direction;
	this.maxFrame = maxFrame;
	this.ctx = ctx;
	this.blockSize = 70;
	this.frame = 0;
	this.sprite = document.getElementById(imageId);
	this.movingSpeed = 1;
	this.isMoving = false;
	this.isSliding = false;
	this.slidingDirection = 'none';
	this.slidingSpeed = 3;
	this.distanceMoved = 0;
	this.ticks = 0;
}
///to draw the character
gameCharacter.prototype.render = function(){
	let col = 0;
	this.ticks++;
	switch(this.direction){
		case 'right':
			col++;
		case 'up':
			col++;
		case 'left':
			col++;
	}
//drawImage(my_pic, clipX, clipY, clipW, clipH, x, y, w, h)
	this.ctx.drawImage(this.sprite, this.width*col, this.height*this.frame, this.width, this.height, this.x, this.y, this.width, this.height);
}

gameCharacter.prototype.animate = function(){
	if(this.ticks%5==0){
		this.frame = (this.frame +1) % this.maxFrame;
	}
}


///to update the position and direction
gameCharacter.prototype.update = function(map){
	if(this.isMoving){
		console.log(this.mapPosRow+' , '+this.mapPosCol);
		map.movingSpeed = this.movingSpeed;
		this.animate();
		this.distanceMoved += this.movingSpeed;
		if(this.distanceMoved >= this.blockSize){
			this.distanceMoved = 0;
			this.isMoving = false;
			this.boxCol = this.destBoxCol;
			this.boxRow = this.destBoxRow;
			map.isMoving = false;
			map.firstBoxCol = map.destBoxCol;
			map.firstBoxRow = map.destBoxRow;
			map.correctPosition();
		}
	}
	else if(this.isSliding){
		if(this.x - 11> this.destBoxCol * this.blockSize){
			this.x -= this.slidingSpeed;
			if(this.x - 11< this.destBoxCol * this.blockSize){
				this.x = this.destBoxCol * this.blockSize + 11;
			}
		}
		else if(this.x - 11 < this.destBoxCol * this.blockSize){
			this.x += this.slidingSpeed;
			if(this.x - 11 > this.destBoxCol * this.blockSize){
				this.x = this.destBoxCol * this.blockSize + 11;
			}
		}
		if(this.y - 11 < this.destBoxRow * this.blockSize){
			this.y += this.slidingSpeed;
			if(this.y - 11 > this.destBoxRow * this.blockSize){
				this.y = this.destBoxRow * this.blockSize + 11;
			}
		}
		else if(this.y - 11> this.destBoxRow * this.blockSize){
			this.y -= this.slidingSpeed;
			if(this.y - 11 < this.destBoxRow * this.blockSize){
				this.y = this.destBoxRow * this.blockSize + 11;
			}
		}

		map.movingSpeed = this.slidingSpeed;
		if(this.x -11 == this.destBoxCol * this.blockSize && this.y - 11 == this.destBoxRow * this.blockSize){
			map.isMoving = false;
			map.firstBoxCol = map.destBoxCol;
			map.firstBoxRow = map.destBoxRow;
			map.correctPosition();
			this.isSliding = false;
		}
	}
	
}
gameCharacter.prototype.slide = function(direction, distance){
	switch(direction){
		case 'left':
			this.x -= distance;
			break;
		case 'up':
			this.y -= distance;
			break;
		case 'right':
			this.x += distance;
			break;
		case 'down':
			this.y += distance;
			break;
		default:
			console.log('wrong direction in character slide');
			break;
	}

	///just for debugging
	if(this.isMoving){
		console.log('something wrong player should not be moving');
	}

}

gameCharacter.prototype.move = function(direction, map){
	if(direction != this.direction){
		if(!this.isSliding && !this.isMoving){
			this.isSliding = true;
			map.isMoving = true;
			this.findDestinationBox(this.oppositeDirection(direction));
			map.findDestinationBox(this.direction, direction);
			this.direction = direction;
		}
	}
	else if(!this.isSliding && !this.isMoving){
		switch(direction){
			case 'left':
				map.destBoxCol = map.firstBoxCol - 1; this.mapDesCol = this.mapPosCol - 1; break;
			case 'up':
				map.destBoxRow = map.firstBoxRow - 1; this.mapDesRow = this.mapPosRow - 1; break;
			case 'right':
				map.destBoxCol = map.firstBoxCol + 1; this.mapDesCol = this.mapPosCol + 1; break;
			case 'down':
				map.destBoxRow = map.firstBoxRow + 1; this.mapDesRow = this.mapPosRow + 1; break;
		}
		///check if is it possible to move to required block
		if(this.isPossibleToMove(this.mapPosRow, this.mapPosCol, this.mapDesRow, this.mapDesCol, map.mapLayer.tiles, map.mapLayer.verWalls)){
			this.isMoving = true;
			this.mapPosCol = this.mapDesCol;
			this.mapPosRow = this.mapDesRow;
			map.isMoving = true;
		}
		else{
			this.mapDesRow = this.mapPosRow;
			this.mapDesCol = this.mapPosCol;
			console.log('not possible to go to block '+this.mapDesRow+', '+this.mapDesCol);
		}
	}
}

//check if possible to move to destination block
gameCharacter.prototype.isPossibleToMove = function(posX, posY, desX, desY, tiles, verWalls){
	if(tiles[desX][desY] != 0){
		console.log('not possible to move this side');
		return false;
	}

	if(posX == desX){
		if(desY < posY){
			if(verWalls[desX][desY+1] != 0)
				return false;
		}
		else{
			if(verWalls[desX][desY] != 0)
				return false;
		}
	}
	return true;

}
gameCharacter.prototype.findDestinationBox = function(direction){
	switch(direction){
		case 'left':
			this.destBoxCol = 2;
			this.destBoxRow = 3;
			break;
		case 'up':
			this.destBoxCol = 3;
			this.destBoxRow = 2;
			break;
		case 'right':
			this.destBoxCol = 4;
			this.destBoxRow = 3;
			break;
		case 'down':
			this.destBoxCol = 3;
			this.destBoxRow = 4;
			break;
	}
}
gameCharacter.prototype.correctPosition = function(){
	if(this.isSliding)
		return;

	let correctX = this.boxCol * this.blockSize + (this.blockSize - this.width)/2;
	let correctY = this.boxRow * this.blockSize + (this.blockSize - this.height)/2;
	if(this.x != correctX){
		this.x = correctX;
	}
	if(this.y != correctY){
		this.y = correctY;
	}
}


gameCharacter.prototype.oppositeDirection = function(direction){
	if(direction == 'left')
		return 'right';
	if(direction == 'right')
		return 'left';
	if(direction == 'up')
		return 'down';
	if(direction == 'down')
		return 'up';
}