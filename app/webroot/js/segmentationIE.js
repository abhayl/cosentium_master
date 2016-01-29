var cnt = 0;
function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}
function getTag() {
		var selection = window.getSelection();
		var range = selection.getRangeAt(0);
		var element = range.commonAncestorContainer;
		var color = get_random_color();
		var container = document.getElementById("container");
		$(".marked").css("background-color", "");
		$(".marked").removeClass('marked');
		//if (isIE() == 9) {
			var startContainer = range.startContainer;
			var endContainer = range.endContainer;
			markNodeIE1(range.commonAncestorContainer, startContainer, endContainer, color);
		//}
		/*
		else {
			markNode(selection, element, color);
		}*/
		document.getElementById('contract').value = document.getElementById('container').innerHTML;
	}
	
function markNodeIE1(commonAncestorContainer, startContainer, endContainer, color) {
	var textNodes = getTextNodesBetween(commonAncestorContainer, startContainer, endContainer);
	
	var currentElement;
	for(var i = 0; i < textNodes.length; i++) {
		currentElement = textNodes[i];
		if(currentElement.nodeName == "#text") {
					var newSpan = document.createElement("span");
					newSpan.innerHTML = currentElement.nodeValue;
					currentElement.parentNode.replaceChild(newSpan,currentElement);
					currentElement = newSpan;
		}
		if(currentElement.className) {
				currentElement.oldClassName = currentElement.className;
		} else {
			currentElement.oldClassName = "";
		}
		currentElement.className =  currentElement.className  + " marked";
				currentElement.style.backgroundColor = color;
	}
}

function getTextNodesBetween(rootNode, startNode, endNode) {
    var pastStartNode = false, reachedEndNode = false, textNodes = [];

    function getTextNodes(node) {
        if (node == startNode) {
            pastStartNode = true;
        } 
		if (node == endNode) {
            reachedEndNode = true;
        } 
		//check for text node
		if (node.nodeType == 3) {
            if (pastStartNode && !reachedEndNode && !/^\s*$/.test(node.nodeValue)) {
                textNodes.push(node);
            } else if ((node == endNode) && !/^\s*$/.test(node.nodeValue)) {
				textNodes.push(node);
			}
        } else if (!reachedEndNode || (node == endNode)) {
			for (var i = 0, len = node.childNodes.length; !reachedEndNode && i < len; ++i) {
				getTextNodes(node.childNodes[i]);
			}
        }
    }

    getTextNodes(rootNode);
    return textNodes;
}



function markNodeIE(range, parentElement, color) {
		var element = parentElement;
		var comprng = document.createRange ?
                        document.createRange() : document.body.createTextRange();
        comprng.selectNodeContents	(element);
		
		//var startToStart = range.compareBoundaryPoints(Range.START_TO_START, comprng);
		//var endToEnd = range.compareBoundaryPoints(Range.END_TO_END, comprng);
		//if (startToStart >= 0 && endToEnd  <= 0) {

		if (range.compareBoundaryPoints(Range.END_TO_START, comprng) < 0 &&
            range.compareBoundaryPoints(Range.START_TO_END, comprng) > 0) {
				if(element.nodeName == "#text") {
					var newSpan = document.createElement("span");
					newSpan.innerHTML = element.nodeValue;
					element.parentNode.replaceChild(newSpan,element);
					element = newSpan;
				}
				if(element.className) {
					element.oldClassName = element.className;
				} else {
					element.oldClassName = "";
				}
				element.className =  element.className  + " marked";
				element.style.backgroundColor = color;
		} else {
			var children = element.childNodes;
			//alert(children);
			var i = 0;
			var length = children.length;
			while( i < length ){	
				//alert(i);
				markNodeIE(range, children[i], color);
				i++;
			}
		}	
}

	 function get_random_color() {
		return randomColor(100);
	}
	
function randomColor(brightness){
		var col = '#' + randomChannel(brightness) + randomChannel(brightness) + randomChannel(brightness);
		return col;
	};

	function randomChannel(brightness){
		var r = 255-brightness;
		var n = 0|((Math.random() * r) + brightness);
		var s = n.toString(16);
		return (s.length==1) ? '0'+s : s;
	};

	
	function deleteSections() {
		var form = document.forms["remove-section-form"];
	}
	