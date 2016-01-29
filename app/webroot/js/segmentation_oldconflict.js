function getTag() {
		var selection = window.getSelection();
		var range = selection.getRangeAt(0);
		var element = range.commonAncestorContainer;
		var color = get_random_color();
		var container = document.getElementById("container");
		$(".marked").css("background-color", "");
		$(".marked").removeClass('marked');
		var startContainer = range.startContainer;
		var endContainer = range.endContainer;
		markNode(range.commonAncestorContainer, startContainer, endContainer, color);
		document.getElementById('contract').value = document.getElementById('container').innerHTML;
	}
	
function markNode(commonAncestorContainer, startContainer, endContainer, color) {
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

function isValid(name){
	return !/[().~`!#@$%\^&*+=\-\[\]\\';,/{}|\\_":<>\?]/g.test(name);
}
	
<<<<<<< .mine
	function submitForm() {
		if(!document.getElementById('contract').value){
			document.getElementById('light1').style.display = 'block';
			document.getElementById('fade1').style.display = 'block';
			return false;
		}
		
		if(isValid(document.getElementById('name').value) == false) {
			document.getElementById('light5').style.display = 'block';
			document.getElementById('fade5').style.display = 'block';
			return false;
		}

		if(!document.getElementById('name').value){
			document.getElementById('light2').style.display = 'block';
			document.getElementById('fade2').style.display = 'block';
			return false;
		}
		document.getElementById('masterDocDefaultChangeControlForm').submit();
						return true;
		/*else {
			var sectionName = $.trim(document.getElementById('name').value);
			sectionName = sectionName.replace(/\s\s+/g, ' ');
			sectionName = sectionName.replace(/ /g,"_");
			$.ajax({
				type: "post",		
				// Request method: post, get
				url: "/masterdocs/validatesectionname/",
				data: {name:sectionName},		// Form variable
				success: function(response) {
					if(response == 1) {
						document.getElementById('masterDocDefaultChangeControlForm').submit();
						return true;
					}	
					else {	
						document.getElementById('light3').style.display = 'block';
						document.getElementById('fade3').style.display = 'block';
						return false;
					}	
				}
			});
			return false;
		}	*/

=======
function submitForm() {
	if(!document.getElementById('contract').value){
		document.getElementById('light1').style.display = 'block';
		document.getElementById('fade1').style.display = 'block';
		return false;
>>>>>>> .r463
	}
	
	if(isValid(document.getElementById('name').value) == false) {
		document.getElementById('light5').style.display = 'block';
		document.getElementById('fade5').style.display = 'block';
		return false;
	}

	if(!document.getElementById('name').value){
		document.getElementById('light2').style.display = 'block';
		document.getElementById('fade2').style.display = 'block';
		return false;
	}
	else {
		var sectionName = $.trim(document.getElementById('name').value);
		sectionName = sectionName.replace(/\s\s+/g, ' ');
		sectionName = sectionName.replace(/ /g,"_");
		$.ajax({
			type: "post",		
			// Request method: post, get
			url: "/masterdocs/validatesectionname/",
			data: {name:sectionName},		// Form variable
			success: function(response) {
				if(response == 1) {
					document.getElementById('masterDocDefaultChangeControlForm').submit();
					return true;
				}	
				else {	
					document.getElementById('light3').style.display = 'block';
					document.getElementById('fade3').style.display = 'block';
					return false;
				}	
			}
		});
		return false;
	}	

}	
	