
String.prototype.trunc = String.prototype.trunc ||
  function(n){
    return this.length>n ? this.substr(0,n-1)+'&hellip;' : this;
  };

var linkstacker = {

  saveLink: function(url) {
    var urls = [];
    urls = JSON.parse(localStorage.urls);
    urls.push(url);
    localStorage.urls = JSON.stringify(urls);
    this.resetBackup();
    this.refresh();
    window.close();
  },

  removeLink: function(index) {
    var urls = JSON.parse(localStorage.urls);
    urls.splice(index, 1);
    localStorage.urls = JSON.stringify(urls);
    this.resetBackup();
    this.refresh();
  },

  deleteAll: function() {
    var urls = [];
    var urlsOld = [];
    urlsOld = JSON.parse(localStorage.urls);
    localStorage.urlsOld = JSON.stringify(urlsOld);
    localStorage.urls = JSON.stringify(urls);
    this.refresh();
  },

  undoAll: function() {
    var urlsOld = [];
    urlsOld = JSON.parse(localStorage.urlsOld);
    localStorage.urls = JSON.stringify(urlsOld);
    this.refresh();
  },

  refresh: function() {
    var urls = [];
    urls = JSON.parse(localStorage.urls);

    var string = "";
    for (var i = 0; i < urls.length; i++) {
      var index = urls.length - i - 1;
      var link = urls[index].trunc(55);
      string += "<p><a id='"+index+"' class='button' href='"+urls[index]+"' target='_blank'>"+link+"</a></p>";
      // string += "<button id='"+index+"'>"+link+"</button><br/>";
      // string += "<button type='button' formaction='"+urls[index]+"' formtarget='_blank' id='"+index+"'>"+link+"</button><br/>";
    };

    $('#savedLink').html(string);

    for (var i = 0; i < urls.length; i++) {
      index = urls.length - i - 1;
      $('#'+index).click(
          function(index) {
          return function() {
            linkstacker.removeLink(index);
          };
        } (index)
      );
    }
  },

  resetBackup: function() {
    var urlsOld = [];
    localStorage.urlsOld = JSON.stringify(urlsOld);
    $('#deleteAll').html("Delete All");
    $('#deleteAll').removeClass("true");
    this.refresh();
  }

};


document.addEventListener('DOMContentLoaded', function () {
  chrome.tabs.getSelected(null, function(tab) {
    $('#currentLink').html(tab.url.trunc(36));
    $('#currentLink').click(function() {
      linkstacker.saveLink(tab.url);
      chrome.tabs.remove(tab.id);
      linkstacker.refresh();
    });
    $('#deleteAll').click(function() {
      var undo = $('#deleteAll').attr('class');
      if (undo) {
        linkstacker.undoAll();
        $('#deleteAll').html("Delete All");
        $('#deleteAll').removeClass("true");
      } else {
        linkstacker.deleteAll();
        $('#deleteAll').html("Undo");
        $('#deleteAll').addClass("true");
      }
    });

    linkstacker.refresh();

  });
});


