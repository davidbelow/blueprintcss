// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults

// TODO: Temporarily stored here. Should reall
function toggleCustomize(step,link){
  new Effect.toggle($(step),'slide');
  // change the name of the link
  var showTxt = "CUSTOMIZE";
  var closeTxt = "CANCEL"
  link.update( (link.innerHTML == showTxt) ? closeTxt : showTxt );
};
function toggleAllSteps(link){
  // loop through step-1 to 5
  for (var i=1; i <= 5; i++) {
    new Effect.toggle($('step-'+i),'slide');
  };
  // change the name of the link
  var showTxt = "SHOW ALL OPTIONS";
  link.update( (link.innerHTML == showTxt) ? "HIDE ALL OPTIONS" : showTxt );
};

function btnResetGridForm(){
  // TODO: sort out this reset functionality in a DRY way.
  var g = default_settings['grid'];
  $('number_of_columns').value = g['number_of_columns'];
  $('column_width').value = g['column_width'];
  $('margin_width').value = g['margin_width'];
  $('page_width').value = g['desired_page_width'];
  
}