var chunkArray = new Array();
var chunckPuzzle = new Array();
var gEndTime,gStartTime;
var tempUI = undefined;
var tempSolutionKey = undefined;
var solutionMap = new Object();

function createChunksPuzzle(inputString, chunkSize) {
	if (chunkSize == undefined) {
		chunkSize = 3;
	}
	chunkEngine(inputString, chunkSize);
    dragDropIntegration();
}

function chunkEngine(chunkInput, chunkSize) {
	var length = chunkInput.length;
	chunkCount = Math.ceil(length / chunkSize);
	chunkArray = new Array(chunkCount);
	for ( var i = 0; i < chunkCount; i++) {
		if (chunkSize < chunkInput.length) {
			chunkArray[i] = chunkInput.substr(0, chunkSize).replace(" ","&nbsp");
			chunkInput = chunkInput.substr(chunkSize, chunkInput.length
					- chunkSize);
		} else {
			chunkArray[i] = chunkInput.replace(" ","&nbsp");
		}
	}
	createPuzzle(chunkArray);
	initializeLayout();
}

function createPuzzle(chunkArray) {
	var counter = 0;
	chunckPuzzle = new Array(chunkArray.length);
	while (counter != chunkArray.length) {
		var index = randomNumberGenerate(chunkArray.length);
		if (chunkArray[index] != undefined) {
			chunckPuzzle[counter] = chunkArray[index].replace(" ","&nbsp");
			counter++;
			chunkArray[index] = undefined;
		}
	}
}


function initializeLayout() {
	var parentDivElement = document.getElementById('chunksPuzzle');
	parentDivElement.innerHTML = '';

    var parentSize = 0;
    parentSize = bindParentWidth();
    var parentSizeHeight = 0;
    parentSizeHeight = bindParentHeight();

	var puzzleContainer = document.createElement('div');
	puzzleContainer.id = 'puzzleDivStyle';
	puzzleContainer.className = 'puzzleDivStyle';
	puzzleContainer.align = 'center';
	puzzleContainer.style.width=parentSize.toString()+"em";
	puzzleContainer.style.height=parentSizeHeight.toString()+"em";

    var solutionContainer = document.createElement('div');
	solutionContainer.id = 'puzzleSolveDiv';
	solutionContainer.className = 'solutionDivStyle';
	solutionContainer.align = 'center';
	solutionContainer.style.width=parentSize.toString()+"em";
	solutionContainer.style.height=parentSizeHeight.toString()+"em";

	puzzleContainer.innerHTML = '';
	solutionContainer.innerHTML = '';
	
	 
	for ( var count = 0; count < chunckPuzzle.length; count++) {
        

		var chunkLabel = document.createElement('div');
		chunkLabel.innerHTML = chunckPuzzle[count].replace(" ","&nbsp");
		chunkLabel.id = 'puzzleLable#' + count;
		chunkLabel.className = 'btn-primary0';
		chunkLabel.align = 'center';
		puzzleContainer.appendChild(chunkLabel);
		
		var chunkLableSolDiv = document.createElement('div');
		         chunkLableSolDiv.className = 'lableDivStyle';
         chunkLableSolDiv.id = 'puzzleLableSolDiv#' + count;
         
		solutionContainer.appendChild(chunkLableSolDiv);
		puzzleContainer.style.display = 'block';
	}

	parentDivElement.appendChild(puzzleContainer);
	parentDivElement.appendChild(solutionContainer);
   	startTime();
}

function bindParentWidth(){
    var chunkNumber = chunkArray.length;
    var parentSize = 0;
    var windowWidth = head.js("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js","ui.js","touch.js", function (){
    var a = window.innerWidth / parseFloat($("body").css("font-size"));
    return a;
    });
    var tempChunkNo = 0;
         if(chunkNumber>8){
    		tempChunkNo = (windowWidth - 1)/3.5;
	        parentSize = tempChunkNo*3.5 + 1;
	    }else
	        {
	         parentSize = chunkNumber*3.5+1;
       }
    return parentSize;
}

