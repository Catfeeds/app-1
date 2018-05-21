$(document).ready(function() {
  var jobCount = $('#list .in').length;
  $('.list-count').text('共 ' + jobCount + ' 条');
  $("#search-text").keyup(function () {
    var searchTerm = $("#search-text").val();
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
  $.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
  	
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
    
    $("#list li span").not(":containsi('" + searchSplit + "')").each(function(e)   {
      $(this).parent('li').addClass('hiding out').removeClass('in');
      setTimeout(function() {
          $('.out').addClass('hidden');
        }, 300);
    });
    $("#list li span:containsi('" + searchSplit + "')").each(function(e) {
      $(this).parent('li').removeClass('hidden out').addClass('in');
      setTimeout(function() {
          $('.in').removeClass('hiding');
        }, 1);
    });
    
      var jobCount = $('#list .in').length;
    $('.list-count').text('共 ' + jobCount + ' 条');
    
    //shows empty state text when no jobs found
    if(jobCount == '0') {
      $('#list').addClass('empty');
    }
    else {
      $('#list').removeClass('empty');
    }
  });
});