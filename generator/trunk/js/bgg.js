//// Created by Kematzy [kematzy gmail dot com] 2007-08-23
//// Released under the same licence as Blueprint CSS.
//// Version v.03 [2007-08-23]


// turn the submit button on when the user has interacted with the app
function enableSubmitBtn(){
   document.getElementById("submit").disabled=false;
}
// code & functionality extracted from // validate.js v 1.98
// by Brian Lalonde http://webcoder.info/downloads/
function fixInt(fld,sep){ // integer check/complainer 
  if(!fld.value.length||fld.disabled) return true; // blank fields are the domain of requireValue 
  var val= fld.value;
  if(typeof(sep)!='undefined') val= val.replace(new RegExp(sep,'g'),'');
  val= parseInt(val);
  // parse error
  if(isNaN(val)){ alert('The field must contain a number.'); return false; }
  fld.value= val;
  return true;
}
// slightly reworked to work better with this page setup
function checkIntRange(fld,minVal,maxVal){
  if(!fixInt(fld)){
    fld.value = minVal; // remove the non-int
    return false;
  } 
  var val= parseInt(fld.value);
  var msg = 'The field value must be in the range of '+minVal+' to '+maxVal+'.';
  if(val < minVal) { fld.value = minVal; alert(msg); recheckFormOnMinMaxValue(); return false; }
  if(val > maxVal) { fld.value = maxVal; alert(msg); recheckFormOnMinMaxValue(); return false; }
  return true;
}
////  


// code & functionality extracted from code produced
// by Adam Vandenberg [flangy gmail dot com]

// #region Class support
// Adds items from props to obj.
function _extend(obj, props){
  for(var key in props){obj[key]=props[key];}
  return obj;
}

// #region String support
_extend(String.prototype,{
  important: function(){ return this.replace(";", " !important;");},
  trim: function() { return this.replace(/^\s+|\s+$/g, ""); },
  
  template: function(vars){
    return this.replace( 
      /\{(\w*)\}/g,
      function(match,submatch,index){return vars[submatch];});
   },
  endsWith: function(suffix){
    var lastIndex = this.lastIndexOf(suffix);
    return (-1 < lastIndex) && (lastIndex == (this.length-suffix.length));
  },
  removeSuffix: function(suffix){
    return (this.endsWith(suffix))? this.substring(0, this.length-suffix.length) : this;
  },
  after: function(s){
    var index = this.indexOf(s);
    var length = s.length || 1;
    return (-1<index) ? this.substring(index+length) : this;
  },
  indexOfAny: function(charsOrStringList){
    var index=-1;
    var s = this;
    foreach(charsOrStringList, function(token){
      index = s.indexOf(token);
      if (-1 < index) return true;
    });
    return index;
  },
  escapeHTML: function(){
    return this
      .replace(/&/g, "&amp;")
      .replace(/\"/g, "&quot;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }
});
// #endregion

// #region DOM & Events support
function $(o) {
  if (typeof(o) == "string") return document.getElementById(o)
  else return o;
}
//#endregion

// grabs the integer value from the form field, or set's it to null
function grabIntValue(input_field){
  var inputValue = $(input_field).value.trim();
  var asNumber = parseInt(inputValue, 10);
  // Convert NaNs to null.
  return isNaN(asNumber) ? null : asNumber;
}


// application code
// the core method that holds/generates the form settings
function GridSettings(grid_settings){
  if (grid_settings == null) grid_settings = default_settings;
    _extend(this, grid_settings);
}
// calculates the total page width.
GridSettings.prototype.computeTotalWidth = function(){
  if (this.number_of_columns == null || this.column_width == null || this.margin_width == null){ 
    throw new Error("Missing values.");
  }
  this.total_page_width = (this.number_of_columns * this.column_width + ((this.number_of_columns-1) * this.margin_width));
}


// it does what it says on the tin ;-)
var current_settings = new GridSettings();

// grabs the current settings in the form and stores them internally
function readCurrentSettings(){
  var s = new GridSettings({
    desired_page_width: grabIntValue("desired_page_width"),
    number_of_columns: grabIntValue("num_cols"),
    column_width: grabIntValue("column_width"),
    margin_width: grabIntValue("margin_width")
  });
  s.computeTotalWidth();
  return s;
}
// resets the form
function btnResetForm(){
  initialize();
}
// initializes the form
function initialize(){
  populateForm(current_settings);
}
// populates the form with the values generated
function populateForm(s){
  $("desired_page_width").value = s.desired_page_width;
  $("num_cols").value = s.number_of_columns;
  $("column_width").value = s.column_width;
  $("margin_width").value = s.margin_width;
  if(s.total_page_width.toString() == '---'){
    var width_string = s.total_page_width.toString();
  }else{
    var width_string = '<span>' + s.total_page_width.toString() + " px";
    if (s.total_page_width < s.desired_page_width){
      width_string += "</span> (" + (s.desired_page_width-s.total_page_width) + " px under)";
    }else if(s.total_page_width > s.desired_page_width){
      width_string += "</span> (" + (s.total_page_width - s.desired_page_width) + " px over)";
    }    
  };
  $("total_width_output").innerHTML = width_string;
}

// decrements the number of columns value (-) button
function btnDecColumns(){
  var s = readCurrentSettings();
  // min 2 columns (any value in having less than two ??)
  if (s.number_of_columns > 2){
    s.number_of_columns--;
    s.computeTotalWidth();
    populateForm(s);
    enableSubmitBtn();
  }
}
// increments the number of columns value (+) button
function btnIncColumns(){
  var s = readCurrentSettings();
  // max 40 columns (any value in having greater than that ??)
  if (s.number_of_columns < 40){
    s.number_of_columns++;
    s.computeTotalWidth();
    populateForm(s);
    enableSubmitBtn();
  }
}
// calculates the number of columns value based on the other form settings
function btnFitColumnCount(){
  var s = readCurrentSettings();
  s.number_of_columns = Math.floor((s.desired_page_width + s.margin_width) / (s.column_width + s.margin_width));
  s.computeTotalWidth();
  populateForm(s);
  enableSubmitBtn();
}
// calculates the width of the column based on the other form settings
function btnFitColumnWidth(){
  var s = readCurrentSettings();
  s.column_width = Math.floor((s.desired_page_width-(s.number_of_columns-1)*s.margin_width)/s.number_of_columns);
  s.computeTotalWidth();
  populateForm(s);
  enableSubmitBtn();
}
// calculates the margin width based on the other form settings
function btnFitMarginWidth(){
  var s = readCurrentSettings();
  s.margin_width = Math.floor((s.desired_page_width-s.number_of_columns*s.column_width)/(s.number_of_columns-1));
  s.computeTotalWidth();
  populateForm(s);
  enableSubmitBtn();
}
// convenience function for refreshing the form after 
// the form being changed on min/max values reached
function recheckFormOnMinMaxValue(){
  var s = readCurrentSettings();
  s.computeTotalWidth();
  populateForm(s);
  enableSubmitBtn();  
}
// handles changes in the form fields and updates things accordingly
function fieldKeypress(){
  var s = readCurrentSettings();
  populateForm(s);
  enableSubmitBtn();
}
// uncommented as we need to keep the values after a POST refresh of the page.
// window.onload=initialize;
