$(document).ready(function () {

    // UI VISUAL

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        let sidebarCollapse = $("#sidebar").hasClass("active") ? 1 : 0;
        $.ajax({  
            type: 'POST',  
            url: '../include/active_sidebar.php',
            data: { 
                activeSidebar: sidebarCollapse
            }
        });
    });

    $("a").addClass("d-print-none");
    $("input[type='text']").attr("autocomplete", "off");

    // DATE PICKER

    $('.datepicker').datepicker({
        todayHighlight: true,
        todayBtn: true,
        format: "yyyy-mm-dd"
    });

    $(".custom-file-input").on("change", function(e){
        const filename = $("#file_input").val().split('\\').pop();
        $(e.target).next().text(filename);
    })

});