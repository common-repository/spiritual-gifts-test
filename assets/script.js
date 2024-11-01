/* function spiritual_gifts_part_one() {
  document.getElementById('spiritual-gifts').style.display = 'block';
  document.getElementById('gifts-results').style.display = 'none';
  document.getElementById('shape-survey').style.display = 'none';
  document.getElementById('submit-form').style.display = 'none';
  scroll(0, 0);
}
function spiritual_gifts_part_two() {
  document.getElementById('spiritual-gifts').style.display = 'none';
  document.getElementById('gifts-results').style.display = 'block';
  document.getElementById('shape-survey').style.display = 'block';
  document.getElementById('submit-form').style.display = 'block';
  scroll(0, 0);
} */
//updates the fields with the correct numbers
jQuery('.ratings').click(function () {
  var typeCount = jQuery('#count_my_arrtypes').val();
  previewResults(typeCount);
})
function previewResults(typeCount) {
  var sortVal = new Array();
  var keyName;
  var cmyarr = jQuery('#count_my_arr').val();
  for (i = 0; i < typeCount; i++) {
    surveyPreview = document.getElementById('typeResult' + i);
    theScore = calculateScore(i, typeCount);
    maxScore = cmyarr / typeCount * 4;
    scoreValue = parseInt(theScore * (100 / maxScore));
    surveyPreview.value = scoreValue + '%';
    keyName = document.getElementById('typeTitle' + i).value;
    //sortVal[keyName] = parseInt(theScore*(100/maxScore))+'%';
    sortVal[i] = new Array(2);
    sortVal[i][0] = scoreValue;
    sortVal[i][1] = keyName;
    //                document.getElementById('typeTitle'+i).value += keyName;
  }
  sortVal.sort(function (a, b) { return ((a[0] > b[0]) ? -1 : ((a[0] < b[0]) ? 1 : 0)) });
  document.getElementById('top_1').value = sortVal[0][1];
  document.getElementById('top_2').value = sortVal[1][1];
  document.getElementById('top_3').value = sortVal[2][1];
  document.getElementById('topScore_1').value = sortVal[0][0] + '%';
  document.getElementById('topScore_2').value = sortVal[1][0] + '%';
  document.getElementById('topScore_3').value = sortVal[2][0] + '%';
  document.getElementById('topreason_1').innerHTML = sortVal[0][1] + ': ';
  document.getElementById('topreason_2').innerHTML = sortVal[1][1] + ': ';
  document.getElementById('topreason_3').innerHTML = sortVal[2][1] + ': ';
}
//adds the correct fields together for the type (missions, etc) score
function calculateScore(catNum, typeCount) {
  catScore = 0;
  var cmyarr = jQuery('#count_my_arr').val();
  for (j = catNum; j < cmyarr; j += typeCount) {
    catScore += parseInt(getCheckedValue(document.forms['mySurvey'].elements['strength_' + j]));
  }
  return catScore;
}
// return the value of the radio button that is checked
// return an empty string if none are checked, or
// there are no radio buttons
// code donated by http://www.somacon.com/p143.php
function getCheckedValue(radioObj) {
  if (!radioObj)
    return '';
  var radioLength = radioObj.length;
  if (radioLength == undefined)
    if (radioObj.checked)
      return radioObj.value;
    else
      return '';
  for (var i = 0; i < radioLength; i++) {
    if (radioObj[i].checked) {
      return radioObj[i].value;
    }
  }
  return '';
}