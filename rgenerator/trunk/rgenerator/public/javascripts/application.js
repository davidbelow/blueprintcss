// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults

// TODO: Temporarily stored here. Should reall
function toggleStep(step,link){
  new Effect.toggle($(step),'slide');
  // change the name of the link
  var showTxt = "CUSTOMIZE";
  var closeTxt = showTxt + "<br>&uarr;"
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

