<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <script>
      function inimage(text){
    var obj= $(".im-message-area")[0];
 var range, node;
 if(!obj.hasfocus) {
  obj.focus();
 }
    if (window.getSelection && window.getSelection().getRangeAt) {
        range = window.getSelection().getRangeAt(0);
  range.collapse(false);
        node = range.createContextualFragment(text);
  var c = node.lastChild;
        range.insertNode(node);
  if(c){
   range.setEndAfter(c);
   range.setStartAfter(c)
  }
  var j = window.getSelection();
  j.removeAllRanges();
  j.addRange(range);
  
    } else if (document.selection && document.selection.createRange) {
        document.selection.createRange().pasteHTML(text);
    }
}
$(document).ready(function(){
 $('#button').click(function(){
  $('.ul').show();
 })
 $('.ul li').each(function(){
  $(this).click(function(){
   inimage($(this).text());
  }) 
 })
});
  </script>
</body>
</html>