function bindParentHeight(){
	var chunkNumber = chunkArray.length;
	var parentSizeHeight = 0;
	var windowWidth = head.js("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js","ui.js","touch.js", function (){
	var a = window.innerWidth / parseFloat($("body").css("font-size"));
	return a;
	});
	var tempChunkNo = 0;
	     if(chunkNumber>8){
		tempChunkNo = (windowWidth - 1)/3.5;
	        parentSizeHeight = chunkNumber/tempChunkNo*2;
	     }else
	        {
	         parentSizeHeight = chunkNumber/tempChunkNo*2;
	       }
    	return parentSizeHeight;
}


function randomNumberGenerate(limit) {
	return Math.floor(Math.random() * limit);
}

function dragDropIntegration(){
    head.js("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js","ui.js","touch.js", function (){
	$(".btn-primary0").draggable({revert:false});
	$(".lableDivStyle").droppable({
	    drop: function( event, ui ) {
	    	playSound("media/click_char.mp3");
	        $(ui.draggable);
	        $(".btn-primary0").draggable({revert:false});
	        if((solutionMap[this.id]==undefined) && (document.getElementById(ui.draggable[0].id).innerHTML != solutionMap[this.id])){
	              solutionMap[this.id] = document.getElementById(ui.draggable[0].id).innerHTML;
	              ui.draggable.position({
				              of: $(this),
				              my: 'left top',
				              at: 'left top'
                      });
                checkSolution();
	            }else{
					   $(".btn-primary0").draggable({revert:true});
					   if((tempSolutionKey!= undefined) && (tempUI != undefined)){
					   solutionMap[tempSolutionKey] = tempUI;
				     }
			     }

	    },
	    over: function(event, ui) {
	    },
	    out: function (event, ui){
			    $(".btn-primary0").draggable({revert:false});
			if(document.getElementById(ui.draggable[0].id).innerHTML === solutionMap[this.id]){
			    tempUI = solutionMap[this.id];
			    tempSolutionKey = this.id;
			    delete solutionMap[this.id];
			    }
	    }
	});
});
}

function getMapKeysCount(){
	 return Object.keys(solutionMap).length;
}

function checkSolution(){
    var solution="";
   // alert(getMapKeysCount());
    if(getMapKeysCount() == chunkArray.length){
		  for(var x=0;x<chunkArray.length;x++){
			    solution = solution + solutionMap['puzzleLableSolDiv#'+x];
		  }
	      if(solution === input.split(" ").join("&nbsp;")){
			     alert('Well Done!!! \n Total Time taken to solve '+showTime());
    	  }
		}
}

function showTime() {
	endTime();
	var interval = calculateTime();
	var timeStr = "time passed: ";
	timeStr = timeStr + interval[0] + " minutes " + interval[1] + " seconds ";
	return timeStr;
}

function playSound(filePath){
	var snd = new Audio(filePath); // buffers automatically when created
	snd.play();
}

function calculateTime() {
	var milliseconds = 0;
	var seconds = 0;
	var minutes = 0;
	if (gStartTime == undefined) {
		return timeElapsed(0, 0, 0);
	}
	if (gEndTime == undefined) {
		return timeElapsed(0, 0, 0);
	}

	var diff = gEndTime - gStartTime;
	milliseconds = diff;

	if (milliseconds >= 1000) {
		seconds = Math.floor(milliseconds / 1000);
		milliseconds = milliseconds % 1000;
	}

	if (seconds >= 60) {
		minutes = Math.floor(seconds / 60);
		seconds = seconds % 60;
	}
	return timeElapsed(minutes, seconds, milliseconds);
}

function startTime() {
	gStartTime = new Date();
}

function endTime() {
	gEndTime = new Date();
}

function timeElapsed(minutes, seconds, milliseconds) {
	// three elements in interval, first is minutes, second is seconds, third is
	// milliseconds
	var interval = new Array();
	interval[0] = minutes;
	interval[1] = seconds;
	interval[2] = milliseconds;
	return interval;
}