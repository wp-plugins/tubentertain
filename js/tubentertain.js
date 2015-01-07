//======================
function youTubetime(b) {
  var a = new Date(1970, 0, 1);
  a.setSeconds(b);
  var c = a.toTimeString().substr(0, 8);
  86399 < b && (c = Math.floor((a - Date.parse("1/1/70")) / 36E5) + c.substr());
  return 0 == c.substr(0, 2) ? c.substr(3) : c;
}
var listScroll;
function listLoaded() {
  listScroll = new IScroll("#listDivHolder", {interactiveScrollbars:!0, mouseWheel:!0, click:!0});
}
function changeUrl(b) {
  var a = localStorage.getItem("starIndex"), c = window.location.href.split("?i=")[0];
  window.location.replace(c + "?i=" + a + "&v=" + b);
  return!1;
}
function playNextVid() {
  localStorage.setItem("featured", 0);
  var b = localStorage.getItem("vidNext");
  void 0 === b && (b = $("ul.listItemsHolder li:nth-child(1)").attr("id"));
  changeUrl(b);
}

jQuery(function( $ ) {
  listLoaded();
  $(".listBackEnd ").on("mouseover", function() {
    listScroll.disable();
  });
  $(".listBackEnd ").on("mouseout", function() {
    listScroll.enable();
  });
  var b = 1;
  $(".listBackEnd ").each(function() {
    $(this).attr("id", "conT" + b);
    var a = $(this).attr("id");
    b++;
    new IScroll("#" + a, {scrollbars:!0, scrollX:!1, scrollY:!0, interactiveScrollbars:!0, mouseWheel:!0});
  });
  (function() {
    setTimeout(function() {
      listScroll.refresh();
    }, 100);
  })();
  $("em.frontFace").on("click", function() {
    var a = $(this).parent("p").parent("div.vidbase").prevAll("div.listBackEnd"), c = $(this).parent("p").parent("div.vidbase").prevAll("div.listFrontEnd");
    a.css({opacity:0, visibility:"visible"}).animate({opacity:1}, 500);
    c.css({opacity:1, visibility:"hidden"}).animate({opacity:0}, 500);
    a = $(this).next("em.backFace");
    $(this).removeClass("showFace").addClass("hideFace");
    a.removeClass("hideFace").addClass("showFace").css({color:"red"});
  });
  $("em.backFace").on("click", function() {
    var a = $(this).parent("p").parent("div.vidbase").prevAll("div.listBackEnd"), c = $(this).parent("p").parent("div.vidbase").prevAll("div.listFrontEnd");
    a.css({opacity:1, visibility:"hidden"}).animate({opacity:0}, 500);
    c.css({opacity:0, visibility:"visible"}).animate({opacity:1}, 500);
    $(this).prev("em.frontFace").removeClass("hideFace").addClass("showFace");
    $(this).removeClass("showFace").addClass("hideFace");
  });
  (function() {
    var a = 1;
    $("ul.listItemsHolder li").each(function() {
      $(this).attr("tabindex", a);
      a++;
    });
  })();
  (function() {
    localStorage.clear();
    localStorage.setItem("featured", 0);
    var a = window.location.href.split("&v=")[1], c = $("ul.listItemsHolder li:nth-child(1)").attr("id"), b = $("#body").attr("featureVid");
    localStorage.setItem("featureVid", b);
    if (a) {
      var d = $("#" + a).next().attr("id"), b = $("#" + a).attr("tabindex");
      localStorage.setItem("vidNow", a);
    } else {
      d = $("#" + c).next().attr("id"), b = $("#" + c).attr("tabindex"), localStorage.setItem("vidNow", c);
    }
    localStorage.setItem("vidNext", d);
    a = $("div#body").attr("iCh");
    localStorage.setItem("starIndex", a);
    localStorage.setItem("tabInd", b);
  })();
  $("a#list").click(function() {
    changeUrl($("ul.listItemsHolder li:nth-child(1)").attr("id"));
    return!1;
  });
  $("a#live").click(function() {
    localStorage.getItem("channelNow");
    var a = window.location.href.split("?i=")[0];
    window.location.replace(a + "?i=0");
    return!1;
  });
  $("a.Share").click(function() {
    window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300");
    return!1;
  });
  (function() {
    if (window.location.href.split("&v=")[1]) {
      var a = localStorage.getItem("vidNow")
    } else {
      localStorage.setItem("featured", 1), a = localStorage.getItem("featureVid");
    }
    $("#wopTubplayer").attr("src", "http://www.youtube.com/embed/" + a + "?enablejsapi=1&modestbranding=0&showinfo=0&theme=light&controls=1&color=red&rel=0&start=0&loop=1&iv_load_policy=3&fs=1&disablekb=0&autohide=1&autoplay=1&wmode=transparent&html5=1");
  })();
  (function() {
    var a = localStorage.getItem("starIndex"), c = window.location.href, b = c.split("&v=")[1], c = c.split("?i=")[0], d = $("ul.listItemsHolder li:nth-child(1)").attr("id"), f = $("#" + b).attr("tabindex");
    b && !f && (localStorage.setItem("vidNow", " "), localStorage.setItem("vidNow", d), window.location.replace(c + "?i=" + a + "&v=" + d));
  })();
});
var player;
function onYouTubePlayerAPIReady() {
  player = new YT.Player("wopTubplayer", {events:{onReady:onPlayerReady, onStateChange:onPlayerStateChange, onError:onPlayerError}});
}
function onPlayerReady(b) {
}
function onPlayerStateChange(b) {
  var a = $("#toppy"), c = localStorage.getItem("featured");
  theTab = localStorage.getItem("tabInd");
  nowList = $("ul.listItemsHolder  li:nth-child(" + theTab + ")");
  title = $("div#body").attr("dChtitle");
  document.getElementsByTagName("title")[0].innerHTML = title + "  now playing on  " + document.domain;
  b.data == YT.PlayerState.PLAYING && ($("html, body").animate({scrollTop:a.offset().top}, 1E3), "1" !== c && ($("ul.listItemsHolder li").removeClass("openLi"), nowList.addClass("openLi"), setInterval(function() {
    var a = player.getCurrentTime(), a = "...playin  " + youTubetime(a);
    nowList.find("div.youplay").html("");
    nowList.find("em.nowplayed").html(a);
  }, 20), listScroll.scrollToElement("ul.listItemsHolder  li:nth-child(" + theTab + ")")));
  b.data == YT.PlayerState.ENDED && ("1" !== c ? playNextVid() : StaticVideo());
}
function onPlayerError(b) {
  $("#videoHolder").before("<span id='takeError' class='tooltip'></span>");
  var a = localStorage.getItem("vidNext"), c = $("#" + a).attr("title"), e = 0, d = $("span#takeError");
  d.hide();
  if (150 === b.data || 100 === b.data) {
    d.fadeIn(), function() {
      var a = setInterval(function() {
        var b = e++;
        $("span#takeError").html("<em>did you see the error detail from <span>YouTube</span> about the Video, don't worry sit back i will try to play next video </em> <strong>Title:</strong> <h3>" + c + "</h3> <em> in " + b + "sec</em>");
        10 == b && (clearInterval(a), d.fadeOut(500), playNextVid());
      }, 1E3);
    }();
  }
}
//===========

//=================
var tag = document.createElement("script");
tag.src = "//www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

