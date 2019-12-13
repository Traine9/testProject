/*================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 3.1
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================

NOTE:
------
PLACE HERE YOUR OWN JS CODES AND IF NEEDED.
WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR CUSTOM SCRIPT IT'S BETTER LIKE THIS. */

var fileInputTextDiv = document.getElementById('file_input_text_div');
var fileInput = document.getElementById('file_input_file');
var fileInputText = document.getElementById('file_input_text');
fileInput.addEventListener('change', changeInputText);
fileInput.addEventListener('change', changeState);

function changeInputText() {
    var str = fileInput.value;
    var i;
    if (str.lastIndexOf('\\')) {
        i = str.lastIndexOf('\\') + 1;
    } else if (str.lastIndexOf('/')) {
        i = str.lastIndexOf('/') + 1;
    }
    fileInputText.value = str.slice(i, str.length);
}

function changeState() {
    if (fileInputText.value.length != 0) {
        if (!fileInputTextDiv.classList.contains("is-focused")) {
            fileInputTextDiv.classList.add('is-focused');
        }
    } else {
        if (fileInputTextDiv.classList.contains("is-focused")) {
            fileInputTextDiv.classList.remove('is-focused');
        }
    }
}