$("#rating i").on({
    mouseenter: function(){
        let elem = $(this);
        elem.prevAll().removeClass("fa-regular").addClass("fa-solid");
        elem.removeClass("fa-regular").addClass("fa-solid");
        // alert("mouseenter");
    },  
    mouseleave:function(){
        let elem = $(this);
        elem.prevAll().removeClass("fa-solid").addClass("fa-regular");
        elem.removeClass("fa-solid").addClass("fa-regular");
    },
    click:function(){
        let elem = $(this);
        let rate = elem.attr('id');
        let movie_id = elem.attr('data-pid');            
        
        let url = `http://localhost/booking/about.php?ratings=${rate}&movie_id=${movie_id}`;
        $(location).attr('href',url);
    }
});