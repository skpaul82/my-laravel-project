/**
 * All common custom JavaScripts will places here
 * @author skpaul <me@skpaul.me>
 */


// numeric value checkup
$('.numeric-filter').keyup(function() {
    this.value = this.value.replace(/[^\0-9]/ig, ""); 
});

// remove tags form input fields
$('.strip-tags').keyup(function() {
    this.value = this.value.replace(/<\/?[^>]+(>|$)/g, "")
});

// -- datepicker         
/*$('.datepicker').datepicker({
    autoclose: true,
    todayHighlight: true,
    clearBtn: true,
    format: 'yyyy-mm',
    viewMode: "months", 
    minViewMode: "months",
    maxViewMode: 0,
});*/

/*$('.datepicker-container .input-append.date').datepicker({
    clearBtn: true,
    autoclose: true,
    todayHighlight: true,
    toggleActive: true
});*/

// radio buttons - Use butons to simulate radio butons
$('#radioBtn a, .radioBtn a').on('click', function(){
    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#'+tog).prop('value', sel);
    
    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
})


// -- modal: get course details with sunbscription prices
$(".get-details").click(function(){
    
    $('.preloader').removeClass('hidden')
    var url = $(this).attr('href');
    console.log(url);
    $.get(url, function(data) {
        // var data = $.parseJSON(data);
        // console.log(data);
        $(".modal-content").html(data);
        $("#getDetailsModal").modal("show");
        $('.preloader').addClass('hidden')
    });
});


// Add Recode on Form Submit --
function addRecordOnFromSubmit(ele, url) {
    
    event.preventDefault();
    
    var action = base_url + url;
    var data = $('form[name="'+ele.name+'"]').serialize();
    
    $.post(action, data, function(data, textStatus, xhr) {
        // console.log(data);
        location.reload();
    });
}

// get data by clicking tab
function getTabContent(path, loader) {
    // if( path!='profile' ){
        $('.preloader').removeClass('hidden')
        $.get(base_url+'/'+path, function(data) {
            $('#'+loader).html(data);
            $('.preloader').addClass('hidden')
        });
    /*}else{
    $('.preloader').addClass('hidden')
    }*/
}


// print content
function printContent(el, title){
    var restorepage = $('body').html();
    var printcontent = $('#' + el).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);
}

// window popup
function windowPopup(url_path, target, width, height) {

    $('.preloader').removeClass('hidden');
    var target = (target)?  target : '_blank' ;
    var w = window.innerWidth;
    var h = window.innerHeight;

    if(w > 768){
        var newWidth = (width)? width : w*0.6;
        var newHeight = (height)? height : h*0.8;
    }else{
        var newWidth = (width)? width : w*0.8;
        var newHeight = (height)? height : h*0.6;
    }

    var top = Math.round((h - newHeight)/2);
    var left = Math.round((w - newWidth)/2);
    $('.preloader').addClass('hidden');

    window.open(url_path, target, "toolbar=no, scrollbars=yes, resizable=yes, top="+top+", left="+left+", width="+newWidth+", height="+newHeight+"");
}// ..end windowPopup()